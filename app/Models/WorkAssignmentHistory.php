<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkAssignmentHistory extends BaseModel
{
    protected $fillable = [
        'work_assignment_id',
        'action_by',
        'from_user_id',
        'to_user_id',
        'action',
        'status',
        'note',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(WorkAssignment::class, 'work_assignment_id');
    }

    public function actionBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'action_by');
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
