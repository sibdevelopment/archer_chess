<?php

namespace App\Console\Commands;


use App\Models\Batch;
use App\Models\Student;
use App\Mail\FeesDueMail;
use App\Models\StudentFee;
use App\Models\StudentBatch;
use App\Models\BatchSchedule;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UsaCanadaSetFessDue extends Command
{
    protected $signature = 'set:fess-due-in-usa-canada';

    protected $description = 'Set the fees due students and remove them from the batch.';

    public function handle()
    {
        $incountry = ['CANADA','USA'];

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $fees_due_student_ids = StudentFee::where('end_date', $today)
        ->whereHas('student', function ($query) use ($incountry) {
            $query->whereIn('country', $incountry);
        })
        ->pluck('student_id');

        // dd($fees_due_student_ids);
        foreach ($fees_due_student_ids as $key => $fees_due_student_id) {
            $fees_due_entry = StudentFee::where('student_id', $fees_due_student_id)->orderBy('end_date', 'desc')->first();
            if($fees_due_entry->end_date == $today->format('Y-m-d')){
                $student = Student::find($fees_due_student_id);
                if ($student) {
                    $student->status = 'FEESDUE';
                    $student->save();


                    $studentfee = StudentFee::where('student_id', $student->id)->where('status', 'ACTIVE')->first();
                    if ($studentfee) {
                        $studentfee->status = 'INACTIVE';
                        $studentfee->save();
                    }
                }

                $student_batch = StudentBatch::where('student_id', $fees_due_student_id)->where('status', 'ACTIVE')->first();
                if ($student_batch) {
                    $student_batch->status = 'INACTIVE';
                    $student_batch->is_fees_due = 1;
                    $student_batch->end_date = Carbon::today();
                    $student_batch->end_time = Carbon::now()->format('H:i:s');
                    $student_batch->save();
                }
                if (!$student || !$student_batch) {
                    continue;
                }
                $batch = Batch::find($student_batch->batch_id);
                $batchSchedule = BatchSchedule::where('batch_id', $batch->id)->pluck('weekday')->toArray();

                $studentTz = $student->kids_time_zone ?: 'Asia/Kolkata';
                $nextCarbon = nextClassDateForUSABatch($batch, $batchSchedule, $studentTz);
                $next_date  = $nextCarbon ? $nextCarbon->format('Y-m-d') : null; // or ->toFormattedDateString()

                if (!empty($student->user->email)) {
                    Mail::to($student->user->email)->send(new FeesDueMail($student, $next_date));
                }
            }
        }
    }
}



function nextClassDateForUSABatch($batch, array $batchSchedule, ?string $timezone = 'Asia/Kolkata'): ?Carbon
{
    if (empty($batchSchedule)) {
        return null;
    }

    // Map weekday strings -> Carbon dayOfWeek numbers (0 = Sun ... 6 = Sat)
    $map = [
        'sunday'    => Carbon::SUNDAY,
        'monday'    => Carbon::MONDAY,
        'tuesday'   => Carbon::TUESDAY,
        'wednesday' => Carbon::WEDNESDAY,
        'thursday'  => Carbon::THURSDAY,
        'friday'    => Carbon::FRIDAY,
        'saturday'  => Carbon::SATURDAY,
    ];

    $allowed = array_values(array_filter(array_map(function ($d) use ($map) {
        $key = strtolower(trim($d));
        return $map[$key] ?? null;
    }, $batchSchedule), fn($v) => $v !== null));

    if (empty($allowed)) {
        return null;
    }

    $tz = $timezone ?: 'Asia/Kolkata';

    $start = Carbon::parse($batch->start_date, $tz)->startOfDay();
    $end   = Carbon::parse($batch->end_date,   $tz)->endOfDay();

    // Start from "now" in tz; if before start_date, start at start_date
    $cursor = Carbon::now($tz)->startOfDay();
    if ($cursor->lt($start)) {
        $cursor = $start->copy();
    }

    // Search up to 14 days ahead (enough to cover any weekly pattern)
    for ($i = 0; $i < 14; $i++) {
        if ($cursor->betweenIncluded($start, $end) && in_array($cursor->dayOfWeek, $allowed, true)) {
            return $cursor;
        }
        $cursor->addDay();
    }

    return null; // none found within ranges
}
