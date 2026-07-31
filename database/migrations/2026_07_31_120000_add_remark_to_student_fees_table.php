<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('student_fees', function (Blueprint $table) {
            $table->text('remark')->nullable()->after('total_amount_paid');
        });
    }

    public function down()
    {
        Schema::table('student_fees', function (Blueprint $table) {
            $table->dropColumn('remark');
        });
    }
};
