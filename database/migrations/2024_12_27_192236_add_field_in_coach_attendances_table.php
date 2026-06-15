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
        Schema::table('coach_attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('masterclass_id')->nullable()->after('batch_id');
            $table->foreign('masterclass_id')->references('id')->on('masterclasses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coach_attendances', function (Blueprint $table) {
            //
        });
    }
};
