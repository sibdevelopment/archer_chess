<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoachAvailabilityPeriod extends BaseModel
{
    use HasFactory;
    protected $table = 'coach_availability_periods';
    protected $fillable = [
        'availability_id',
        'from_period',
        'to_period',
        'status',
        'created_by',
        'updated_by',
    ];

    public function coachAvailability()
    {
        return $this->belongsTo(CoachAvailability::class, 'availability_id');
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
