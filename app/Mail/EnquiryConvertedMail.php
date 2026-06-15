<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnquiryConvertedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $demoLeadEnquiry;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($demoLeadEnquiry)
    {
        $this->demoLeadEnquiry = $demoLeadEnquiry;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@archerchessacademy.com', 'Archer Chess Academy')
                    ->subject('Archer Chess Academy - Your Enquiry has been Converted')
                    ->markdown('Email.enquiryConverted')
                    ->with([
                        'email' => $this->demoLeadEnquiry->email,
                        'otp' => $this->demoLeadEnquiry->email_otp,
                        'demoLeadEnquiry' => $this->demoLeadEnquiry,
                    ]);
    }
}