<?php

namespace App\Jobs;

use App\Exceptions\WatiRequestException;
use App\Models\WatiNotification;
use App\Services\WatiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Client\RequestException;
use Throwable;

class SendWatiTemplateMessage implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 30;

    public int $uniqueFor = 900;

    public function __construct(public int $notificationId)
    {
    }

    public function uniqueId(): string
    {
        return (string) $this->notificationId;
    }

    public function middleware(): array
    {
        return [(new WithoutOverlapping("wati-notification-{$this->notificationId}"))->releaseAfter(60)];
    }

    public function backoff(): array
    {
        return [60, 300, 900];
    }

    public function handle(WatiService $wati): void
    {
        $notification = WatiNotification::find($this->notificationId);
        if (! $notification || in_array($notification->status, [
            WatiNotification::STATUS_ACCEPTED,
            WatiNotification::STATUS_SENT,
            WatiNotification::STATUS_DELIVERED,
            WatiNotification::STATUS_READ,
        ], true)) {
            return;
        }

        if (! $wati->configuredFor($notification->type)) {
            $notification->update([
                'status' => WatiNotification::STATUS_FAILED,
                'failed_at' => now(),
                'last_error' => 'WATI is disabled or its credentials/template are incomplete.',
            ]);
            return;
        }

        $notification->update([
            'status' => WatiNotification::STATUS_PROCESSING,
            'attempts' => $notification->attempts + 1,
            'last_error' => null,
        ]);

        try {
            $result = $wati->sendTemplate($notification);
            $body = $result['body'];

            $notification->update([
                'status' => WatiNotification::STATUS_ACCEPTED,
                'response_status' => $result['status'],
                'provider_response' => $body,
                'provider_message_id' => $this->findValue($body, ['id', 'broadcast_id']),
                'whatsapp_message_id' => $this->findValue($body, ['whatsappMessageId', 'whatsapp_message_id']),
                'accepted_at' => now(),
                'failed_at' => null,
            ]);
        } catch (Throwable $exception) {
            $responseStatus = $exception instanceof WatiRequestException
                ? $exception->responseStatus
                : ($exception instanceof RequestException ? $exception->response->status() : null);
            $providerResponse = $exception instanceof WatiRequestException
                ? $exception->responseBody
                : ($exception instanceof RequestException && is_array($exception->response->json())
                    ? $exception->response->json()
                    : null);

            $notification->update([
                'status' => WatiNotification::STATUS_FAILED,
                'response_status' => $responseStatus,
                'provider_response' => $providerResponse,
                'failed_at' => now(),
                'last_error' => mb_substr($exception->getMessage(), 0, 65000),
            ]);

            throw $exception;
        }
    }

    public function failed(Throwable $exception): void
    {
        WatiNotification::whereKey($this->notificationId)->update([
            'status' => WatiNotification::STATUS_FAILED,
            'failed_at' => now(),
            'last_error' => mb_substr($exception->getMessage(), 0, 65000),
        ]);
    }

    private function findValue(array $payload, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (isset($payload[$key]) && is_scalar($payload[$key])) {
                return (string) $payload[$key];
            }
        }

        foreach ($payload as $value) {
            if (is_array($value) && ($found = $this->findValue($value, $keys))) {
                return $found;
            }
        }

        return null;
    }
}
