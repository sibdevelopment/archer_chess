<?php

namespace App\Mail;

use Carbon\Carbon;
use App\Models\StudentFee;
use App\Models\StudentBatch;
use App\Models\BatchSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeesDueMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $next_date;

    public function __construct($student, $next_date)
    {
        $this->student = $student;
        $this->next_date = $next_date;   
    }

    public function build()
    {
        return $this->markdown('Email.fees_due', [
            'student' => $this->student,
            'next_date' => $this->next_date,
        ])
        ->from("support@archerchessacademy.com", "Archer Chess Academy")
        ->subject("Chess Classes Fee Reminder for " . $this->student->first_name . " – Upcoming Chess Module");
    }
}