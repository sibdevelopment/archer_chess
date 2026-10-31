<?php

namespace Tests\Unit;

use App\Models\DemoLeadEnquiry;
use PHPUnit\Framework\TestCase;

class DemoLeadEnquirySourceTest extends TestCase
{
    public function test_it_identifies_meta_instant_form_leads(): void
    {
        $enquiry = new DemoLeadEnquiry([
            'integration_source' => 'META_INSTANT_FORM',
        ]);

        $this->assertSame('Meta Instant Form', $enquiry->lead_source_label);
    }

    public function test_it_uses_meta_lead_id_for_legacy_meta_leads(): void
    {
        $enquiry = new DemoLeadEnquiry([
            'meta_lead_id' => '1234567890',
        ]);

        $this->assertSame('Meta Instant Form', $enquiry->lead_source_label);
    }

    public function test_it_identifies_website_landing_page_leads(): void
    {
        $enquiry = new DemoLeadEnquiry([
            'utm_source' => 'SIB_Meta',
        ]);

        $this->assertSame('Website Landing Page', $enquiry->lead_source_label);
    }
}
