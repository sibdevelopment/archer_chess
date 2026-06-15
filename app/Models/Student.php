<?php

namespace App\Models;

use App\Models\Level;
use App\Models\BaseModel;
use App\Models\StudentFee;
use App\Models\Paymentlevel;
use App\Models\StudentBatch;
use App\Models\StudentStatus;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends BaseModel
{
    use HasFactory;
    protected $table = 'students';
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'age',
        'mobile',
        'email',
        'timezone',
        'city',
        'country',
        'fees_country',
        'student_id',
        'level_id',
        'monthly_fees',
        'currency',
        'image',
        'status',
        'created_by',
        'updated_by',
        'portal_password',
        'lastpayment_level_id',
    ];

    // protected $appends = [
    //     'full_name',
    // ];

    public static function boot()
    {
        parent::boot();

        // Set the created_by and updated_by fields when creating a new record
        static::creating(function ($model) {
            $userId = Auth::id();
            if ($userId) {
                $model->created_by = $userId;
                $model->updated_by = $userId;
            }
        });

        // Set the updated_by field when updating an existing record
        static::updating(function ($model) {
            $userId = Auth::id();
            if ($userId) {
                $model->updated_by = $userId;
            }
        });
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function studentFees()
    {
        return $this->hasMany(StudentFee::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function studentBatches()
    {
        return $this->hasMany(StudentBatch::class);
    }

    public function studentStatuses()
    {
        return $this->hasMany(StudentStatus::class);
    }

    public function studentAttendances()
    {
        return $this->hasMany(StudentAttendance::class);
    }

    public function getFullNameAttribute()
    {
        $first_name = $this->first_name ?? '';
        $last_name = $this->last_name ?? '';
        return trim($first_name . ' ' . $last_name);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function generateNewStudentMessage()
    // {
    //     $user = User::find($this->user_id);

    //     $fullName = $this->first_name . ' ' . $this->last_name;
    //     $message = "Dear $fullName, This is to inform you that your chess class enrollment has been confirmed with Archer Chess Academy.\n";
    //     $message .= "Dashboard Link: https://archerchessacademy.com/student/login\n";
    //     $message .= "Your Username: $this->mobile\n";
    //     $message .= "Password: {$user->device_id}\n\n";
    //     $message .= "Kindly login & check all details. If you have any queries, feel free to call us.\n\n";
    //     $message .= "Thanks for choosing Archer!\n";
    //     $message .= "Happy Learning! 😊\n\n";
    //     $message .= "For tournament and academic information, join this group: https://chat.whatsapp.com/JsGy4TaJazT7ufRYNG4AdA";

    //     return $message;
    // }


    public function generateNewStudentMessage()
    {
        $user = User::find($this->user_id);

        $fullName = $this->first_name . ' ' . $this->last_name;
        $message = "Dear $fullName, This is to inform you that your chess class enrollment has been confirmed with Archer Chess Academy.\n";
        $message .= "Dashboard Link: https://archerchessacademy.com/student/login\n";
        $message .= "Your Username: $this->mobile\n";
        $message .= "Password: {$user->device_id}\n\n";

        $message .= "*Chesslang Credentials:*\n";
        $message .= "Portal Link: https://app.chesslang.com/login \n";
        $message .= "Username: $this->student_id\n";
        $message .= "Password: {$this->portal_password}\n\n";

        $message .= "Kindly login & check all details. If you have any queries, feel free to call us.\n\n";
        $message .= "Thanks for choosing Archer!\n";
        $message .= "Happy Learning! 😊\n\n";
        $message .= "For tournament and academic information, join this group: https://chat.whatsapp.com/JsGy4TaJazT7ufRYNG4AdA";

        return $message;
    }


    public function paymentlevel()
    {
        return $this->belongsTo(Paymentlevel::class, 'lastpayment_level_id');
    }

    public function latestBatch()
    {
        return $this->hasOne(StudentBatch::class, 'student_id')->latest('created_at')->with('batch');
    }

    // public function latestBatch()
    // {
    //     return $this->hasOne(StudentBatch::class, 'student_id', 'id')
    //         ->latest('created_at')
    //         ->with('batch');
    // }



}


// public function latestBatch()
// {
//     $studentBatch = StudentBatch::where('student_id', $this->id)->orderBy('id', 'desc')->first();
//     // $batch = Batch::find($studentBatch->batch_id);
//     // $batchscheduls = BatchSchedule::where('batch_id', $studentBatch->batch_id)->get();
//     return $studentBatch;
// }
