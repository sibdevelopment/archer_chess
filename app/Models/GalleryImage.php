<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GalleryImage extends BaseModel
{
    use HasFactory;

    protected $fillable = ['image','status','index'];

    protected $appends = array('photo_link');

    public function getPhotoLinkAttribute(){
        $photo_link = null;
        if($this->image != null){
            $photo_link = getDomainUrl().''.Storage::url($this->image);
        }
        return $photo_link; 
    }

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
