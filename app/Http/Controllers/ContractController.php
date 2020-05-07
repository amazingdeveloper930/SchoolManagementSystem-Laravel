<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Contract;
use App\Enrollment;
use App\Service;
use App\Fee;
use App\Student;
use Carbon\Carbon;
use App\Annuity;
use App\Total;
use App\Contract_Service;

class ContractController extends Controller
{
    
    public function index(){
    }


    public function getData($student_id){
        
        $student = Student::find($student_id);
        $contracts = $student->contracts;
        $extra_payments = $student->extraPayments;

        return [
            'contracts' => $contracts,
            'extra_payments' => $extra_payments,
            //'dataForYear' => $dataForYear,
        ];
    }


    public function create($enrollment_id){
        
        $enrollment = Enrollment::find($enrollment_id);

        $today = Carbon::today();
        $toYear = $today->format('Y');
        $reference = Carbon::create($toYear,8,1,0,0,0);


        if($today->lt($reference)){

            $annuity = Annuity::where('year', $toYear)->first();
        }else{
            
            $annuity = Annuity::where('year', $toYear+1)->first();
        }


        $reference = Carbon::createFromFormat('Y-m-d',$annuity->maximum_date);


        if(!$today->lte($reference)){
            $annuity->discount = 0;
        }

        //CONSULTAR SOLO SERVICIOS REQUERIDOS PARA CREAR UN CONTRATO
        $services_required = Service::where('state',Service::REQUIRED)->get();

        return [
            'annuity' => $annuity,
            'enrollment' => $enrollment,
            'services_required' => $services_required,
        ];
    }


    public function store(Request $request){

        try {

            DB::beginTransaction();

            $contract = new Contract;

            //PASAR ESTUDIANTE A ACTIVO
            $student = Student::find($request->student_id);
            $student->status = \App\Student::ACTIVE;
            $student->peace_save = false;
            $student->update();

            $contract->enrollment_grade = $request->enrollment_grade;
            $contract->enrollment_bachelor = $request->enrollment_bachelor;
            $contract->enrollment_cost = $request->enrollment_cost;
            $contract->receipt= '[]';
            $contract->year = $request->year;
            $contract->student_id = $request->student_id; 
            $contract->user_id = Auth::id();
            $contract->observation = $request->observation;
            $contract->annuity_cost = 0; //MIENTRAS SE REALIZA LA COMPARACION


            //INSERTAR NUMERO DE SOLICITUD =========================================
            $num_contracts = Contract::where('year',$request->year)->get()->count();
            $contract->request = str_pad($num_contracts + 1, 4, '0', STR_PAD_LEFT) . '-' . $request->year;
            //======================================================================

            $contract->save();

            // $contract = Contract::all()->last();

            
            //ALMACENANDO SERVICION EN CONTRACT_SERVICES ===========================
            foreach($request->get('services_required') as $service_required_aux){

                $service = Service::find($service_required_aux['id']);

                $service_required = new Contract_Service();

                $service_required->description = $service->description;
                $service_required->state = $service->state;
                $service_required->cost = $service_required_aux['cost'];
                $service_required->receipt = '[]';
                $service_required->contract_id = $contract->id;
                $service_required->save();
            }

            //ALGORITMO PARA TOMAR EN CUENTA LA FECHA MAXIMA ===========================================
            $today = Carbon::today();
            $reference = Carbon::createFromFormat('Y-m-d',$request->annuity_max_date);
        


            //DETERMINACION DE LAS CUOTAS ===============================================================
            /*$decimals_fee_aux = (string)(($request->annuity_cost - $request->annuity_discount)/11);
            $decimals_fee_aux = substr($decimals_fee_aux, strpos($decimals_fee_aux,'.'));
            $decimals_fee_aux = strlen($decimals_fee_aux);
            $comprob = $decimals_fee_aux > 3;*/

            $contract->annuity_cost = ($request->annuity_cost) - ($request->annuity_discount);

            //if($comprob){
            //    $cost_fee = intVal($request->annuity_cost/11);
            //}
            //else{
                $cost_fee = ($request->annuity_cost - $request->annuity_discount)/11;
            //}

            $contract->update(['annuity_cost' => $contract->annuity_cost]);



            //$acum = 0;
            $dateFeee = Carbon::create($request->year, $request->annuity_second_month - 1, 1, 23,59,0);
            //Metodo para pruebas de recargos
            //$dateFeee = Carbon::create(null, null, null, 23, 59, 0);
            //$dateFeee->subDay();

            for($i=0; $i<10; $i++){
                
                if ($i == 0){
                    $fee = new Fee();
                    $fee->cost = $cost_fee;  
                    $fee->receipt = '[]';  
                    $fee->contract_id = $contract->id;
                    $fee->order = $i+1;
                    $fee->save();
                }else{
                    $dateFeee = $dateFeee->addMonth(1);

                    $fee = new Fee();
                    $fee->cost = $cost_fee;
                    $fee->receipt = '[]';
                    $fee->contract_id = $contract->id;
                    $fee->order = $i+1;
                    $fee->date = $dateFeee;
                    $fee->save();
                }
                
                //$acum += $cost_fee;
            }

            $fee = new Fee();
        
            $fee->cost = ($request->annuity_cost - $request->annuity_discount) - 10.0 * $cost_fee;
            $fee->receipt = '[]';
            $fee->contract_id = $contract->id;
            $fee->order = 11;
            $fee->date = $dateFeee->addMonth(1);
            $fee->save();

            DB::commit();

            return [
                'result' => 'satisfactorio',
            ];
            
        } catch (Exception $e) {
            DB::rollBack();
        }        
        
    }


    public function show($student_id, $contract_year = null){

        $student = Student::find($student_id);

        $today = Carbon::today();
        $toYear = $today->format('Y');
        $reference = Carbon::create($toYear,8,1,0,0,0);

        if(!$today->lt($reference)) $toYear++;

            
        if($student->contracts()->count() > 0){
            
            if(is_null($contract_year)) $contract = $student->contracts->last();
            else $contract = $student->contracts->where('year',$contract_year)->first();
            
            $extra_payments = $student->extraPayments()->where('year',$contract->year)->get();
            $date_contract = $contract->created_at->format('d/m/Y - H:m');
            $fees = $contract->fees;
            $services_contract_required = $contract->contract_services()->where('state',Contract_Service::REQUIRED)->get();
            $contract_has_actual_year = true;

            return [
                'date_contract' => $date_contract,
                'contract_has_actual_year' => $contract_has_actual_year,
                'contract' => $contract,
                'user' => $contract->user,
                'fees'=> $fees,
                'services_contract_required' => $services_contract_required,
                'extra_payments' => $extra_payments,
            ];          
        }
        
        if($student->extraPayments()->count() > 0){

            if(is_null($contract_year)){
                $ultimo = $student->extraPayments->last()->year;
                $extra_payments = $student->extraPayments()->where('year',$ultimo)->get();
            }
            else{
                $extra_payments = $student->extraPayments()->where('year',$contract_year)->get();
            }

            return [
                'extra_payments' => $extra_payments,
            ];
        }


        return;
        

        //NOTA: $contract_has_actual_year ES NECESARIA PARA HABILITAR EL BOTON DE CANCELAR CONTRATO EN LA VISTA.
        //A MODO DE PRUEBA POR EL MOMENTO.
    }


    public function changeFee(Request $request){

        $fee = Fee::find($request->get('id'));

        $fee->contract->annuity_cost -= $fee->cost;
        $fee->contract->annuity_cost += $request->get('cost');
        $fee->contract->update();

        $fee->cost = $request->get('cost');

        if($request->get('cost') == 0){
            $fee->status = Fee::PAY;
        }

        $fee->update();

        /*return [
            'fee' => $fee,
            'annuity_cost' => $fee->contract->annuity_cost,
        ];*/

        return response()->json([
            'fee' => $fee,
            'annuity_cost' => $fee->contract->annuity_cost,
        ]);
    }
    

    public function edit($id){

        //
    }
    

    public function update(Request $request, $id){
        
        //
    }
    

    public function destroy(/*$student_id*/$contract_id){
        /*$student = Student::find($student_id);
        $contract = $student->contracts->last();*/

        $contract = Contract::find($contract_id);

        $contract->contract_services()->delete();
        //$student->inactived();
        //$contract->total->delete();
        $contract->fees()->delete();


        $contract->student->inactived();

        //POR EL MOMENTO: PORQUE AUN NO SE MANEJAN CUOTAS
        $contract->student->peace_save = false;
        $contract->student->save();


        $contract->delete();

        
        //===============================================

        return [
            'result' => 'exito'
        ];
    }
}
