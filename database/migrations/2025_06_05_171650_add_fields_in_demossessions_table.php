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
        Schema::table('demo_sessions', function (Blueprint $table) {
            $table->text('start_url')->nullable()->after('index');
            $table->text('join_url')->nullable()->after('start_url');
            $table->string('zoom_meeting_id')->nullable()->after('join_url');
            $table->string('zoom_meeting_uuid')->nullable()->after('zoom_meeting_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demossessions', function (Blueprint $table) {
            //
        });
    }
};
