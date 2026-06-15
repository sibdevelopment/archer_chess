<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BatchSchedule extends BaseModel
{
    use HasFactory;

    protected $table = 'batch_schedules';
    protected $fillable = [
        'batch_id',
        'weekday',
        'from_time',
        'to_time',
        'status',
        'start_date',
        'end_date',
        'confirm_reassign',
         'created_by', 
         'updated_by'
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
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
