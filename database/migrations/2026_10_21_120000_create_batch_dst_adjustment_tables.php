<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batch_dst_adjustment_runs', function (Blueprint $table) {
            $table->id();
            $table->string('transition_key')->unique();
            $table->string('region');
            $table->date('transition_date');
            $table->smallInteger('minutes');
            $table->string('status');
            $table->unsignedInteger('batch_count')->default(0);
            $table->timestamp('applied_at')->nullable();
            $table->timestamp('rolled_back_at')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
        });

        Schema::create('batch_dst_adjustment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('run_id')->constrained('batch_dst_adjustment_runs')->cascadeOnDelete();
            $table->unsignedBigInteger('batch_id');
            $table->json('before_state');
            $table->json('after_state');
            $table->timestamps();

            $table->unique(['run_id', 'batch_id']);
            $table->index('batch_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_dst_adjustment_items');
        Schema::dropIfExists('batch_dst_adjustment_runs');
    }
};
