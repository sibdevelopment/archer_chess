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

class UkSetFessDue extends Command
{
    protected $signature = 'set:fess-due-in-uk';

    protected $description = 'Set the fees due status for students in the UK';

    public function handle()
    {
        $incountry = ['UK'];

        $cutoffDate = Carbon::yesterday()->format('Y-m-d');

        $students = Student::whereIn('country', $incountry)
            ->where('status', '!=', 'FEESDUE')
            ->get();

        foreach ($students as $student) {
            try {
                $studentfee = StudentFee::where('student_id', $student->id)
                    ->where('status', 'ACTIVE')
                    ->orderBy('end_date', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();

                if (!$studentfee || $studentfee->end_date > $cutoffDate) {
                    continue;
                }

                $student->status = 'FEESDUE';
                $student->save();

                $studentfee->status = 'INACTIVE';
                $studentfee->save();

                $student_batch = StudentBatch::where('student_id', $student->id)
                    ->where('status', 'ACTIVE')
                    ->latest('id')
                    ->first();

                if (!$student_batch) {
                    Log::warning('UK fees due batch update skipped: active student batch not found', [
                        'student_id' => $student->id,
                        'student_fee_id' => $studentfee->id,
                    ]);
                    continue;
                }

                $student_batch->status = 'INACTIVE';
                $student_batch->is_fees_due = 1;
                $student_batch->end_date = $cutoffDate;
                $student_batch->end_time = Carbon::now()->format('H:i:s');
                $student_batch->save();

                $batch = Batch::find($student_batch->batch_id);
                if (!$batch) {
                    Log::warning('UK fees due mail skipped: batch not found', [
                        'student_id' => $student->id,
                        'student_batch_id' => $student_batch->id,
                    ]);
                    continue;
                }

                $batchSchedule = BatchSchedule::where('batch_id', $batch->id)->pluck('weekday')->toArray();

                $studentTz = $student->kids_time_zone ?: 'Asia/Kolkata';
                $nextCarbon = nextClassDateForUkBatch($batch, $batchSchedule, $studentTz);
                $next_date  = $nextCarbon ? $nextCarbon->format('Y-m-d') : null; // or ->toFormattedDateString()

                if (!empty(optional($student->user)->email)) {
                    Mail::to($student->user->email)->send(new FeesDueMail($student, $next_date));
                }
            } catch (\Throwable $e) {
                Log::error('UK fees due cron failed for student', [
                    'student_id' => $student->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}


function nextClassDateForUkBatch($batch, array $batchSchedule, ?string $timezone = 'Asia/Kolkata'): ?Carbon
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

    return null; // none found within range
}
