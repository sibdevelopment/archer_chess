<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeDueWhatsappNotification extends Model
{
    protected $fillable = [
        'student_id',
        'student_fee_id',
        'sent_by',
        'fee_amount',
        'currency',
        'message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function studentFee()
    {
        return $this->belongsTo(StudentFee::class);
    }

    public function sentBy()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
