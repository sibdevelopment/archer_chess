<?php

namespace App\Services;

use App\Models\BatchSchedule;
use App\Models\Coach;
use App\Models\Coverupclass;
use Carbon\Carbon;
use RuntimeException;

class CoverupMeetingService
{
    public function create(Coverupclass $coverup, Coach $coach, BatchSchedule $schedule): array
    {
        if (! $coach->zoom_id || ! $coach->zoom_api_key || ! $coach->zoom_client_secret || ! $coach->zoom_user_id) {
            throw new RuntimeException('The selected coach does not have complete Zoom credentials.');
        }

        $batch = $coverup->batch;
        if (! $batch) {
            throw new RuntimeException('The cover-up batch could not be found.');
        }

        $zoom = new ZoomMeetingService(
            $coach->zoom_api_key,
            $coach->zoom_client_secret,
            $coach->zoom_id
        );

        $meeting = $zoom->createCoverUpClassMeeting([
            'title' => 'Cover Up - '.$coach->user?->first_name.' - '.$batch->name,
            'duration_in_minute' => 40,
            'start_date_time' => Carbon::parse($coverup->date.' '.$schedule->from_time)->toIso8601String(),
        ], $coach->zoom_user_id);

        if (empty($meeting['join_url']) || empty($meeting['start_url']) || empty($meeting['id'])) {
            throw new RuntimeException('Zoom did not return complete meeting links.');
        }

        return $meeting;
    }
}
