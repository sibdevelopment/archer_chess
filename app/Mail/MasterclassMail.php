<?php

namespace App\Mail;

use Carbon\Carbon;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MasterclassMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $masterclass;
    public $studentDate;
    public $studentTime;

    public function __construct($student, $masterclass)
    {
        $this->student = $student;
        $this->masterclass = $masterclass;

        // Convert time using your helper
        $convertedTime = convertTimeZoneWiseTime(
            $masterclass->date,
            $masterclass->time,
            $student->id
        );

        // Keep original date (you can also convert date if needed)
        $this->studentDate = Carbon::parse($masterclass->date)->format('d M Y');
        $this->studentTime = $convertedTime;

        // dd($this->studentDate, $this->studentTime);
    }

    public function build()
    {
        return $this->markdown('Email.masterclass', [
            'student' => $this->student,
            'masterclass' => $this->masterclass,
            'studentDate' => $this->studentDate,
            'studentTime' => $this->studentTime,
        ])
        ->from("support@archerchessacademy.com", "Archer Chess Academy")
        ->subject("Reminder: Online Master Class for " . $this->student->first_name . ' ' . $this->student->last_name);
    }
}
