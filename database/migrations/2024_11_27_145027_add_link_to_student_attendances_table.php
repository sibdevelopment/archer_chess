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
        Schema::table('student_attendances', function (Blueprint $table) {
            $table->text('homework_link')->nullable()->after('time');
            $table->text('recording_link')->nullable()->after('homework_link');
        });
        Schema::table('coach_attendances', function (Blueprint $table) {
            $table->text('homework_link')->nullable()->after('time');
            $table->text('recording_link')->nullable()->after('homework_link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_attendances', function (Blueprint $table) {
            //
        });
    }
};
