<?php

namespace App\Models;

use App\Models\Level;
use App\Models\BaseModel;

use App\Models\BatchSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Batch extends BaseModel
{
    protected $table = 'batchs';
    protected $fillable = [
        'name',
        'kids_zone_name',
        'is_one_to_one',
        'coach_id',
        'level_id',
        'status',
        'version',
        'parent_id',
        'country',
        'confirm_reassign',
        'confirm_reassign_batch_id',
        'created_by',
        'updated_by',
        'number_of_sessions',
        'start_date',
        'end_date',
        'start_url',
        'join_url',
        'zoom_meeting_id',
        'zoom_meeting_uuid',
    ];

    protected $casts = [
        'country' => 'array',
        'is_one_to_one' => 'boolean',
    ];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function batchSchedules()
    {
        return $this->hasMany(BatchSchedule::class, 'batch_id');
    }

    public function studentBatches()
    {
        return $this->hasMany(StudentBatch::class, 'batch_id');
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class, 'coach_id');
    }

    public function parent()
    {
        return $this->belongsTo(Batch::class, 'parent_id');
    }

    public function ancestors()
    {
        return $this->parent()->with('ancestors');
    }

    public static function boot()
    {
        parent::boot();

        // Set the created_by and updated_by fields when creating a new record
        static::creating(function ($model) {
            $userId = Auth::id();
            if ($userId) {
                $model->created_by = $userId;
                $model->updated_by = $userId;
            }
        });

        // Set the updated_by field when updating an existing record
        static::updating(function ($model) {
            $userId = Auth::id();
            if ($userId) {
                $model->updated_by = $userId;
            }
        });
    }

    public function coachAttendances()
    {
        return $this->hasMany(CoachAttendance::class, 'batch_id');
    }

}

