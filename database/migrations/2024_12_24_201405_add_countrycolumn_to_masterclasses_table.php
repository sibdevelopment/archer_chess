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
        Schema::table('masterclasses', function (Blueprint $table) {
            $table->text('country')->nullable()->after('level_ids');
        });
        Schema::table('tournaments', function (Blueprint $table) {
            $table->text('country')->nullable()->after('level_ids');
            $table->string('certificate')->nullable()->after('country');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('masterclasses', function (Blueprint $table) {
            //
        });
    }
};
