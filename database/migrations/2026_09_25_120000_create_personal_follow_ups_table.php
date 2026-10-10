<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('follow_up_at');
            $table->string('related_to')->nullable();
            $table->string('related_name')->nullable();
            $table->string('follow_up_type')->nullable();
            $table->text('what_to_do');
            $table->string('priority')->default('MEDIUM');
            $table->string('status')->default('PENDING');
            $table->text('reason_note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['user_id', 'status', 'follow_up_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_follow_ups');
    }
};
