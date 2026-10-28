<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demoleadenquiries', function (Blueprint $table) {
            $table->string('integration_source')->nullable()->after('utm_medium')->index();
            $table->string('integration_external_id')->nullable()->after('integration_source')->unique();
            $table->timestamp('source_created_at')->nullable()->after('integration_external_id');
            $table->string('meta_form_id', 64)->nullable()->after('meta_lead_id');
            $table->string('meta_form_name')->nullable()->after('meta_form_id');
            $table->string('meta_page_id', 64)->nullable()->after('meta_form_name');
            $table->string('meta_campaign_id', 64)->nullable()->after('meta_page_id');
            $table->string('meta_campaign_name')->nullable()->after('meta_campaign_id');
            $table->string('meta_adset_id', 64)->nullable()->after('meta_campaign_name');
            $table->string('meta_adset_name')->nullable()->after('meta_adset_id');
            $table->string('meta_ad_id', 64)->nullable()->after('meta_adset_name');
            $table->string('meta_ad_name')->nullable()->after('meta_ad_id');
        });
    }

    public function down(): void
    {
        Schema::table('demoleadenquiries', function (Blueprint $table) {
            $table->dropUnique(['integration_external_id']);
            $table->dropIndex(['integration_source']);
            $table->dropColumn([
                'integration_source',
                'integration_external_id',
                'source_created_at',
                'meta_form_id',
                'meta_form_name',
                'meta_page_id',
                'meta_campaign_id',
                'meta_campaign_name',
                'meta_adset_id',
                'meta_adset_name',
                'meta_ad_id',
                'meta_ad_name',
            ]);
        });
    }
};
