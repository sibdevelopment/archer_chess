<?php

namespace App\Models;

use App\Models\User;
use App\Models\Coach;
use App\Models\Student;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends BaseModel
{
    protected $fillable = ['user_id','student_id','coach_id','full_name','feedback','status'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function student(){
        return $this->belongsTo(Student::class,'student_id');
    }
    public function coach(){
        return $this->belongsTo(Coach::class,'coach_id');
    }
    
}