# Users Module Documentation - ArcherKids

## 1. Project Context

This document explains the **Users module** of the ArcherKids Laravel application.

Project directory:

```text
C:\xampp\htdocs\archerkids
```

Framework and major packages:

```text
Laravel 10
PHP 8+
Spatie Laravel Permission
Laravel Passport
Laravel Sanctum
Yajra DataTables
```

The Users module is part of the admin panel and controls internal users, roles, coaches, permissions, and coach availability.

## 2. Users Module Overview

In the admin sidebar, the Users module includes:

- Coach Availability
- Roles
- Employees
- Coaches

Dedicated deep-dive documents are available in the same folder:

```text
docs/users/01-COACH-AVAILABILITY.md
docs/users/02-ROLES.md
docs/users/03-EMPLOYEES.md
docs/users/04-COACHES.md
```

Use this file for the overall concept, and use the dedicated files when a developer or client needs screen-wise implementation detail.

This module is important because many other parts of the system depend on it:

- Demo scheduling
- Batch scheduling
- Level test scheduling
- Coach dashboard
- Student dashboard
- Reports
- Leave requests
- Cover-up class assignment
- Country-based data access

Extra micro details to remember:

- Roles carry both permissions and country scope.
- Employees are internal staff users and can have extra flags like `student_fees_edit`, `batch_edit`, and `component_tab`.
- Coaches are login users plus coach profiles, and can store Zoom/portal credentials.
- Coach availability means the coach can work in that period; the availability grid also checks actual class/demo usage before showing a slot as free.

## 3. Main Route File

The Users module routes are defined in:

```text
routes/backend.php
```

All admin routes are protected by:

```php
Route::middleware(['auth', 'admin', 'preventBackHistory'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // Admin routes
    });
});
```

This means a user must be logged in, must pass the admin middleware, and browser back-button access is prevented after logout.

## 4. Coach Availability

### Purpose

The Coach Availability screen shows a weekly availability grid for a selected coach.

Admin can select:

- Coach
- Date

The system then displays a week-wise grid with 30-minute slots.

Color meaning:

- Green: Coach is available.
- Red: Coach has a class or booked slot.
- Yellow: Coach is not available / N/A.

### Routes

```php
Route::get('availability/index', [DashboardController::class, 'availabilityIndex'])->name('availability.index');
Route::get('availability', [DashboardController::class, 'availability'])->name('availability');
```

### Main Files

```text
app/Http/Controllers/Admin/DashboardController.php
app/Http/Controllers/Admin/CoachAvailabilityController.php
app/Models/Coach.php
app/Models/CoachAvailability.php
app/Models/CoachAvailabilityPeriod.php
resources/views/Admin/Dashboard/availability-index.blade.php
resources/views/Admin/Dashboard/availability.blade.php
resources/views/Admin/CoachAvailabilities/*
```

### Database Tables

```text
coachs
coach_availabilities
coach_availability_periods
batchs
batch_schedules
demo_sessions
coach_attendances
holidays
leave_requests
```

### Functional Explanation

Coach availability is used to decide when a coach can take classes or demos.

Example:

```text
Coach: Sunayana Kulkarni
Monday: 3:00 PM - 6:00 PM
Thursday: 4:00 PM - 8:00 PM
Saturday: 6:00 PM - 8:00 PM
```

The availability module stores the weekday in `coach_availabilities` and time ranges in `coach_availability_periods`.

## 5. Coach Availability Management

### Routes

```php
Route::prefix('coaches/{coach}')->name('coaches.')->group(function () {
    Route::resource('coach_availabilities', CoachAvailabilityController::class);
    Route::post('coach_availabilities/data', [CoachAvailabilityController::class, 'data'])->name('coach_availabilities.data');
    Route::post('coach_availabilities/change-status', [CoachAvailabilityController::class, 'changeStatus'])->name('coach_availabilities.change.status');
    Route::get('edit_availabilities', [CoachAvailabilityController::class, 'editAll'])->name('coach_availabilities.editAll');
    Route::put('update_availabilities', [CoachAvailabilityController::class, 'updateAll'])->name('coach_availabilities.updateAll');
});
```

Additional AJAX/helper routes:

```php
Route::post('coach_availabilities/list', [CoachAvailabilityController::class, 'list'])->name('coach_availabilities.list');
Route::post('coach_availabilities/add-day', [CoachAvailabilityController::class, 'addDay'])->name('coach_availabilities.add_day');
Route::post('coach_availabilities/edit-day', [CoachAvailabilityController::class, 'editDay'])->name('coach_availabilities.edit_day');
Route::post('coach_availabilities/delete-day', [CoachAvailabilityController::class, 'deleteDay'])->name('coach_availabilities.delete_day');
Route::post('coach_availabilities/add-day-period', [CoachAvailabilityController::class, 'addPeriod'])->name('coach_availabilities.add_day_period');
Route::post('coach_availabilities/edit-period', [CoachAvailabilityController::class, 'editPeriod'])->name('coach_availabilities.edit_period');
Route::post('coach_availabilities/delete-period', [CoachAvailabilityController::class, 'deletePeriod'])->name('coach_availabilities.delete_period');
```

### Important Controller

```text
app/Http/Controllers/Admin/CoachAvailabilityController.php
```

Important methods:

```php
index()
data()
list()
create()
addDay()
addPeriod()
store()
editAll()
editDay()
editPeriod()
updateAll()
changeStatus()
```

### Data Relationship

```text
Coach has many CoachAvailability
CoachAvailability has many CoachAvailabilityPeriod
```

Code:

```php
// app/Models/Coach.php
public function coach_availabilities()
{
    return $this->hasMany(CoachAvailability::class);
}

// app/Models/CoachAvailability.php
public function periods()
{
    return $this->hasMany(CoachAvailabilityPeriod::class, 'availability_id');
}

// app/Models/CoachAvailabilityPeriod.php
public function coachAvailability()
{
    return $this->belongsTo(CoachAvailability::class, 'availability_id');
}
```

## 6. Roles

### Purpose

Roles define what an employee/admin user can access.

Examples:

```text
CRM USA and Canada
CRM Asia and Australia
Sales Executive USA Canada
Admin UK
CRE UK and EU
```

### Routes

```php
Route::resource('roles', RoleController::class);
Route::post('roles/data', [RoleController::class, 'data'])->name('roles.data');
Route::post('roles/list', [RoleController::class, 'list'])->name('roles.list');
Route::get('roles/{role}/permission/show', [RoleController::class, 'permissionsShow'])->name('roles.permissions.show');
Route::post('roles/{role}/permission/update', [RoleController::class, 'permissionsUpdate'])->name('roles.permissions.update');
```

### Main Files

```text
app/Http/Controllers/Admin/RoleController.php
app/Models/Role.php
app/Models/Permission.php
app/Models/Permissiongroup.php
database/seeders/PermissionSeeder.php
resources/views/Admin/Roles/*
```

### Database Tables

```text
roles
permissions
permissiongroups
model_has_roles
model_has_permissions
role_has_permissions
```

### Functional Explanation

The app uses Spatie Laravel Permission.

When permissions are updated for a role, the system also updates users who already have that role.

Important logic:

```php
$role->syncPermissions($request->permissions);
$users = User::role($role->name)->get();

foreach ($users as $user) {
    $user->syncPermissions($request->permissions);
}
```

### Validation

Role creation requires:

```text
name
countries
```

The role name must be unique and alphabetic.

## 7. Employees

### Purpose

Employees are internal staff/admin users. They can log in to the admin panel and their access depends on assigned roles.

### Available Actions

- View employee list
- Search employees
- Filter by status
- Create employee
- Edit employee
- View employee
- Activate/deactivate employee

### Routes

```php
Route::resource('employees', EmployeeController::class);
Route::post('employees/data', [EmployeeController::class, 'data'])->name('employees.data');
Route::post('employees/list', [EmployeeController::class, 'list'])->name('employees.list');
Route::post('employees/change-status', [EmployeeController::class, 'changeStatus'])->name('employees.change.status');
```

### Main Files

```text
app/Http/Controllers/Admin/EmployeeController.php
app/Models/Employee.php
app/Models/User.php
resources/views/Admin/Employees/*
```

### Database Tables

```text
users
employees
roles
permissions
model_has_roles
model_has_permissions
camera_checks
```

### Important Fields

User fields:

```text
first_name
last_name
email
country_code
mobile
password
status
device_id
```

Employee fields:

```text
user_id
decrypt_password
camera_consented
camera_available
camera_snapshot_path
```

### Employee Creation Flow

1. Admin fills employee form.
2. System creates a record in `users`.
3. Password is hashed and saved in `users.password`.
4. Selected roles are assigned.
5. Permissions from roles are synced to the user.
6. Employee record is created in `employees`.

Important code:

```php
$user = new User;
$user->fill($request->all());
$user->password = bcrypt($request->password);
$user->save();

$user->assignRole($request->roles);
$user->syncPermissions($permissions);

$employee = new Employee;
$employee->user_id = $user->id;
$employee->decrypt_password = $request->password;
$employee->save();
```

### Status

```text
ACTIVE
INACTIVE
```

The status toggle updates the related `users.status` field.

Security note:

The application currently stores employee plain password in `employees.decrypt_password`. This should be removed in a future security cleanup.

## 8. Coaches

### Purpose

Coaches are teaching users. A coach is linked to a `users` record and automatically receives the `Coach` role.

Coaches are used in:

- Batches
- Demo sessions
- Level tests
- Masterclasses
- Tournaments
- Cover-up classes
- Reports
- Attendance

### Available Actions

- View coach list
- Search coaches
- Filter by country
- Filter by available weekday
- Filter by status
- Create coach
- Edit coach
- View coach details
- Login as coach
- Activate/deactivate coach
- Manage coach availability

### Routes

```php
Route::resource('coaches', CoachController::class);
Route::post('coaches/data', [CoachController::class, 'data'])->name('coaches.data');
Route::post('coaches/list', [CoachController::class, 'list'])->name('coaches.list');
Route::post('coaches/change-status', [CoachController::class, 'changeStatus'])->name('coaches.change.status');
```

### Main Files

```text
app/Http/Controllers/Admin/CoachController.php
app/Http/Controllers/Admin/CoachAvailabilityController.php
app/Models/Coach.php
app/Models/User.php
app/Models/CoachAvailability.php
app/Models/CoachAvailabilityPeriod.php
resources/views/Admin/Coachs/*
resources/views/Admin/CoachAvailabilities/*
```

### Database Tables

```text
users
coachs
coach_availabilities
coach_availability_periods
batchs
student_batches
coach_attendances
leave_requests
```

### Important Fields

```text
status
user_id
decrypt_password
zoom_id
zoom_password
portal_id
portal_password
pan_number
zoom_link
country
zoom_api_key
zoom_client_secret
zoom_user_id
created_by
updated_by
```

### Coach Creation Flow

1. Admin fills coach form.
2. System creates a `users` record.
3. System hashes the password.
4. System assigns the `Coach` role.
5. System syncs Coach role permissions.
6. System creates a `coachs` record linked to the user.

Important code:

```php
$user = new User;
$user->fill($request->all());
$user->password = bcrypt($request->password);
$user->save();

$user->assignRole('Coach');
$role = Role::where('name', 'Coach')->first();
$permissions = $role->permissions()->get();
$user->syncPermissions($permissions);

$coach = new Coach;
$coach->fill($request->all());
$coach->user_id = $user->id;
$coach->decrypt_password = $request->password;
$coach->save();
```

### Coach Status

```text
ACTIVE
INACTIVE
```

Only active coaches should be used for new scheduling.

### Country Filtering

Coach data is filtered by country for non-SuperAdmin users.

Important logic:

```php
if (!$user->roles()->where('name', 'SuperAdmin')->exists()) {
    $countries = $user->roles()->pluck('countries')->flatten()->filter()->first();
}
```

If a role has country restrictions, the coach list is filtered based on the countries assigned to that role.

## 9. User Model

Main file:

```text
app/Models/User.php
```

Important traits:

```php
use HasApiTokens, HasFactory, Notifiable, HasRoles, Hashidable;
```

This means the user model supports:

- Laravel Passport API tokens
- Notifications
- Spatie roles and permissions
- Hashid route keys

Important relationships:

```php
public function coach()
{
    return $this->hasOne(Coach::class, 'user_id');
}
```

Computed attribute:

```php
public function getFullNameAttribute()
{
    return $this->first_name . ' ' . $this->last_name;
}
```

## 10. Permission System

The project uses:

```text
spatie/laravel-permission
```

This controls:

- Roles
- Permissions
- Which menu/action each user can access
- Country-based access indirectly through role `countries`

Important files:

```text
app/Models/Role.php
app/Models/Permission.php
app/Models/Permissiongroup.php
database/seeders/PermissionSeeder.php
```

Client explanation:

> A permission is a small access rule, such as "view students" or "edit batches". A role is a group of permissions. Employees get roles, and their role decides what they can see or change.

## 11. Country-Based Access

Some roles are connected to selected countries. This is used to restrict what data an employee can see.

Example:

```text
SuperAdmin: sees all data
CRM USA and Canada: sees USA/CANADA-related data
Admin UK: sees UK-related data
```

Country-based filtering is used in multiple modules, including:

```text
CoachController
DashboardController
StudentController
DemoLeadController
LeadEnquiryController
NewEnrollmentController
```

## 12. Common Client Workflows

### Create a New Role

1. Go to Users > Roles.
2. Click Create Role.
3. Enter role name.
4. Select countries.
5. Save.
6. Open Permissions.
7. Select allowed permissions.
8. Save permissions.

### Create a New Employee

1. Go to Users > Employees.
2. Click Create Employee.
3. Fill first name, last name, email, mobile, and password.
4. Select role.
5. Select status.
6. Save.

### Create a New Coach

1. Go to Users > Coaches.
2. Click Create Coach.
3. Fill coach details.
4. Select country.
5. Add Zoom/portal details if required.
6. Set status as ACTIVE.
7. Save.
8. Add coach availability.

### Add Coach Availability

1. Go to Users > Coaches.
2. Click Availability for the coach.
3. Add weekday.
4. Add one or more time periods.
5. Save.

### Check Weekly Coach Availability

1. Go to Users > Coach Availability.
2. Select coach.
3. Select date.
4. Review weekly grid.

## 13. Developer Change Guide

### Change Roles Behavior

Check:

```text
app/Http/Controllers/Admin/RoleController.php
resources/views/Admin/Roles/*
database/seeders/PermissionSeeder.php
```

### Change Employee Form or Logic

Check:

```text
app/Http/Controllers/Admin/EmployeeController.php
resources/views/Admin/Employees/form.blade.php
resources/views/Admin/Employees/index.blade.php
app/Models/Employee.php
```

### Change Coach Form or Logic

Check:

```text
app/Http/Controllers/Admin/CoachController.php
resources/views/Admin/Coachs/form.blade.php
resources/views/Admin/Coachs/index.blade.php
app/Models/Coach.php
```

### Change Coach Availability Logic

Check:

```text
app/Http/Controllers/Admin/CoachAvailabilityController.php
app/Http/Controllers/Admin/DashboardController.php
resources/views/Admin/CoachAvailabilities/*
resources/views/Admin/Dashboard/availability*.blade.php
app/Models/CoachAvailability.php
app/Models/CoachAvailabilityPeriod.php
```

## 14. Security Notes

These points should be reviewed before production handover:

1. Plain passwords are stored in `decrypt_password` fields for employees and coaches.
2. Coach Zoom credentials are stored in coach records.
3. The coach list displays sensitive fields like Zoom password and portal password.
4. "Login as Coach" should be restricted to trusted admin users only.
5. Inactive employees/coaches should not be able to access protected screens.
6. `.env` must never be committed to Git.
7. Payment, Zoom, SMS, and API credentials should live in `.env`, not directly in code.

## 15. Summary

The Users module is the base layer for admin access and scheduling.

- Roles control access.
- Employees are internal admin/staff users.
- Coaches are teaching users linked with sessions and batches.
- Coach Availability controls when coaches can be scheduled.
- Country-based role access controls what data employees can see.

Before changing demo scheduling, batch scheduling, reports, attendance, or leave management, developers should understand this module first because those features depend heavily on users, roles, coaches, and availability.
