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
        Schema::table('coachs', function (Blueprint $table) {
            $table->string('zoom_api_key')->nullable()->after('zoom_id');
            $table->string('zoom_client_secret')->nullable()->after('zoom_api_key');
            $table->string('zoom_user_id')->nullable()->after('zoom_client_secret');
            $table->string('zoom_user_email')->nullable()->after('zoom_user_id');
        });
        
        Schema::table('batchs', function (Blueprint $table) {
            $table->text('start_url')->nullable()->after('confirm_reassign_batch_id');
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
        Schema::table('coachs', function (Blueprint $table) {
            //
        });
    }
};
