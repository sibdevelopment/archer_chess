<?php

namespace App\Console\Commands;

use App\Mail\FeesDueMail;
use App\Models\Batch;
use App\Models\BatchSchedule;
use App\Models\Student;
use App\Models\StudentBatch;
use App\Models\StudentFee;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MarkFeesDueBySchedule extends Command
{
    protected $signature = 'fees:mark-due-by-schedule
        {--dry-run : Show who would be marked without changing data}
        {--buffer=15 : Minutes to wait after the last scheduled class end time}
        {--no-class-cutoff=12:00 : IST time to mark fee due when fee ends today but no class is scheduled}';

    protected $description = 'Mark fee-due students after their last scheduled class on fee end date, with backfill.';

    public function handle()
    {
        $lockKey = 'fees:mark-due-by-schedule:running';
        if (! Cache::add($lockKey, true, now()->addMinutes(25))) {
            $this->warn('Another fees:mark-due-by-schedule run is already active.');
            return self::SUCCESS;
        }

        $dryRun = (bool) $this->option('dry-run');
        $bufferMinutes = max(0, (int) $this->option('buffer'));
        $noClassCutoff = (string) $this->option('no-class-cutoff');
        $now = Carbon::now('Asia/Kolkata');
        $today = $now->toDateString();

        $this->info(($dryRun ? '[DRY RUN] ' : '') . "Fee due schedule check started at {$now->toDateTimeString()} IST");

        $marked = 0;
        $skipped = 0;
        $errors = 0;
        $staleFeesInactivated = 0;

        try {
            $staleFeesInactivated = $this->inactivateStaleActiveFees($today, $dryRun);

            Student::where('status', '!=', 'FEESDUE')
                ->orderBy('id')
                ->chunkById(100, function ($students) use ($dryRun, $bufferMinutes, $noClassCutoff, $now, $today, &$marked, &$skipped, &$errors) {
                    foreach ($students as $student) {
                        try {
                            $studentFee = $this->latestActiveFee($student);

                            if (! $studentFee) {
                                $skipped++;
                                $this->logDecision('SKIPPED_NO_ACTIVE_FEE', $student);
                                continue;
                            }

                            $feeEndDate = Carbon::parse($studentFee->end_date, 'Asia/Kolkata')->toDateString();

                            if ($feeEndDate > $today) {
                                $skipped++;
                                $this->logDecision('SKIPPED_FEE_NOT_ENDING_YET', $student, $studentFee);
                                continue;
                            }

                            if ($feeEndDate < $today) {
                                $this->markFeesDue($student, $studentFee, $feeEndDate, 'BACKFILL_END_DATE_PASSED', $dryRun);
                                $marked++;
                                continue;
                            }

                            $latestEndAt = $this->latestScheduleEndAtForStudent($student, $feeEndDate, $bufferMinutes);

                            if (! $latestEndAt) {
                                $cutoffAt = $this->noClassCutoffAt($feeEndDate, $noClassCutoff);

                                if ($now->lt($cutoffAt)) {
                                    $skipped++;
                                    $this->logDecision('SKIPPED_NO_CLASS_TODAY_CUTOFF_PENDING', $student, $studentFee, [
                                        'fee_end_date' => $feeEndDate,
                                        'mark_after' => $cutoffAt->toDateTimeString(),
                                    ]);
                                    continue;
                                }

                                $this->markFeesDue($student, $studentFee, $feeEndDate, 'TODAY_NO_CLASS_CUTOFF_REACHED', $dryRun, [
                                    'mark_after' => $cutoffAt->toDateTimeString(),
                                ]);
                                $marked++;
                                continue;
                            }

                            if ($now->lt($latestEndAt)) {
                                $skipped++;
                                $this->logDecision('SKIPPED_CLASS_PENDING', $student, $studentFee, [
                                    'fee_end_date' => $feeEndDate,
                                    'mark_after' => $latestEndAt->toDateTimeString(),
                                ]);
                                continue;
                            }

                            $this->markFeesDue($student, $studentFee, $feeEndDate, 'TODAY_LAST_CLASS_COMPLETED', $dryRun, [
                                'mark_after' => $latestEndAt->toDateTimeString(),
                            ]);
                            $marked++;
                        } catch (\Throwable $e) {
                            $errors++;
                            Log::error('Schedule based fees due cron failed for student', [
                                'student_db_id' => $student->id,
                                'student_id' => $student->student_id,
                                'error' => $e->getMessage(),
                            ]);
                            $this->error("Student {$student->id} failed: {$e->getMessage()}");
                        }
                    }
                });
        } finally {
            Cache::forget($lockKey);
        }

        $this->info("Fee due schedule check finished. Marked: {$marked}, stale fees inactivated: {$staleFeesInactivated}, skipped: {$skipped}, errors: {$errors}");

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function inactivateStaleActiveFees(string $today, bool $dryRun): int
    {
        $query = StudentFee::query()
            ->from('student_fees as sf')
            ->join('students as s', 's.id', '=', 'sf.student_id')
            ->where('sf.status', 'ACTIVE')
            ->whereDate('sf.end_date', '<', $today)
            ->where(function ($query) {
                $query->where('s.status', 'FEESDUE')
                    ->orWhereExists(function ($exists) {
                        $exists->selectRaw('1')
                            ->from('student_fees as newer_sf')
                            ->whereColumn('newer_sf.student_id', 'sf.student_id')
                            ->where('newer_sf.status', 'ACTIVE')
                            ->where(function ($newer) {
                                $newer->whereColumn('newer_sf.end_date', '>', 'sf.end_date')
                                    ->orWhere(function ($sameEndDate) {
                                        $sameEndDate->whereColumn('newer_sf.end_date', 'sf.end_date')
                                            ->whereColumn('newer_sf.id', '>', 'sf.id');
                                    });
                            });
                    });
            });

        $count = (clone $query)->count('sf.id');

        if ($count === 0) {
            return 0;
        }

        if ($dryRun) {
            Log::info('Schedule based fees due stale active fee dry-run cleanup', [
                'count' => $count,
                'before_date' => $today,
            ]);
            $this->line("[DRY RUN] STALE_ACTIVE_FEES: {$count} fee rows would be marked INACTIVE");
            return $count;
        }

        $ids = (clone $query)->pluck('sf.id');

        StudentFee::whereIn('id', $ids)->update(['status' => 'INACTIVE']);

        Log::info('Schedule based fees due stale active fees inactivated', [
            'count' => $count,
            'before_date' => $today,
        ]);

        return $count;
    }

    private function latestActiveFee(Student $student): ?StudentFee
    {
        return StudentFee::where('student_id', $student->id)
            ->where('status', 'ACTIVE')
            ->orderBy('end_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();
    }

    private function latestScheduleEndAtForStudent(Student $student, string $date, int $bufferMinutes): ?Carbon
    {
        $weekday = Carbon::parse($date, 'Asia/Kolkata')->format('l');

        $activeBatchIds = StudentBatch::where('student_id', $student->id)
            ->eligibleOn($date)
            ->pluck('batch_id')
            ->unique()
            ->values();

        if ($activeBatchIds->isEmpty()) {
            return null;
        }

        $schedules = BatchSchedule::whereIn('batch_id', $activeBatchIds)
            ->where('status', 'ACTIVE')
            ->where('weekday', $weekday)
            ->whereNotNull('from_time')
            ->whereNotNull('to_time')
            ->get();

        if ($schedules->isEmpty()) {
            return null;
        }

        return $schedules
            ->map(function ($schedule) use ($date, $bufferMinutes) {
                $fromAt = Carbon::parse($date . ' ' . $schedule->from_time, 'Asia/Kolkata');
                $toAt = Carbon::parse($date . ' ' . $schedule->to_time, 'Asia/Kolkata');

                if ($toAt->lte($fromAt)) {
                    $toAt->addDay();
                }

                return $toAt->addMinutes($bufferMinutes);
            })
            ->sort()
            ->last();
    }

    private function noClassCutoffAt(string $date, string $cutoff): Carbon
    {
        if (! preg_match('/^\d{2}:\d{2}$/', $cutoff)) {
            $cutoff = '12:00';
        }

        return Carbon::parse($date . ' ' . $cutoff, 'Asia/Kolkata');
    }

    private function markFeesDue(Student $student, StudentFee $studentFee, string $feeEndDate, string $reason, bool $dryRun, array $context = []): void
    {
        $activeBatch = StudentBatch::where('student_id', $student->id)
            ->where('status', 'ACTIVE')
            ->latest('id')
            ->first();

        $logContext = array_merge([
            'reason' => $reason,
            'dry_run' => $dryRun,
            'student_db_id' => $student->id,
            'student_id' => $student->student_id,
            'student_fee_id' => $studentFee->id,
            'fee_end_date' => $feeEndDate,
            'student_batch_id' => optional($activeBatch)->id,
            'batch_id' => optional($activeBatch)->batch_id,
        ], $context);

        if ($dryRun) {
            Log::info('Schedule based fees due dry-run match', $logContext);
            $this->line("[DRY RUN] {$reason}: {$student->student_id} fee {$studentFee->id}");
            return;
        }

        DB::transaction(function () use ($student, $activeBatch, $feeEndDate) {
            $student->status = 'FEESDUE';
            $student->save();

            StudentFee::where('student_id', $student->id)
                ->where('status', 'ACTIVE')
                ->whereDate('end_date', '<=', $feeEndDate)
                ->update(['status' => 'INACTIVE']);

            if ($activeBatch) {
                $activeBatch->status = 'INACTIVE';
                $activeBatch->is_fees_due = 1;
                $activeBatch->end_date = $feeEndDate;
                $activeBatch->end_time = Carbon::now('Asia/Kolkata')->format('H:i:s');
                $activeBatch->save();
            }
        });

        Log::info('Schedule based fees due marked', $logContext);
        $this->info("Marked {$student->student_id} as FEESDUE ({$reason})");

        if (! $activeBatch) {
            Log::warning('Schedule based fees due batch update skipped: active student batch not found', $logContext);
            return;
        }

        $batch = Batch::find($activeBatch->batch_id);
        if (! $batch) {
            Log::warning('Schedule based fees due mail skipped: batch not found', $logContext);
            return;
        }

        $nextDate = $this->nextClassDateForBatch($batch);
        if (! empty(optional($student->user)->email)) {
            Mail::to($student->user->email)->send(new FeesDueMail($student, $nextDate));
        }
    }

    private function nextClassDateForBatch(Batch $batch): ?string
    {
        $weekdays = BatchSchedule::where('batch_id', $batch->id)
            ->where('status', 'ACTIVE')
            ->pluck('weekday')
            ->toArray();

        if (empty($weekdays)) {
            return null;
        }

        $map = [
            'sunday' => Carbon::SUNDAY,
            'monday' => Carbon::MONDAY,
            'tuesday' => Carbon::TUESDAY,
            'wednesday' => Carbon::WEDNESDAY,
            'thursday' => Carbon::THURSDAY,
            'friday' => Carbon::FRIDAY,
            'saturday' => Carbon::SATURDAY,
        ];

        $allowed = array_values(array_filter(array_map(function ($day) use ($map) {
            return $map[strtolower(trim($day))] ?? null;
        }, $weekdays), fn ($day) => $day !== null));

        if (empty($allowed)) {
            return null;
        }

        $start = Carbon::parse($batch->start_date, 'Asia/Kolkata')->startOfDay();
        $end = Carbon::parse($batch->end_date, 'Asia/Kolkata')->endOfDay();
        $cursor = Carbon::now('Asia/Kolkata')->startOfDay();

        if ($cursor->lt($start)) {
            $cursor = $start->copy();
        }

        for ($i = 0; $i < 14; $i++) {
            if ($cursor->betweenIncluded($start, $end) && in_array($cursor->dayOfWeek, $allowed, true)) {
                return $cursor->format('Y-m-d');
            }
            $cursor->addDay();
        }

        return null;
    }

    private function logDecision(string $reason, Student $student, ?StudentFee $studentFee = null, array $context = []): void
    {
        Log::debug('Schedule based fees due skipped', array_merge([
            'reason' => $reason,
            'student_db_id' => $student->id,
            'student_id' => $student->student_id,
            'student_fee_id' => optional($studentFee)->id,
        ], $context));
    }
}
