<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class StudentBatch extends BaseModel
{
    use HasFactory;
    protected $table = 'student_batches';

    protected $fillable = [
        'student_id', 'batch_id', 'coach_id', 'level_id', 'status', 'start_date', 'end_date', 'number_of_sessions',
        'confirm_reassign', 'created_by', 'updated_by','is_fees_due'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function isActiveBatch()
    {
        $now = Carbon::now();
        return $now->between($this->start_date, $this->end_date) && $this->status === 'ACTIVE';
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

        // Update batch schedules when start_date or end_date is changed
        static::updating(function ($studentBatch) {
            if ($studentBatch->isDirty(['start_date', 'end_date'])) {
                $batchSchedules = BatchSchedule::where('batch_id', $studentBatch->batch_id);
                if ($batchSchedules->exists()) {
                    $batchSchedules->update([
                        'start_date' => $studentBatch->start_date,
                        'end_date' => $studentBatch->end_date,
                    ]);
                }
            }
        });
    }
}
