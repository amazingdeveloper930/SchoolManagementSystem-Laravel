<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use App\Payment;
use App\Service;
use App\Bank;
use App\Student;

class BalanceController extends Controller{

    public function index(){

        return view('balance.index');
    }


    public function getData($year = null){
    

        if(is_null($year)) $year = Carbon::now()->format('Y');

        $sample = [
            '1'     => 0.00,
            '2'   => 0.00,
            '3'     => 0.00,
            '4'     => 0.00,
            '5'      => 0.00,
            '6'     => 0.00,
            '7'     => 0.00,
            '8'    => 0.00,
            '9'    => 0.00,
            '10'       => 0.00,
            '11'     => 0.00,
            '12'     => 0.00
        ];

        $data = [];

        //$data['Deposito'] = $sample;
        
        $required_services = Service::where('state',2)->get();


        foreach($required_services as $service){
            $data[$service->description] = $sample;
        }
        
        $data['Matricula'] = $sample;
        $data['Cuotas'] = $sample;
        $data['Servicios'] = $sample;
        $data['Recargos'] = $sample;
        $data['Deposito'] = $sample;
        $data['Banco'] = $sample;
        $data['Balance'] = $sample;
    
        
        $payments = Payment::where('status',1)->get();

        foreach($payments as $payment){



            if(Carbon::createFromFormat('Y-m-d',$payment->deposit_at)->format('Y') == $year){

                $info_json = json_decode($payment->info_str,true);

                foreach ($info_json as $student) {

                    $month = (string)(int)(Carbon::createFromFormat('Y-m-d',$payment->deposit_at)->format('m'));
                    
                    foreach ($student['payments'] as $pay) {

                        $data['Deposito'][$month] += $pay['total'];
                        
                        switch ($pay['type']){
                            case 'enrollment' :         $data['Matricula'][$month] += $pay['total']; break;
                            case 'contract_service' :   $data[$pay['description']][$month] += $pay['total']; break;
                            case 'extra_payment' :      $data['Servicios'][$month] += $pay['total']; break;
                            case 'fee' :                $data['Cuotas'][$month] += $pay['total']; break;
                            case 'r1' :                 $data['Recargos'][$month] += $pay['total']; break;
                            case 'r15' :                $data['Recargos'][$month] += $pay['total']; break;
                        }
                    }
                }                
            }
        }

        $banks = Bank::where('year',$year)->get();

        for($i=1; $i<13; $i++){
            
            if($banks->where('month',(string)$i)->count()){
                $bank_ = $banks->where('month',(string)$i)->last();
                $data['Banco'][$i] = $bank_->amount;
                $data['Balance'][(string)$i] = $data['Deposito'][(string)$i] - $data['Banco'][(string)$i];
            }
        }

        $students = Student::where('status','!=','4')->with(['contracts'=>function($query) use($year){
            $query->where('year',$year)->with(['fees'=>function($query2){
                $query2->where('status',0)->get();
            }])->get();
        }])->get();


        return [
            'datos' => $data,
            'year' => $year,
            'students' => $students
        ];
    }


    /*public function balanceStudents(){

        return view('balance.balance_students.index');
    }*/


    


    public function createBank($month, $year){

        //$banks_all = Bank::all();

        $banks = Bank::where('month', $month)->where('year', $year)->orderBy('date','desc')->get();

        

        /*$banks = $banks_all->filter('date',function($query) use($month,$year){

            //$month_ = (int) Carbon::createFromFormat('Y-m-d',$query)->format('m');
            //$year_ = Carbon::createFromFormat('Y-m-d',$query)->format('Y');

            return $month == $month_ && $year == $year__;
        })->all();*/


        return [
            'banks' => $banks
        ];
    }

    public function registreBank(Request $request){

        $bank = new Bank();
        $bank->user = Auth::user()->name;
        $bank->amount = $request->get('amount');
        $bank->date = Carbon::now();
        $bank->month = $request->get('month');
        $bank->year = $request->get('year');
        $bank->save();

        return [
            'result' => 'satisfactorio'
        ];
    }

   
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        //
    }

    
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

   
    public function destroy($id)
    {
        //
    }
}
