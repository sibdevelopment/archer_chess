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
        Schema::table('demoleads', function (Blueprint $table) {
            $table->string('kids_time_zone')->nullable()->after('country');
            $table->date('kids_date')->nullable()->after('date');
            $table->time('kids_time')->nullable()->after('time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demoleads', function (Blueprint $table) {
            //
        });
    }
};
