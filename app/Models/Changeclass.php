<?php

namespace App\Models;
use App\Models\Batch;
use App\Models\Student;
use App\Models\Employee;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Changeclass extends BaseModel
{
    use HasFactory;

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
        'current_batch_id',
        'created_by',
        'updated_by',
        'employee_ids'
    ];

    protected $casts = [
        'employee_ids' => 'array'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
