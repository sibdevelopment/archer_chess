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
        Schema::create('coach_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coach_id')->nullable();
            $table->string('type')->nullable(); 
            $table->unsignedBigInteger('demolead_id')->nullable();
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->date('date')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('coach_id')->references('id')->on('coachs')->onDelete('cascade');
            $table->foreign('demolead_id')->references('id')->on('demoleads')->onDelete('cascade');
            $table->foreign('batch_id')->references('id')->on('batchs')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coach_attendances');
    }
};
