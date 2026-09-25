<?php

namespace App\Mail;

use Carbon\Carbon;
use App\Models\StudentFee;
use App\Models\StudentBatch;
use App\Models\BatchSchedule;
use App\Services\PaymentLevelService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeesDueMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $next_date;
    public $fee_due_amount;
    public $fee_due_currency;
    public $fee_due_payment_level;

    public function __construct($student, $next_date)
    {
        $this->student = $student;
        $this->next_date = $next_date;   
    }

    public function build()
    {
        $dueSummary = app(PaymentLevelService::class)->dueAmountSummary($this->student);
        $this->fee_due_amount = $dueSummary['amount'];
        $this->fee_due_currency = $dueSummary['currency'];
        $this->fee_due_payment_level = $dueSummary['payment_level'];

        return $this->markdown('Email.fees_due', [
            'student' => $this->student,
            'next_date' => $this->next_date,
            'fee_due_amount' => $this->fee_due_amount,
            'fee_due_currency' => $this->fee_due_currency,
            'fee_due_payment_level' => $this->fee_due_payment_level,
        ])
        ->from("support@archerchessacademy.com", "Archer Chess Academy")
        ->subject("Chess Classes Fee Reminder for " . $this->student->first_name . " – Upcoming Chess Module");
    }
}
