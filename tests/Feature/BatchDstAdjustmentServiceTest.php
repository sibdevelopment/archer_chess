<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\BatchSchedule;
use App\Services\BatchDstAdjustmentService;
use Carbon\CarbonImmutable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Tests\TestCase;

class BatchDstAdjustmentServiceTest extends TestCase
{
    private BatchDstAdjustmentService $service;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        config()->set('cache.default', 'array');
        DB::purge('sqlite');

        config()->set('batch_dst.eligible_batch_statuses', ['ACTIVE', 'STANDBY', 'UPCOMING']);
        config()->set('batch_dst.eligible_schedule_statuses', ['ACTIVE']);
        config()->set('batch_dst.region_priority', ['uk-eu', 'australia', 'new-zealand']);
        config()->set('batch_dst.regions', [
            'uk-eu' => [
                'label' => 'UK + EU',
                'countries' => ['UK', 'EU'],
                'transitions' => [
                    'test-autumn' => ['date' => '2026-10-25', 'minutes' => 60, 'automatic' => true],
                    'test-spring' => ['date' => '2027-03-28', 'minutes' => -60, 'automatic' => true],
                ],
            ],
            'australia' => [
                'label' => 'Australia',
                'countries' => ['AUSTRALIA'],
                'transitions' => [
                    'test-spring' => ['date' => '2026-10-04', 'minutes' => -60, 'automatic' => true],
                ],
            ],
            'new-zealand' => [
                'label' => 'New Zealand',
                'countries' => ['NEWZEALAND'],
                'transitions' => [
                    'test-spring' => ['date' => '2026-09-27', 'minutes' => -60, 'automatic' => true],
                ],
            ],
        ]);

        Schema::create('batchs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('status');
            $table->text('country')->nullable();
            $table->timestamps();
        });

        Schema::create('batch_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id');
            $table->string('weekday');
            $table->time('from_time');
            $table->time('to_time');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('batch_dst_adjustment_runs', function (Blueprint $table) {
            $table->id();
            $table->string('transition_key')->unique();
            $table->string('region');
            $table->date('transition_date');
            $table->smallInteger('minutes');
            $table->string('status');
            $table->unsignedInteger('batch_count')->default(0);
            $table->timestamp('applied_at')->nullable();
            $table->timestamp('rolled_back_at')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
        });

        Schema::create('batch_dst_adjustment_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('run_id');
            $table->unsignedBigInteger('batch_id');
            $table->json('before_state');
            $table->json('after_state');
            $table->timestamps();
        });

        $this->service = app(BatchDstAdjustmentService::class);
    }

    public function test_preview_handles_midnight_weekday_and_batch_name_rollover(): void
    {
        $batch = $this->batch('Coach WF 11PM IST', ['UK']);
        // Live data can store schedule rows in a different order than the name code.
        $this->schedule($batch, 'Friday', '23:00:00', '23:45:00');
        $this->schedule($batch, 'Wednesday', '23:00:00', '23:45:00');

        $preview = $this->service->preview('uk-eu', 'test-autumn');

        $this->assertCount(1, $preview['rows']);
        $this->assertEmpty($preview['blocked']);
        $this->assertSame('Coach THS 12AM IST', $preview['rows'][0]['after']['name']);
        $this->assertSame('Saturday', $preview['rows'][0]['after']['schedules'][0]['weekday']);
        $this->assertSame('Thursday', $preview['rows'][0]['after']['schedules'][1]['weekday']);
        $this->assertSame('00:00:00', $preview['rows'][0]['after']['schedules'][0]['from_time']);

        $this->assertSame('Coach WF 11PM IST', $batch->fresh()->name);
    }

    public function test_apply_is_date_protected_and_cannot_run_twice(): void
    {
        $batch = $this->batch('Coach MF 8 PM IST', ['UK']);
        $this->schedule($batch, 'Monday', '20:00:00', '21:00:00');
        $this->schedule($batch, 'Friday', '20:00:00', '21:00:00');

        try {
            $this->service->apply('uk-eu', 'test-autumn', CarbonImmutable::parse('2026-10-24 12:00:00'));
            $this->fail('Wrong-date execution should have failed.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('Date protection blocked', $exception->getMessage());
        }

        $this->assertSame('Coach MF 8 PM IST', $batch->fresh()->name);
        $run = $this->service->apply('uk-eu', 'test-autumn', CarbonImmutable::parse('2026-10-25 00:10:00'));

        $this->assertSame('APPLIED', $run->status);
        $this->assertSame('Coach MF 9 PM IST', $batch->fresh()->name);
        $this->assertSame('21:00:00', $batch->batchSchedules()->orderBy('id')->first()->from_time);
        $this->assertDatabaseCount('batch_dst_adjustment_items', 1);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('cannot run twice');
        $this->service->apply('uk-eu', 'test-autumn', CarbonImmutable::parse('2026-10-25 00:11:00'));
    }

    public function test_rollback_restores_exact_saved_state(): void
    {
        $batch = $this->batch('Coach MF 8:30PM IST', ['UK']);
        $this->schedule($batch, 'Monday', '20:30:00', '21:30:00');
        $this->schedule($batch, 'Friday', '20:30:00', '21:30:00');

        $run = $this->service->apply('uk-eu', 'test-autumn', CarbonImmutable::parse('2026-10-25 00:10:00'));

        // MySQL JSON columns can reorder object keys; rollback compares values, not JSON key order.
        $item = $run->items()->first();
        $after = $item->after_state;
        $item->after_state = [
            'name' => $after['name'],
            'schedules' => collect($after['schedules'])->map(fn ($schedule) => [
                'to_time' => $schedule['to_time'],
                'id' => $schedule['id'],
                'from_time' => $schedule['from_time'],
                'weekday' => $schedule['weekday'],
            ])->all(),
            'batch_id' => $after['batch_id'],
        ];
        $item->save();

        $rolledBack = $this->service->rollback($run);

        $this->assertSame('ROLLED_BACK', $rolledBack->status);
        $this->assertSame('Coach MF 8:30PM IST', $batch->fresh()->name);
        $this->assertSame('20:30:00', $batch->batchSchedules()->orderBy('id')->first()->from_time);
    }

    public function test_forward_and_reverse_transitions_restore_the_original_state(): void
    {
        $batch = $this->batch('Coach WF 11PM IST', ['UK']);
        $this->schedule($batch, 'Wednesday', '23:00:00', '23:45:00');
        $this->schedule($batch, 'Friday', '23:00:00', '23:45:00');

        $this->service->apply('uk-eu', 'test-autumn', CarbonImmutable::parse('2026-10-25 00:10:00'));
        $this->assertSame('Coach THS 12AM IST', $batch->fresh()->name);

        $this->service->apply('uk-eu', 'test-spring', CarbonImmutable::parse('2027-03-28 00:10:00'));

        $this->assertSame('Coach WF 11PM IST', $batch->fresh()->name);
        $this->assertSame(
            [
                ['weekday' => 'Wednesday', 'from_time' => '23:00:00', 'to_time' => '23:45:00'],
                ['weekday' => 'Friday', 'from_time' => '23:00:00', 'to_time' => '23:45:00'],
            ],
            $batch->batchSchedules()
                ->orderBy('id')
                ->get(['weekday', 'from_time', 'to_time'])
                ->map(fn ($schedule) => $schedule->toArray())
                ->all()
        );
    }

    public function test_region_priority_prevents_a_shared_batch_from_being_shifted_twice(): void
    {
        $batch = $this->batch('Coach MF 12PM IST', ['AUSTRALIA', 'NEWZEALAND']);
        $this->schedule($batch, 'Monday', '12:00:00', '13:00:00');
        $this->schedule($batch, 'Friday', '12:00:00', '13:00:00');

        $preview = $this->service->preview('australia', 'test-spring');
        $newZealandPreview = $this->service->preview('new-zealand', 'test-spring');

        $this->assertCount(1, $preview['rows']);
        $this->assertEmpty($preview['overlapSkipped']);
        $this->assertEmpty($newZealandPreview['rows']);
        $this->assertSame('australia', $newZealandPreview['overlapSkipped'][0]['owner']);
    }

    private function batch(string $name, array $countries, string $status = 'ACTIVE'): Batch
    {
        $batch = new Batch(['name' => $name, 'country' => $countries, 'status' => $status]);
        $batch->save();

        return $batch;
    }

    private function schedule(Batch $batch, string $weekday, string $from, string $to): BatchSchedule
    {
        $schedule = new BatchSchedule([
            'batch_id' => $batch->id,
            'weekday' => $weekday,
            'from_time' => $from,
            'to_time' => $to,
            'status' => 'ACTIVE',
        ]);
        $schedule->save();

        return $schedule;
    }
}
