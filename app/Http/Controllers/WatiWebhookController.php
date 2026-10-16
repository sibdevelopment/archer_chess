<?php

namespace App\Http\Controllers;

use App\Models\WatiNotification;
use App\Services\WatiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class WatiWebhookController extends Controller
{
    public function __invoke(Request $request, WatiService $wati): JsonResponse
    {
        $secret = (string) config('services.wati.webhook_secret');
        $provided = (string) ($request->header('X-Wati-Webhook-Secret') ?: $request->query('token'));

        if (! $wati->configured() || $secret === '') {
            return response()->json(['message' => 'WATI webhook is not configured.'], 503);
        }

        if ($provided === '' || ! hash_equals($secret, $provided)) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $events = $request->input('events');
        if (! is_array($events) || ! isset($events[0])) {
            $events = [$request->all()];
        }

        $updated = 0;
        foreach ($events as $event) {
            if (is_array($event) && $this->applyEvent($event)) {
                $updated++;
            }
        }

        return response()->json(['received' => count($events), 'updated' => $updated]);
    }

    private function applyEvent(array $event): bool
    {
        $localId = Arr::get($event, 'localMessageId') ?? Arr::get($event, 'local_message_id');
        $whatsappId = Arr::get($event, 'whatsappMessageId') ?? Arr::get($event, 'whatsapp_message_id');
        $providerId = Arr::get($event, 'id');

        $notification = WatiNotification::query()
            ->when($localId, fn ($query) => $query->where('local_message_id', $localId))
            ->when(! $localId && $whatsappId, fn ($query) => $query->where('whatsapp_message_id', $whatsappId))
            ->when(! $localId && ! $whatsappId && $providerId, fn ($query) => $query->where('provider_message_id', $providerId))
            ->first();

        if (! $notification) {
            return false;
        }

        $status = $this->status($event);
        $updates = [
            'last_webhook_payload' => Arr::only($event, [
                'eventType', 'statusString', 'id', 'localMessageId', 'whatsappMessageId',
                'failedCode', 'failedDetail', 'timestamp', 'conversationId', 'ticketId',
            ]),
            'provider_message_id' => $providerId ?: $notification->provider_message_id,
            'whatsapp_message_id' => $whatsappId ?: $notification->whatsapp_message_id,
        ];

        if ($status && $this->rank($status) >= $this->rank($notification->status)) {
            $updates['status'] = $status;
            $updates[$this->timestampColumn($status)] = now();
        }

        if ($status === WatiNotification::STATUS_FAILED) {
            $updates['last_error'] = trim((string) (($event['failedCode'] ?? '') . ' ' . ($event['failedDetail'] ?? '')));
        }

        $notification->update($updates);

        return true;
    }

    private function status(array $event): ?string
    {
        $value = strtolower((string) (($event['statusString'] ?? '') . ' ' . ($event['eventType'] ?? '')));

        return match (true) {
            str_contains($value, 'fail') => WatiNotification::STATUS_FAILED,
            str_contains($value, 'read') => WatiNotification::STATUS_READ,
            str_contains($value, 'deliver') => WatiNotification::STATUS_DELIVERED,
            str_contains($value, 'sent') => WatiNotification::STATUS_SENT,
            default => null,
        };
    }

    private function rank(string $status): int
    {
        return match ($status) {
            WatiNotification::STATUS_QUEUED => 10,
            WatiNotification::STATUS_PROCESSING => 20,
            WatiNotification::STATUS_ACCEPTED => 30,
            WatiNotification::STATUS_SENT => 40,
            WatiNotification::STATUS_DELIVERED => 50,
            WatiNotification::STATUS_READ => 60,
            WatiNotification::STATUS_FAILED => 70,
            default => 0,
        };
    }

    private function timestampColumn(string $status): string
    {
        return match ($status) {
            WatiNotification::STATUS_SENT => 'sent_at',
            WatiNotification::STATUS_DELIVERED => 'delivered_at',
            WatiNotification::STATUS_READ => 'read_at',
            WatiNotification::STATUS_FAILED => 'failed_at',
            default => 'accepted_at',
        };
    }
}
