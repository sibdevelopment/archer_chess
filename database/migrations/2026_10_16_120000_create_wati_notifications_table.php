<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wati_notifications', function (Blueprint $table) {
            $table->id();
            $table->uuid('local_message_id')->unique();
            $table->string('idempotency_key', 64)->unique();
            $table->string('type', 40)->index();
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->foreignId('student_fee_id')->nullable()->constrained('student_fees')->nullOnDelete();
            $table->foreignId('batch_class_session_id')->nullable()->constrained('batch_class_sessions')->nullOnDelete();
            $table->foreignId('initiated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('recipient', 30);
            $table->string('template_name');
            $table->string('template_language', 20)->default('en');
            $table->string('broadcast_name');
            $table->json('parameters')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('currency', 20)->nullable();
            $table->string('status', 30)->default('QUEUED')->index();
            $table->string('provider_message_id')->nullable()->index();
            $table->string('whatsapp_message_id')->nullable()->index();
            $table->unsignedInteger('attempts')->default(0);
            $table->unsignedSmallInteger('response_status')->nullable();
            $table->json('provider_response')->nullable();
            $table->json('last_webhook_payload')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamp('queued_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();

            $table->index(['type', 'created_at']);
            $table->index(['batch_class_session_id', 'student_id'], 'wati_session_student_index');
        });

        Schema::table('fee_due_whatsapp_notifications', function (Blueprint $table) {
            $table->foreignId('wati_notification_id')
                ->nullable()
                ->after('student_fee_id')
                ->constrained('wati_notifications')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('fee_due_whatsapp_notifications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('wati_notification_id');
        });

        Schema::dropIfExists('wati_notifications');
    }
};
