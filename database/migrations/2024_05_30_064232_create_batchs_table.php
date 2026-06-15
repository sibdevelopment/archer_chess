<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatebatchsTable extends Migration
{
    public function up()
    {
        Schema::create('batchs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('coach_id')->nullable();
            $table->string('status')->default('INACTIVE');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('coach_id')->references('id')->on('coachs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('batchs');
    }
}
