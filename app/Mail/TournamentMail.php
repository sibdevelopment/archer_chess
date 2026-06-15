<?php

namespace App\Mail;

use Carbon\Carbon;
use App\Models\Student;
use App\Models\Tournament;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TournamentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $tournament;
    public $studentDateTime;
    public $studentTime;
    public $studentTimezone;

    /**
     * Create a new message instance.
     *
     * @param Student $student
     * @param Tournament $tournament
     */
    public function __construct($student, $tournament)
    {
        $this->student = $student;
        $this->tournament = $tournament;

         $convertedTime = convertTimeZoneWiseTime(
            $tournament->date,
            $tournament->time,
            $student->id
        );


        $this->studentTimezone = $student->timezone ?? 'Asia/Kolkata';
        $this->studentDateTime = Carbon::parse($tournament->date)->format('d M Y');
        $this->studentTime = $convertedTime;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('Email.tournament', [
            'student' => $this->student,
            'tournament' => $this->tournament,
            'studentDateTime' => $this->studentDateTime,
            'studentTime' => $this->studentTime,
            'timezone' => $this->studentTimezone,
        ])
        ->from("support@archerchessacademy.com", "Archer Chess Academy")
        ->subject("Reminder: " . $this->tournament->name);
    }
}
