<?php
 namespace App\Models;

 use Carbon\Carbon;
 use App\Traits\Hashidable;
 use App\Models\Student;
 use App\Models\BaseModel;
 use Illuminate\Support\Facades\Auth;
 use Illuminate\Database\Eloquent\Model;
 use Spatie\Activitylog\Traits\LogsActivity;
 use Spatie\Activitylog\LogOptions; // Add this import

 class StudentFee extends Model
 {
     use LogsActivity ,Hashidable;

     protected $table = 'student_fees';
     protected $fillable = [
         'student_id',
         'start_date',
         'end_date',
         'receive_date',
         'currency',
         'monthly_fees',
         'total_amount_paid',
         'status',
         'created_by',
         'updated_by'
     ];

     public function student()
     {
         return $this->belongsTo(Student::class);
     }

     public function generateFeeDueMessage() {
         // Fetch the related student to access its first_name, last_name, currency, and monthly_fees
         $student = $this->student()->first();
         $fullName = $student->first_name . ' ' . $student->last_name;
         $currency = $student->currency;

         $message = "Dear $fullName, This is to inform you that the Chess Class fee has been due with Archer Chess Academy. ";
         $message .= "Your previous module has ended on *".Carbon::parse($this->end_date)->format('l, d-M-Y')."* ";
         $message .= "Please attend to this matter as soon as you possibly can. Thank you very much. \n";
         $message .= "The total due amount is *".$this->monthly_fees." ".$currency."*. ";
         $message .= "Kindly check out Archer Chess Academy or Archer Kids on the payment gateway before making payment.";

         return $message;
     }

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

     // Implement the getActivitylogOptions method
     public function getActivitylogOptions(): LogOptions
     {
         return LogOptions::defaults()
             ->logAll() // Log all attributes (or you can specify the ones you want to log)
             ->logOnly(['student_id', 'start_date', 'end_date', 'currency', 'monthly_fees', 'total_amount_paid', 'status']); // Specify attributes to log
     }
 }



