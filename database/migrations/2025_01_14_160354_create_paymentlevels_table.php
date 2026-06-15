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
        Schema::create('paymentlevels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('level_id')->nullable();
            $table->string('sequence')->nullable();
            $table->string('usa_fees')->nullable();
            $table->string('canada_fees')->nullable();
            $table->string('australia_fees')->nullable();
            $table->string('newzealand_fees')->nullable();
            $table->string('india_fees')->nullable();
            $table->string('uae_fees')->nullable();
            $table->string('uk_fees')->nullable();
            $table->string('status')->default('ACTIVE');
            $table->timestamps();
        });

        // Schema::table('students', function (Blueprint $table) {
        //     $table->foreignId('lastpayment_level_id')->nullable()->constrained('paymentlevels')->onDelete('set null');
        // });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paymentlevels');
    }
};
