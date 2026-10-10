<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkAssignment extends BaseModel
{
    public const TYPE_HANDOVER = 'HANDOVER';
    public const TYPE_TASK = 'TASK';

    protected $fillable = [
        'type',
        'created_by',
        'assigned_to',
        'current_owner_id',
        'related_type',
        'related_name',
        'country',
        'last_action',
        'next_action',
        'requirement',
        'priority',
        'status',
        'source_note',
        'final_reason',
        'action_at',
        'assignee_note',
    ];

    protected $casts = [
        'action_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function currentOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_owner_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(WorkAssignmentHistory::class)->latest();
    }
}
