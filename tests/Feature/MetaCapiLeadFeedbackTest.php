<?php

namespace Tests\Feature;

use App\Models\DemoLeadEnquiry;
use App\Models\LeadEnquiryCapiFeedback;
use App\Services\MetaCapi\LeadEnquiryFeedbackService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MetaCapiLeadFeedbackTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'meta_capi.enabled' => true,
            'meta_capi.allow_legacy_leads' => false,
            'meta_capi.test_lead_ids' => [],
            'meta_capi.lead_feedback_events' => [
                LeadEnquiryCapiFeedback::INTERESTED => 'QualifiedLead',
                LeadEnquiryCapiFeedback::NOT_INTERESTED => 'UnqualifiedLead',
            ],
        ]);

        DB::purge('sqlite');

        Schema::create('demoleadenquiries', function (Blueprint $table) {
            $table->id();
            $table->boolean('meta_tracking_allowed')->nullable();
            $table->string('meta_lead_id')->nullable();
            $table->string('meta_fbc')->nullable();
            $table->string('meta_fbp')->nullable();
            $table->string('meta_landing_url')->nullable();
            $table->string('meta_client_ip')->nullable();
            $table->string('meta_client_user_agent')->nullable();
            $table->string('kids_first_name')->nullable();
            $table->string('kids_last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('city')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->timestamps();
        });

        Schema::create('meta_capi_events', function (Blueprint $table) {
            $table->id();
            $table->uuid('event_id')->unique();
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->unsignedBigInteger('lead_enquiry_id')->nullable();
            $table->string('event_name');
            $table->string('erp_status')->nullable();
            $table->timestamp('event_time');
            $table->json('payload');
            $table->string('status')->default('PENDING');
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->timestamp('next_attempt_at')->nullable();
            $table->unsignedSmallInteger('response_status')->nullable();
            $table->text('response_body')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('lead_enquiry_capi_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_enquiry_id');
            $table->string('outcome');
            $table->text('note');
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->timestamp('submitted_at');
            $table->unsignedBigInteger('meta_capi_event_id')->nullable();
            $table->timestamps();
        });
    }

    public function test_it_keeps_notes_internal_and_only_queues_events_for_decision_changes(): void
    {
        $enquiry = DemoLeadEnquiry::create([
            'meta_tracking_allowed' => true,
            'meta_lead_id' => '98765432101234567',
            'kids_first_name' => 'Test',
            'kids_last_name' => 'Lead',
            'email' => 'parent@example.com',
            'mobile' => '+447000000001',
            'city' => 'London',
        ]);

        $service = app(LeadEnquiryFeedbackService::class);

        $first = $service->submit($enquiry->id, 'INTERESTED', 'Call again on Friday.', null);
        $duplicate = $service->submit($enquiry->id, 'INTERESTED', 'Call again on Friday.', null);
        $noteOnly = $service->submit($enquiry->id, 'INTERESTED', 'Parent requested a morning call.', null);
        $changed = $service->submit($enquiry->id, 'NOT_INTERESTED', 'No longer looking for classes.', null);

        $this->assertTrue($duplicate['duplicate']);
        $this->assertFalse($noteOnly['event_queued']);
        $this->assertTrue($changed['event_queued']);
        $this->assertDatabaseCount('lead_enquiry_capi_feedbacks', 3);
        $this->assertDatabaseCount('meta_capi_events', 2);

        $this->assertSame('QualifiedLead', $first['feedback']->metaCapiEvent->event_name);
        $this->assertSame('UnqualifiedLead', $changed['feedback']->metaCapiEvent->event_name);

        $payloads = DB::table('meta_capi_events')->pluck('payload')->implode(' ');
        $this->assertStringNotContainsString('Call again on Friday.', $payloads);
        $this->assertStringNotContainsString('No longer looking for classes.', $payloads);
    }
}
