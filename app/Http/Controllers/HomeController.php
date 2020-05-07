<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{User, Contract, Fee, Student, Contract_Service, Service};
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('/');
    }

    public function error() {
        $students = Student::all();

        foreach ($students as $student) {

            $contract = $student->contracts->last();

            if (!is_null($contract)) {

                //AGREGADO POR OSWALD
                if(count($contract->fees) > 11){
                    for($i=0;$i<11;$i++){
                        if($i==0){
                            
                            $fee = $contract->fees()->where('order',$i+1)->where('receipt','<>','[]')->first();

                            if($fee != null){
                                $id = $fee->id;
                            }
                            else{
                                $fee = $contract->fees()->where('order',$i+1)->where('receipt','[]')->first();
                                $id = $fee->id;
                            }

                            $precio = $fee->cost;
                            $contract->fees()->where('order',$i+1)->where('receipt','[]')->where('id','<>',$id)->delete();

                            continue;
                        }

                        $fee = $contract->fees()->where('order',$i+1)->where('receipt','<>','[]')->where('cost',$precio)->first();

                        if($fee != null){
                            $id = $fee->id;
                        }
                        else{
                            $fee = $contract->fees()->where('order',$i+1)->where('receipt','[]')->where('cost',$precio)->first();
                            $id = $fee->id;
                        }
                     
                        //$fee = $contract->fees()->where('order',$i+1)->where('receipt','[]')->where('cost',$precio)->first();
                        //$id = $fee->id;
                        $contract->fees()->where('order',$i+1)->where('receipt','[]')->where('id','<>',$id)->delete();
                    }
                }

                if(count($contract->contract_services) > 1){

                    //$contract_services = Contract_Service::all();

                    //foreach($contract_services as $contract_service){
                        
                    $id = $contract->contract_services()->where('receipt','<>','[]')
                                                    ->first()->id;

                    $contract->contract_services()->where('receipt','[]')
                                                    ->where('id','<>',$id)
                                                    ->delete();
                    //}
                }


                if (!count($contract->fees)) {
                    $dateFeee = Carbon::create(2019, 3, 1, 23,59,0);
                    $cost_fee = 2035/11;

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

                    }

                    $fee = new Fee();

                    $fee->cost = (2035) - 10.0 * $cost_fee;
                    $fee->receipt = '[]';
                    $fee->contract_id = $contract->id;
                    $fee->order = 11;
                    $fee->date = $dateFeee->addMonth(1);
                    $fee->save();

                    $contract->annuity_cost = 2035;
                    $contract->save();
                }

                if (!count($contract->contract_services)) {
                    $services = Service::where('state',2)->get();
                  
                    foreach ($services as $service) {
                        $service_required = new Contract_Service();

                        $service_required->description = $service->description;
                        $service_required->state = $service->state;
                        $service_required->cost = $service->cost;
                        $service_required->receipt = '[]';
                        $service_required->contract_id = $contract->id;
                        $service_required->save();
                    }
                }
            }

        }

        return 'Exito';
    }

}
