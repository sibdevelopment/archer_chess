<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends BaseModel
{

    protected $table = 'blogs';


    protected $fillable = [
        'index',
        'date',
        'label',
        'title',
        'short_description',
        'description',
        'cover_img',
        'main_img',
        'slug',
        'meta_title',
        'meta_description',
        'status',
        'home_featured',
    ];
}
