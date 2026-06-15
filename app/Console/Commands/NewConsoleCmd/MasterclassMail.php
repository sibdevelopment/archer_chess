<?php

namespace App\Console\Commands\Email\Reminder;

use Carbon\Carbon;
use App\Models\Batch;
use App\Models\Student;
use App\Models\Masterclass;
use App\Models\StudentBatch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MasterclassMailaa extends Command
{
    protected $signature = 'masterclass:reminderaa';
    protected $description = 'Send email reminders to students ~3 hours before a masterclass (runs every 30 minutes)';

    public function handle()
    {
        $now = Carbon::now();
        $windowMinutes = 30;

        $windowStart = $now->copy()->addHours(3);
        $windowEnd   = $windowStart->copy()->addMinutes($windowMinutes);

        $this->info("Checking masterclasses starting between {$windowStart->toDateTimeString()} and {$windowEnd->toDateTimeString()} ...");

        $masterclasses = Masterclass::query()
            ->where('status', 'ACTIVE')
            ->where('is_reminder_sent', 'NO')
            ->whereBetween(DB::raw("TIMESTAMP(`date`,`time`)"), [$windowStart, $windowEnd])
            ->orderBy('date')->orderBy('time')
            ->get();

        if ($masterclasses->isEmpty()) {
            $this->info('No masterclasses to send reminders for in this window.');
            return Command::SUCCESS;
        }

        foreach ($masterclasses as $mc) {
            $batchIds   = $this->toArray($mc->batch_ids);
            $studentIds = $this->toArray($mc->student_ids);
            $levelIds   = $this->toArray($mc->level_ids);
            $countries  = $this->toArray($mc->countries);

            // ---- Derive batches from levels (ACTIVE only)
            $levelBatchIds = !empty($levelIds)
                ? Batch::whereIn('level_id', $levelIds)
                    ->where('status', '!=', 'INACTIVE')
                    ->pluck('id')->all()
                : [];

            // ---- Merge batches from direct selection + level-derived, unique once
            $allBatchIds = array_values(array_unique(array_merge($batchIds, $levelBatchIds)));

            // ---- Pull students from batches (single query even if no direct batches)
            $batchStudentIds = !empty($allBatchIds)
                ? StudentBatch::whereIn('batch_id', $allBatchIds)->pluck('student_id')->all()
                : [];

            // ---- Validate explicit student IDs actually exist
            $explicitStudentIds = !empty($studentIds)
                ? Student::whereIn('id', $studentIds)->pluck('id')->all()
                : [];

            // ---- Candidates = explicit + from batches (unique)
            $candidateIds = array_values(array_unique(array_merge($explicitStudentIds, $batchStudentIds)));

            // ---- Final filter on Students (optionally by countries)
            $finalStudentIds = !empty($candidateIds)
                ? Student::whereIn('id', $candidateIds)
                    ->when(!empty($countries), fn($q) => $q->whereIn('country', $countries))
                    ->pluck('id')->all()
                : [];

            // ---- Result for this masterclass
            dd($finalStudentIds);
        }

        return Command::SUCCESS;
    }

    /**
     * Convert mixed input (array|json array string|csv string|scalar|null) to a clean array.
     */
    private function toArray($value): array
    {
        if (is_array($value)) {
            return array_values(array_filter($value, static fn($v) => $v !== null && $v !== ''));
        }

        if (is_string($value)) {
            $s = trim($value);
            if ($s === '') return [];
            // Looks like JSON array?
            if (strlen($s) >= 2 && $s[0] === '[' && substr($s, -1) === ']') {
                $decoded = json_decode($s, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return array_values(array_filter($decoded, static fn($v) => $v !== null && $v !== ''));
                }
            }
            // Fallback: CSV
            return array_values(array_filter(array_map('trim', explode(',', $s)), static fn($v) => $v !== ''));
        }

        if ($value === null) return [];
        // Single scalar → wrap
        return [$value];
    }
}
