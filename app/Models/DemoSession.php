<?php

namespace App\Models;

use App\Models\Coach;
use App\Models\Level;
use App\Models\DemoLead;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DateTime;

class DemoSession extends BaseModel
{
    use HasFactory;
    protected $table = 'demo_sessions';
    protected $fillable = [
        'status',
        'index',
        'demolead_id',
        'date',
        'time',
        'coach_id',
        'slot',
        'level_id',
        'coach_attendance_status',
        'created_by',
        'updated_by'
    ];

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

    public function demolead()
    {
        return $this->belongsTo(DemoLead::class);
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function getMessage() {

        $formattedDate = date('d-M-Y', strtotime($this->date));
        $formattedTime = date('h:i A', strtotime($this->time));
        $weekday = date('l', strtotime($this->date));

        $kidsDateTime = new DateTime($this->demolead->kids_date . ' ' . $this->demolead->kids_time);
        $kidsFormattedDateTime = $kidsDateTime->format('d-M-Y | h:i A');

        $coachFirstName = $this->coach->user->first_name;
        $coachLastName = $this->coach->user->last_name;
        $kidsTimezone = $this->demolead->kids_time_zone;

        $user = $this->demolead->user;

        $message = "Dear " . $this->demolead->first_name . " " . $this->demolead->last_name . " (" . $this->demolead->country . "), Your Chess demo has been scheduled on " . $weekday . ", " . $formattedDate . " With Archer Chess Academy at IST " . $formattedTime . ". Your timing (" . $kidsFormattedDateTime . " | " . $kidsTimezone . "). Kindly join on time or if you want to cancel due to any circumstances, kindly let us know before 30 minutes so that we can provide this slot to other kids.

Thank you for your understanding. We look forward to seeing you in class!

Coach: " . $coachFirstName . " " . $coachLastName."

For Joining The Demo Kindly open the Dashboard and click on the Join Demo Button. If you have any queries, please feel free to contact us at +91 76270 86196.

Credentials:
URL: https://archerchessacademy.com/student/login

Username: " . ($user ? $user->mobile : '') . "
Password: " . ($user ? $user->device_id : '') . "

Note: The Demo session will be conducted on Zoom. Kindly download the Zoom app on your device.

Thanks & Regards
Archer Chess Academy";
        return $message;
    }

    public function coachattendance()
    {
        return $this->hasOne(CoachAttendance::class, 'demolead_id', 'id');
    }

}




// Dear Sai sriram bellamkonda  (USA), Your Chess demo has been scheduled on Tuesday, 31-Dec-2024 With Archer Chess Academy at IST 3:30 AM. Your timing 5:00 PM (04-Jan-2025 | 05:00 PM | Eastern Standard Time). Kindly join on time or if you want to cancel due to any circumstances, kindly let us know before 30 minutes so that we can provide this slot to other kids.

// Thank you for your understanding. We look forward to seeing you in class!
// Coach:{{  }}
// For Joining The Demo Kindly open the Dashboard and click on the Join Demo Button. If you have any queries, please feel free to contact us at +91 76270 86196.
// Credentials:
// URL: https://archerchessacademy.com/student/login
// Username: {{  }}
// Password: {{  }}

// Thanks & Regards
// Archer Chess Academy

// USA , CANADA, UK
// AKids

// Rest
// ARCHERCHESS

// $message = "Dear " . $demosessions->demolead->first_name . " " . $demosessions->demolead->last_name . " (" . $demosessions->demolead->country . "), Your Chess demo has been scheduled on " . $weekday . ", " . $formattedDate . " With Archer Chess Academy at IST " . $formattedTime . ". Your timing (" . $kidsFormattedDateTime . " | " . $kidsTimezone . "). Kindly join on time or if you want to cancel due to any circumstances, kindly let us know before 30 minutes so that we can provide this slot to other kids.

// Thank you for your understanding. We look forward to seeing you in class!
// " . $coachFirstName . " " . $coachLastName . " is inviting you to a scheduled Zoom meeting.
// Join Zoom Meeting,
// $zoomLink
// Meeting ID: " . $zoomId . "
// Passcode: " . $zoomPassword;

// $whatsappUrl = "https://api.whatsapp.com/send?phone=" . $demosessions->demolead->mobile . "&text=" . urlencode($message);
// $whatsappLink = '<a target="_blank" class="badge bg-success fs-1" href="' . $whatsappUrl . '"><div class="tcul-contact_icon"><i class="fab fa-whatsapp my-float"></i></div></a>';
