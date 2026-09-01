<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Batch;
use App\Models\DemoLead;
use App\Models\DemoSession;
use App\Models\StudentBatch;
use App\Models\StudentFee;  
use App\Models\BatchSchedule;
use App\Models\CoachAttendance;
use App\Models\Coverupclass;
use Illuminate\Console\Command;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\Log;
use App\Models\DelayedBatch;
use App\Services\BatchOccurrenceService;

class CancelDelayBatch extends Command
{
    protected $signature = 'cancel:delay-batch';
    protected $description = 'Track delayed batches and cancel if no coach attendance after the allowed delay from start time';

    public function handle()
    {
        $occurrences = app(BatchOccurrenceService::class);
        $now = Carbon::now();
        $today = $now->format('l');

        Log::info("CancelDelayBatch command started at {$now->toDateTimeString()} for weekday: {$today}");
        // Get active batches scheduled for today
        $activeBatchIds = Batch::where('status', 'ACTIVE')
            ->whereDate('start_date', '<=', $now->toDateString())
            ->whereDate('end_date', '>=', $now->toDateString())
            ->whereHas('batchSchedules', function ($query) use ($today) {
                $query->where('weekday', $today)
                    ->where('status', 'ACTIVE');
            })
            ->pluck('id');

        $schedules = BatchSchedule::whereIn('batch_id', $activeBatchIds)
            ->where('weekday', $today)
            ->where('status', 'ACTIVE')
            ->get();

        foreach ($schedules as $schedule) {
            $date = $now->toDateString();
            $scheduledStart = Carbon::parse($date . ' ' . $schedule->from_time);
            $lateTime = $scheduledStart->copy()->addMinutes(3);
            $cutoffTime = $scheduledStart->copy()->addMinutes(8);
            $batchId = $schedule->batch_id;
            $delayedBatchKey = [
                'occurrence_type' => 'BATCH',
                'batch_id' => $batchId,
                'batchschedule_id' => $schedule->id,
                'date' => $date,
            ];
            
            $batch = Batch::find($batchId);

            if (! $batch) {
                continue;
            }

            $coverupExists = $occurrences->coverupForOccurrence($batchId, $schedule->id, $date) !== null;

            if ($coverupExists) {
                $this->info("Skipped batch {$schedule->batch_id} due to cover-up class.");
                continue;
            }

            if ($occurrences->holidayForBatch($batch, $date, $schedule)) {
                $occurrences->markHolidayOccurrence($batch, $schedule, $date);
                $this->info("Marked HOLIDAY for batch {$schedule->batch_id}.");
                continue;
            }

            if ($occurrences->approvedLeaveForSchedule($batch->coach_id, $date, $schedule->from_time, $schedule->to_time)) {
                $this->info("Skipped batch {$schedule->batch_id} due to approved coach leave.");
                continue;
            }

            if (! $this->hasActiveStudentsForBatch($batchId, $date)) {
                DelayedBatch::where($delayedBatchKey)->delete();
                $this->info("Skipped batch {$schedule->batch_id} because no active student is eligible for this class.");
                continue;
            }

            $coachAttendanceExists = CoachAttendance::where('batch_id', $batchId)
                ->whereDate('date', $date)
                ->exists();

            if (! $coachAttendanceExists && $now->greaterThan($lateTime)) {
                $cancelledPenaltyExists = DelayedBatch::where($delayedBatchKey)
                    ->where('penalty_type', 'CANCELLED')
                    ->exists();

                if (! $cancelledPenaltyExists) {
                    DelayedBatch::updateOrCreate(
                        $delayedBatchKey,
                        [
                            'coach_id' => $batch->coach_id,
                            'time' => $now->format('H:i:s'),
                            'batch_name' => $batch->name,
                            'country' => $batch->country,
                            'batch_status' => $batch->status,
                            'level_name' => optional($batch->level)->name,
                            'timeline' => sprintf(
                                'Scheduled start: %s | Marked late at: %s',
                                $scheduledStart->format('d-M-Y h:i:s A'),
                                $now->format('d-M-Y h:i:s A')
                            ),
                            'penalty_type' => 'LATE',
                            'fine_amount' => 150,
                            'fine_currency' => 'INR',
                            'late_popup_acknowledged_at' => null,
                            'canceled_date' => null,
                            'canceled_time' => null,
                        ]
                    );
                }
            }

            if ($now->greaterThanOrEqualTo($cutoffTime)) {
                $studentAttendanceExists = StudentAttendance::where('batch_id', $batchId)
                    ->whereDate('date', $date)
                    ->exists();

                if (! $studentAttendanceExists || ! $coachAttendanceExists) {
                    if (!$studentAttendanceExists) {
                        $occurrences->shiftCancelledOccurrence($batch, $schedule, $date, 'Batch Cancelled');
                        $this->info("Marked CANCELLED for students in batch $batchId");
                    }

                    if (! $coachAttendanceExists) {
                        $coachAttendance = CoachAttendance::create([
                            'coach_id' => $batch->coach_id,
                            'batch_id' => $batchId,
                            'type' => $schedule->type ?? null,
                            'level_id' => $schedule->level_id,
                            'date' => $date,
                            'status' => 'CANCELLED',
                        ]);

                        DelayedBatch::where($delayedBatchKey)
                            ->where('penalty_type', 'LATE')
                            ->delete();

                        DelayedBatch::updateOrCreate(
                            $delayedBatchKey,
                            [
                                'coach_id' => $batch->coach_id,
                                'coach_attendance_id' => $coachAttendance->id,
                                'time' => $now->format('H:i:s'),
                                'batch_name' => $batch->name,
                                'country' => $batch->country,
                                'batch_status' => $batch->status,
                                'level_name' => optional($batch->level)->name,
                                'timeline' => sprintf(
                                    'Scheduled start: %s | Auto-cancelled at: %s',
                                    $scheduledStart->format('d-M-Y h:i:s A'),
                                    $now->format('d-M-Y h:i:s A')
                                ),
                                'canceled_date' => $date,
                                'canceled_time' => $now->format('H:i:s'),
                                'penalty_type' => 'CANCELLED',
                                'fine_amount' => 350,
                                'fine_currency' => 'INR',
                                'late_popup_acknowledged_at' => null,
                            ]
                        );

                        $this->info("Marked CANCELLED for coach in batch $batchId");
                    }
                }
            }
        }

        $this->markDelayedCoverupClasses($now, $occurrences);
        $this->markDelayedDemoSessions($now);

        $this->info('Batch auto-cancellation and extension completed.');
    }

    private function hasActiveStudentsForBatch(int $batchId, string $date): bool
    {
        return StudentBatch::where('batch_id', $batchId)
            ->eligibleOn($date)
            ->whereHas('student', function ($query) {
                $query->where('status', 'ACTIVE');
            })
            ->exists();
    }

    private function markDelayedCoverupClasses(Carbon $now, BatchOccurrenceService $occurrences): void
    {
        $date = $now->toDateString();
        $today = $now->format('l');

        $coverups = Coverupclass::with(['batch.level', 'batchSchedule', 'schedule'])
            ->whereDate('date', $date)
            ->whereNotNull('new_coach_id')
            ->get();

        foreach ($coverups as $coverup) {
            $batch = $coverup->batch;

            if (! $batch || $batch->status !== 'ACTIVE') {
                continue;
            }

            $schedule = $coverup->batchSchedule
                ?: $coverup->schedule
                ?: BatchSchedule::where('batch_id', $batch->id)
                    ->where('weekday', $today)
                    ->where('status', 'ACTIVE')
                    ->first();

            if (! $schedule || $schedule->status !== 'ACTIVE') {
                continue;
            }

            if ($occurrences->holidayForBatch($batch, $date, $schedule)) {
                $occurrences->markHolidayOccurrence($batch, $schedule, $date);
                $this->info("Marked HOLIDAY for coverup batch {$batch->id}.");
                continue;
            }

            $scheduledStart = Carbon::parse($date . ' ' . $schedule->from_time);
            $lateTime = $scheduledStart->copy()->addMinutes(3);
            $cutoffTime = $scheduledStart->copy()->addMinutes(8);
            $delayedCoverupKey = [
                'occurrence_type' => 'COVERUP',
                'batch_id' => $batch->id,
                'batchschedule_id' => $schedule->id,
                'date' => $date,
            ];

            $coachAttendance = CoachAttendance::where('batch_id', $batch->id)
                ->where('coach_id', $coverup->new_coach_id)
                ->whereDate('date', $date)
                ->whereRaw('UPPER(type) = ?', ['COVERUP'])
                ->orderByDesc('id')
                ->first();

            if ($coachAttendance) {
                if ($coachAttendance->status !== 'CANCELLED') {
                    DelayedBatch::where($delayedCoverupKey)->delete();
                }

                continue;
            }

            if ($now->greaterThan($lateTime)) {
                $cancelledPenaltyExists = DelayedBatch::where($delayedCoverupKey)
                    ->where('penalty_type', 'CANCELLED')
                    ->exists();

                if (! $cancelledPenaltyExists) {
                    DelayedBatch::updateOrCreate(
                        $delayedCoverupKey,
                        [
                            'coach_id' => $coverup->new_coach_id,
                            'time' => $now->format('H:i:s'),
                            'batch_name' => $batch->name,
                            'country' => $batch->country,
                            'batch_status' => $batch->status,
                            'level_name' => optional($batch->level)->name,
                            'timeline' => sprintf(
                                'Coverup scheduled start: %s | Marked late at: %s',
                                $scheduledStart->format('d-M-Y h:i:s A'),
                                $now->format('d-M-Y h:i:s A')
                            ),
                            'penalty_type' => 'LATE',
                            'fine_amount' => 150,
                            'fine_currency' => 'INR',
                            'late_popup_acknowledged_at' => null,
                            'canceled_date' => null,
                            'canceled_time' => null,
                        ]
                    );
                }
            }

            if ($now->greaterThanOrEqualTo($cutoffTime)) {
                $occurrences->shiftCancelledOccurrence($batch, $schedule, $date, 'Coverup Cancelled');

                $coachAttendance = CoachAttendance::create([
                    'coach_id' => $coverup->new_coach_id,
                    'batch_id' => $batch->id,
                    'type' => 'COVERUP',
                    'level_id' => $schedule->level_id,
                    'date' => $date,
                    'time' => $now->format('H:i:s'),
                    'status' => 'CANCELLED',
                    'number_of_batch_sessions' => 0,
                ]);

                StudentAttendance::where('batch_id', $batch->id)
                    ->whereDate('date', $date)
                    ->where('status', 'CANCELLED')
                    ->update([
                        'type' => 'COVERUP',
                        'coach_id' => $coverup->new_coach_id,
                        'remark' => 'Coverup Cancelled',
                    ]);

                DelayedBatch::where($delayedCoverupKey)
                    ->where('penalty_type', 'LATE')
                    ->delete();

                DelayedBatch::updateOrCreate(
                    $delayedCoverupKey,
                    [
                        'coach_id' => $coverup->new_coach_id,
                        'coach_attendance_id' => $coachAttendance->id,
                        'time' => $now->format('H:i:s'),
                        'batch_name' => $batch->name,
                        'country' => $batch->country,
                        'batch_status' => $batch->status,
                        'level_name' => optional($batch->level)->name,
                        'timeline' => sprintf(
                            'Coverup scheduled start: %s | Auto-cancelled at: %s',
                            $scheduledStart->format('d-M-Y h:i:s A'),
                            $now->format('d-M-Y h:i:s A')
                        ),
                        'canceled_date' => $date,
                        'canceled_time' => $now->format('H:i:s'),
                        'penalty_type' => 'CANCELLED',
                        'fine_amount' => 350,
                        'fine_currency' => 'INR',
                        'late_popup_acknowledged_at' => null,
                    ]
                );

                $this->info("Marked CANCELLED for coverup batch {$batch->id}");
            }
        }
    }

    private function markDelayedDemoSessions(Carbon $now): void
    {
        $date = $now->toDateString();

        $demoSessions = DemoSession::with(['demolead', 'level'])
            ->where('status', 'ACTIVE')
            ->whereDate('date', $date)
            ->whereNotNull('coach_id')
            ->where(function ($query) {
                $query->whereNull('coach_attendance_status')
                    ->orWhereNotIn('coach_attendance_status', ['COMPLETED', 'CANCELLED', 'INACTIVE']);
            })
            ->whereHas('demolead', function ($query) {
                $query->whereIn('status', ['SCHEDULED', 'RESCHEDULED']);
            })
            ->get();

        foreach ($demoSessions as $demoSession) {
            $demoStartTime = $demoSession->time ?: $this->demoSlotStart($demoSession);

            if (! $demoStartTime) {
                continue;
            }

            $scheduledStart = Carbon::parse($date . ' ' . $demoStartTime);

            $lateTime = $scheduledStart->copy()->addMinutes(5);
            $cutoffTime = $scheduledStart->copy()->addMinutes(9);
            $delayedDemoKey = [
                'occurrence_type' => 'DEMO',
                'demo_session_id' => $demoSession->id,
                'demolead_id' => $demoSession->demolead_id,
                'date' => $date,
            ];

            $coachAttendanceExists = CoachAttendance::where('coach_id', $demoSession->coach_id)
                ->where('demolead_id', $demoSession->demolead_id)
                ->whereDate('date', $date)
                ->exists();

            if ($coachAttendanceExists) {
                $this->clearDemoOccurrencePenalties($demoSession, $date, $demoStartTime);
                continue;
            }

            if (! $coachAttendanceExists && $now->greaterThan($lateTime)) {
                $cancelledPenaltyExists = $this->demoOccurrencePenaltyQuery($demoSession, $date, $demoStartTime)
                    ->where('penalty_type', 'CANCELLED')
                    ->exists();

                if (! $cancelledPenaltyExists) {
                    DelayedBatch::updateOrCreate(
                        $delayedDemoKey,
                        [
                            'coach_id' => $demoSession->coach_id,
                            'time' => $now->format('H:i:s'),
                            'batch_name' => 'Demo - ' . trim(optional($demoSession->demolead)->first_name . ' ' . optional($demoSession->demolead)->last_name),
                            'country' => optional($demoSession->demolead)->country ? [optional($demoSession->demolead)->country] : null,
                            'batch_status' => optional($demoSession->demolead)->status,
                            'level_name' => optional($demoSession->level)->name,
                            'timeline' => sprintf(
                                'Demo scheduled start: %s | Marked late at: %s',
                                $scheduledStart->format('d-M-Y h:i:s A'),
                                $now->format('d-M-Y h:i:s A')
                            ),
                            'penalty_type' => 'LATE',
                            'fine_amount' => 100,
                            'fine_currency' => 'INR',
                            'late_popup_acknowledged_at' => null,
                            'canceled_date' => null,
                            'canceled_time' => null,
                        ]
                    );
                }
            }

            if ($now->greaterThanOrEqualTo($cutoffTime) && ! $coachAttendanceExists) {
                $coachAttendance = CoachAttendance::create([
                    'coach_id' => $demoSession->coach_id,
                    'type' => 'Demo',
                    'demolead_id' => $demoSession->demolead_id,
                    'date' => $date,
                    'time' => $now->format('H:i:s'),
                    'status' => 'CANCELLED',
                    'number_of_demo_sessions' => 0,
                ]);

                StudentAttendance::updateOrCreate(
                    ['demolead_id' => $demoSession->demolead_id],
                    [
                        'type' => 'Demo',
                        'coach_id' => $demoSession->coach_id,
                        'demolead_id' => $demoSession->demolead_id,
                        'level_id' => $demoSession->level_id,
                        'status' => 'CANCELLED',
                        'date' => $date,
                        'time' => $now->format('H:i:s'),
                        'remark' => 'Demo Cancelled',
                    ]
                );

                $demoSession->coach_attendance_status = 'CANCELLED';
                $demoSession->save();

                DemoLead::where('id', $demoSession->demolead_id)->update(['status' => 'CANCELLED']);

                $this->demoOccurrencePenaltyQuery($demoSession, $date, $demoStartTime)
                    ->where('penalty_type', 'LATE')
                    ->delete();

                DelayedBatch::updateOrCreate(
                    $delayedDemoKey,
                    [
                        'coach_id' => $demoSession->coach_id,
                        'coach_attendance_id' => $coachAttendance->id,
                        'time' => $now->format('H:i:s'),
                        'batch_name' => 'Demo - ' . trim(optional($demoSession->demolead)->first_name . ' ' . optional($demoSession->demolead)->last_name),
                        'country' => optional($demoSession->demolead)->country ? [optional($demoSession->demolead)->country] : null,
                        'batch_status' => optional($demoSession->demolead)->status,
                        'level_name' => optional($demoSession->level)->name,
                        'timeline' => sprintf(
                            'Demo scheduled start: %s | Auto-cancelled at: %s',
                            $scheduledStart->format('d-M-Y h:i:s A'),
                            $now->format('d-M-Y h:i:s A')
                        ),
                        'canceled_date' => $date,
                        'canceled_time' => $now->format('H:i:s'),
                        'penalty_type' => 'CANCELLED',
                        'fine_amount' => 100,
                        'fine_currency' => 'INR',
                        'late_popup_acknowledged_at' => null,
                    ]
                );

                $this->info("Marked CANCELLED for demo session {$demoSession->id}");
            }
        }
    }

    private function demoSlotStart(DemoSession $demoSession): ?string
    {
        if (! $demoSession->slot || ! str_contains($demoSession->slot, ' - ')) {
            return null;
        }

        return trim(explode(' - ', $demoSession->slot)[0]);
    }

    private function clearDemoOccurrencePenalties(DemoSession $demoSession, string $date, string $demoStartTime): void
    {
        $this->demoOccurrencePenaltyQuery($demoSession, $date, $demoStartTime)->delete();
    }

    private function demoOccurrencePenaltyQuery(DemoSession $demoSession, string $date, string $demoStartTime)
    {
        $demoSessionIds = $this->matchingDemoOccurrenceIds($demoSession, $date, $demoStartTime);

        return DelayedBatch::where('occurrence_type', 'DEMO')
            ->where('demolead_id', $demoSession->demolead_id)
            ->where('coach_id', $demoSession->coach_id)
            ->whereDate('date', $date)
            ->whereIn('demo_session_id', $demoSessionIds);
    }

    private function matchingDemoOccurrenceIds(DemoSession $demoSession, string $date, string $demoStartTime): array
    {
        $demoStartTime = $this->normalizeDemoTime($demoStartTime);

        return DemoSession::where('demolead_id', $demoSession->demolead_id)
            ->where('coach_id', $demoSession->coach_id)
            ->whereDate('date', $date)
            ->get()
            ->filter(function (DemoSession $candidate) use ($demoStartTime) {
                $candidateStart = $candidate->time ?: $this->demoSlotStart($candidate);

                return $candidateStart && $this->normalizeDemoTime($candidateStart) === $demoStartTime;
            })
            ->pluck('id')
            ->push($demoSession->id)
            ->unique()
            ->values()
            ->toArray();
    }

    private function normalizeDemoTime(string $time): string
    {
        return Carbon::parse($time)->format('H:i:s');
    }

}
