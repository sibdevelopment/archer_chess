<?php

namespace App\Mail;

use Carbon\Carbon;
use App\Models\StudentFee;
use App\Models\StudentBatch;
use App\Models\BatchSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemoScheduleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $demolead;
    public $demosession;
    public $istDateTime;
    public $studentDateTime;

    public function __construct($demolead, $demosession)
    {
        $this->demolead = $demolead;
        $this->demosession = $demosession;

        $this->istDateTime = Carbon::parse($demosession->date . ' ' . $demosession->time)
            ->format('d M Y | h:i A');
        // Student local datetime using stored kids_time_zone
        $this->studentDateTime = convertToKidsTime(
            $demosession->date,
            $demosession    ->time,
            $demosession->country ?? 'INDIA'
        );



    }

    public function build()
    {
        return $this->markdown('Email.demo', [
            'demolead' => $this->demolead,
            'demosession' => $this->demosession,
            'istDateTime' => $this->istDateTime,
            'studentDateTime' => $this->studentDateTime,
        ])
        ->from("support@archerchessacademy.com", "Archer Chess Academy")
        ->subject("Demo Class Scheduled");
    }

}