<?php
namespace App\Console\Commands;

use App\Models\Batch;
use App\Models\BatchSchedule;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ShiftBatchOneHour extends Command
{
    protected $signature = 'shift:batch-one-hour';

    protected $description = 'Shift batch schedules forward by one hour for specific countries';

    public function handle()
    {
        $country = ['AUSTRALIA'];

        $batches = Batch::where(function ($query) use ($country) {
            foreach ($country as $c) {
                $query->orWhereJsonContains('country', $c);
            }
        })->get();

        // dd($batches);

        // Matches 12h time in the real name (e.g. "3PM", "3 PM", "10:30PM"); optional " IST"-style text after uses \b on AM/PM only.
        $timeInNamePattern = '/(?<![0-9.])(\d{1,2})(?:(?:\.|:)(\d{2}))?\s*(AM|PM)\b/i';

        foreach ($batches as $batch) {
            $matches = [];
            $matchedOnOriginal = preg_match($timeInNamePattern, $batch->name, $matches);

            if (! $matchedOnOriginal) {
                // Fallback: normalize tricky labels (e.g. TTH12 → TTH 12) then match on the normalized copy.
                $name = strtoupper($batch->name);
                $name = preg_replace('/(\d{1,2})\.(\d{2})/', '$1:$2', $name);
                $name = preg_replace('/(\d)(AM|PM)/', '$1 $2', $name);
                $name = preg_replace('/([A-Z])(\d)/', '$1 $2', $name);

                if (! preg_match('/(?<!\d)(\d{1,2}(:\d{2})?\s?(AM|PM))\b/', $name, $matches)) {
                    $this->warn("❌ Skipped: {$batch->name}");
                    continue;
                }
                $matches = ['', trim($matches[1])];
            }

            $oldTimeString = $matchedOnOriginal ? $matches[0] : trim($matches[1]);
            $timeString = strtoupper(preg_replace('/\s+/', '', $oldTimeString));
            $timeString = preg_replace('/(\d{1,2})\.(\d{2})/', '$1:$2', $timeString);

            // Extract hour safely
            preg_match('/^(\d{1,2})/', $timeString, $hourMatch);
            $hour = (int) ($hourMatch[1] ?? 0);

            if ($hour < 1 || $hour > 12) {
                $this->warn("❌ Invalid time skipped: {$batch->name}");
                continue;
            }

            // Parse time safely
            try {
                if (strpos($timeString, ':') !== false) {
                    $oldTime = Carbon::createFromFormat('g:iA', $timeString);
                } else {
                    $oldTime = Carbon::createFromFormat('gA', $timeString);
                }
            } catch (\Exception $e) {
                $this->warn("❌ Invalid format skipped: {$batch->name}");
                continue;
            }

            // Batch name time: swap active line vs commented line to flip direction.
            // $newTime = $oldTime->subHour()->format('g:iA'); // old: -1 hour
            $newTime = $oldTime->addHour()->format('g:iA'); // new: +1 hour

            // Replace first 12h time in the stored name (same pattern as match — avoids "3 PM" vs "3PM" + suffixes like " IST").
            $batch->name = preg_replace($timeInNamePattern, $newTime, $batch->name, 1);

            $batch->save();

            // Update schedules: swap active lines vs commented block to flip direction.
            foreach (BatchSchedule::where('batch_id', $batch->id)->get() as $schedule) {
                // $schedule->from_time = Carbon::parse($schedule->from_time)->subHour()->format('H:i:s'); // old: -1 hour
                // $schedule->to_time = Carbon::parse($schedule->to_time)->subHour()->format('H:i:s');
                $schedule->from_time = Carbon::parse($schedule->from_time)->addHour()->format('H:i:s'); // new: +1 hour
                $schedule->to_time = Carbon::parse($schedule->to_time)->addHour()->format('H:i:s');
                $schedule->save();
            }

            $this->info("✅ Updated: {$batch->name}");
        }
    }
}