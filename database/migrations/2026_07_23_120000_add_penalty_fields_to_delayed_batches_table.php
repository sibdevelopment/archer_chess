<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delayed_batches', function (Blueprint $table) {
            $table->string('penalty_type')->nullable()->after('canceled_time');
            $table->decimal('fine_amount', 10, 2)->default(0)->after('penalty_type');
            $table->string('fine_currency', 10)->default('INR')->after('fine_amount');
        });
    }

    public function down(): void
    {
        Schema::table('delayed_batches', function (Blueprint $table) {
            $table->dropColumn([
                'penalty_type',
                'fine_amount',
                'fine_currency',
            ]);
        });
    }
};
