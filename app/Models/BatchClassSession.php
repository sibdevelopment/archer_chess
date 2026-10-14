<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BatchClassSession extends BaseModel
{
    use HasFactory;

    public const STATUS_PENDING = 'PENDING';
    public const STATUS_CREATING = 'CREATING';
    public const STATUS_READY = 'READY';
    public const STATUS_FAILED = 'FAILED';
    public const STATUS_BLOCKED = 'BLOCKED';
    public const STATUS_COMPLETED = 'COMPLETED';

    protected $fillable = [
        'batch_id',
        'batch_schedule_id',
        'coach_id',
        'class_date',
        'scheduled_start_at',
        'scheduled_end_at',
        'weekday',
        'from_time',
        'to_time',
        'status',
        'start_url',
        'join_url',
        'zoom_meeting_id',
        'zoom_meeting_uuid',
        'creation_attempts',
        'last_attempted_at',
        'last_error',
    ];

    protected $casts = [
        'class_date' => 'date',
        'scheduled_start_at' => 'datetime',
        'scheduled_end_at' => 'datetime',
        'last_attempted_at' => 'datetime',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function schedule()
    {
        return $this->belongsTo(BatchSchedule::class, 'batch_schedule_id');
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function coachAttendances()
    {
        return $this->hasMany(CoachAttendance::class);
    }

    public function studentAttendances()
    {
        return $this->hasMany(StudentAttendance::class);
    }
}
