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
        Schema::table('batchs', function (Blueprint $table) {
            $table->unsignedBigInteger('confirm_reassign_batch_id')->nullable()->after('confirm_reassign');
            $table->foreign('confirm_reassign_batch_id')->references('id')->on('batchs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batchs', function (Blueprint $table) {
            //
        });
    }
};
