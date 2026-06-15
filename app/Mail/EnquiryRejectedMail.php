<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\DemoLeadEnquiry;

class EnquiryRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $enquiry;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(DemoLeadEnquiry $enquiry)
    {
        $this->enquiry = $enquiry;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('Email.enquiry_rejected')
                    ->with([
                        'first_name' => $this->enquiry->kids_first_name,
                        'last_name' => $this->enquiry->kids_last_name,
                        'email' => $this->enquiry->email,
                    ])
                    ->from("support@archerchessacademy.com", "Archer Chess Academy")
                    ->subject("Archer Chess Academy - Enquiry Rejected");
    }
}