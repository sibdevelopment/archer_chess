<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demoleadenquiries', function (Blueprint $table) {
            $table->string('utm_campaign')->nullable()->after('utm_medium');
            $table->string('utm_content')->nullable()->after('utm_campaign');
            $table->string('utm_term')->nullable()->after('utm_content');
        });
    }

    public function down(): void
    {
        Schema::table('demoleadenquiries', function (Blueprint $table) {
            $table->dropColumn(['utm_campaign', 'utm_content', 'utm_term']);
        });
    }
};
