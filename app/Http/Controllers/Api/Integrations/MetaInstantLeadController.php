<?php

namespace App\Http\Controllers\Api\Integrations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreMetaInstantLeadRequest;
use App\Models\DemoLeadEnquiry;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class MetaInstantLeadController extends Controller
{
    public function __invoke(StoreMetaInstantLeadRequest $request): JsonResponse
    {
        $data = $request->validated();
        $externalId = trim($data['meta_lead_id']);

        $enquiry = DemoLeadEnquiry::firstOrCreate(
            ['integration_external_id' => $externalId],
            [
                'integration_source' => 'META_INSTANT_FORM',
                'source_created_at' => ! empty($data['created_time'])
                    ? Carbon::parse($data['created_time'])->utc()
                    : null,
                'meta_lead_id' => $externalId,
                'meta_form_id' => $data['form_id'],
                'meta_form_name' => $data['form_name'] ?? null,
                'meta_page_id' => $data['page_id'] ?? null,
                'meta_campaign_id' => $data['campaign_id'] ?? null,
                'meta_campaign_name' => $data['campaign_name'] ?? null,
                'meta_adset_id' => $data['adset_id'] ?? null,
                'meta_adset_name' => $data['adset_name'] ?? null,
                'meta_ad_id' => $data['ad_id'] ?? null,
                'meta_ad_name' => $data['ad_name'] ?? null,
                'kids_first_name' => trim($data['kids_first_name'] ?? $data['full_name']),
                'kids_last_name' => $data['kids_last_name'] ?? null,
                'parent_name' => $data['parent_name'] ?? null,
                'email' => strtolower(trim($data['email'])),
                'mobile' => $this->normalizePhone($data['phone'], $data['country_code'] ?? null),
                'country' => normalizeCountryValue($data['country']),
                'city' => $data['city'] ?? null,
                'age' => $data['age'] ?? null,
                'timezone' => $data['timezone'] ?? null,
                'language_preference' => $data['language_preference'] ?? null,
                'level' => $data['level'] ?? null,
                'duration' => '25_minutes',
                'status' => 'ACTIVE',
                'lead_status' => 'ACTIVE',
                'utm_source' => $data['utm_source'] ?? 'facebook',
                'utm_medium' => $data['utm_medium'] ?? 'paid_social',
                'utm_campaign' => $data['utm_campaign'] ?? $data['campaign_name'] ?? null,
                'utm_content' => $data['utm_content'] ?? $data['ad_name'] ?? null,
                'utm_term' => $data['utm_term'] ?? $data['adset_name'] ?? null,
                'meta_tracking_allowed' => true,
                'is_hide' => '0',
            ]
        );

        return response()->json([
            'status' => 'success',
            'created' => $enquiry->wasRecentlyCreated,
            'message' => $enquiry->wasRecentlyCreated
                ? 'Lead enquiry created.'
                : 'Lead enquiry already received.',
            'lead_enquiry_id' => $enquiry->id,
            'meta_lead_id' => $externalId,
        ], $enquiry->wasRecentlyCreated ? 201 : 200);
    }

    private function normalizePhone(string $phone, ?string $countryCode): string
    {
        $phone = trim($phone);
        $hasInternationalPrefix = str_starts_with($phone, '+');
        $digits = preg_replace('/\D/', '', $phone);

        if ($hasInternationalPrefix) {
            return '+' . $digits;
        }

        if ($countryCode) {
            $countryDigits = preg_replace('/\D/', '', $countryCode);

            return '+' . (
                str_starts_with($digits, $countryDigits)
                    ? $digits
                    : $countryDigits . $digits
            );
        }

        return $digits;
    }
}
