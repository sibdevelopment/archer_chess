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
            $table->date('start_date')->after('status')->nullable();
            $table->date('end_date')->after('start_date')->nullable();
            $table->string('number_of_sessions')->after('end_date')->nullable();

            $table->foreignId('level_id')->after('coach_id')->nullable()->constrained('levels')->onDelete('set null');
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
