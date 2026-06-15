<?php

namespace App\Mail;

use Carbon\Carbon;
use App\Models\StudentFee;
use App\Models\StudentBatch;
use App\Models\BatchSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Before24HrFeesDueMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $date;
    public $fee_due;

    public function __construct($student, $date)
    {
        $this->student = $student;
        $this->date = $date;

        $this->fee_due = StudentFee::where('student_id', $student->id)
            ->latest('created_at')
            ->first();

        if ($this->fee_due) {
            $latestBatch = StudentBatch::where('student_id', $student->id)
                ->latest('updated_at')
                ->first();

            $nextScheduledDate = 'N/A';

            if ($latestBatch && $latestBatch->batch) {
                $batch = $latestBatch->batch;
                $endDate = $batch->end_date ? Carbon::parse($batch->end_date) : null;

                if ($endDate) {
                    $weekdays = BatchSchedule::where('batch_id', $batch->id)
                        ->pluck('weekday')
                        ->filter()
                        ->toArray();

                    if (!empty($weekdays)) {
                        // Map weekday names to numeric (0 = Sunday ... 6 = Saturday)
                        $weekdayMap = [
                            'Sunday' => 0,
                            'Monday' => 1,
                            'Tuesday' => 2,
                            'Wednesday' => 3,
                            'Thursday' => 4,
                            'Friday' => 5,
                            'Saturday' => 6,
                        ];

                        $weekdayNumbers = collect($weekdays)
                            ->map(fn($day) => $weekdayMap[$day])
                            ->unique();

                        $nextDate = collect(range(1, 7))->map(function ($i) use ($endDate, $weekdayNumbers) {
                            $checkDate = $endDate->copy()->addDays($i);
                            return $weekdayNumbers->contains($checkDate->dayOfWeek) ? $checkDate : null;
                        })->filter()->first();

                        if ($nextDate) {
                            $nextScheduledDate = $nextDate->format('l, d-M-Y');
                        }
                    }
                }

                $this->fee_due->next_scheduled_date = $nextScheduledDate;
            }
        }
    }

    public function build()
    {
        return $this->markdown('Email.before_24hr_fees_due', [
            'student' => $this->student,
            'date' => $this->date,
            'fee_due' => $this->fee_due,
        ])
        ->from("support@archerchessacademy.com", "Archer Chess Academy")
        ->subject("Follow-Up: Chess Classes Fee Pending for " . $this->student->first_name . ' ' . $this->student->last_name . " – Upcoming Module");
    }

}