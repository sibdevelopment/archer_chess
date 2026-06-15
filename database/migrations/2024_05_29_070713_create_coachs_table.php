<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatecoachsTable extends Migration
{
    public function up()
    {
        Schema::create('coachs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable();
            // $table->string('first_name')->nullable();
            // $table->string('last_name')->nullable();
            // $table->string('mobile')->nullable();
            // $table->string('email')->nullable();
            $table->string('zoom_id')->nullable();
            $table->string('zoom_password')->nullable();
            $table->string('portal_id')->nullable();
            $table->string('portal_password')->nullable();
            $table->string('index')->nullable();
            $table->string('status')->default('INACTIVE');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('coachs');
    }
}
