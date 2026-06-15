<?php 

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveRequest extends BaseModel
{
    protected $table = 'leaverequests';
    protected $fillable = [
        'coach_id',
        'from_time', 
        'to_time',   
        'from_date',
        'to_date',
        'reason',
        'status',
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
    
    public function coach()
    {
        return $this->belongsTo(Coach::class, 'coach_id');
    }
    

}
