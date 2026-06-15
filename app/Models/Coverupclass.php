<?php

namespace App\Models;

use App\Models\Batch;
use App\Models\Coach;
use App\Models\BaseModel;
use App\Models\BatchSchedule;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coverupclass extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'schedule_id',
        'old_coach_id',
        'new_coach_id',
        'date',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function schedule()
    {
        return $this->belongsTo(BatchSchedule::class, 'schedule_id');
    }

    public function oldCoach()
    {
        return $this->belongsTo(Coach::class, 'old_coach_id');
    }

    public function newCoach()
    {
        return $this->belongsTo(Coach::class, 'new_coach_id');
    }
}
