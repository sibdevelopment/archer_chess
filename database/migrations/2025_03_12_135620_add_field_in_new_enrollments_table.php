<?php

use App\Models\NewEnrollment;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_enrollments', function (Blueprint $table) {
            $table->text('employee_ids')->nullable()->after('employee_id');
        });

        $new_enrollments = NewEnrollment::all();

        foreach ($new_enrollments as $new_enrollment) {
            $employee_ids = [];
            if ($new_enrollment->employee_id) {
                $employee_ids[] = $new_enrollment->employee_id;
            }
            $new_enrollment->employee_ids = $employee_ids;
            $new_enrollment->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_enrollments', function (Blueprint $table) {
            //
        });
    }
};
