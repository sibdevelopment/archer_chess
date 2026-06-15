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
        Schema::table('galleries', function (Blueprint $table) {
            $table->longText('images_1')->nullable()->after('title');
            $table->longText('images_2')->nullable()->after('images_1');
            $table->longText('images_3')->nullable()->after('images_2');
            $table->longText('images_4')->nullable()->after('images_3');
            $table->longText('images_5')->nullable()->after('images_4');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('galleries', function (Blueprint $table) {
            //
        });
    }
};
