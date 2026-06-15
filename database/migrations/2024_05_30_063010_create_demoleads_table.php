<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatedemoleadsTable extends Migration
{
    public function up()
    {
        Schema::create('demoleads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('age')->nullable();
            $table->string('mobile')->nullable();
            $table->string('address')->nullable();
            $table->string('remark')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('status')->default('INACTIVE');
            $table->string('index')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('demoleads');
    }
}
