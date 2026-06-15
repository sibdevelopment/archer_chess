<?php

namespace App\Mail;

use Carbon\Carbon;
use App\Models\StudentFee;
use App\Models\StudentBatch;
use App\Models\BatchSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnrollmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $student_fee;

    public function __construct($student, $student_fee)
    {
        $this->student = $student;
        $this->student_fee = $student_fee;
    }

    public function build()
    {
        return $this->markdown('Email.enrollment', [
            'student' => $this->student,
            'student_fee' => $this->student_fee,
        ])
        ->from("support@archerchessacademy.com", "Archer Chess Academy")
        ->subject("Enrollment Confirmation & Login Details – Archer Chess Academy");
    }

}