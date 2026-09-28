<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_due_whatsapp_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('student_fee_id')->nullable()->constrained('student_fees')->nullOnDelete();
            $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('fee_amount', 10, 2)->nullable();
            $table->string('currency', 20)->nullable();
            $table->text('message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'student_fee_id'], 'fee_due_whatsapp_unique_student_fee');
            $table->index(['student_id', 'sent_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_due_whatsapp_notifications');
    }
};
