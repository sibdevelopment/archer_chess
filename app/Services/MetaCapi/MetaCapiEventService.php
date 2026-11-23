<?php

namespace App\Services\MetaCapi;

use App\Models\DemoLead;
use App\Models\DemoLeadEnquiry;
use App\Models\LeadEnquiryCapiFeedback;
use App\Models\MetaCapiEvent;
use App\Models\NewEnrollment;
use Illuminate\Support\Str;

class MetaCapiEventService
{
    public function recordInitialLead(DemoLeadEnquiry $enquiry): ?MetaCapiEvent
    {
        if (
            ! config('meta_capi.send_initial_lead')
            || $enquiry->integration_source === 'META_INSTANT_FORM'
        ) {
            return null;
        }

        return $this->record(
            'demo_lead_enquiry',
            $enquiry->id,
            $enquiry,
            config('meta_capi.initial_event_name'),
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

    public function recordEnrollment(DemoLead $demoLead, NewEnrollment $enrollment): ?MetaCapiEvent
    {
        $eventName = config('meta_capi.enrollment_event_name');

        if (! $eventName) {
            return null;
        }

        $existingEvent = MetaCapiEvent::query()
            ->where('source_id', $demoLead->id)
            ->where('event_name', $eventName)
            ->whereIn('source_type', ['demo_lead', 'demo_lead_enrollment'])
            ->first();

        if ($existingEvent) {
            return $existingEvent;
        }

        $enquiry = $this->enquiryFor($demoLead);
        $user = $demoLead->user;

        return $this->record(
            'demo_lead_enrollment',
            $demoLead->id,
            $enquiry,
            $eventName,
            'CONVERTED',
            [
                'email' => $enquiry?->email ?: $user?->email,
                'phone' => $enquiry?->mobile ?: $demoLead->mobile ?: $user?->mobile,
                'first_name' => $enquiry?->kids_first_name ?: $demoLead->first_name,
                'last_name' => $enquiry?->kids_last_name ?: $demoLead->last_name,
                'city' => $enquiry?->city ?: $demoLead->city,
            ],
            [
                'value' => is_numeric($enrollment->received_fees)
                    ? (float) $enrollment->received_fees
                    : null,
                'currency' => strtoupper(trim((string) $enrollment->currency)),
                'new_enrollment_id' => $enrollment->id,
                'payment_level_id' => $enrollment->payment_level_id,
            ]
        );
    }

    public function recordLeadEnquiryFeedback(
        DemoLeadEnquiry $enquiry,
        LeadEnquiryCapiFeedback $feedback
    ): ?MetaCapiEvent {
        $eventName = config("meta_capi.lead_feedback_events.{$feedback->outcome}");

        if (! $eventName) {
            return null;
        }

        $existingEvent = MetaCapiEvent::query()
            ->where('source_type', 'lead_enquiry_feedback')
            ->where('source_id', $feedback->id)
            ->first();

        if ($existingEvent) {
            return $existingEvent;
        }

        return $this->record(
            'lead_enquiry_feedback',
            $feedback->id,
            $enquiry,
            $eventName,
            $feedback->outcome,
            [
                'email' => $enquiry->email,
                'phone' => $enquiry->mobile,
                'first_name' => $enquiry->kids_first_name,
                'last_name' => $enquiry->kids_last_name,
                'city' => $enquiry->city,
            ]
        );
    }

    public function recordSyntheticTest(): MetaCapiEvent
    {
        $eventId = (string) Str::uuid();

        return MetaCapiEvent::create([
            'event_id' => $eventId,
            'source_type' => 'system_test',
            'event_name' => config('meta_capi.initial_event_name'),
            'erp_status' => 'CAPI_TEST',
            'event_time' => now(),
            'payload' => [
                'event_name' => config('meta_capi.initial_event_name'),
                'event_time' => now()->timestamp,
                'event_id' => $eventId,
                'action_source' => 'system_generated',
                'user_data' => [
                    'external_id' => [hash('sha256', 'archer-capi-development-test')],
                ],
                'custom_data' => [
                    'event_source' => 'crm',
                    'lead_event_source' => config('meta_capi.lead_event_source'),
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
        array $customer,
        array $customData = []
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
            'lead_id' => $enquiry->meta_lead_id,
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
            'custom_data' => array_filter(array_merge([
                'event_source' => 'crm',
                'lead_event_source' => config('meta_capi.lead_event_source'),
                'erp_status' => $erpStatus,
                'utm_source' => $enquiry->utm_source,
                'utm_medium' => $enquiry->utm_medium,
            ], $customData), fn ($value) => $value !== null && $value !== ''),
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
