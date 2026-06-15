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
        Schema::create('coverupclasses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('batchschedule_id');
            $table->unsignedBigInteger('old_coach_id');
            $table->unsignedBigInteger('new_coach_id');
            $table->date('date');
            $table->timestamps();

            $table->foreign('batch_id')->references('id')->on('batchs')->onDelete('cascade');
            $table->foreign('batchschedule_id')->references('id')->on('batch_schedules')->onDelete('cascade');
            $table->foreign('old_coach_id')->references('id')->on('coachs')->onDelete('cascade');
            $table->foreign('new_coach_id')->references('id')->on('coachs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coverupclasses');
    }
};
