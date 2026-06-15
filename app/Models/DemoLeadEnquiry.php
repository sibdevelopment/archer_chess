<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DemoLeadEnquiry extends BaseModel
{
    use HasFactory;
    protected $table = 'demoleadenquiries';
    protected $fillable = [
        'user_id',
        'status',
        'email_verified',
        'created_at',
        'kids_first_name',
        'kids_last_name',
        'date',
        'time',
        'ist_date',
        'ist_time',
        'utm_source',
        'utm_medium',
        'timezone',
        'age',
        'mobile',
        'email',
        'city',
        'country',
        'available_device',
        'enrollment_plan',
        'language_preference',
        'level',
        'duration',
        'email_otp',
        'mobile_verified',
        'lead_status',
        'parent_name',
        'dob',
        'created_by',
        'updated_by',
        'is_hide'
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}


/*
Actions
Status
Verified
Lead Date Time
Demo Date Time
Full Name

 */
