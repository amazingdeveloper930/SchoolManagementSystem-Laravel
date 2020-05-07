<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use App\Student;
use App\Config;

class Suspension extends Mailable
{
    use Queueable, SerializesModels;

    public $student, $date, $director, $firma;
    public $print = 0;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Student $student, $date)
    {
        $this->student = $student;
        $this->date = Carbon::parse($date)->addDay(2)->format('d/m/Y');
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
        return $this->subject('Suspensión de Estudiante')->view('emails.students.suspension');
    }
}
