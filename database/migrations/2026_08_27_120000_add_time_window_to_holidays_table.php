<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('holidays', function (Blueprint $table) {
            if (! Schema::hasColumn('holidays', 'from_time')) {
                $table->time('from_time')->default('00:00:00')->after('end_date');
            }

            if (! Schema::hasColumn('holidays', 'to_time')) {
                $table->time('to_time')->default('23:59:00')->after('from_time');
            }

            if (! Schema::hasColumn('holidays', 'timezone')) {
                $table->string('timezone')->default('Asia/Kolkata')->after('to_time');
            }
        });
    }

    public function down(): void
    {
        Schema::table('holidays', function (Blueprint $table) {
            if (Schema::hasColumn('holidays', 'timezone')) {
                $table->dropColumn('timezone');
            }

            if (Schema::hasColumn('holidays', 'to_time')) {
                $table->dropColumn('to_time');
            }

            if (Schema::hasColumn('holidays', 'from_time')) {
                $table->dropColumn('from_time');
            }
        });
    }
};
