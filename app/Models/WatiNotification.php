<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatiNotification extends Model
{
    public const TYPE_CLASS_REMINDER = 'CLASS_REMINDER';
    public const TYPE_FEE_DUE = 'FEE_DUE';

    public const STATUS_QUEUED = 'QUEUED';
    public const STATUS_PROCESSING = 'PROCESSING';
    public const STATUS_ACCEPTED = 'ACCEPTED';
    public const STATUS_SENT = 'SENT';
    public const STATUS_DELIVERED = 'DELIVERED';
    public const STATUS_READ = 'READ';
    public const STATUS_FAILED = 'FAILED';
    public const STATUS_SKIPPED = 'SKIPPED';

    protected $fillable = [
        'local_message_id',
        'idempotency_key',
        'type',
        'student_id',
        'student_fee_id',
        'batch_class_session_id',
        'initiated_by',
        'recipient',
        'template_name',
        'template_language',
        'broadcast_name',
        'parameters',
        'amount',
        'currency',
        'status',
        'provider_message_id',
        'whatsapp_message_id',
        'attempts',
        'response_status',
        'provider_response',
        'last_webhook_payload',
        'last_error',
        'queued_at',
        'accepted_at',
        'sent_at',
        'delivered_at',
        'read_at',
        'failed_at',
    ];

    protected $casts = [
        'parameters' => 'array',
        'provider_response' => 'array',
        'last_webhook_payload' => 'array',
        'queued_at' => 'datetime',
        'accepted_at' => 'datetime',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function studentFee()
    {
        return $this->belongsTo(StudentFee::class);
    }

    public function batchClassSession()
    {
        return $this->belongsTo(BatchClassSession::class);
    }

    public function initiatedBy()
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    public function canRetry(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }
}
