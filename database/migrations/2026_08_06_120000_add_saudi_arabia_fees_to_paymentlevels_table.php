<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('paymentlevels', 'saudi_arabia_fees')) {
            Schema::table('paymentlevels', function (Blueprint $table) {
                $table->string('saudi_arabia_fees')->nullable()->after('south_africa_fees');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('paymentlevels', 'saudi_arabia_fees')) {
            Schema::table('paymentlevels', function (Blueprint $table) {
                $table->dropColumn('saudi_arabia_fees');
            });
        }
    }
};
