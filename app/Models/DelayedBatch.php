<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

class DelayedBatch extends BaseModel
{
    protected $fillable = [
        'batch_id',
        'coach_id',
        'coach_attendance_id',
        'batch_name',
        'country',
        'batch_status',
        'level_name',
        'timeline',
        'canceled_date',
        'canceled_time',
        'date',
        'time',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date' => 'date',
        'country' => 'array',
        'canceled_date' => 'date',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class, 'coach_id');
    }

    public function coachAttendance()
    {
        return $this->belongsTo(CoachAttendance::class, 'coach_attendance_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $userId = Auth::id();
            if ($userId) {
                $model->created_by = $userId;
                $model->updated_by = $userId;
            }
        });

        static::updating(function ($model) {
            $userId = Auth::id();
            if ($userId) {
                $model->updated_by = $userId;
            }
        });
    }
}
 