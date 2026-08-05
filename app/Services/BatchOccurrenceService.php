<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\BatchSchedule;
use App\Models\CoachAttendance;
use App\Models\Coverupclass;
use App\Models\DelayedBatch;
use App\Models\Holiday;
use App\Models\LeaveRequest;
use App\Models\StudentAttendance;
use App\Models\StudentBatch;
use App\Models\StudentFee;
use Illuminate\Support\Carbon;

class BatchOccurrenceService
{
    public function approvedLeaveForSchedule(int $coachId, string $date, string $fromTime, string $toTime): ?LeaveRequest
    {
        return LeaveRequest::where('coach_id', $coachId)
            ->where('status', 'APPROVED')
            ->whereDate('from_date', $date)
            ->get()
            ->first(function (LeaveRequest $leave) use ($fromTime, $toTime) {
                return $this->timeRangesOverlap(
                    $fromTime,
                    $toTime,
                    $leave->from_time,
                    $leave->to_time
                );
            });
    }

    public function coverupForOccurrence(int $batchId, int $scheduleId, string $date): ?Coverupclass
    {
        return Coverupclass::where('batch_id', $batchId)
            ->where('batchschedule_id', $scheduleId)
            ->whereDate('date', $date)
            ->first();
    }

    public function holidayForBatch(Batch $batch, string $date): ?Holiday
    {
        $batchCountries = $this->normalizeCountries($batch->country ?? []);

        if (empty($batchCountries)) {
            return null;
        }

        return Holiday::where('status', 'ACTIVE')
            ->whereDate('start_date', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $date);
            })
            ->get()
            ->first(function (Holiday $holiday) use ($batchCountries) {
                $holidayCountries = $this->normalizeCountries($holiday->country ?? []);

                return ! empty($holidayCountries)
                    && empty(array_diff($batchCountries, $holidayCountries));
            });
    }

    public function markHolidayOccurrence(Batch $batch, BatchSchedule $schedule, string $date): bool
    {
        $this->clearDelayedPenalty($batch->id, $schedule->id, $date);

        $existingAttendance = CoachAttendance::where('batch_id', $batch->id)
            ->where('coach_id', $batch->coach_id)
            ->whereDate('date', $date)
            ->orderByDesc('id')
            ->first();

        if ($existingAttendance) {
            if ($existingAttendance->status === 'CANCELLED') {
                $existingAttendance->status = 'HOLIDAY';
                $existingAttendance->save();

                StudentAttendance::where('batch_id', $batch->id)
                    ->whereDate('date', $date)
                    ->where('status', 'CANCELLED')
                    ->update(['remark' => 'Cancelled due to holiday']);
            }

            return false;
        }

        $latestCoachAttendance = CoachAttendance::where('batch_id', $batch->id)
            ->orderByDesc('id')
            ->first();

        $coachAttendance = new CoachAttendance();
        $coachAttendance->coach_id = $batch->coach_id;
        $coachAttendance->type = 'BATCH';
        $coachAttendance->batch_id = $batch->id;
        $coachAttendance->date = $date;
        $coachAttendance->time = $schedule->from_time;
        $coachAttendance->status = 'HOLIDAY';
        $coachAttendance->homework_link = '';
        $coachAttendance->recording_link = '';
        $coachAttendance->chapter_name = '';
        $coachAttendance->number_of_batch_sessions = $latestCoachAttendance
            ? $latestCoachAttendance->number_of_batch_sessions
            : 0;
        $coachAttendance->save();

        $this->shiftCancelledOccurrence(
            $batch,
            $schedule,
            $date,
            'Cancelled due to holiday',
            $coachAttendance->number_of_batch_sessions
        );

        return true;
    }

    public function markApprovedLeaveOccurrence(Batch $batch, BatchSchedule $schedule, string $date): bool
    {
        $this->clearDelayedPenalty($batch->id, $schedule->id, $date);

        $existingAttendance = CoachAttendance::where('batch_id', $batch->id)
            ->where('coach_id', $batch->coach_id)
            ->whereDate('date', $date)
            ->orderByDesc('id')
            ->first();

        if ($existingAttendance) {
            if ($existingAttendance->status === 'CANCELLED') {
                $existingAttendance->status = 'ON LEAVE';
                $existingAttendance->save();

                StudentAttendance::where('batch_id', $batch->id)
                    ->whereDate('date', $date)
                    ->where('status', 'CANCELLED')
                    ->update(['remark' => 'Cancelled due to approved coach leave']);
            }

            return false;
        }

        $latestCoachAttendance = CoachAttendance::where('batch_id', $batch->id)
            ->orderByDesc('id')
            ->first();

        $coachAttendance = new CoachAttendance();
        $coachAttendance->coach_id = $batch->coach_id;
        $coachAttendance->type = 'BATCH';
        $coachAttendance->batch_id = $batch->id;
        $coachAttendance->date = $date;
        $coachAttendance->time = $schedule->from_time;
        $coachAttendance->status = 'ON LEAVE';
        $coachAttendance->homework_link = '';
        $coachAttendance->recording_link = '';
        $coachAttendance->chapter_name = '';
        $coachAttendance->number_of_batch_sessions = $latestCoachAttendance
            ? $latestCoachAttendance->number_of_batch_sessions
            : 0;
        $coachAttendance->save();

        $this->shiftCancelledOccurrence(
            $batch,
            $schedule,
            $date,
            'Cancelled due to approved coach leave',
            $coachAttendance->number_of_batch_sessions
        );

        return true;
    }

    public function clearDelayedPenalty(int $batchId, int $scheduleId, string $date): void
    {
        DelayedBatch::where('batch_id', $batchId)
            ->where('batchschedule_id', $scheduleId)
            ->whereDate('date', $date)
            ->delete();
    }

    public function shiftCancelledOccurrence(
        Batch $batch,
        BatchSchedule $schedule,
        string $date,
        string $remark = 'Batch Cancelled',
        ?int $numberOfBatchSessions = null
    ): void {
        $batchSchedules = BatchSchedule::where('batch_id', $batch->id)
            ->where('status', 'ACTIVE')
            ->get();

        $scheduledDays = $batchSchedules->pluck('weekday')
            ->map(fn ($day) => strtolower($day))
            ->filter()
            ->values()
            ->toArray();

        if (empty($scheduledDays)) {
            return;
        }

        $batch->end_date = $this->nextScheduledDate(Carbon::parse($batch->end_date), $scheduledDays)->toDateString();
        $batch->save();

        $studentBatches = StudentBatch::where('batch_id', $batch->id)
            ->eligibleOn($date)
            ->get();

        foreach ($studentBatches as $studentBatch) {
            StudentAttendance::firstOrCreate(
                [
                    'student_id' => $studentBatch->student_id,
                    'batch_id' => $batch->id,
                    'date' => $date,
                ],
                [
                    'level_id' => $batch->level_id,
                    'time' => $schedule->from_time,
                    'status' => 'CANCELLED',
                    'remark' => $remark,
                    'type' => 'BATCH',
                    'coach_id' => $batch->coach_id,
                    'homework_link' => '',
                    'recording_link' => '',
                    'chapter_name' => '',
                    'number_of_batch_sessions' => $numberOfBatchSessions ?? 0,
                ]
            );

            $studentBatch->end_date = $batch->end_date;
            $studentBatch->save();

            $studentLatestFee = StudentFee::where('student_id', $studentBatch->student_id)
                ->orderByDesc('id')
                ->first();

            if ($studentLatestFee) {
                $studentLatestFee->end_date = $this->nextScheduledDate(
                    Carbon::parse($studentLatestFee->end_date),
                    $scheduledDays
                )->toDateString();
                $studentLatestFee->save();
            }
        }
    }

    public function timeRangesOverlap(?string $fromA, ?string $toA, ?string $fromB, ?string $toB): bool
    {
        if (!$fromA || !$toA || !$fromB || !$toB) {
            return false;
        }

        $startA = Carbon::parse($fromA);
        $endA = Carbon::parse($toA);
        $startB = Carbon::parse($fromB);
        $endB = Carbon::parse($toB);

        if ($endA->lessThanOrEqualTo($startA)) {
            $endA->addDay();
        }

        if ($endB->lessThanOrEqualTo($startB)) {
            $endB->addDay();
        }

        return $startA->lt($endB) && $endA->gt($startB);
    }

    private function nextScheduledDate(Carbon $fromDate, array $scheduledDays): Carbon
    {
        $nextScheduledDay = null;

        foreach ($scheduledDays as $day) {
            $dayDifference = (Carbon::parse($day)->dayOfWeek - $fromDate->dayOfWeek + 7) % 7;
            if ($dayDifference > 0) {
                $nextScheduledDay = $fromDate->copy()->addDays($dayDifference);
                break;
            }
        }

        if ($nextScheduledDay) {
            return $nextScheduledDay;
        }

        return $fromDate->copy()->addDays(
            (Carbon::parse($scheduledDays[0])->dayOfWeek - $fromDate->dayOfWeek + 7) % 7
        );
    }

    private function normalizeCountries($countries): array
    {
        if (is_string($countries)) {
            $decoded = json_decode($countries, true);
            $countries = json_last_error() === JSON_ERROR_NONE ? $decoded : explode(',', $countries);
        }

        if (! is_array($countries)) {
            return [];
        }

        return collect($countries)
            ->flatten()
            ->filter()
            ->map(fn ($country) => strtoupper(trim((string) $country)))
            ->unique()
            ->values()
            ->toArray();
    }
}
