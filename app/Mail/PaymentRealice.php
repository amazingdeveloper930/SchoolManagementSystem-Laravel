<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Student;
use App\Payment;
use App\Config;
use Carbon\Carbon;

class PaymentRealice extends Mailable
{
    use Queueable, SerializesModels;

    public $payment, $payments_students, $director, $firma;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payment){

        //$this->student = $student;
        $this->payment = $payment;
        $this->payments_students = json_decode($payment->info_str, true);
        $this->director = Config::where('type','name')->first();
        $this->firma = Config::where('type','firma')->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Pago realizado')->view('emails.payments.old_payment_realice');
    }
}
