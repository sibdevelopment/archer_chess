<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class StudentFeeInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $student_fee;
    public $logoPath;

    public function __construct($student, $student_fee)
    {
        $this->student = $student;
        $this->student_fee = $student_fee;
        $this->logoPath = public_path('backend/images/ArcherKids-logo.png');
    }

    public function build()
    {
        $pdf = Pdf::loadView('Admin.StudentFees.invoicePdf', [
            'student'     => $this->student,
            'student_fee' => $this->student_fee,
            'logoPath'    => $this->logoPath,
        ]);

        return $this->subject('Your subscription has been Confirmed')
            ->view('Email.student_fee_confirmed')
            ->attachData(
                $pdf->output(),
                'Invoice_' . $this->student->student_id . '_' . $this->student_fee->id . '.pdf',
                ['mime' => 'application/pdf']
            );
    }
}
