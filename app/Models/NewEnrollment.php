<?php

namespace App\Models;

use App\Models\Batch;
use App\Models\Student;
use App\Models\Employee;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewEnrollment extends BaseModel
{
    protected $table = 'new_enrollments';

    protected $fillable = [
        'start_date',
        'end_date',
        'receive_date',
        'fees',
        'received_fees',
        'currency',
        'remark',
        'employee_id',
        'student_id',
        'batch_id',
        'employee_ids'
    ];

    protected $casts = [
        'employee_id' => 'array',
        'employee_ids' => 'array'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
