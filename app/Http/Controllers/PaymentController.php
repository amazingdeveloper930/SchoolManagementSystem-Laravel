<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Payment;
use App\Student;
use App\Service;
use Carbon\Carbon;
use App\ExtraPayment;
use App\Contract;
use App\Contract_Service;
use App\Fee;
use App\User;
use App\Mail\PaymentRealice;

class PaymentController extends Controller{

    public function index(){
        
        return view('payments.index');
    }



    public function getData(Request $request){

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $mostrar = $request->mostrar;
        $desde = $request->desde != '' ? Carbon::createFromFormat('d/m/Y H',$request->desde . ' 0') : '';
        $hasta = $request->hasta != '' ? Carbon::createFromFormat('d/m/Y H',$request->hasta . ' 0') : '';
        $pay_state = $request->pay_state;


        if ($buscar == '')
            $payments = Payment::orderBy('created_at','desc')->with('students');//->paginate($mostrar);
        
        else if($buscar == 'desc' || $buscar == 'asc') 
            $payments = Payment::orderBy($criterio, $buscar);
        
        else{
            //$payments = Payment::where($criterio, 'like', '%' . $buscar . '%');//->orderBy('deposit_at','desc')->paginate($mostrar);

            $columns = ['receipt','attendant','operation_number','amount','info_str'];
            $search = explode(' ',$buscar);

            $payments = Payment::where(function ($query) use ($columns,$search) {
                foreach ($search as $palabra) {
                    $query = $query->where(function ($query) use ($columns,$palabra) {
                        foreach ($columns as $column) {
                            $query->orWhere($column,'like',"%$palabra%");
                        }
                    });
                }
            });
        }

        if($desde != '' && $hasta != ''){
            $payments = $payments->where('deposit_at', '>=' , $desde)->where('deposit_at', '<=' , $hasta);
        }
        else if($desde != ''){
            $payments = $payments->where('deposit_at', '>=' , $desde);
        }
        else if($hasta != ''){
            $payments = $payments->where('deposit_at', '<=' , $hasta);
        }

        if ($pay_state != 2) {
            $payments = $payments->where('status' , $pay_state);
        }

        $payments = $payments->paginate($mostrar);

        foreach ($payments as $payment) {
            $payment->deposit_at = Carbon::createFromFormat('Y-n-d', $payment->deposit_at)->format('d/m/Y');
        }

        return [
            'pagination' => [
                'total' => $payments->total(),
                'current_page' => $payments->currentPage(),
                'per_page' => $payments->perPage(),
                'last_page' => $payments->lastPage(),
                'from' => $payments->firstItem(),
                'to' => $payments->lastItem(),
            ],
            'payments' => $payments,
        ];
    }


    public function create(){

        return view('payments.create.index');
    }


    public function getStudents(Request $request){

        $buscar = $request->buscar;

        if($buscar == '') $students = [];
        else $students = Student::where('name', 'like', '%' . $buscar . '%')
                                //->whereNotIn('status', [0])
                                ->orderBy('name','asc')
                                ->get();

        return [
            'students' => $students,
        ];
    }

    
    public function addStudent($id){ 

        $today = Carbon::today();
        $toYear = $today->format('Y');
        $reference = Carbon::create($toYear,8,1,0,0,0);

        if(!$today->lt($reference)) $toYear++;

        $student = Student::where('id',$id)
            ->with(['extraPayments'=>function($query) use($toYear){
                $query->where('year', $toYear)->get();
            }])->with(['contracts' => function($query){
                $query->orderBy('year','desc')->take(1)->with(['contract_services', 'fees']);
            }])->first();

        return [
            'student' => $student,
        ];
    }


    public function getActivesServices($student_id){

        $student = Student::find($student_id);

        $today = Carbon::today();
        $toYear = $today->format('Y');
        $reference = Carbon::create($toYear,8,1,0,0,0);

        if(!$today->lt($reference)) $toYear++;

        $active_services = Service::where('state',Service::ACTIVE)->get();

        return [
            'active_services' => $active_services,
            'to_year' => $toYear,
        ];
    }


    /*public function storeExtraPayments(Request $request){


        $student = Student::find($request->get('student_id'));

        $today = Carbon::today();
        $toYear = $today->format('Y');
        $reference = Carbon::create($toYear,8,1,0,0,0);

        if(!$today->lt($reference)) $toYear++;
        
        foreach ($request->get('extra_payments') as $obj) {

            $service = Service::find($obj);
            
            $extra_payment = new ExtraPayment();
            $extra_payment->description = $service->description;
            $extra_payment->cost = $service->cost;
            $extra_payment->receipt = '[]';
            $extra_payment->state = $service->state;
            $extra_payment->year = $toYear;
            $extra_payment->student_id = $request->get('student_id');
            $extra_payment->service_id = $service->id;
            $extra_payment->save();
        }


        //ACTUALIZAR ESTUDIANTE ============================================
        $student = Student::where('id',$request->get('student_id'))
            ->with(['extraPayments'=>function($query) use($toYear){

                $query->where('year', $toYear)->get();
            }])->with(['contracts' => function($query){
                
                $query->orderBy('year','desc')->take(1)->with(['fees','contract_services']);
            }])->first();



        return [
            'result' => 'Exito',
            'student' => $student,
        ];
    }*/


    /*public function destroyExtraPayment(Request $request){

        $extra_payment = ExtraPayment::find($request->get('extra_payment_id'));
        
        $extra_payment->delete();

        //AÑO ACTUAL
        $today = Carbon::today();
        $toYear = $today->format('Y');
        $reference = Carbon::create($toYear,8,1,0,0,0);

        if(!$today->lt($reference)) $toYear++;

        //ACTUALIZAR ESTUDIANTE ============================================
        $student = Student::where('id',$request->get('student_id'))
            ->with(['extraPayments'=>function($query) use($toYear){

                $query->where('year', $toYear)->get();
            }])->with(['contracts' => function($query){
                
                $query->orderBy('year','desc')->take(1)->with(['fees','contract_services']);
            }])->first();        

        return [
            'student' => $student,
            'result' => 'satisfactorio' 
        ];
    }*/



    public function store(Request $request){
        $inputs = $request->all();
        //GUARDAR PAGO
        $payment = new Payment();
        $payment->deposit_at = Carbon::createFromFormat('d/m/Y', $request->get('deposit_at'));
        

        $payment->operation_number = $request->get('operation_number');

        if(Payment::where('operation_number', $payment->operation_number)
                    ->where('status',1)->count() != 0){
            $request->validate([
                'operation_number' => 'unique:payments',
            ]);
        }
        

        $payment->amount = $request->get('amount');
        $payment->attendant = $request->get('students')[0]['attendant'];
        $payment->info_str = $request->get('info_str');
        $payment->user = User::find(Auth::id())->name;
        $nums_payments = Payment::all()->count();

        $payment->pay_method = $request->get('pay_method');

        $payment->receipt = str_pad($nums_payments + 1, 6, '0', STR_PAD_LEFT);
        $payment->refund = $request->get('refund');
        //$payment->refund_comment = $request->get('refund_comment');
        $payment->save();

        //EXTRAER ULTIMO PAGO
        $payment = Payment::all()->last();


        //AGREGAR NUEVOS SERVICIOS AL ESTUDIANTE
        foreach($request->get('students') as $student){
            
            foreach($student['extra_payments'] as $extra_payment){

                $extra_payment_aux = ExtraPayment::make($extra_payment);

                if($extra_payment_aux->id == null){     

                    $aux = [
                        'total' => $extra_payment_aux->cost,
                        'type' => 'extra_payment',
                        'receipt' => $payment->receipt,
                        'payment_id' => $payment->id,
                        'date' => Carbon::createFromFormat('Y-m-d',$payment->deposit_at)->format('d/m/Y'),
                        'operation_number' => $payment->operation_number,
                    ];

                    $pay = json_decode($extra_payment_aux->receipt, true);
                    array_push($pay, $aux);
                    $extra_payment_aux->receipt = json_encode($pay);

                    $extra_payment_aux->save();

                    $extra_payment = ExtraPayment::all()->last();
                }
            }
        }
        

        //COMPLETAR INFO DE RECIBOS
        $info_payments = json_decode($payment->info_str);

        foreach($info_payments as $student){

            //Mail::to(Student::find($student->id)->email)->send(new PaymentRealice(Student::find($student->id), $payment, $student->payments));
           
            foreach($student->payments as $pay){

                if($pay->type == 'fee'){
                    $fee = Fee::find($pay->id);
                    $data_payments = json_decode($fee->receipt, true);
                    
                    $aux = [
                        'total' => $pay->total,
                        'type' => 'fee',
                        'receipt' => $payment->receipt,
                        'payment_id' => $payment->id,
                        'date' => Carbon::createFromFormat('Y-m-d',$payment->deposit_at)->format('d/m/Y'),
                        'operation_number' => $payment->operation_number
                    ];

                    $fee->paid_out += $pay->total;

                    if($fee->paid_out == $fee->cost){
                        $fee->status = 2;
                    }

                    array_push($data_payments, $aux);
                    $fee->receipt = json_encode($data_payments);
                    $fee->update();
                }


                if($pay->type == 'r15'){
                    $r15 = Fee::find($pay->id);
                    $data_payments = json_decode($r15->receipt, true);
                    
                    $aux = [
                        'total' => $pay->total,
                        'type' => 'r15',
                        'receipt' => $payment->receipt,
                        'payment_id' => $payment->id,
                        'date' => Carbon::createFromFormat('Y-m-d',$payment->deposit_at)->format('d/m/Y'),
                        'operation_number' => $payment->operation_number
                    ];

                    $r15->r15_paid_out += $pay->total;

                    if($r15->r15_paid_out == $r15->r15){
                        $r15->r15_status = Fee::RECHARGE_PAY;
                    }

                    array_push($data_payments, $aux);
                    $r15->receipt = json_encode($data_payments);
                    $r15->update();
                }


                if($pay->type == 'r1'){
                    $r1 = Fee::find($pay->id);
                    $data_payments = json_decode($r1->receipt, true);
                    
                    $aux = [
                        'total' => $pay->total,
                        'type' => 'r1',
                        'receipt' => $payment->receipt,
                        'payment_id' => $payment->id,
                        'date' => Carbon::createFromFormat('Y-m-d',$payment->deposit_at)->format('d/m/Y'),
                        'operation_number' => $payment->operation_number
                    ];

                    $r1->r1_paid_out += $pay->total;

                    if($r1->r1_paid_out == $r1->r1){
                        $r1->r1_status = Fee::RECHARGE_PAY;
                    }

                    array_push($data_payments, $aux);
                    $r1->receipt = json_encode($data_payments);
                    $r1->update();
                }

                if($pay->type == 'enrollment'){
                    $contract = Contract::find($pay->id);
                    $data_payments = json_decode($contract->receipt, true);

                    $aux = [
                        'total' => $pay->total,
                        'type' => 'enrollment',
                        'receipt' => $payment->receipt,
                        'payment_id' => $payment->id,
                        'date' => Carbon::createFromFormat('Y-m-d',$payment->deposit_at)->format('d/m/Y'),
                        'operation_number' => $payment->operation_number
                    ];

                    $contract->update([
                        'paid_out' => $contract->paid_out + $pay->total,
                    ]);

                    array_push($data_payments, $aux);
                    $contract->receipt = json_encode($data_payments);
                    $contract->update();
                }

                if($pay->type == 'contract_service'){
                    $contract_service = Contract_Service::find($pay->id);
                    $data_payments = json_decode($contract_service->receipt, true);

                    $aux = [
                        'total' => $pay->total,
                        'type' => 'contract_service',
                        'receipt' => $payment->receipt,
                        'payment_id' => $payment->id,
                        'date' => Carbon::createFromFormat('Y-m-d',$payment->deposit_at)->format('d/m/Y'),
                        'operation_number' => $payment->operation_number
                    ];

                    $contract_service->update([
                        'paid_out' => $contract_service->paid_out + $pay->total,
                    ]);

                    //$contract_service->paid_out += $pay->total;

                    array_push($data_payments, $aux);
                    $contract_service->receipt = json_encode($data_payments);
                    $contract_service->update();
                }
            }
        }


        //EXTRAER ESTUDIANTES
        $students = $request->get('students');

        $students_aux = '';

        foreach($students as $student){

            if(isset($student['contracts'][0])){
                //ACTUALIZAR PAGO DE MATRICULA Y DE ANUALIDAD TOTAL DEL CONTRATO
                $contract_aux = Contract::make($student['contracts'][0]);
                $contract = Contract::find($contract_aux->id);
                $contract->update([
                    'annuity_paid_out' => $contract->annuity_paid_out + $contract_aux->annuity_paid_out,
                    //'paid_out' => $contract_aux->paid_out,
                    'r15_paid_out' => $contract->r15_paid_out + $contract_aux->r15_paid_out,
                    'r1_paid_out' => $contract->r1_paid_out + $contract_aux->r1_paid_out
                ]);
                

                //ACTUALIZAR PAGO DE SERVICIOS OBLIGATORIOS
                /*foreach($student['contracts'][0]['contract_services'] as $contract_service_aux){

                    $contract_service_aux = Contract_Service::make($contract_service_aux);

                    $contract_service = Contract_Service::find($contract_service_aux->id);
                    
                    $contract_service->update([
                        'paid_out' => $contract_service_aux->paid_out,
                    ]);
                }*/


                //ACTUALIZAR ACCIONES DE LOS RECARGOS
                foreach($student['contracts'][0]['fees'] as $fee_aux){

                    $fee_aux = Fee::make($fee_aux);

                    $fee = Fee::find($fee_aux->id);

                    if($fee_aux->r15_status == Fee::RECHARGE_CANCEL && $fee->r15_cancel_data == null){
                        $fee->update([
                            'r15_status' => Fee::RECHARGE_CANCEL,    
                            //'comment_cancel_r15' => $request->get('comment_cancel_r15'),
                            'r15_cancel_data' => '{"receipt": "'. $payment->receipt .
                                                    '" , "date": "' . Carbon::now()->format('d/m/Y H:m') . 
                                                    '", "comment": "' . $inputs['c_r15'] . 
                                                    '", "user": "' . Auth::user()->name . '"}',
                        ]);

                        $contract->update([
                            'r15_total' => $contract->r15_total - $fee->r15,
                        ]);
                    }

                    if($fee_aux->r1_status == Fee::RECHARGE_CANCEL && $fee->r1_cancel_data == null){
                        $fee->update([
                            'r1_status' => Fee::RECHARGE_CANCEL,
                            //'comment_cancel_r1' => $request->get('comment_cancel_r1'),    
                            'r1_cancel_data' => '{"receipt": "'. $payment->receipt . 
                                                    '" , "date": "' . Carbon::now()->format('d/m/Y H:m') . 
                                                    '", "comment": "' . $inputs['c_r1'] . 
                                                    '", "user": "' . Auth::user()->name . '"}',
                        ]);

                        $contract->update([
                            'r1_total' => $contract->r1_total - $fee->r1,
                        ]);
                    }
                }
            }
        }

        
        //VERIFICAR QUE EL ESTUDIANTE ESTE PAZ Y SALVO
        foreach($students as $student_aux){
            if(isset($student_aux['contracts'][0])){

                $contract = Contract::find($student_aux['contracts'][0]['id']);
                $student = Student::find($student_aux['id']);

                if ($contract->fees->where('status',0)->count() >= 2 && $student->status != 2) {
                    $student->suspended();
                    continue;
                }
                else{
                    $status = 0;

                    foreach($contract->contract_services as $contract_service){

                        $condition = ($contract_service->cost != $contract_service->paid_out);

                        if($condition){
                            $status = 1;
                            break;
                        }
                    }

                    if($status == 0){
                        if($contract->enrollment_cost != $contract->paid_out){

                            $status = 1;
                        }

                        if($status == 0){
                            foreach ($contract->fees as $fee){
                                
                                $condition = $fee->r15_status != Fee::RECHARGE_ACTIVE && 
                                                $fee->r1_status != Fee::RECHARGE_ACTIVE &&
                                                $fee->status == Fee::PAY;

                                if(!$condition){
                                    $status = 1;
                                    break;                        
                                }
                            }
                        }
                    }

                    if(!$status){

                        $student->update([
                            'peace_save' => Student::SALVE,
                        ]);
                    }else{

                        $student->update([
                            'peace_save' => !Student::SALVE,
                        ]);
                    }
                }
            }
        }


        Mail::to($request->get('students')[0]['email'])->send(new PaymentRealice($payment));

        session()->flash('payment',$payment);

        return [
            'result' => 'satisfactorio',
            'payment_id' => $payment->id
        ];
    }



    public function showReceipt($id){
        
        $payment = Payment::find($id);

        $payment->deposit_at = Carbon::createFromFormat('Y-m-d', $payment->deposit_at)->format('d/m/Y');

        return [
            'payment' => $payment,
        ];
    }



    public function cancelPayment(Request $request){

        $payment = Payment::find($request->get('payment_id'));

        $payment->status = false;

        //$payment->cancel_info = '[]';

        $payment->cancel_info = '{"user":"' . User::find(Auth::id())->name . '", "date":"' . Carbon::today()->format('d/m/Y') . '", "comment":"' . $request->cancel_comment . '" }';

        $students = json_decode($payment->info_str, true);

        foreach($students as $student_aux){

            $student = Student::find($student_aux['id']);

            if(!is_null($student_aux['contract_id'])){
                $contract = Contract::find($student_aux['contract_id']);

                foreach($contract->fees as $fee){
                    
                    if(!is_null($fee->r15_cancel_data)){
                        $r15_cancel_data = json_decode($fee->r15_cancel_data, true);

                        if($payment->receipt == $r15_cancel_data['receipt']){
                            $fee->r15_cancel_data = null;
                            $fee->r15_status  = Fee::RECHARGE_ACTIVE;
                            $contract->update([
                                'r15_total' => $contract->r15_total + $fee->r15,
                            ]);
                            $fee->update();
                        }
                    }

                    if(!is_null($fee->r1_cancel_data)){
                        $r1_cancel_data = json_decode($fee->r1_cancel_data, true);
                    
                        if($payment->receipt == $r1_cancel_data['receipt']){
                            $fee->r1_cancel_data = null;
                            $fee->r1_status  = Fee::RECHARGE_ACTIVE;
                            $contract->update([
                                'r1_total' => $contract->r1_total + $fee->r1,
                            ]);
                            $fee->update();
                        }
                    }    
                        
                }
            }

            foreach($student_aux['payments'] as $pay){

                if($pay['type'] == 'enrollment'){

                    $contract = Contract::find($pay['id']);
                    $contract->paid_out -= $pay['total'];
                    $receipts = json_decode($contract->receipt);
                    $receipts_aux = $receipts;

                    $aux = 0;

                    foreach($receipts_aux as $receipt){
                        
                        if($receipt->receipt == $payment->receipt){

                            array_splice($receipts,$aux,1);
                        }

                        $aux++;
                    }

                    $receipts = json_encode($receipts);
                    $contract->receipt = $receipts;
                    $contract->update();
                }

                if($pay['type'] == 'contract_service'){

                    $contract_service = Contract_Service::find($pay['id']);
                    $contract_service->paid_out -= $pay['total'];

                    $receipts = json_decode($contract->receipt);
                    $receipts_aux = $receipts;

                    $aux = 0;

                    foreach($receipts_aux as $receipt){
                        
                        if($receipt->receipt == $payment->receipt){

                            array_splice($receipts,$aux,1);
                        }

                        $aux++;
                    }

                    $receipts = json_encode($receipts);
                    $contract_service->receipt = $receipts;
                    $contract_service->update();
                }

                if($pay['type'] == 'extra_payment'){

                    ExtraPayment::where('service_id',$pay['service_id'])
                                    ->where('year',$pay['year'])
                                    ->delete();
                }

                if($pay['type'] == 'fee'){

                    $fee = Fee::find($pay['id']);
                    $fee->contract->annuity_paid_out -= $pay['total'];
                    
                    $fee->contract->update();

                    $today = Carbon::today();
                    $toYear = $today->format('Y');
                    //$reference = Carbon::create($toYear,8,1,0,0,0);
                    
                    if($today > $fee->date){
                        $fee->status = 0;
                    }else{
                        $fee->status = 1;
                    }

                    $fee->paid_out -= $pay['total'];

                    $receipts = json_decode($fee->receipt);
                    $receipts_aux = $receipts;

                    $aux = 0;

                    foreach($receipts_aux as $receipt){
                        
                        if($receipt->receipt == $payment->receipt){

                            array_splice($receipts,$aux,1);
                        }

                        $aux++;
                    }

                    $receipts = json_encode($receipts);
                    $fee->receipt = $receipts;
                    $fee->update();   
                }

                if($pay['type'] == 'r15'){

                    $r15 = Fee::find($pay['id']);
                    $r15->contract->r15_paid_out -= $pay['total'];
                    $r15->contract->update();

                    $r15->r15_status = Fee::RECHARGE_ACTIVE;
                    $r15->r15_paid_out -= $pay['total'];

                    $receipts = json_decode($r15->receipt);
                    $receipts_aux = $receipts;

                    $aux = 0;

                    foreach($receipts_aux as $receipt){
                        
                        if($receipt->receipt == $payment->receipt){

                            array_splice($receipts,$aux,1);
                        }

                        $aux++;
                    }

                    $receipts = json_encode($receipts);
                    $r15->receipt = $receipts;
                    $r15->update();
                }

                if($pay['type'] == 'r1'){

                    $r1 = Fee::find($pay['id']);
                    $r1->contract->r1_paid_out -= $pay['total'];
                    $r1->contract->update();

                    $r1->r1_status = Fee::RECHARGE_ACTIVE;
                    $r1->r1_paid_out -= $pay['total'];

                    $receipts = json_decode($r1->receipt);
                    $receipts_aux = $receipts;

                    $aux = 0;

                    foreach($receipts_aux as $receipt){
                        
                        if($receipt->receipt == $payment->receipt){

                            array_splice($receipts,$aux,1);
                        }

                        $aux++;
                    }

                    $receipts = json_encode($receipts);
                    $r1->receipt = $receipts;
                    $r1->update();
                }
            }
        }

        $payment->update();

        //$students = json_decode($payment->info_str, true);        

       //VERIFICAR QUE EL ESTUDIANTE ESTE PAZ Y SALVO
        foreach($students as $student_aux){
            if(isset($student_aux['contracts'][0])){

                $contract = Contract::find($student_aux['contracts'][0]['id']);
                $student = Student::find($student_aux['id']);

                if ($contract->fees->where('status',0)->count() >= 2 && $student->status != 2) {
                    $student->suspended();
                    continue;
                }
                else{
                    $status = 0;

                    foreach($contract->contract_services as $contract_service){

                        $condition = ($contract_service->cost != $contract_service->paid_out);

                        if($condition){
                            $status = 1;
                            break;
                        }
                    }

                    if($status == 0){
                        if($contract->enrollment_cost != $contract->paid_out){

                            $status = 1;
                        }

                        if($status == 0){
                            foreach ($contract->fees as $fee){
                                
                                $condition = $fee->r15_status != Fee::RECHARGE_ACTIVE && 
                                                $fee->r1_status != Fee::RECHARGE_ACTIVE &&
                                                $fee->status == Fee::PAY;

                                if(!$condition){
                                    $status = 1;
                                    break;                        
                                }
                            }
                        }
                    }

                    if(!$status){

                        $student->update([
                            'peace_save' => Student::SALVE,
                        ]);
                    }else{

                        $student->update([
                            'peace_save' => !Student::SALVE,
                        ]);
                    }
                }
            }
        }

        return [
            'result' => 'satisfactorio'
        ];
    }



    public function printReceipt($id){

        $payment = Payment::find($id);

        $data_payments = json_decode($payment->info_str, true);

        $date_payment = Carbon::createFromFormat('Y-m-d H:m:s', $payment->created_at)->format('d/m/Y');

        return view('payments.print.receipt',compact('payment','date_payment','data_payments'));
    }



    public function edit($id){
        //
    }



    public function update(Request $request, $id){
        //
    }


    public function destroy($id){
        //
    }
}
