<?php

namespace App\Observers;

use App\Models\DemoLeadEnquiry;
use App\Services\MetaCapi\MetaAttributionService;
use App\Services\MetaCapi\MetaCapiEventService;
use Throwable;

class DemoLeadEnquiryObserver
{
    public function created(DemoLeadEnquiry $enquiry): void
    {
        try {
            if (app()->bound('request') && request()->isMethod('post')) {
                $enquiry->forceFill(app(MetaAttributionService::class)->capture(request()))->saveQuietly();
            }

            if (config('meta_capi.enabled')) {
                app(MetaCapiEventService::class)->recordInitialLead($enquiry->fresh());
            }
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
