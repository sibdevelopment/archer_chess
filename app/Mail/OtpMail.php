<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $otp;
    public $demoLeadEnquiry;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->email = $data['email'];
        $this->otp = $data['otp'];
        $this->demoLeadEnquiry = $data['demoLeadEnquiry'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('Email.otpmail', [
                'email' => $this->email,
                'otp' => $this->otp,
                'demoLeadEnquiry' => $this->demoLeadEnquiry
            ])
            ->from("support@archerchessacademy.com", "Archer Chess Academy")
            ->subject("Archer Chess Academy - Verify Your Email Address");
    }
}