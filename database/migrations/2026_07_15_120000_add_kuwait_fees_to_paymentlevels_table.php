<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paymentlevels', function (Blueprint $table) {
            $table->string('kuwait_fees')->nullable()->after('oman_fees');
        });
    }

    public function down(): void
    {
        Schema::table('paymentlevels', function (Blueprint $table) {
            $table->dropColumn('kuwait_fees');
        });
    }
};
