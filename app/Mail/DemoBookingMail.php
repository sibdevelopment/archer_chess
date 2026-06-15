<?php

namespace App\Mail;

use Carbon\Carbon;
use App\Models\StudentFee;
use App\Models\StudentBatch;
use App\Models\BatchSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemoBookingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $demoLeadEnquiry;
    public $user;

    public function __construct($demoLeadEnquiry, $user)
    {
        $this->demoLeadEnquiry = $demoLeadEnquiry;
        $this->user = $user;
    }

    public function build()
    {
        return $this->markdown('Email.demo_booking', [
            'demoLeadEnquiry' => $this->demoLeadEnquiry,
            'user' => $this->user,
        ])
        ->from("support@archerchessacademy.com", "Archer Chess Academy")
        ->subject("Student Dashboard Login Details – Archer Chess Academy");
    }

}