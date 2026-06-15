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
            $table->date('ist_date')->nullable()->after('date');
            $table->time('ist_time')->nullable()->after('time');
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
