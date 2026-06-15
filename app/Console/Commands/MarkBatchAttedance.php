<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Batch;
use App\Models\StudentBatch;
use App\Models\CoachAttendance;
use Illuminate\Console\Command;
use App\Models\StudentAttendance;

class MarkBatchAttedance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mark:batch-attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark batch attendance for all students';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
         $this->info("Starting attendance marking for past 3 days...");

        $dates = [];
        for ($i = 1; $i <= 3; $i++) {
            $dates[] = Carbon::now()->subDays($i)->toDateString();
        }
        
        $batches = Batch::with(['batchSchedules', 'coach'])->where('status', 'ACTIVE')->get();
        
        foreach ($dates as $date) {
            $dayName = Carbon::parse($date)->format('l');

            foreach ($batches as $batch) {
                $schedule = $batch->batchSchedules->where('weekday', $dayName)->first();
                if (! $schedule) {
                    continue;
                }

                // dd("Processing batch ID {$batch->id} for date {$date} on {$dayName}");
                $existingCoachAttendance = CoachAttendance::where('batch_id', $batch->id)
                    ->whereDate('date', $date)
                    ->where('type', 'Batch')
                    ->first();

                if ($existingCoachAttendance) {
                    $this->line("Attendance already exists for batch ID {$batch->id} on {$date}, skipping...");
                    continue;
                }

                if (! $batch->coach) {
                    $this->warn("No coach found for batch ID {$batch->id}, skipping...");
                    continue;
                }

                // Mark coach attendance
                CoachAttendance::create([
                    'coach_id' => $batch->coach->id,
                    'type' => 'Batch',
                    'batch_id' => $batch->id,
                    'date' => $date,
                    'time' => now()->format('H:i:s'),
                    'status' => 'COMPLETED'
                ]);

                // Get active students for that date
                $studentBatches = StudentBatch::where('batch_id', $batch->id)
                    ->whereDate('start_date', '<=', $date)
                    ->whereDate('end_date', '>=', $date)
                    ->get();

                foreach ($studentBatches as $studentBatch) {
                    StudentAttendance::create([
                        'type' => 'BATCH',
                        'student_id' => $studentBatch->student_id,
                        'batch_id' => $batch->id,
                        'level_id' => $studentBatch->level_id,
                        'date' => $date,
                        'time' => now()->format('H:i:s'),
                        'status' => 'PRESENT',
                        'remark' => '',
                        'coach_id' => $batch->coach->id,
                    ]);
                }

                $this->info("Marked attendance for student batch ID {$batch->id} on {$date}");
            }
        }

        $this->info("Attendance marking completed.");
        return 0;
    }
}
