<?php

namespace App\Observers;

use App\Models\DemoLead;
use App\Services\MetaCapi\MetaCapiEventService;
use Throwable;

class DemoLeadObserver
{
    public function updated(DemoLead $demoLead): void
    {
        if (! config('meta_capi.enabled') || ! $demoLead->wasChanged('status')) {
            return;
        }

        try {
            app(MetaCapiEventService::class)->recordDemoLeadStatus($demoLead);
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
