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
            $table->string('status')->nullable()->after('level');
            $table->boolean('email_verified')->nullable()->after('status');
            $table->boolean('mobile_verified')->nullable()->after('email_verified');
            $table->string('lead_status')->nullable()->after('mobile_verified');
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
