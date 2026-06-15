<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Student;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AssignRoleTOStudent extends Command
{

    // Command signature and description
    protected $signature = 'assign:role-to-student';
    protected $description = 'Assign the "Student" role to students and create associated users if not present';

    public function handle()
    {
        $students = Student::all();
        // $duplicateMobiles = Student::query()
        //         ->select('mobile')
        //         ->groupBy('mobile')
        //         ->havingRaw('COUNT(mobile) > 1')
        //         ->pluck('mobile')
        //         ->toArray();

        // dd($duplicateMobiles);

        foreach ($students as $student) {
            if (isset($student->id)) {
                $user = User::find($student->user_id);

                // Create a new user if not found
                if (!$user) {
                    $user = new User();
                    $user->password = Hash::make('1234'); // Set default password
                }

                // Update user details
                $user->first_name = $student->first_name;
                $user->last_name = $student->last_name;
                $user->email = $student->email;
                $user->mobile = $student->mobile;
                $user->save();

                $password = 'archer@' . $user->id;
                $user->device_id = $password;
                $user->password = Hash::make($password);
                $user->save();


                // Assign the "Student" role
                $user->assignRole('Student');

                // Sync permissions for the "Student" role
                $role = Role::where('name', 'Student')->first();
                if ($role) {
                    $permissions = $role->permissions()->pluck('name')->toArray();
                    $user->syncPermissions($permissions);
                }

                // Update the student record with the user ID
                $student->user_id = $user->id;
                $student->save();

                print_r("Student ID: " . $student->id . " | User ID: " . $user->id . "\n");
            }
        }

        $this->info('Roles and users assigned to all students successfully.');
        return Command::SUCCESS;
    }
}
