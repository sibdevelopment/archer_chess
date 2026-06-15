<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateleaverequestsTable extends Migration
{
    public function up()
    {
        Schema::create('leaverequests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coach_id')->nullable(); 
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->string('reason')->nullable();
            $table->string('status')->nullable();


            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('coach_id')->references('id')->on('coachs')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('leaverequests');
    }
}
