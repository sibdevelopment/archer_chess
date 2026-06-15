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
        Schema::table('changeclasses', function (Blueprint $table) {
            $table->text('employee_ids')->nullable()->after('employee_id');
            $table->string('is_submitted')->default('0')->after('employee_ids');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('changeclasses', function (Blueprint $table) {
            //
        });
    }
};
