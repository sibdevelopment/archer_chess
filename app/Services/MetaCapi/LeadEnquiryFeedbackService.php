<?php

namespace App\Services\MetaCapi;

use App\Models\DemoLeadEnquiry;
use App\Models\LeadEnquiryCapiFeedback;
use Illuminate\Support\Facades\DB;

class LeadEnquiryFeedbackService
{
    public function __construct(private MetaCapiEventService $metaCapiEventService)
    {
    }

    public function submit(int $enquiryId, string $outcome, string $note, ?int $submittedBy): array
    {
        return DB::transaction(function () use ($enquiryId, $outcome, $note, $submittedBy) {
            $enquiry = DemoLeadEnquiry::query()->lockForUpdate()->findOrFail($enquiryId);
            $latest = LeadEnquiryCapiFeedback::query()
                ->where('lead_enquiry_id', $enquiry->id)
                ->latest('id')
                ->first();

            $normalizedNote = trim($note);

            if (
                $latest
                && $latest->outcome === $outcome
                && trim($latest->note) === $normalizedNote
            ) {
                return [
                    'feedback' => $latest,
                    'duplicate' => true,
                    'event_queued' => false,
                ];
            }

            $feedback = LeadEnquiryCapiFeedback::create([
                'lead_enquiry_id' => $enquiry->id,
                'outcome' => $outcome,
                'note' => $normalizedNote,
                'submitted_by' => $submittedBy,
                'submitted_at' => now(),
            ]);

            $event = null;
            if (! $latest || $latest->outcome !== $outcome) {
                $event = $this->metaCapiEventService->recordLeadEnquiryFeedback($enquiry, $feedback);

                if ($event) {
                    $feedback->update(['meta_capi_event_id' => $event->id]);
                }
            }

            return [
                'feedback' => $feedback->fresh(['submittedBy', 'metaCapiEvent']),
                'duplicate' => false,
                'event_queued' => $event !== null,
            ];
        });
    }
}
