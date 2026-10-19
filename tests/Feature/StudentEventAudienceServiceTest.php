<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\Masterclass;
use App\Models\Student;
use App\Models\StudentBatch;
use App\Services\StudentEventAudienceService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StudentEventAudienceServiceTest extends TestCase
{
    private StudentEventAudienceService $audience;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('country');
            $table->timestamps();
        });

        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('batchs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('level_id')->nullable();
            $table->string('status');
            $table->text('country')->nullable();
            $table->timestamps();
        });

        Schema::create('student_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('batch_id');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('masterclasses', function (Blueprint $table) {
            $table->id();
            $table->text('batch_ids')->nullable();
            $table->text('student_ids')->nullable();
            $table->text('level_ids')->nullable();
            $table->text('country')->nullable();
            $table->string('status')->default('ACTIVE');
            $table->timestamps();
        });

        $this->audience = app(StudentEventAudienceService::class);
    }

    public function test_event_targets_are_combined_with_or_inside_country_scope(): void
    {
        $direct = $this->student('ACTIVE', 'USA');
        $byBatch = $this->student('ACTIVE', 'USA');
        $byLevel = $this->student('ACTIVE', 'USA');
        $inactive = $this->student('INACTIVE', 'USA');
        $wrongCountry = $this->student('ACTIVE', 'CANADA');
        $inactiveAssignment = $this->student('ACTIVE', 'USA');

        $selectedBatch = $this->batch('ACTIVE', 10, ['USA']);
        $levelBatch = $this->batch('ACTIVE', 20, ['USA']);

        $this->assignment($byBatch, $selectedBatch, 'ACTIVE');
        $this->assignment($byLevel, $levelBatch, 'ACTIVE');
        $this->assignment($inactiveAssignment, $selectedBatch, 'INACTIVE');

        $event = new Masterclass([
            'student_ids' => [(string) $direct->id, (string) $inactive->id, (string) $wrongCountry->id],
            'batch_ids' => [(string) $selectedBatch->id],
            'level_ids' => ['20'],
            'country' => ['USA'],
        ]);

        $actual = $this->audience->studentsForEvent($event)->pluck('id')->sort()->values()->all();
        $expected = collect([$direct->id, $byBatch->id, $byLevel->id])->sort()->values()->all();

        $this->assertSame($expected, $actual);
    }

    public function test_dashboard_visibility_uses_direct_batch_and_level_targets(): void
    {
        $student = $this->student('ACTIVE', 'USA');
        $batch = $this->batch('ACTIVE', 7, ['USA']);
        $this->assignment($student, $batch, 'ACTIVE');

        $direct = $this->masterclass(['999'], [(string) $student->id], ['999'], ['USA']);
        $byBatch = $this->masterclass([(string) $batch->id], ['999'], ['999'], ['USA']);
        $byLevel = $this->masterclass(['999'], ['999'], ['7'], ['USA']);
        $wrongCountry = $this->masterclass([(string) $batch->id], [(string) $student->id], ['7'], ['CANADA']);

        $visibleIds = $this->audience
            ->visibleToStudent(Masterclass::query(), $student)
            ->pluck('id')
            ->sort()
            ->values()
            ->all();

        $expected = collect([$direct->id, $byBatch->id, $byLevel->id])->sort()->values()->all();

        $this->assertSame($expected, $visibleIds);
        $this->assertNotContains($wrongCountry->id, $visibleIds);
    }

    public function test_standby_batch_assignments_remain_eligible_for_event_targeting(): void
    {
        $standbyStudent = $this->student('ACTIVE', 'USA');
        $inactiveStudent = $this->student('ACTIVE', 'USA');
        $inactiveBatchStudent = $this->student('ACTIVE', 'USA');
        $batch = $this->batch('ACTIVE', 7, ['USA']);
        $inactiveBatch = $this->batch('INACTIVE', 7, ['USA']);

        $this->assignment($standbyStudent, $batch, 'STANDBY');
        $this->assignment($inactiveStudent, $batch, 'INACTIVE');
        $this->assignment($inactiveBatchStudent, $inactiveBatch, 'STANDBY');

        $event = $this->masterclass(
            [(string) $batch->id, (string) $inactiveBatch->id],
            [],
            ['7'],
            ['USA']
        );

        $targetedIds = $this->audience->studentsForEvent($event)->pluck('id')->all();
        $visibleIds = $this->audience
            ->visibleToStudent(Masterclass::query(), $standbyStudent)
            ->pluck('id')
            ->all();

        $this->assertContains($standbyStudent->id, $targetedIds);
        $this->assertNotContains($inactiveStudent->id, $targetedIds);
        $this->assertNotContains($inactiveBatchStudent->id, $targetedIds);
        $this->assertContains($event->id, $visibleIds);
    }

    private function student(string $status, string $country): Student
    {
        $student = new Student(['status' => $status, 'country' => $country]);
        $student->save();

        return $student;
    }

    private function batch(string $status, int $levelId, array $countries): Batch
    {
        $batch = new Batch([
            'status' => $status,
            'level_id' => $levelId,
            'country' => $countries,
        ]);
        $batch->save();

        return $batch;
    }

    private function assignment(Student $student, Batch $batch, string $status): StudentBatch
    {
        $assignment = new StudentBatch([
            'student_id' => $student->id,
            'batch_id' => $batch->id,
            'status' => $status,
        ]);
        $assignment->save();

        return $assignment;
    }

    private function masterclass(array $batchIds, array $studentIds, array $levelIds, array $countries): Masterclass
    {
        $masterclass = new Masterclass([
            'batch_ids' => $batchIds,
            'student_ids' => $studentIds,
            'level_ids' => $levelIds,
            'country' => $countries,
            'status' => 'ACTIVE',
        ]);
        $masterclass->save();

        return $masterclass;
    }
}
