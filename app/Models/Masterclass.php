<?php

namespace App\Models;

use App\Models\Coach;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Masterclass extends BaseModel
{
    protected $fillable = [
        'batch_ids',
        'student_ids',
        'level_ids',
        'name',
        'date',
        'time',
        'coach_id',
        'status',
        'country',
    ];

    protected $casts = [
        'batch_ids' => 'array',
        'student_ids' => 'array',
        'level_ids' => 'array',
        'country' => 'array',
    ];
   
    public function coach()
    {
        return $this->belongsTo(Coach::class, 'coach_id');
    }
}
