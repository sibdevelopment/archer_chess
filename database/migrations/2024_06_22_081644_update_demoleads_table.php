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
            $table->dropColumn('address');
            $table->string('city')->nullable()->after('mobile');
            $table->string('country')->nullable()->after('city');
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
