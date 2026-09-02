<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('paymentlevels', 'malaysia_fees')) {
            Schema::table('paymentlevels', function (Blueprint $table) {
                $table->string('malaysia_fees')->nullable()->after('singapore_fees');
            });
        }

        if (! Schema::hasColumn('paymentlevels', 'hong_kong_fees')) {
            Schema::table('paymentlevels', function (Blueprint $table) {
                $table->string('hong_kong_fees')->nullable()->after('malaysia_fees');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('paymentlevels', 'hong_kong_fees')) {
            Schema::table('paymentlevels', function (Blueprint $table) {
                $table->dropColumn('hong_kong_fees');
            });
        }

        if (Schema::hasColumn('paymentlevels', 'malaysia_fees')) {
            Schema::table('paymentlevels', function (Blueprint $table) {
                $table->dropColumn('malaysia_fees');
            });
        }
    }
};
