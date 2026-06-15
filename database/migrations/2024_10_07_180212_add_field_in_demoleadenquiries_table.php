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
        Schema::table('demoleadenquiries', function (Blueprint $table) {
            $table->string('utm_source')->nullable()->after('email_otp');
            $table->string('utm_medium')->nullable()->after('utm_source');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demoleadenquiries', function (Blueprint $table) {
            //
        });
    }
};
