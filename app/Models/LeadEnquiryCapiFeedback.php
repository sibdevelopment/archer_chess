<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadEnquiryCapiFeedback extends Model
{
    use HasFactory;

    protected $table = 'lead_enquiry_capi_feedbacks';

    public const INTERESTED = 'INTERESTED';
    public const NOT_INTERESTED = 'NOT_INTERESTED';

    protected $fillable = [
        'lead_enquiry_id',
        'outcome',
        'note',
        'submitted_by',
        'submitted_at',
        'meta_capi_event_id',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function enquiry()
    {
        return $this->belongsTo(DemoLeadEnquiry::class, 'lead_enquiry_id');
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function metaCapiEvent()
    {
        return $this->belongsTo(MetaCapiEvent::class, 'meta_capi_event_id');
    }
}
