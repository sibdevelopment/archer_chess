<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_assignment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_assignment_id')->constrained('work_assignments')->cascadeOnDelete();
            $table->foreignId('action_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('from_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->string('status')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['work_assignment_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_assignment_histories');
    }
};
