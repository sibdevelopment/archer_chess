<?php

namespace Tests\Feature;

use App\Models\DemoLead;
use App\Models\DemoLeadEnquiry;
use App\Models\NewEnrollment;
use App\Services\MetaCapi\MetaCapiEventService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MetaCapiEnrollmentEventTest extends TestCase
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
            'meta_capi.enrollment_event_name' => 'CompleteRegistration',
        ]);

        DB::purge('sqlite');

        Schema::create('demoleadenquiries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
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
    }

    public function test_it_records_one_enrollment_event_after_fee_details_are_available(): void
    {
        $enquiry = DemoLeadEnquiry::create([
            'meta_tracking_allowed' => true,
            'meta_lead_id' => '12345678901234567',
            'kids_first_name' => 'Test',
            'kids_last_name' => 'Student',
            'email' => 'parent@example.com',
            'mobile' => '+447000000000',
            'city' => 'London',
            'utm_source' => 'facebook',
            'utm_medium' => 'paid_social',
        ]);

        $demoLead = new DemoLead([
            'demo_lead_enquiry_id' => $enquiry->id,
            'first_name' => 'Test',
            'last_name' => 'Student',
            'status' => 'CONVERTED',
        ]);
        $demoLead->id = 25;
        $demoLead->exists = true;

        $enrollment = new NewEnrollment([
            'received_fees' => '149.50',
            'currency' => 'gbp',
            'payment_level_id' => 3,
        ]);
        $enrollment->id = 42;
        $enrollment->exists = true;

        $service = app(MetaCapiEventService::class);

        $this->assertNull($service->recordDemoLeadStatus($demoLead));
        $this->assertDatabaseCount('meta_capi_events', 0);

        $firstEvent = $service->recordEnrollment($demoLead, $enrollment);
        $duplicateEvent = $service->recordEnrollment($demoLead, $enrollment);

        $this->assertNotNull($firstEvent);
        $this->assertSame($firstEvent->id, $duplicateEvent->id);
        $this->assertDatabaseCount('meta_capi_events', 1);
        $this->assertSame('demo_lead_enrollment', $firstEvent->source_type);
        $this->assertSame('CompleteRegistration', $firstEvent->event_name);
        $this->assertSame('CONVERTED', $firstEvent->erp_status);
        $this->assertSame(149.5, $firstEvent->payload['custom_data']['value']);
        $this->assertSame('GBP', $firstEvent->payload['custom_data']['currency']);
        $this->assertSame(42, $firstEvent->payload['custom_data']['new_enrollment_id']);
        $this->assertSame(3, $firstEvent->payload['custom_data']['payment_level_id']);
    }
}
