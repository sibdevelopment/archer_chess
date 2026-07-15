<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paymentlevels', function (Blueprint $table) {
            $table->string('bahrain_fees')->nullable()->after('kuwait_fees');
            $table->string('south_africa_fees')->nullable()->after('bahrain_fees');
        });
    }

    public function down(): void
    {
        Schema::table('paymentlevels', function (Blueprint $table) {
            $table->dropColumn(['bahrain_fees', 'south_africa_fees']);
        });
    }
};
