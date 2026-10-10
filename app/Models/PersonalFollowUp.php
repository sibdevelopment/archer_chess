<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalFollowUp extends BaseModel
{
    protected $fillable = [
        'user_id',
        'follow_up_at',
        'related_to',
        'related_name',
        'follow_up_type',
        'what_to_do',
        'priority',
        'status',
        'reason_note',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'follow_up_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
