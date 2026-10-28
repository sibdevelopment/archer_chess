<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MetaInstantLeadWebhookTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'integrations.make.instant_form_secret' => 'make-test-secret',
            'meta_capi.enabled' => false,
        ]);

        DB::purge('sqlite');

        Schema::create('demoleadenquiries', function (Blueprint $table) {
            $table->id();
            $table->string('integration_source')->nullable();
            $table->string('integration_external_id')->nullable()->unique();
            $table->timestamp('source_created_at')->nullable();
            $table->string('meta_lead_id')->nullable();
            $table->string('meta_form_id')->nullable();
            $table->string('meta_form_name')->nullable();
            $table->string('meta_page_id')->nullable();
            $table->string('meta_campaign_id')->nullable();
            $table->string('meta_campaign_name')->nullable();
            $table->string('meta_adset_id')->nullable();
            $table->string('meta_adset_name')->nullable();
            $table->string('meta_ad_id')->nullable();
            $table->string('meta_ad_name')->nullable();
            $table->string('meta_fbc')->nullable();
            $table->string('meta_fbp')->nullable();
            $table->string('meta_fbclid')->nullable();
            $table->string('meta_landing_url')->nullable();
            $table->string('meta_client_ip')->nullable();
            $table->string('meta_client_user_agent')->nullable();
            $table->boolean('meta_tracking_allowed')->nullable();
            $table->timestamp('meta_tracking_consent_at')->nullable();
            $table->string('kids_first_name')->nullable();
            $table->string('kids_last_name')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->integer('age')->nullable();
            $table->string('timezone')->nullable();
            $table->string('language_preference')->nullable();
            $table->string('level')->nullable();
            $table->string('duration')->nullable();
            $table->string('status')->nullable();
            $table->string('lead_status')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_content')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('is_hide')->default('0');
            $table->timestamps();
        });
    }

    public function test_it_rejects_requests_without_the_make_secret(): void
    {
        $this->postJson('/api/integrations/make/meta-instant-leads')
            ->assertUnauthorized();
    }

    public function test_it_creates_one_enquiry_and_treats_a_retry_as_a_duplicate(): void
    {
        $payload = [
            'leadgen_id' => '12345678901234567',
            'created_time' => '2026-10-27T08:15:00+00:00',
            'form_id' => '987654321',
            'form_name' => 'UK Free Trial',
            'full_name' => 'Test Student',
            'parent_name' => 'Test Parent',
            'email' => 'PARENT@EXAMPLE.COM',
            'phone_number' => '98765 43210',
            'country_code' => '+91',
            'country' => 'India',
            'city' => 'Mumbai',
            'age' => 9,
            'campaign_id' => 'campaign-123',
            'campaign_name' => 'UK Free Trial Campaign',
            'adset_name' => 'UK Parents',
            'ad_name' => 'Free Demo Creative',
        ];

        $headers = ['Authorization' => 'Bearer make-test-secret'];

        $this->postJson('/api/integrations/make/meta-instant-leads', $payload, $headers)
            ->assertCreated()
            ->assertJsonPath('created', true)
            ->assertJsonPath('meta_lead_id', '12345678901234567');

        $this->postJson('/api/integrations/make/meta-instant-leads', $payload, $headers)
            ->assertOk()
            ->assertJsonPath('created', false);

        $this->assertDatabaseCount('demoleadenquiries', 1);
        $this->assertDatabaseHas('demoleadenquiries', [
            'integration_source' => 'META_INSTANT_FORM',
            'integration_external_id' => '12345678901234567',
            'kids_first_name' => 'Test Student',
            'email' => 'parent@example.com',
            'mobile' => '+919876543210',
            'country' => 'INDIA',
            'utm_source' => 'facebook',
            'utm_medium' => 'paid_social',
            'utm_campaign' => 'UK Free Trial Campaign',
            'utm_content' => 'Free Demo Creative',
            'utm_term' => 'UK Parents',
            'status' => 'ACTIVE',
        ]);
    }
}
