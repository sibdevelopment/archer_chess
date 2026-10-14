<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batch_class_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batchs')->cascadeOnDelete();
            $table->foreignId('batch_schedule_id')->nullable()->constrained('batch_schedules')->nullOnDelete();
            $table->foreignId('coach_id')->nullable()->constrained('coachs')->nullOnDelete();
            $table->date('class_date');
            $table->dateTime('scheduled_start_at');
            $table->dateTime('scheduled_end_at');
            $table->string('weekday');
            $table->time('from_time');
            $table->time('to_time');
            $table->string('status')->default('PENDING');
            $table->text('start_url')->nullable();
            $table->text('join_url')->nullable();
            $table->string('zoom_meeting_id')->nullable()->index();
            $table->string('zoom_meeting_uuid')->nullable();
            $table->unsignedInteger('creation_attempts')->default(0);
            $table->timestamp('last_attempted_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();

            $table->unique(
                ['batch_id', 'batch_schedule_id', 'class_date'],
                'batch_class_sessions_occurrence_unique'
            );
            $table->index(['status', 'scheduled_start_at'], 'batch_class_sessions_status_start_index');
        });

        Schema::table('coach_attendances', function (Blueprint $table) {
            $table->foreignId('batch_class_session_id')
                ->nullable()
                ->after('batch_id')
                ->constrained('batch_class_sessions')
                ->nullOnDelete();
        });

        Schema::table('student_attendances', function (Blueprint $table) {
            $table->foreignId('batch_class_session_id')
                ->nullable()
                ->after('batch_id')
                ->constrained('batch_class_sessions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('student_attendances', function (Blueprint $table) {
            $table->dropConstrainedForeignId('batch_class_session_id');
        });

        Schema::table('coach_attendances', function (Blueprint $table) {
            $table->dropConstrainedForeignId('batch_class_session_id');
        });

        Schema::dropIfExists('batch_class_sessions');
    }
};
