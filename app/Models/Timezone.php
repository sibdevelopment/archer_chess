<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Timezone extends BaseModel
{
    use HasFactory;
    protected $table = 'timezones';

    protected $fillable = [
        'country',
        'weekday',
        'timezone',
        'india_start_time',
        'india_end_time',
        'country_start_time',
        'country_end_time',
        'status',
        'created_by',
        'updated_by'
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
}
