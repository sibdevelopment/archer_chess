<?php

namespace App\Services\MetaCapi;

use App\Models\MetaCapiEvent;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class MetaCapiClient
{
    public function send(MetaCapiEvent $event): bool
    {
        $this->assertConfigured();

        $event->forceFill([
            'status' => 'PROCESSING',
            'attempts' => $event->attempts + 1,
            'next_attempt_at' => null,
        ])->save();

        try {
            $response = Http::asJson()
                ->acceptJson()
                ->timeout(config('meta_capi.timeout_seconds'))
                ->post($this->endpoint(), $this->requestBody($event));

            if (! $response->successful() || (int) $response->json('events_received', 0) < 1) {
                throw new RuntimeException($this->responseError($response));
            }

            $event->forceFill([
                'status' => 'SENT',
                'response_status' => $response->status(),
                'response_body' => $this->truncate($response->body()),
                'last_error' => null,
                'sent_at' => now(),
            ])->save();

            return true;
        } catch (Throwable $exception) {
            $delay = min(60, 2 ** min($event->attempts, 6));
            $event->forceFill([
                'status' => 'FAILED',
                'response_status' => isset($response) ? $response->status() : null,
                'response_body' => isset($response) ? $this->truncate($response->body()) : null,
                'last_error' => $this->truncate($exception->getMessage()),
                'next_attempt_at' => $event->attempts < config('meta_capi.max_attempts')
                    ? now()->addMinutes($delay)
                    : null,
            ])->save();

            report($exception);

            return false;
        }
    }

    private function assertConfigured(): void
    {
        if (! config('meta_capi.enabled')) {
            throw new RuntimeException('Meta CAPI is disabled.');
        }

        if (! config('meta_capi.dataset_id') || ! config('meta_capi.access_token')) {
            throw new RuntimeException('Meta CAPI dataset ID or access token is missing.');
        }
    }

    private function endpoint(): string
    {
        return sprintf(
            'https://graph.facebook.com/%s/%s/events',
            trim(config('meta_capi.api_version'), '/'),
            config('meta_capi.dataset_id')
        );
    }

    private function requestBody(MetaCapiEvent $event): array
    {
        return array_filter([
            'data' => [$event->payload],
            'access_token' => config('meta_capi.access_token'),
            'test_event_code' => config('meta_capi.test_event_code'),
        ], fn ($value) => $value !== null && $value !== '');
    }

    private function responseError(Response $response): string
    {
        $message = $response->json('error.message');

        return $message
            ? "Meta CAPI request failed ({$response->status()}): {$message}"
            : "Meta CAPI request failed with HTTP {$response->status()}.";
    }

    private function truncate(?string $value): ?string
    {
        return $value === null ? null : mb_substr($value, 0, 5000);
    }
}
