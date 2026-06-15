<?php
namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoachAttendance extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'type',
        'demolead_id',
        'batch_id',
        'date',
        'time',
        'status',
        'number_of_batch_sessions',
        'number_of_demo_sessions',
        'created_by', 
        'updated_by',
        'chapter_name',
    ];

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function demo()
    {
        return $this->belongsTo(DemoLead::class, 'demo_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
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
}