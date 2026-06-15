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
            $table->string('is_hide')->default('0')->after('utm_medium');
        });
        Schema::table('demoleads', function (Blueprint $table) {
            $table->string('is_hide')->default('0')->after('index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
