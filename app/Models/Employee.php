<?php

namespace App\Models;

use App\Models\BaseModel;

class Employee extends BaseModel
{
    protected $fillable = ['user_id','decrypt_password',    'camera_consented','camera_available','camera_snapshot_path' ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function cameraChecks()
{
    return $this->hasMany(\App\Models\CameraCheck::class);
}

}

