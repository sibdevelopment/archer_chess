<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delayed_batches', function (Blueprint $table) {
            if (! Schema::hasColumn('delayed_batches', 'batchschedule_id')) {
                $table->unsignedBigInteger('batchschedule_id')->nullable()->after('batch_id');
            }

            if (! Schema::hasColumn('delayed_batches', 'late_popup_acknowledged_at')) {
                $table->timestamp('late_popup_acknowledged_at')->nullable()->after('fine_currency');
            }
        });
    }

    public function down(): void
    {
        Schema::table('delayed_batches', function (Blueprint $table) {
            if (Schema::hasColumn('delayed_batches', 'late_popup_acknowledged_at')) {
                $table->dropColumn('late_popup_acknowledged_at');
            }

            if (Schema::hasColumn('delayed_batches', 'batchschedule_id')) {
                $table->dropColumn('batchschedule_id');
            }
        });
    }
};
