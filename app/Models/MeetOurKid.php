<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class MeetOurKid extends BaseModel
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'status',
        'title',
        'image',
        'created_by',
        'updated_by',
    ];
}
