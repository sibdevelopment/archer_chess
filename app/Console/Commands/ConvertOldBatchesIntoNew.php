<?php

namespace App\Console\Commands;

use App\Models\Batch;
use App\Models\StudentBatch;
use Illuminate\Console\Command;

class ConvertOldBatchesIntoNew extends Command
{
    protected $signature = 'convert:old-batches-into-new';

    protected $description = 'Convert old batches into new';

    public function handle()
    {
        $all_batches = Batch::all();

        foreach ($all_batches as $batch) {
            $latest_student_batch = StudentBatch::where('batch_id', $batch->id)->latest()->first();
            // dd($latest_student_batch);
            if ($latest_student_batch) {
                $batch->start_date = $latest_student_batch->start_date;
                $batch->end_date = $latest_student_batch->end_date;
                $batch->number_of_sessions = $latest_student_batch->number_of_sessions;
                $batch->level_id = $latest_student_batch->level_id;
                $batch->save();
            }else{
                // dd($batch);
            }

        }

    }
}

