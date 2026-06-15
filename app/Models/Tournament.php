<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tournament extends BaseModel
{
    protected $fillable = [
        'batch_ids',
        'student_ids',
        'level_ids',
        'name',
        'date',
        'time',
        'link',
        'status',
        'country',
    ];

    protected $casts = [
        'batch_ids' => 'array',
        'student_ids' => 'array',
        'level_ids' => 'array',
        'country' => 'array',
        'certificate' => 'array',
    ];
}
