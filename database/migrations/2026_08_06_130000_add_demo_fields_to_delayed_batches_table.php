<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE delayed_batches MODIFY batch_id BIGINT UNSIGNED NULL');

        Schema::table('delayed_batches', function (Blueprint $table) {
            if (! Schema::hasColumn('delayed_batches', 'occurrence_type')) {
                $table->string('occurrence_type', 20)->default('BATCH')->after('id');
            }

            if (! Schema::hasColumn('delayed_batches', 'demo_session_id')) {
                $table->unsignedBigInteger('demo_session_id')->nullable()->after('batchschedule_id');
            }

            if (! Schema::hasColumn('delayed_batches', 'demolead_id')) {
                $table->unsignedBigInteger('demolead_id')->nullable()->after('demo_session_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('delayed_batches', function (Blueprint $table) {
            if (Schema::hasColumn('delayed_batches', 'demolead_id')) {
                $table->dropColumn('demolead_id');
            }

            if (Schema::hasColumn('delayed_batches', 'demo_session_id')) {
                $table->dropColumn('demo_session_id');
            }

            if (Schema::hasColumn('delayed_batches', 'occurrence_type')) {
                $table->dropColumn('occurrence_type');
            }
        });
    }
};
