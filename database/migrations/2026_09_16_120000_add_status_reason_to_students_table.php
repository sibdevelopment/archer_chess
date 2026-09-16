<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('students', 'status_reason')) {
            Schema::table('students', function (Blueprint $table) {
                $table->text('status_reason')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('students', 'status_reason')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('status_reason');
            });
        }
    }
};
