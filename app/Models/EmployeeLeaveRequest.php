<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

class EmployeeLeaveRequest extends BaseModel
{
    protected $fillable = [
        'employee_id',
        'from_date',
        'to_date',
        'from_time',
        'to_time',
        'leave_type',
        'reason',
        'status',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::id()) {
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::id()) {
                $model->updated_by = Auth::id();
            }
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
