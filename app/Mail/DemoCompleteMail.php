<?php

namespace App\Mail;

use Carbon\Carbon;
use App\Models\StudentFee;
use App\Models\StudentBatch;
use App\Models\BatchSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemoCompleteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $demolead;
    public $studentAttendance;

    public function __construct($demolead, $studentAttendance)
    {
        $this->demolead = $demolead;
        $this->studentAttendance = $studentAttendance;
    }

    public function build()
    {
        return $this->markdown('Email.trial', [
            'demolead' => $this->demolead,
            'studentAttendance' => $this->studentAttendance,
        ])
        ->from("support@archerchessacademy.com", "Archer Chess Academy")
        ->subject("Demo Class Completed");
    }

}