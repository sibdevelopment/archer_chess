<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gallery extends BaseModel
{
    use HasFactory;

    protected $fillable = ['title','status','index'];

    protected $casts = [
        'images_1' => 'array',
        'images_2' => 'array',
        'images_3' => 'array',
        'images_4' => 'array',
        'images_5' => 'array',
    ];

    public function galleryImages(){
        return $this->hasMany(GalleryImage::class,'gallery_id','id');
    }
}
