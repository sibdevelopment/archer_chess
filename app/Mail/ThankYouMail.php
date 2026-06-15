<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ThankYouMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function build()
    {
        return $this->markdown('Email.thankyou', ['details' => $this->details])
                    ->from("support@archerchessacademy.com", "Archer Chess Academy")
                    ->subject("Archer Chess Academy - Thank You for Booking a Trial Class");
    }
}