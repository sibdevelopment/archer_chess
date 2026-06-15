<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCameraChecksTable extends Migration
{
    public function up()
    {
        Schema::create('camera_checks', function (Blueprint $table) {
            $table->bigIncrements('id');

            // link to employees table (nullable if not applicable)
            $table->unsignedBigInteger('employee_id')->nullable()->index();
            // optional: store user_id (account) that triggered it
            $table->unsignedBigInteger('user_id')->nullable()->index();

            $table->boolean('consented')->default(false);
            $table->boolean('available')->default(false);

            // store the saved snapshot path (if any)
            $table->string('snapshot_path')->nullable();

            // meta for audit
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            // foreign key constraints (optional)
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            // if user_id refers to users table uncomment below:
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('camera_checks');
    }
}
