<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\{Contract,Student,Fee, Config};
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\{Suspension,PaymentReminder,OverduePayment};


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule){
        
        $schedule->call(function () {

            $students = Student::all();
            $config = Config::where('type','mail')->first();
            $mail = (int)$config->content;
            $reminder_date = Carbon::create(null, null, null, 5, 0)->format('Y-m-d H:i');
            $nowDate = Carbon::now()->format('Y-m-d H:i');

            /**
             * Verificacion de Status
             */
            
            // foreach ($students as $student) {
            //     $contract = $student->contracts->last();
            //     if (!is_null($contract)) {

            //         $today = Carbon::today();
            //         $toYear = $today->format('Y');
            //         $reference = Carbon::create($toYear,8,1,0,0,0);

            //         if(!$today->lt($reference)) $toYear++;

            //         if($contract->year == $toYear){
            //             $student->actived();
            //         }
            //         else{
            //             $student->inactived();
            //         }
            //     }
            // }
            
            /**
             * Recargos y suspencion de estudiantes
             */

            foreach ($students as $student) {
                
                $contract = $student->contracts->last();

                if(!is_null($contract)) {

                    //AGREGADO POR OSWALD===============================================
                    //==================================================================
                    // $today = Carbon::today();
                    // $toYear = $today->format('Y');
                    // $reference = Carbon::create($toYear,8,1,0,0,0);

                    // if(!$today->lt($reference)) $toYear++;

                    // if($contract->year == $toYear){
                    //     $student->actived();
                    // }
                    // else{
                    //     $student->inactived();
                    // }
                    //====================================================================
                    //====================================================================

                    /**
                     * Verificar paz y salvo
                     */
                    $this->peaceSalve($student,$contract);
                    /**
                     * ==================================
                     */

                    if ($student->status != 4) {
                        
                        // if ($contract->fees->where('status',0)->count() >= 2 && $student->status != 2) {
                        // $student->suspended();
                        // Mail::to($student->email)->send(new Suspension($student,$contract->fees->where('status',0)->last()->date));
                        // }

                        // if ($contract->fees->where('status',0)->count() <= 1 && $student->status == 2) {
                        //    $student->actived();
                        // }

                    }

                    /**
                     * Ciclo de cuotas
                     */
                    foreach ($contract->fees as $fee) {

                        $date_r1 = Carbon::parse($fee->date)->addMonth(1)->startOfDay();

                        //$date_r1 = Carbon::parse($fee->date)->addMinute()->startofMinute();

                        /*if ($contract->fees->where('status',0)->count() >= 2 && $fee->r1 == null && $fee->order > 2) {
                            $monto = $contract->fees->where('status',0)->sum('cost') + $contract->fees->where('status',0)->sum('r15') + $contract->fees->where('status',0)->sum('r1');
                            $fee->r1($monto);
                        }*/
                        
                        if ($date_r1 < Carbon::now() && $fee->order > 1 && $fee->status == 0 && $fee->r1_status == 0) {
                            $monto = $contract->fees->where('status',0)->sum('cost') + $contract->fees->where('status',0)->sum('r15') + $contract->fees->where('status',0)->sum('r1');
                            $fee->r1($monto);
                        }

                        if (Carbon::parse($fee->date) < Carbon::now() && $fee->status == 1 && $fee->order > 1) {
                            $fee->surcharge();
                        }

                        if ($student->status != 2 && $student->status != 4) {
                            /**
                            * Recordatorio
                            */
                            if (!$contract->fees->where('status',0)->count()) {
                                $aux_date = Carbon::parse($fee->date);
                                if ($aux_date->diffInDays() == 7 && $fee->order > 1 && $reminder_date == $nowDate) {
                                    Mail::to($student->email)->send(new PaymentReminder($student,$fee));
                                }
                            }
                            /**
                            * Aviso vencimiento
                            */
                            if ($contract->fees->where('status',0)->count() >= 1 && $fee->order > 1) {
                                if (Carbon::now()->day == $mail) {
                                    if ($fee->status == Fee::INACTIVE) {
                                        $this->avisos($fee,$student);
                                    }
                                }
                            } elseif ($fee->order == 1 && $fee->paid_out < $fee->cost) {
                                if (Carbon::now()->day == $mail) {
                                        $this->avisos($fee,$student);
                                }
                            }
                        }
                        
                    }
                    /**
                     * Fin ciclo de cuotas
                     */
                }else{
                    $student->inactived();
                }
            }
            $this->bandera($config);
        })->everyMinute();

    }

    protected function avisos($fee,$student) {
        $contract = $student->contracts->last();
        $services = $contract->contract_services;
        
        switch (Carbon::now()->day) {
            case 2:
                Mail::to($student->email)->send(new OverduePayment(1,$fee,$student));
                break;

            case 15:
                Mail::to($student->email)->send(new OverduePayment(2,$fee,$student));
                break;

            case 25:
                Mail::to($student->email)->send(new OverduePayment(3,$fee,$student));
                break;
                
            case 7:
                Mail::to($student->email)->send(new OverduePayment(3,$fee,$student));
                break;
        }
    }

    protected function bandera($config) {
        switch (Carbon::now()->day) {
            case 2:
                $config->content = 15;
                $config->save();
                break;

            case 15:
                $config->content = 25;
                $config->save();
                break;

            case 25:
                $config->content = 2;
                $config->save();
                break;
                
            case 7:
                $config->content = 15;
                $config->save();
                break;
        }
    }

    protected function peaceSalve($student, $contract) {
        $fees_active = $contract->fees->where('status',1)->count();
        $fees_inactive = $contract->fees->where('status',0)->count();
        $enrolloment = false;
        if ($contract->fees->where('order',1)->first()->cost == $contract->fees->where('order',1)->first()->paid_out) {
            $fee_1 = true;
        } else {
            $fee_1 = false;
        }
        $services = $contract->contract_services->where('state',1)->count();
        if ($contract->enrollment_cost == $contract->paid_out) {
            $enrolloment = true;
        }
        
        if ($fees_active <= 1 && $fees_inactive == 0 && $fee_1 && $enrolloment && $services == 0) {
            $student->peace_save = 1;
            $student->save();
        } else {
            $student->peace_save = 0;
            $student->save();
        }
        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
