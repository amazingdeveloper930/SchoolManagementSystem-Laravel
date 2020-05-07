<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Student;
use App\Fee;
use App\Config;
use Carbon\Carbon;

class PaymentReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $student, $fee, $director, $firma, $date, $month_aux, $month_ven;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($student, $fee)
    {
        $this->student = $student;
        $this->fee = $fee;
        $this->date =  Carbon::parse($fee->date)->format('Y-m-d');
        $this->month_aux =  Carbon::parse($this->date)->format('m') == 1 ? 12 : Carbon::parse($this->date)->format('m') - 1;
        $this->month_ven = Carbon::createFromFormat('m', $this->month_aux);
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
        return $this->subject('Recordatorio de Pago')->view('emails.students.payment_reminder');
    }
}
