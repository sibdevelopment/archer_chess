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
        Schema::create('demoleadenquiries', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('timezone')->nullable();
            $table->string('duration')->default('25_minutes');
            $table->string('parent_name')->nullable();
            $table->string('kids_first_name')->nullable();
            $table->string('kids_last_name')->nullable();
            $table->integer('age')->nullable();
            $table->date('dob')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('available_device')->nullable();
            $table->string('enrollment_plan')->nullable();
            $table->string('language_preference')->nullable();
            $table->string('level')->nullable();
            
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('demo_lead_enquiries');
    }
};
