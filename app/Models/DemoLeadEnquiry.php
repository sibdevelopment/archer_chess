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
        'integration_source',
        'integration_external_id',
        'source_created_at',
        'meta_lead_id',
        'meta_form_id',
        'meta_form_name',
        'meta_page_id',
        'meta_campaign_id',
        'meta_campaign_name',
        'meta_adset_id',
        'meta_adset_name',
        'meta_ad_id',
        'meta_ad_name',
        'meta_fbc',
        'meta_fbp',
        'meta_fbclid',
        'meta_landing_url',
        'meta_client_ip',
        'meta_client_user_agent',
        'meta_tracking_allowed',
        'meta_tracking_consent_at',
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

    protected $casts = [
        'source_created_at' => 'datetime',
        'meta_tracking_allowed' => 'boolean',
        'meta_tracking_consent_at' => 'datetime',
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
