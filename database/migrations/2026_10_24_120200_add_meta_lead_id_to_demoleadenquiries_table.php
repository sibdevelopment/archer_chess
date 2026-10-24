<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demoleadenquiries', function (Blueprint $table) {
            $table->string('meta_lead_id', 20)->nullable()->after('utm_medium')->index();
        });
    }

    public function down(): void
    {
        Schema::table('demoleadenquiries', function (Blueprint $table) {
            $table->dropIndex(['meta_lead_id']);
            $table->dropColumn('meta_lead_id');
        });
    }
};
