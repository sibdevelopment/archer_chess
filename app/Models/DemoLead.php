<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DemoLead extends BaseModel
{
    protected $table = 'demoleads';
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'age',
        'mobile',
        'city',
        'country',
        'kids_time_zone',
        'remark',
        'reason',
        'status',
        'index',
        'date',
        'time',
        'kids_date',
        'kids_time',
        'created_by',
        'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function demosessions()
    {
        return $this->hasMany(DemoSession::class, 'demolead_id');
    }
    public function firstDemoSession()
    {
        return $this->demosessions()->first();
    }

    public function coachAttendance()
    {
        return $this->hasMany(CoachAttendance::class, 'demolead_id');
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
