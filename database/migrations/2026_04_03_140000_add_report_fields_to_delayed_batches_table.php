<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delayed_batches', function (Blueprint $table) {
            $table->unsignedBigInteger('coach_attendance_id')->nullable()->after('coach_id');
            $table->string('batch_name')->nullable()->after('coach_attendance_id');
            $table->json('country')->nullable()->after('batch_name');
            $table->string('batch_status')->nullable()->after('country');
            $table->string('level_name')->nullable()->after('batch_status');
            $table->text('timeline')->nullable()->after('level_name');
            $table->date('canceled_date')->nullable()->after('timeline');
            $table->time('canceled_time')->nullable()->after('canceled_date');

            $table->foreign('coach_attendance_id')->references('id')->on('coach_attendances')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('delayed_batches', function (Blueprint $table) {
            $table->dropForeign(['coach_attendance_id']);
            $table->dropColumn([
                'coach_attendance_id',
                'batch_name',
                'country',
                'batch_status',
                'level_name',
                'timeline',
                'canceled_date',
                'canceled_time',
            ]);
        });
    }
};
