<?php

namespace Tests\Unit;

use App\Services\ZoomMeetingService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ZoomMeetingServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_it_fetches_a_fresh_meeting_start_url(): void
    {
        Http::fake([
            'zoom.us/oauth/token*' => Http::response(['access_token' => 'test-token']),
            'api.zoom.us/v2/meetings/2058421750' => Http::response([
                'id' => 2058421750,
                'host_id' => 'coach-zoom-user',
                'start_url' => 'https://zoom.test/s/2058421750?zak=fresh',
                'join_url' => 'https://zoom.test/j/2058421750',
            ]),
        ]);

        $meeting = $this->service()->getMeeting('2058421750');

        $this->assertSame('https://zoom.test/s/2058421750?zak=fresh', $meeting['start_url']);
        Http::assertSent(fn (Request $request) => $request->url() === 'https://api.zoom.us/v2/meetings/2058421750'
            && $request->hasHeader('Authorization', 'Bearer test-token')
        );
    }

    public function test_coverup_meetings_are_created_as_scheduled_meetings(): void
    {
        Http::fake([
            'zoom.us/oauth/token*' => Http::response(['access_token' => 'test-token']),
            'api.zoom.us/v2/users/coach-zoom-user/meetings' => Http::response([
                'id' => 1234567890,
                'start_url' => 'https://zoom.test/s/1234567890?zak=fresh',
                'join_url' => 'https://zoom.test/j/1234567890',
            ]),
        ]);

        $this->service()->createCoverUpClassMeeting([
            'title' => 'Cover Up Test',
            'duration_in_minute' => 60,
            'start_date_time' => '2026-10-30T05:30:00+05:30',
        ], 'coach-zoom-user');

        Http::assertSent(function (Request $request) {
            if ($request->url() !== 'https://api.zoom.us/v2/users/coach-zoom-user/meetings') {
                return false;
            }

            return $request['type'] === 2
                && $request['duration'] === 60
                && $request['settings']['use_pmi'] === false;
        });
    }

    private function service(): ZoomMeetingService
    {
        return new ZoomMeetingService('client-key', 'client-secret', 'account-id');
    }
}
