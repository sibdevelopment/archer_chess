<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_enquiry_capi_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_enquiry_id')
                ->constrained('demoleadenquiries')
                ->cascadeOnDelete();
            $table->string('outcome', 32);
            $table->text('note');
            $table->foreignId('submitted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('submitted_at');
            $table->unsignedBigInteger('meta_capi_event_id')->nullable();
            $table->timestamps();

            $table->index(['lead_enquiry_id', 'submitted_at'], 'lead_feedback_history_index');
            $table->index('meta_capi_event_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_enquiry_capi_feedbacks');
    }
};
