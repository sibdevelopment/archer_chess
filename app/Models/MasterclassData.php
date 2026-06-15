<?php

namespace App\Models;

use App\Models\Coach;
use App\Models\Student;
use App\Models\BaseModel;
use App\Models\Masterclass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterclassData extends BaseModel
{
    protected $table = 'masterclasses_data';

    protected $fillable = [
        'masterclass_id',
        'student_id',
    ];

    public function masterclass()
    {
        return $this->belongsTo(Masterclass::class, 'masterclass_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}

