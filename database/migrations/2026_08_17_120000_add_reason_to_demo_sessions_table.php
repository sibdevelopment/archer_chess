<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('demo_sessions', 'reason')) {
            Schema::table('demo_sessions', function (Blueprint $table) {
                $table->text('reason')->nullable()->after('slot');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('demo_sessions', 'reason')) {
            Schema::table('demo_sessions', function (Blueprint $table) {
                $table->dropColumn('reason');
            });
        }
    }
};
