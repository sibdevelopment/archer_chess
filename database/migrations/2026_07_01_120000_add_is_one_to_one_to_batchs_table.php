<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsOneToOneToBatchsTable extends Migration
{
    public function up()
    {
        Schema::table('batchs', function (Blueprint $table) {
            $table->boolean('is_one_to_one')->default(false)->after('kids_zone_name');
        });
    }

    public function down()
    {
        Schema::table('batchs', function (Blueprint $table) {
            $table->dropColumn('is_one_to_one');
        });
    }
}
