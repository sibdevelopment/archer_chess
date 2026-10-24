<?php

namespace App\Services\MetaCapi;

use App\Models\DemoLead;
use App\Models\DemoLeadEnquiry;
use App\Models\MetaCapiEvent;
use Illuminate\Support\Str;

class MetaCapiEventService
{
    public function recordInitialLead(DemoLeadEnquiry $enquiry): ?MetaCapiEvent
    {
        if (! config('meta_capi.send_initial_lead')) {
            return null;
        }

        return $this->record(
            'demo_lead_enquiry',
            $enquiry->id,
            $enquiry,
            'Lead',
            $enquiry->status ?: 'CREATED',
            [
                'email' => $enquiry->email,
                'phone' => $enquiry->mobile,
                'first_name' => $enquiry->kids_first_name,
                'last_name' => $enquiry->kids_last_name,
                'city' => $enquiry->city,
            ]
        );
    }

    public function recordDemoLeadStatus(DemoLead $demoLead): ?MetaCapiEvent
    {
        $status = strtoupper(trim((string) $demoLead->status));
        $eventName = config("meta_capi.demo_status_events.{$status}");

        if (! $eventName) {
            return null;
        }

        $enquiry = $this->enquiryFor($demoLead);
        $user = $demoLead->user;

        return $this->record(
            'demo_lead',
            $demoLead->id,
            $enquiry,
            $eventName,
            $status,
            [
                'email' => $enquiry?->email ?: $user?->email,
                'phone' => $enquiry?->mobile ?: $demoLead->mobile ?: $user?->mobile,
                'first_name' => $enquiry?->kids_first_name ?: $demoLead->first_name,
                'last_name' => $enquiry?->kids_last_name ?: $demoLead->last_name,
                'city' => $enquiry?->city ?: $demoLead->city,
            ]
        );
    }

    public function recordSyntheticTest(): MetaCapiEvent
    {
        $eventId = (string) Str::uuid();

        return MetaCapiEvent::create([
            'event_id' => $eventId,
            'source_type' => 'system_test',
            'event_name' => 'Lead',
            'erp_status' => 'CAPI_TEST',
            'event_time' => now(),
            'payload' => [
                'event_name' => 'Lead',
                'event_time' => now()->timestamp,
                'event_id' => $eventId,
                'action_source' => 'system_generated',
                'user_data' => [
                    'external_id' => [hash('sha256', 'archer-capi-development-test')],
                ],
                'custom_data' => [
                    'erp_status' => 'CAPI_TEST',
                    'environment' => app()->environment(),
                ],
            ],
        ]);
    }

    private function record(
        string $sourceType,
        int $sourceId,
        ?DemoLeadEnquiry $enquiry,
        string $eventName,
        string $erpStatus,
        array $customer
    ): ?MetaCapiEvent {
        $trackingAllowed = $enquiry?->meta_tracking_allowed === true
            || ($enquiry?->meta_tracking_allowed === null && config('meta_capi.allow_legacy_leads'));

        if (! config('meta_capi.enabled') || ! $trackingAllowed) {
            return null;
        }

        if (! $this->isAllowedTestLead($sourceId, $enquiry->id)) {
            return null;
        }

        $userData = array_filter([
            'em' => $this->hashed($customer['email'] ?? null, fn ($value) => strtolower(trim($value))),
            'ph' => $this->hashed($customer['phone'] ?? null, fn ($value) => preg_replace('/\D+/', '', $value)),
            'fn' => $this->hashed($customer['first_name'] ?? null, fn ($value) => strtolower(trim($value))),
            'ln' => $this->hashed($customer['last_name'] ?? null, fn ($value) => strtolower(trim($value))),
            'ct' => $this->hashed($customer['city'] ?? null, fn ($value) => strtolower(preg_replace('/\s+/', '', trim($value)))),
            'external_id' => [hash('sha256', "archer-enquiry-{$enquiry->id}")],
            'fbc' => $enquiry->meta_fbc,
            'fbp' => $enquiry->meta_fbp,
            'client_ip_address' => $enquiry->meta_client_ip,
            'client_user_agent' => $enquiry->meta_client_user_agent,
        ], fn ($value) => $value !== null && $value !== '' && $value !== []);

        $eventId = (string) Str::uuid();
        $payload = [
            'event_name' => $eventName,
            'event_time' => now()->timestamp,
            'event_id' => $eventId,
            'action_source' => 'system_generated',
            'user_data' => $userData,
            'custom_data' => array_filter([
                'erp_status' => $erpStatus,
                'utm_source' => $enquiry->utm_source,
                'utm_medium' => $enquiry->utm_medium,
            ], fn ($value) => $value !== null && $value !== ''),
        ];

        if ($enquiry->meta_landing_url) {
            $payload['event_source_url'] = $enquiry->meta_landing_url;
        }

        return MetaCapiEvent::create([
            'event_id' => $eventId,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'lead_enquiry_id' => $enquiry->id,
            'event_name' => $eventName,
            'erp_status' => $erpStatus,
            'event_time' => now(),
            'payload' => $payload,
        ]);
    }

    private function enquiryFor(DemoLead $demoLead): ?DemoLeadEnquiry
    {
        if ($demoLead->demo_lead_enquiry_id) {
            return DemoLeadEnquiry::find($demoLead->demo_lead_enquiry_id);
        }

        if (! $demoLead->user_id) {
            return null;
        }

        return DemoLeadEnquiry::where('user_id', $demoLead->user_id)
            ->latest('id')
            ->first();
    }

    private function isAllowedTestLead(int $sourceId, int $enquiryId): bool
    {
        $allowlist = config('meta_capi.test_lead_ids', []);

        return $allowlist === []
            || in_array((string) $sourceId, $allowlist, true)
            || in_array((string) $enquiryId, $allowlist, true)
            || in_array("enquiry:{$enquiryId}", $allowlist, true);
    }

    private function hashed(mixed $value, callable $normalizer): ?array
    {
        if (! is_scalar($value) || trim((string) $value) === '') {
            return null;
        }

        $normalized = $normalizer((string) $value);

        return $normalized === '' ? null : [hash('sha256', $normalized)];
    }
}
