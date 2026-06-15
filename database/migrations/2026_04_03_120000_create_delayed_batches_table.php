<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Records batch sessions where the coach started more than the allowed delay after the scheduled time.
     */
    public function up(): void
    {
        Schema::create('delayed_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('coach_id')->nullable();

            $table->date('date')->nullable();
            $table->time('time')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('batch_id')->references('id')->on('batchs')->onDelete('cascade');
            $table->foreign('coach_id')->references('id')->on('coachs')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            $table->index(['batch_id', 'date', 'time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delayed_batches');
    }
};
