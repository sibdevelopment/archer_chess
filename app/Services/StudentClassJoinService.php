<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\Coverupclass;

class StudentClassJoinService
{
    public function resolve(int $batchId, string $date, ?int $scheduleId = null): ?string
    {
        $coverupQuery = Coverupclass::query()
            ->where('batch_id', $batchId)
            ->whereDate('date', $date);

        if ($scheduleId) {
            $scheduledCoverup = (clone $coverupQuery)->where(function ($query) use ($scheduleId) {
                $query->where('batchschedule_id', $scheduleId)
                    ->orWhere('schedule_id', $scheduleId);
            })->latest('id')->first();

            if ($scheduledCoverup) {
                return $this->cleanUrl($scheduledCoverup->join_url);
            }

            // A cover-up exists, but this page references an obsolete schedule.
            // Never fall back to the original coach's meeting in that case.
            if ((clone $coverupQuery)->exists()) {
                return null;
            }
        }

        $coverup = $coverupQuery->latest('id')->first();

        if ($coverup) {
            return $this->cleanUrl($coverup->join_url);
        }

        return $this->cleanUrl(Batch::whereKey($batchId)->value('join_url'));
    }

    private function cleanUrl(?string $url): ?string
    {
        $url = trim((string) $url);

        return $url !== '' ? $url : null;
    }
}
