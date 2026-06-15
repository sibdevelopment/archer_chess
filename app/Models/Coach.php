<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coach extends BaseModel
{
    protected $table = 'coachs';
    protected $fillable = [
        'status',
        'index',
        'user_id',
        'decrypt_password',
        'zoom_id',
        'zoom_password',
        'portal_id',
        'portal_password',
        'created_by',
        'pan_number',
        'zoom_link',
        'updated_by',
        'country',
        'zoom_api_key',
        'zoom_client_secret',
        'zoom_user_id',
    ];

    protected $casts = [
        'country' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function coach_availabilities()
    {
        return $this->hasMany(CoachAvailability::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function studentBatches()
    {
        return $this->hasMany(StudentBatch::class, 'coach_id');
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

    public function isDemoAssign($batchId)
    {

        $batch = Batch::find($batchId);
        $day = Carbon::today()->format('l');
        $from_time = BatchSchedule::where('batch_id', $batchId)
            ->where('weekday', $day)
            ->value('from_time');




        // Get all demo sessions for a day or context-
        $demo_sessions = DemoSession::
            where('coach_id', $this->id)
            ->whereDate('date', Carbon::today())
            ->get();
        // dd($demo_sessions);
       

        $matched_sessions = $demo_sessions->filter(function ($session) use ($from_time) {
            if (strpos($session->slot, ' - ') !== false) {
                [$start, $end] = explode(' - ', $session->slot);
                return $from_time >= trim($start) && $from_time <= trim($end);
            }
            return false;
        })->toArray();

        // dd($matched_sessions);

        if (!empty($matched_sessions)) {
            return '11';
        } else {
            return '22';
        }
    }

    public function leaves()
    {
        return $this->hasMany(LeaveRequest::class, 'coach_id');
    }


}
