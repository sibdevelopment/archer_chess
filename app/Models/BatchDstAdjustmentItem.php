<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchDstAdjustmentItem extends Model
{
    protected $fillable = ['run_id', 'batch_id', 'before_state', 'after_state'];

    protected $casts = [
        'before_state' => 'array',
        'after_state' => 'array',
    ];

    public function run()
    {
        return $this->belongsTo(BatchDstAdjustmentRun::class, 'run_id');
    }
}
