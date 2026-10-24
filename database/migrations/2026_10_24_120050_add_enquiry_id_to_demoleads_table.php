<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demoleads', function (Blueprint $table) {
            $table->unsignedBigInteger('demo_lead_enquiry_id')->nullable()->after('user_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('demoleads', function (Blueprint $table) {
            $table->dropIndex(['demo_lead_enquiry_id']);
            $table->dropColumn('demo_lead_enquiry_id');
        });
    }
};
