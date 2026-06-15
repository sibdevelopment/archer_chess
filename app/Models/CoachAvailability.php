<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoachAvailability extends BaseModel
{
    use HasFactory;
    protected $table = 'coach_availabilities';
    protected $fillable = [
        'status',
        'index',
        'coach_id',
        'day_of_week',
        'created_by', 
        'updated_by'
    ];

    public function coach()
    {
        return $this->belongsTo(Coach::class, 'coach_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function periods()
    {
        return $this->hasMany(CoachAvailabilityPeriod::class, 'availability_id');
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
