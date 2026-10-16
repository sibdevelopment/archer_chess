<?php

namespace App\Services;

use App\Exceptions\WatiRequestException;
use App\Models\WatiNotification;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class WatiService
{
    public function configured(): bool
    {
        return (bool) config('services.wati.enabled')
            && filled(config('services.wati.base_url'))
            && filled(config('services.wati.token'))
            && filled(config('services.wati.channel_number'));
    }

    public function configuredFor(string $type): bool
    {
        return $this->configured() && filled($this->templateName($type));
    }

    public function templateName(string $type): ?string
    {
        $key = $type === WatiNotification::TYPE_FEE_DUE ? 'fee_due' : 'class_reminder';

        return config("services.wati.templates.{$key}.name");
    }

    public function templateLanguage(string $type): string
    {
        $key = $type === WatiNotification::TYPE_FEE_DUE ? 'fee_due' : 'class_reminder';

        return (string) config("services.wati.templates.{$key}.language", 'en');
    }

    public function sendTemplate(WatiNotification $notification): array
    {
        if (! $this->configuredFor($notification->type)) {
            throw new RuntimeException('WATI is disabled or its credentials/template are incomplete.');
        }

        $payload = [
            'channel' => (string) config('services.wati.channel_number'),
            'template_name' => $notification->template_name,
            'broadcast_name' => $notification->broadcast_name,
            'recipients' => [[
                'phone_number' => $notification->recipient,
                'local_message_id' => $notification->local_message_id,
                'custom_params' => collect($notification->parameters ?? [])
                    ->map(fn ($value, $name) => [
                        'name' => (string) $name,
                        'value' => (string) ($value ?? ''),
                    ])
                    ->values()
                    ->all(),
            ]],
        ];

        $response = Http::acceptJson()
            ->asJson()
            ->withToken((string) config('services.wati.token'))
            ->timeout(max(5, (int) config('services.wati.timeout', 15)))
            ->post(rtrim((string) config('services.wati.base_url'), '/') . '/api/ext/v3/messageTemplates/send', $payload);

        $response->throw();

        $body = $this->responseBody($response);
        $recipientErrors = collect($body['recipients'] ?? [])
            ->flatMap(fn ($recipient) => is_array($recipient) ? ($recipient['errors'] ?? []) : [])
            ->filter()
            ->values();

        if (($body['success'] ?? true) !== true || $recipientErrors->isNotEmpty()) {
            $message = ($body['error'] ?? null)
                ?: ($recipientErrors->implode('; ') ?: 'WATI rejected the template message.');
            throw new WatiRequestException((string) $message, $response->status(), $body);
        }

        return [
            'status' => $response->status(),
            'body' => $body,
        ];
    }

    private function responseBody(Response $response): array
    {
        $body = $response->json();

        return is_array($body) ? $body : ['body' => mb_substr($response->body(), 0, 5000)];
    }
}
