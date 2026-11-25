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
        });

        Schema::create('batch_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id');
            $table->string('weekday');
            $table->string('status');
        });
    }

    public function test_weekday_filter_uses_only_the_newest_active_batch_assignment(): void
    {
        DB::table('students')->insert([
            ['id' => 1, 'status' => 'ACTIVE'],
            ['id' => 2, 'status' => 'ACTIVE'],
            ['id' => 3, 'status' => 'ACTIVE'],
            ['id' => 4, 'status' => 'FEESDUE'],
        ]);

        DB::table('batch_schedules')->insert([
            ['batch_id' => 10, 'weekday' => 'Tuesday', 'status' => 'ACTIVE'],
            ['batch_id' => 20, 'weekday' => 'Wednesday', 'status' => 'ACTIVE'],
            ['batch_id' => 30, 'weekday' => 'Friday', 'status' => 'ACTIVE'],
            ['batch_id' => 40, 'weekday' => 'Tuesday', 'status' => 'ACTIVE'],
        ]);

        DB::table('student_batches')->insert([
            ['id' => 1, 'student_id' => 1, 'batch_id' => 10, 'status' => 'INACTIVE'],
            ['id' => 2, 'student_id' => 1, 'batch_id' => 20, 'status' => 'ACTIVE'],
            ['id' => 3, 'student_id' => 2, 'batch_id' => 10, 'status' => 'ACTIVE'],
            ['id' => 4, 'student_id' => 2, 'batch_id' => 30, 'status' => 'ACTIVE'],
            ['id' => 5, 'student_id' => 3, 'batch_id' => 40, 'status' => 'ACTIVE'],
            ['id' => 6, 'student_id' => 4, 'batch_id' => 40, 'status' => 'ACTIVE'],
            ['id' => 7, 'student_id' => 4, 'batch_id' => 20, 'status' => 'INACTIVE'],
        ]);

        $query = Student::query();
        $method = new ReflectionMethod(StudentController::class, 'applyCurrentBatchWeekdayFilter');
        $method->setAccessible(true);
        $method->invoke(app(StudentController::class), $query, 'Tuesday');

        $this->assertSame([3, 4], $query->orderBy('id')->pluck('id')->all());
    }
}
