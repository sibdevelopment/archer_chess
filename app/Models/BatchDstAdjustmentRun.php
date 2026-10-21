<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchDstAdjustmentRun extends Model
{
    protected $fillable = [
        'transition_key', 'region', 'transition_date', 'minutes', 'status',
        'batch_count', 'applied_at', 'rolled_back_at', 'error',
    ];

    protected $casts = [
        'transition_date' => 'date',
        'applied_at' => 'datetime',
        'rolled_back_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(BatchDstAdjustmentItem::class, 'run_id');
    }
}
