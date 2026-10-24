<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demoleadenquiries', function (Blueprint $table) {
            $table->text('meta_fbc')->nullable()->after('utm_medium');
            $table->string('meta_fbp')->nullable()->after('meta_fbc');
            $table->text('meta_fbclid')->nullable()->after('meta_fbp');
            $table->text('meta_landing_url')->nullable()->after('meta_fbclid');
            $table->string('meta_client_ip', 45)->nullable()->after('meta_landing_url');
            $table->text('meta_client_user_agent')->nullable()->after('meta_client_ip');
            $table->boolean('meta_tracking_allowed')->nullable()->after('meta_client_user_agent');
            $table->timestamp('meta_tracking_consent_at')->nullable()->after('meta_tracking_allowed');
        });
    }

    public function down(): void
    {
        Schema::table('demoleadenquiries', function (Blueprint $table) {
            $table->dropColumn([
                'meta_fbc',
                'meta_fbp',
                'meta_fbclid',
                'meta_landing_url',
                'meta_client_ip',
                'meta_client_user_agent',
                'meta_tracking_allowed',
                'meta_tracking_consent_at',
            ]);
        });
    }
};
