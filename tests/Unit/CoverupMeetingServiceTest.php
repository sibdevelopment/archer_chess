<?php

namespace Tests\Unit;

use App\Models\Batch;
use App\Models\BatchSchedule;
use App\Models\Coach;
use App\Models\Coverupclass;
use App\Models\User;
use App\Services\CoverupMeetingService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class CoverupMeetingServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_it_creates_the_replacement_coachs_meeting(): void
    {
        Http::fake([
            'zoom.us/oauth/token*' => Http::response(['access_token' => 'test-token']),
            'api.zoom.us/v2/users/replacement-zoom-user/meetings' => Http::response([
                'id' => 81747541730,
                'uuid' => 'meeting-uuid',
                'start_url' => 'https://zoom.test/s/81747541730?zak=fresh',
                'join_url' => 'https://zoom.test/j/81747541730',
            ]),
        ]);

        $coach = new Coach([
            'zoom_id' => 'account-id',
            'zoom_api_key' => 'client-key',
            'zoom_client_secret' => 'client-secret',
            'zoom_user_id' => 'replacement-zoom-user',
        ]);
        $coach->setRelation('user', new User(['first_name' => 'Saumil']));

        $coverup = new Coverupclass(['date' => '2026-11-02']);
        $coverup->setRelation('batch', new Batch(['name' => 'Praveen Ramesh - MTH 1:00PM IST']));

        $meeting = app(CoverupMeetingService::class)->create(
            $coverup,
            $coach,
            new BatchSchedule(['from_time' => '13:00:00'])
        );

        $this->assertSame('https://zoom.test/j/81747541730', $meeting['join_url']);
        Http::assertSent(fn (Request $request) => $request->url()
            === 'https://api.zoom.us/v2/users/replacement-zoom-user/meetings');
    }

    public function test_it_rejects_a_replacement_coach_without_zoom_credentials(): void
    {
        $this->expectException(RuntimeException::class);

        app(CoverupMeetingService::class)->create(
            new Coverupclass(['date' => '2026-11-02']),
            new Coach,
            new BatchSchedule(['from_time' => '13:00:00'])
        );
    }
}
