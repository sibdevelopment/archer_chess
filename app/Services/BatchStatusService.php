<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\StudentBatch;

class BatchStatusService
{
    public function syncEmptyActiveOrStandbyToUpcoming(?Batch $batch): bool
    {
        if (! $batch) {
            return false;
        }

        if (! in_array($batch->status, ['ACTIVE', 'STANDBY'])) {
            return false;
        }

        if ($this->hasActiveOrFeeDueStudents($batch->id)) {
            return false;
        }

        $batch->status = 'UPCOMING';
        $batch->save();

        return true;
    }

    public function hasActiveOrFeeDueStudents(int $batchId): bool
    {
        return StudentBatch::where('batch_id', $batchId)
            ->where(function ($query) {
                $query->where('status', 'ACTIVE')
                    ->orWhere(function ($feeDueQuery) {
                        $feeDueQuery->where('is_fees_due', 1)
                            ->whereHas('student', function ($studentQuery) {
                                $studentQuery->where('status', 'FEESDUE');
                            });
                    });
            })
            ->exists();
    }
}
