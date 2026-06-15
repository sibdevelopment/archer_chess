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
        //Date, Mode, Short Desc, location
        Schema::table('events', function (Blueprint $table) {
           $table->string('date')->nullable()->after('index');
           $table->string('mode')->nullable()->after('date');
           $table->longText('short_description')->nullable()->after('mode');
           $table->longText('location')->nullable()->after('short_description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            //
        });
    }
};
