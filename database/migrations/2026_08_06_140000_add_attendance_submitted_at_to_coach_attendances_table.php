<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coach_attendances', function (Blueprint $table) {
            if (! Schema::hasColumn('coach_attendances', 'attendance_submitted_at')) {
                $table->timestamp('attendance_submitted_at')->nullable()->after('time');
            }
        });
    }

    public function down(): void
    {
        Schema::table('coach_attendances', function (Blueprint $table) {
            if (Schema::hasColumn('coach_attendances', 'attendance_submitted_at')) {
                $table->dropColumn('attendance_submitted_at');
            }
        });
    }
};
