<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\BatchClassSession;
use App\Models\BatchSchedule;
use App\Models\Coach;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BatchClassSessionService
{
    private BatchOccurrenceService $occurrences;

    public function __construct(BatchOccurrenceService $occurrences)
    {
        $this->occurrences = $occurrences;
    }

    public function find(Batch $batch, BatchSchedule $schedule, string $date): ?BatchClassSession
    {
        return BatchClassSession::where('batch_id', $batch->id)
            ->where('batch_schedule_id', $schedule->id)
            ->whereDate('class_date', Carbon::parse($date)->toDateString())
            ->first();
    }

    public function startUrl(Batch $batch, BatchSchedule $schedule, string $date): ?string
    {
        $session = $this->find($batch, $schedule, $date);

        if ($session?->status === BatchClassSession::STATUS_BLOCKED) {
            return null;
        }

        return $this->hasUsableMeeting($session) ? $session->start_url : $batch->start_url;
    }

    public function joinUrl(Batch $batch, BatchSchedule $schedule, string $date): ?string
    {
        $session = $this->find($batch, $schedule, $date);

        if ($session?->status === BatchClassSession::STATUS_BLOCKED) {
            return null;
        }

        return $this->hasUsableMeeting($session) ? $session->join_url : $batch->join_url;
    }

    public function ensureStartUrl(Batch $batch, BatchSchedule $schedule, string $date): ?string
    {
        $session = $this->ensure($batch, $schedule, $date);

        if ($session->status === BatchClassSession::STATUS_BLOCKED) {
            return null;
        }

        return $this->hasUsableMeeting($session) ? $session->start_url : $batch->start_url;
    }

    public function ensureJoinUrl(Batch $batch, BatchSchedule $schedule, string $date): ?string
    {
        $session = $this->ensure($batch, $schedule, $date);

        if ($session->status === BatchClassSession::STATUS_BLOCKED) {
            return null;
        }

        return $this->hasUsableMeeting($session) ? $session->join_url : $batch->join_url;
    }

    public function ensure(Batch $batch, BatchSchedule $schedule, string $date): BatchClassSession
    {
        $date = Carbon::parse($date)->toDateString();
        $lock = Cache::lock("batch-class-session:{$batch->id}:{$schedule->id}:{$date}", 120);

        return $lock->block(10, function () use ($batch, $schedule, $date) {
            $batch->refresh();
            $schedule->refresh();
            [$scheduledStart, $scheduledEnd] = $this->scheduledWindow($date, $schedule);

            $session = BatchClassSession::firstOrCreate(
                [
                    'batch_id' => $batch->id,
                    'batch_schedule_id' => $schedule->id,
                    'class_date' => $date,
                ],
                $this->snapshot($batch, $schedule, $scheduledStart, $scheduledEnd)
            );

            $blockedReason = $this->blockedReason($batch, $schedule, $date);
            if ($blockedReason) {
                return $this->block($session, $blockedReason);
            }

            if ($session->status === BatchClassSession::STATUS_COMPLETED) {
                return $session;
            }

            if ($session->status === BatchClassSession::STATUS_READY
                && $this->snapshotMatches($session, $batch, $schedule, $scheduledStart, $scheduledEnd)) {
                return $session;
            }

            if ($session->status === BatchClassSession::STATUS_CREATING
                && $session->last_attempted_at?->gt(now()->subMinutes(10))) {
                return $session;
            }

            if ($session->status === BatchClassSession::STATUS_FAILED
                && $session->last_attempted_at?->gt(now()->subMinutes(5))) {
                return $session;
            }

            $coach = $batch->coach;
            if (! $this->hasZoomCredentials($coach)) {
                return $this->fail($session, 'Zoom credentials are incomplete for the assigned coach.');
            }

            $previousMeetingId = $session->zoom_meeting_id;
            $previousCoach = $session->coach;
            $newSnapshot = $this->snapshot($batch, $schedule, $scheduledStart, $scheduledEnd);

            $session->status = BatchClassSession::STATUS_CREATING;
            $session->creation_attempts++;
            $session->last_attempted_at = now();
            $session->last_error = null;
            $session->save();

            $response = null;
            try {
                $zoom = $this->zoomFor($coach);
                $response = $zoom->createScheduledUserMeeting([
                    'title' => "Batch - {$coach->user->first_name} - {$batch->name} - {$date}",
                    'duration_in_minute' => max(1, $scheduledStart->diffInMinutes($scheduledEnd)),
                    'start_date_time' => $scheduledStart->toIso8601String(),
                    'timezone' => config('app.timezone', 'Asia/Kolkata'),
                ], $coach->zoom_user_id);

                if (empty($response['start_url']) || empty($response['join_url']) || empty($response['id'])) {
                    throw new \RuntimeException('Zoom returned incomplete meeting details.');
                }

                $session->update(array_merge($newSnapshot, [
                    'status' => BatchClassSession::STATUS_READY,
                    'start_url' => $response['start_url'],
                    'join_url' => $response['join_url'],
                    'zoom_meeting_id' => (string) $response['id'],
                    'zoom_meeting_uuid' => $response['uuid'] ?? null,
                    'last_error' => null,
                ]));

                if ($previousMeetingId && (string) $previousMeetingId !== (string) $response['id']) {
                    $this->deleteZoomMeetingById($previousMeetingId, $previousCoach);
                }
            } catch (\Throwable $exception) {
                if (! empty($response['id'])) {
                    $this->deleteZoomMeetingById((string) $response['id'], $coach);
                }

                Log::error('Regular batch Zoom occurrence creation failed.', [
                    'batch_id' => $batch->id,
                    'batch_schedule_id' => $schedule->id,
                    'class_date' => $date,
                    'message' => $exception->getMessage(),
                ]);

                return $this->fail($session, $exception->getMessage());
            }

            return $session->fresh();
        });
    }

    public function reconcile(Carbon $from, Carbon $to): array
    {
        $counts = ['ready' => 0, 'failed' => 0, 'blocked' => 0];
        $from = $from->copy()->timezone(config('app.timezone'))->startOfMinute();
        $to = $to->copy()->timezone(config('app.timezone'))->endOfMinute();

        for ($date = $from->copy()->startOfDay(); $date->lte($to); $date->addDay()) {
            $dateString = $date->toDateString();
            $weekday = $date->format('l');

            Batch::with(['coach.user', 'batchSchedules' => function ($query) use ($weekday) {
                $query->where('status', 'ACTIVE')->where('weekday', $weekday);
            }])
                ->where('status', 'ACTIVE')
                ->where(function ($query) use ($dateString) {
                    $query->whereNull('start_date')->orWhereDate('start_date', '<=', $dateString);
                })
                ->where(function ($query) use ($dateString) {
                    $query->whereNull('end_date')->orWhereDate('end_date', '>=', $dateString);
                })
                ->whereHas('batchSchedules', function ($query) use ($weekday) {
                    $query->where('status', 'ACTIVE')->where('weekday', $weekday);
                })
                ->chunkById(100, function ($batches) use ($dateString, $from, $to, &$counts) {
                    foreach ($batches as $batch) {
                        foreach ($batch->batchSchedules as $schedule) {
                            [$start] = $this->scheduledWindow($dateString, $schedule);
                            if ($start->lt($from) || $start->gt($to)) {
                                continue;
                            }

                            try {
                                $session = $this->ensure($batch, $schedule, $dateString);
                                $key = strtolower($session->status);
                                if (array_key_exists($key, $counts)) {
                                    $counts[$key]++;
                                }
                            } catch (\Throwable $exception) {
                                $counts['failed']++;
                                Log::error('Unable to reconcile regular batch occurrence.', [
                                    'batch_id' => $batch->id,
                                    'batch_schedule_id' => $schedule->id,
                                    'class_date' => $dateString,
                                    'message' => $exception->getMessage(),
                                ]);
                            }
                        }
                    }
                });
        }

        $this->blockInvalidFutureSessions($from);

        return $counts;
    }

    public function sessionForMeeting(string $meetingId): ?BatchClassSession
    {
        return BatchClassSession::with(['batch', 'schedule', 'coach'])
            ->where('zoom_meeting_id', $meetingId)
            ->first();
    }

    private function blockedReason(Batch $batch, BatchSchedule $schedule, string $date): ?string
    {
        if ($batch->status !== 'ACTIVE' || $schedule->status !== 'ACTIVE') {
            return 'Batch or schedule is not active.';
        }

        if ($schedule->batch_id !== $batch->id || Carbon::parse($date)->format('l') !== $schedule->weekday) {
            return 'Schedule no longer matches this occurrence.';
        }

        if (($batch->start_date && $date < Carbon::parse($batch->start_date)->toDateString())
            || ($batch->end_date && $date > Carbon::parse($batch->end_date)->toDateString())) {
            return 'Occurrence is outside the batch date range.';
        }

        if ($this->occurrences->holidayForBatch($batch, $date, $schedule)) {
            return 'Occurrence is blocked by a holiday.';
        }

        if ($this->occurrences->approvedLeaveForSchedule(
            $batch->coach_id,
            $date,
            $schedule->from_time,
            $schedule->to_time
        )) {
            return 'Occurrence is blocked by approved coach leave.';
        }

        if ($this->occurrences->coverupForOccurrence($batch->id, $schedule->id, $date)) {
            return 'Occurrence is handled by a cover-up class.';
        }

        return null;
    }

    private function block(BatchClassSession $session, string $reason): BatchClassSession
    {
        if ($session->zoom_meeting_id) {
            $this->deleteZoomMeeting($session);
        }

        $session->update([
            'status' => BatchClassSession::STATUS_BLOCKED,
            'start_url' => null,
            'join_url' => null,
            'zoom_meeting_id' => null,
            'zoom_meeting_uuid' => null,
            'last_error' => $reason,
        ]);

        return $session->fresh();
    }

    private function fail(BatchClassSession $session, string $message): BatchClassSession
    {
        $session->update([
            'status' => BatchClassSession::STATUS_FAILED,
            'last_error' => mb_substr($message, 0, 65000),
        ]);

        return $session->fresh();
    }

    private function snapshot(Batch $batch, BatchSchedule $schedule, Carbon $start, Carbon $end): array
    {
        return [
            'coach_id' => $batch->coach_id,
            'scheduled_start_at' => $start,
            'scheduled_end_at' => $end,
            'weekday' => $schedule->weekday,
            'from_time' => $schedule->from_time,
            'to_time' => $schedule->to_time,
        ];
    }

    private function snapshotMatches(
        BatchClassSession $session,
        Batch $batch,
        BatchSchedule $schedule,
        Carbon $start,
        Carbon $end
    ): bool {
        return (int) $session->coach_id === (int) $batch->coach_id
            && $session->scheduled_start_at?->equalTo($start)
            && $session->scheduled_end_at?->equalTo($end)
            && $session->weekday === $schedule->weekday
            && Carbon::parse($session->from_time)->format('H:i:s') === Carbon::parse($schedule->from_time)->format('H:i:s')
            && Carbon::parse($session->to_time)->format('H:i:s') === Carbon::parse($schedule->to_time)->format('H:i:s');
    }

    private function scheduledWindow(string $date, BatchSchedule $schedule): array
    {
        $timezone = config('app.timezone', 'Asia/Kolkata');
        $start = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . Carbon::parse($schedule->from_time)->format('H:i:s'), $timezone);
        $end = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . Carbon::parse($schedule->to_time)->format('H:i:s'), $timezone);

        if ($end->lessThanOrEqualTo($start)) {
            $end->addDay();
        }

        return [$start, $end];
    }

    private function hasZoomCredentials(?Coach $coach): bool
    {
        return $coach
            && $coach->zoom_api_key
            && $coach->zoom_client_secret
            && $coach->zoom_id
            && $coach->zoom_user_id;
    }

    private function hasUsableMeeting(?BatchClassSession $session): bool
    {
        return $session
            && in_array($session->status, [
                BatchClassSession::STATUS_READY,
                BatchClassSession::STATUS_COMPLETED,
            ], true)
            && $session->start_url
            && $session->join_url;
    }

    private function zoomFor(Coach $coach): ZoomMeetingService
    {
        return new ZoomMeetingService($coach->zoom_api_key, $coach->zoom_client_secret, $coach->zoom_id);
    }

    private function deleteZoomMeeting(BatchClassSession $session): void
    {
        $this->deleteZoomMeetingById($session->zoom_meeting_id, $session->coach, $session->id);
    }

    private function deleteZoomMeetingById(?string $meetingId, ?Coach $coach, ?int $sessionId = null): void
    {
        if (! $meetingId || ! $this->hasZoomCredentials($coach)) {
            return;
        }

        try {
            $this->zoomFor($coach)->deleteMeeting($meetingId);
        } catch (\Throwable $exception) {
            Log::warning('Unable to delete superseded regular batch Zoom meeting.', [
                'batch_class_session_id' => $sessionId,
                'zoom_meeting_id' => $meetingId,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    private function blockInvalidFutureSessions(Carbon $from): void
    {
        BatchClassSession::with(['batch.coach.user', 'schedule', 'coach'])
            ->where('scheduled_start_at', '>=', $from)
            ->whereIn('status', [
                BatchClassSession::STATUS_PENDING,
                BatchClassSession::STATUS_CREATING,
                BatchClassSession::STATUS_READY,
                BatchClassSession::STATUS_FAILED,
            ])
            ->chunkById(100, function ($sessions) {
                foreach ($sessions as $session) {
                    if (! $session->batch || ! $session->schedule) {
                        $this->block($session, 'Batch schedule was removed.');
                        continue;
                    }

                    $reason = $this->blockedReason(
                        $session->batch,
                        $session->schedule,
                        $session->class_date->toDateString()
                    );

                    if ($reason) {
                        $this->block($session, $reason);
                    }
                }
            });
    }
}
