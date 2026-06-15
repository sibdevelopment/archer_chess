<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'student_fee_id',
        'razorpay_payment_id',
        'amount',
        'currency',
        'razorpay_data',
    ];


    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function studentFee()
    {
        return $this->belongsTo(StudentFee::class);
    }
}

