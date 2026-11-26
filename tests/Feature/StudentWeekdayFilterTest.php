<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\StudentController;
use App\Models\Student;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use ReflectionMethod;
use Tests\TestCase;

class StudentWeekdayFilterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('status');
        });

        Schema::create('student_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('batch_id');
            $table->string('status');
            $table->boolean('is_fees_due')->default(false);
        });

        Schema::create('batch_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id');
            $table->string('weekday');
            $table->string('status');
        });
    }

    public function test_weekday_filter_uses_the_current_active_or_suspended_fee_due_assignment(): void
    {
        DB::table('students')->insert([
            ['id' => 1, 'status' => 'ACTIVE'],
            ['id' => 2, 'status' => 'ACTIVE'],
            ['id' => 3, 'status' => 'ACTIVE'],
            ['id' => 4, 'status' => 'FEESDUE'],
            ['id' => 5, 'status' => 'FEESDUE'],
            ['id' => 6, 'status' => 'FEESDUE'],
            ['id' => 7, 'status' => 'ACTIVE'],
            ['id' => 8, 'status' => 'ACTIVE'],
            ['id' => 9, 'status' => 'FEESDUE'],
        ]);

        DB::table('batch_schedules')->insert([
            ['batch_id' => 10, 'weekday' => 'Tuesday', 'status' => 'ACTIVE'],
            ['batch_id' => 20, 'weekday' => 'Wednesday', 'status' => 'ACTIVE'],
            ['batch_id' => 30, 'weekday' => 'Friday', 'status' => 'ACTIVE'],
            ['batch_id' => 40, 'weekday' => 'Tuesday', 'status' => 'ACTIVE'],
            ['batch_id' => 50, 'weekday' => 'Tuesday', 'status' => 'INACTIVE'],
            ['batch_id' => 50, 'weekday' => 'Friday', 'status' => 'ACTIVE'],
            ['batch_id' => 60, 'weekday' => 'Tuesday', 'status' => 'ACTIVE'],
            ['batch_id' => 70, 'weekday' => 'Friday', 'status' => 'ACTIVE'],
        ]);

        DB::table('student_batches')->insert([
            ['id' => 1, 'student_id' => 1, 'batch_id' => 10, 'status' => 'INACTIVE', 'is_fees_due' => 0],
            ['id' => 2, 'student_id' => 1, 'batch_id' => 20, 'status' => 'ACTIVE', 'is_fees_due' => 0],
            ['id' => 3, 'student_id' => 2, 'batch_id' => 10, 'status' => 'ACTIVE', 'is_fees_due' => 0],
            ['id' => 4, 'student_id' => 2, 'batch_id' => 30, 'status' => 'ACTIVE', 'is_fees_due' => 0],
            ['id' => 5, 'student_id' => 3, 'batch_id' => 40, 'status' => 'ACTIVE', 'is_fees_due' => 0],
            ['id' => 6, 'student_id' => 4, 'batch_id' => 40, 'status' => 'ACTIVE', 'is_fees_due' => 0],
            ['id' => 7, 'student_id' => 4, 'batch_id' => 20, 'status' => 'INACTIVE', 'is_fees_due' => 0],
            ['id' => 8, 'student_id' => 5, 'batch_id' => 40, 'status' => 'INACTIVE', 'is_fees_due' => 1],
            ['id' => 9, 'student_id' => 6, 'batch_id' => 40, 'status' => 'INACTIVE', 'is_fees_due' => 0],
            ['id' => 10, 'student_id' => 7, 'batch_id' => 40, 'status' => 'INACTIVE', 'is_fees_due' => 1],
            ['id' => 11, 'student_id' => 8, 'batch_id' => 50, 'status' => 'ACTIVE', 'is_fees_due' => 0],
            ['id' => 12, 'student_id' => 9, 'batch_id' => 60, 'status' => 'INACTIVE', 'is_fees_due' => 1],
            ['id' => 13, 'student_id' => 9, 'batch_id' => 70, 'status' => 'INACTIVE', 'is_fees_due' => 1],
        ]);

        $query = Student::query();
        $method = new ReflectionMethod(StudentController::class, 'applyCurrentBatchWeekdayFilter');
        $method->setAccessible(true);
        $method->invoke(app(StudentController::class), $query, 'Tuesday');

        $this->assertSame([3, 4, 5], $query->orderBy('id')->pluck('id')->all());
    }
}
