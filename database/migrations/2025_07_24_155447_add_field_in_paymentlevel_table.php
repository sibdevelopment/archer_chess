<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paymentlevels', function (Blueprint $table) {
            $table->string('qatar_fees')->nullable()->after('uk_fees');
            $table->string('singapore_fees')->nullable()->after('qatar_fees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paymentlevel', function (Blueprint $table) {
            //
        });
    }
};
