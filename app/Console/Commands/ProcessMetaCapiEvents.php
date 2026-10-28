<?php

namespace App\Console\Commands;

use App\Models\MetaCapiEvent;
use App\Services\MetaCapi\MetaCapiClient;
use Illuminate\Console\Command;

class ProcessMetaCapiEvents extends Command
{
    protected $signature = 'meta-capi:process {--limit= : Maximum events to process}';

    protected $description = 'Send pending ERP lead lifecycle events to Meta Conversions API';

    public function handle(MetaCapiClient $client): int
    {
        if (! config('meta_capi.enabled')) {
            $this->line('Meta CAPI is disabled.');

            return self::SUCCESS;
        }

        $limit = max(1, (int) ($this->option('limit') ?: config('meta_capi.batch_size')));
        $events = MetaCapiEvent::ready()->oldest('id')->limit($limit)->get();
        $sent = 0;
        $failed = 0;

        foreach ($events as $event) {
            $client->send($event) ? $sent++ : $failed++;
        }

        $this->info("Meta CAPI processed {$events->count()} event(s): {$sent} sent, {$failed} failed.");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
