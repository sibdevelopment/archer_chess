<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CameraCheck extends BaseModel
{
    protected $fillable = [
        'employee_id',
        'user_id',
        'consented',
        'available',
        'snapshot_path',
        'ip_address',
        'user_agent',
    ];

    // relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
