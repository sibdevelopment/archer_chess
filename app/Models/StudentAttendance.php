<?php
namespace App\Models;

use App\Models\Batch;
use App\Models\Coach;
use App\Models\Student;
use App\Models\DemoLead;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentAttendance extends BaseModel
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'type',
        'coach_id',
        'demolead_id',
        'level_id',
        'batch_id',
        'number_of_batch_sessions',
        'status',
        'remark',
        'date',
        'time',
        'created_by',
        'updated_by',
        'homework_link',
        'recording_link',
        'chapter_name',
    ];

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

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function demoLead()
    {
        return $this->belongsTo(DemoLead::class, 'demolead_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

}
