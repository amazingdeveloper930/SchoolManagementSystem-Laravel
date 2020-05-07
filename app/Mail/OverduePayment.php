<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Config;
use Carbon\Carbon;

class OverduePayment extends Mailable
{
    use Queueable, SerializesModels;

    public $nro, $fee, $student, $director, $firma, $services, $contract;
    public $yearNow;
    public $print = 0;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nro, $fee, $student)
    {
        $this->nro = $nro;
        $this->fee = $fee;
        $this->student = $student;
        $this->contract = $student->contracts->last();
        $this->services = $this->contract->contract_services;
        $this->director = Config::where('type','name')->first();
        $this->firma = Config::where('type','firma')->first();

        $this->yearNow = Carbon::now()->addYear()->year;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('IMPORTANTE - AVISO DE PAGO VENCIDO')->view('emails.students.overdue_payment');
    }
}
