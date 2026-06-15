<?php 

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Level extends BaseModel
{
    protected $table = 'levels';
    protected $fillable = [
        'status',
        'name',
        'index',
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
    

    public function demoSessions()
    {
        return $this->hasMany(DemoSession::class, 'level_id');
    }

}
