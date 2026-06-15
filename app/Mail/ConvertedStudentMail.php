<?php

namespace App\Mail;

use App\Models\StudentFee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConvertedStudentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;

    public function __construct($student)
    {
        $this->student = $student; 
    }

    public function build()
    {
        return $this->markdown('Email.convert_student', [
            'student' => $this->student,
        ])
        ->from("support@archerchessacademy.com", "Archer Chess Academy")
        ->subject("Welcome to ArcherChess Academy, " . $this->student->first_name . "!");
    }

}