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
        Schema::table('leaverequests', function (Blueprint $table) {
            $table->time('from_time')->nullable()->after('coach_id');
            $table->time('to_time')->nullable()->after('from_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leaverequests', function (Blueprint $table) {
            //
        });
    }
};
