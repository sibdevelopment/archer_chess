<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('current_owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('related_type')->nullable();
            $table->string('related_name')->nullable();
            $table->string('country')->nullable();
            $table->text('last_action')->nullable();
            $table->text('next_action')->nullable();
            $table->text('requirement')->nullable();
            $table->string('priority')->default('MEDIUM');
            $table->string('status')->default('PENDING');
            $table->text('source_note')->nullable();
            $table->text('final_reason')->nullable();
            $table->dateTime('action_at')->nullable();
            $table->text('assignee_note')->nullable();
            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index(['current_owner_id', 'status']);
            $table->index(['created_by', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_assignments');
    }
};
