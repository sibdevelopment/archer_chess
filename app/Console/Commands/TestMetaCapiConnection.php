<?php

namespace App\Console\Commands;

use App\Services\MetaCapi\MetaCapiClient;
use App\Services\MetaCapi\MetaCapiEventService;
use Illuminate\Console\Command;

class TestMetaCapiConnection extends Command
{
    protected $signature = 'meta-capi:test';

    protected $description = 'Send a synthetic event to Meta Test Events';

    public function handle(MetaCapiEventService $events, MetaCapiClient $client): int
    {
        if (! config('meta_capi.enabled')) {
            $this->error('Meta CAPI is disabled.');

            return self::FAILURE;
        }

        if (! config('meta_capi.test_event_code')) {
            $this->error('META_CAPI_TEST_EVENT_CODE is required for this command.');

            return self::FAILURE;
        }

        $event = $events->recordSyntheticTest();
        if (! $client->send($event)) {
            $this->error("Meta CAPI test failed. Audit event ID: {$event->id}");

            return self::FAILURE;
        }

        $this->info("Meta CAPI test event sent successfully. Audit event ID: {$event->id}");

        return self::SUCCESS;
    }
}
