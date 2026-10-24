<?php

namespace App\Services\MetaCapi;

use Carbon\Carbon;
use Illuminate\Http\Request;

class MetaAttributionService
{
    public function capture(Request $request): array
    {
        $landingUrl = $request->input('meta_landing_url') ?: $request->headers->get('referer');
        $fbclid = $request->input('fbclid') ?: $this->queryValue($landingUrl, 'fbclid');
        $leadId = $this->leadId($request);
        $consent = $this->consent($request);
        $requiresConsent = $this->landingPageRequiresConsent($landingUrl);
        $trackingAllowed = $consent !== null
            ? (bool) ($consent['marketing'] ?? false)
            : ! $requiresConsent;

        if (! $trackingAllowed) {
            return [
                'meta_tracking_allowed' => false,
                'meta_tracking_consent_at' => $this->consentTimestamp($consent),
            ];
        }

        $fbc = $request->cookie('_fbc');
        if (! $fbc && $fbclid) {
            $fbc = sprintf('fb.1.%d.%s', (int) floor(microtime(true) * 1000), $fbclid);
        }

        return array_filter([
            'meta_lead_id' => $leadId,
            'meta_fbc' => $fbc,
            'meta_fbp' => $request->cookie('_fbp'),
            'meta_fbclid' => $fbclid,
            'meta_landing_url' => $landingUrl,
            'meta_client_ip' => $request->ip(),
            'meta_client_user_agent' => $request->userAgent(),
            'meta_tracking_allowed' => true,
            'meta_tracking_consent_at' => $this->consentTimestamp($consent),
        ], fn ($value) => $value !== null && $value !== '');
    }

    private function leadId(Request $request): ?string
    {
        $value = $request->input('meta_lead_id')
            ?: $request->input('lead_id')
            ?: $request->input('leadgen_id');

        if (! is_scalar($value)) {
            return null;
        }

        $value = trim((string) $value);

        return preg_match('/^\d{15,17}$/', $value) === 1 ? $value : null;
    }

    private function consent(Request $request): ?array
    {
        $value = $request->cookie('archer_cookie_consent');
        if (! $value) {
            return null;
        }

        $decoded = json_decode($value, true);
        if (! is_array($decoded)) {
            $decoded = json_decode(urldecode($value), true);
        }

        return is_array($decoded) ? $decoded : null;
    }

    private function consentTimestamp(?array $consent): ?Carbon
    {
        if (empty($consent['savedAt'])) {
            return null;
        }

        try {
            return Carbon::parse($consent['savedAt']);
        } catch (\Throwable $exception) {
            return null;
        }
    }

    private function landingPageRequiresConsent(?string $landingUrl): bool
    {
        if (! $landingUrl) {
            return false;
        }

        $path = strtolower((string) parse_url($landingUrl, PHP_URL_PATH));

        return str_contains($path, 'united-kingdom') || str_contains($path, 'european-union');
    }

    private function queryValue(?string $url, string $key): ?string
    {
        if (! $url) {
            return null;
        }

        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
        $value = $query[$key] ?? null;

        return is_string($value) && $value !== '' ? $value : null;
    }
}
