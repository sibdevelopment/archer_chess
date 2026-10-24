<?php

namespace Tests\Unit;

use App\Services\MetaCapi\MetaAttributionService;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class MetaAttributionServiceTest extends TestCase
{
    public function test_it_captures_meta_identifiers_for_a_regular_landing_page(): void
    {
        $request = Request::create(
            '/book-trial',
            'POST',
            [],
            ['_fbc' => 'fb.1.123.click', '_fbp' => 'fb.1.123.browser'],
            [],
            [
                'HTTP_REFERER' => 'https://dev.archerchessacademy.com/?fbclid=click-id',
                'REMOTE_ADDR' => '203.0.113.10',
                'HTTP_USER_AGENT' => 'Test Browser',
            ]
        );

        $captured = (new MetaAttributionService())->capture($request);

        $this->assertTrue($captured['meta_tracking_allowed']);
        $this->assertSame('fb.1.123.click', $captured['meta_fbc']);
        $this->assertSame('fb.1.123.browser', $captured['meta_fbp']);
        $this->assertSame('click-id', $captured['meta_fbclid']);
        $this->assertSame('203.0.113.10', $captured['meta_client_ip']);
    }

    public function test_it_blocks_tracking_on_uk_landing_page_without_marketing_consent(): void
    {
        $request = Request::create(
            '/book-trial',
            'POST',
            [],
            ['_fbc' => 'fb.1.123.click'],
            [],
            ['HTTP_REFERER' => 'https://dev.archerchessacademy.com/united-kingdom?fbclid=click-id']
        );

        $captured = (new MetaAttributionService())->capture($request);

        $this->assertFalse($captured['meta_tracking_allowed']);
        $this->assertArrayNotHasKey('meta_fbc', $captured);
        $this->assertArrayNotHasKey('meta_client_ip', $captured);
    }

    public function test_it_honours_marketing_consent_on_eu_landing_page(): void
    {
        $consent = json_encode([
            'necessary' => true,
            'analytics' => false,
            'marketing' => true,
            'savedAt' => '2026-10-24T08:00:00.000Z',
        ]);

        $request = Request::create(
            '/book-trial',
            'POST',
            [],
            ['archer_cookie_consent' => rawurlencode($consent), '_fbp' => 'fb.1.123.browser'],
            [],
            ['HTTP_REFERER' => 'https://dev.archerchessacademy.com/european-union']
        );

        $captured = (new MetaAttributionService())->capture($request);

        $this->assertTrue($captured['meta_tracking_allowed']);
        $this->assertSame('fb.1.123.browser', $captured['meta_fbp']);
        $this->assertNotNull($captured['meta_tracking_consent_at']);
    }
}
