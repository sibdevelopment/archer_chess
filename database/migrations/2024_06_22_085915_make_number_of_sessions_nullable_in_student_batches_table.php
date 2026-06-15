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
        Schema::table('student_batches', function (Blueprint $table) {
            $table->dropColumn('number_of_sessions');
        });
    
        Schema::table('student_batches', function (Blueprint $table) {
            $table->integer('number_of_sessions')->nullable()->after('level_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_batches', function (Blueprint $table) {
            //
        });
    }
};
