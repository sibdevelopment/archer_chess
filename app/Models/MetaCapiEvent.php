<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MetaCapiEvent extends Model
{
    protected $fillable = [
        'event_id',
        'source_type',
        'source_id',
        'lead_enquiry_id',
        'event_name',
        'erp_status',
        'event_time',
        'payload',
        'status',
        'attempts',
        'next_attempt_at',
        'response_status',
        'response_body',
        'last_error',
        'sent_at',
    ];

    protected $casts = [
        'event_time' => 'datetime',
        'payload' => 'array',
        'next_attempt_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function scopeReady(Builder $query): Builder
    {
        return $query
            ->where('attempts', '<', config('meta_capi.max_attempts'))
            ->where(function (Builder $query) {
                $query->whereIn('status', ['PENDING', 'FAILED'])
                    ->orWhere(function (Builder $query) {
                        $query->where('status', 'PROCESSING')
                            ->where('updated_at', '<=', now()->subMinutes(10));
                    });
            })
            ->where(function (Builder $query) {
                $query->whereNull('next_attempt_at')
                    ->orWhere('next_attempt_at', '<=', now());
            });
    }
}
