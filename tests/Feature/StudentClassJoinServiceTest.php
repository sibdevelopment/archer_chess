<?php

namespace Tests\Feature;

use App\Services\StudentClassJoinService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StudentClassJoinServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge('sqlite');

        Schema::create('batchs', function (Blueprint $table) {
            $table->id();
            $table->text('join_url')->nullable();
        });
        Schema::create('coverupclasses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('batchschedule_id')->nullable();
            $table->date('date');
            $table->text('join_url')->nullable();
        });
    }

    public function test_current_coverup_link_overrides_the_regular_batch_link(): void
    {
        DB::table('batchs')->insert([
            'id' => 10,
            'join_url' => 'https://zoom.test/j/original-coach',
        ]);
        DB::table('coverupclasses')->insert([
            'id' => 20,
            'batch_id' => 10,
            'batchschedule_id' => 30,
            'date' => '2026-11-02',
            'join_url' => 'https://zoom.test/j/replacement-coach',
        ]);

        $url = app(StudentClassJoinService::class)->resolve(10, '2026-11-02', 30);

        $this->assertSame('https://zoom.test/j/replacement-coach', $url);
    }

    public function test_coverup_without_a_link_never_falls_back_to_the_original_coach(): void
    {
        DB::table('batchs')->insert([
            'id' => 10,
            'join_url' => 'https://zoom.test/j/original-coach',
        ]);
        DB::table('coverupclasses')->insert([
            'id' => 20,
            'batch_id' => 10,
            'batchschedule_id' => 30,
            'date' => '2026-11-02',
            'join_url' => null,
        ]);

        $url = app(StudentClassJoinService::class)->resolve(10, '2026-11-02', 30);

        $this->assertNull($url);
    }

    public function test_regular_batch_link_is_used_when_there_is_no_coverup(): void
    {
        DB::table('batchs')->insert([
            'id' => 10,
            'join_url' => 'https://zoom.test/j/original-coach',
        ]);

        $url = app(StudentClassJoinService::class)->resolve(10, '2026-11-02', 30);

        $this->assertSame('https://zoom.test/j/original-coach', $url);
    }

    public function test_obsolete_schedule_never_falls_back_to_the_original_coach(): void
    {
        DB::table('batchs')->insert([
            'id' => 10,
            'join_url' => 'https://zoom.test/j/original-coach',
        ]);
        DB::table('coverupclasses')->insert([
            'id' => 20,
            'batch_id' => 10,
            'batchschedule_id' => 30,
            'date' => '2026-11-02',
            'join_url' => 'https://zoom.test/j/replacement-coach',
        ]);

        $url = app(StudentClassJoinService::class)->resolve(10, '2026-11-02', 999);

        $this->assertNull($url);
    }
}
