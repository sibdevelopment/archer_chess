<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return  $this->markdown('Email.confirmUserMail', ['email' => $this->user->email, ])
                ->from("support@archerchessacademy.com","Archer Chess Academy")
                ->subject("Archer Chess Academy - Verify Your Email Address");
    }

}