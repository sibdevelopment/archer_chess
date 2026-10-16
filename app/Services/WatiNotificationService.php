<?php

namespace App\Services;

use App\Jobs\SendWatiTemplateMessage;
use App\Models\BatchClassSession;
use App\Models\Student;
use App\Models\StudentFee;
use App\Models\User;
use App\Models\WatiNotification;
use Carbon\Carbon;
use Illuminate\Support\Str;
use InvalidArgumentException;

class WatiNotificationService
{
    public function __construct(private WatiService $wati)
    {
    }

    public function availableFor(string $type): bool
    {
        return $this->wati->configuredFor($type);
    }

    public function feeDue(
        Student $student,
        StudentFee $studentFee,
        array $dueSummary,
        ?User $initiator
    ): WatiNotification {
        $type = WatiNotification::TYPE_FEE_DUE;
        $template = $this->requiredTemplate($type);
        $recipient = $this->normalizePhone($student->mobile);
        $amount = $dueSummary['amount'] ?? null;
        $currency = $dueSummary['currency'] ?? $studentFee->currency;

        return WatiNotification::firstOrCreate(
            ['idempotency_key' => hash('sha256', "fee|{$student->id}|{$studentFee->id}|{$template}")],
            [
                'local_message_id' => (string) Str::uuid(),
                'type' => $type,
                'student_id' => $student->id,
                'student_fee_id' => $studentFee->id,
                'initiated_by' => $initiator?->id,
                'recipient' => $recipient,
                'template_name' => $template,
                'template_language' => $this->wati->templateLanguage($type),
                'broadcast_name' => 'fee-due-' . now()->format('Ymd'),
                'parameters' => [
                    'student_name' => $student->full_name,
                    'fee_end_date' => optional($studentFee->end_date ? Carbon::parse($studentFee->end_date) : null)?->format('d M Y') ?? '',
                    'payment_level' => (string) ($dueSummary['payment_level'] ?? ''),
                    'amount' => $amount === null ? '' : (string) $amount,
                    'currency' => (string) ($currency ?? ''),
                    'payment_url' => url('/student/login'),
                ],
                'amount' => $amount,
                'currency' => $currency,
                'status' => WatiNotification::STATUS_QUEUED,
                'queued_at' => now(),
            ]
        );
    }

    public function classReminder(BatchClassSession $session, Student $student): WatiNotification
    {
        $type = WatiNotification::TYPE_CLASS_REMINDER;
        $template = $this->requiredTemplate($type);
        $recipient = $this->normalizePhone($student->mobile);
        $session->loadMissing(['batch', 'coach.user']);
        $start = $session->scheduled_start_at->copy();

        if ($student->timezone) {
            try {
                $start->setTimezone($student->timezone);
            } catch (\Throwable) {
                // Keep the application timezone when a legacy timezone value is invalid.
            }
        }

        return WatiNotification::firstOrCreate(
            ['idempotency_key' => hash('sha256', "class|{$session->id}|{$student->id}|{$template}")],
            [
                'local_message_id' => (string) Str::uuid(),
                'type' => $type,
                'student_id' => $student->id,
                'batch_class_session_id' => $session->id,
                'recipient' => $recipient,
                'template_name' => $template,
                'template_language' => $this->wati->templateLanguage($type),
                'broadcast_name' => 'class-reminder-' . $session->class_date->format('Ymd'),
                'parameters' => [
                    'student_name' => $student->full_name,
                    'batch_name' => (string) $session->batch?->name,
                    'class_date' => $start->format('d M Y'),
                    'class_time' => $start->format('h:i A'),
                    'timezone' => $start->getTimezone()->getName(),
                    'coach_name' => trim(($session->coach?->user?->first_name ?? '') . ' ' . ($session->coach?->user?->last_name ?? '')),
                    'join_url' => (string) $session->join_url,
                ],
                'status' => WatiNotification::STATUS_QUEUED,
                'queued_at' => now(),
            ]
        );
    }

    public function dispatch(WatiNotification $notification, bool $retryFailed = false): bool
    {
        if (! $this->availableFor($notification->type)) {
            return false;
        }

        if ($notification->status !== WatiNotification::STATUS_QUEUED
            && ! ($retryFailed && $notification->canRetry())) {
            return false;
        }

        if ($retryFailed) {
            $notification->update([
                'status' => WatiNotification::STATUS_QUEUED,
                'queued_at' => now(),
                'failed_at' => null,
                'last_error' => null,
            ]);
        }

        SendWatiTemplateMessage::dispatch($notification->id)
            ->onQueue((string) config('services.wati.queue', 'default'));

        return true;
    }

    public function normalizePhone(?string $phone): string
    {
        $phone = preg_replace('/\D+/', '', (string) $phone);
        $phone = preg_replace('/^00/', '', $phone);

        if (strlen($phone) < 8 || strlen($phone) > 15) {
            throw new InvalidArgumentException('Student WhatsApp number must include a valid country code.');
        }

        return $phone;
    }

    private function requiredTemplate(string $type): string
    {
        if (! $this->availableFor($type)) {
            throw new InvalidArgumentException('WATI credentials or the required template are not configured.');
        }

        return (string) $this->wati->templateName($type);
    }
}
