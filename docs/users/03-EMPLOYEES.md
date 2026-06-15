# Employees Module

## Purpose

Employees are internal admin/staff users. They are different from coaches and students. An employee gets a login account in `users`, an employee profile in `employees`, one or more roles, copied permissions, and extra operational toggles.

Employees commonly represent:

- Sales staff.
- CRM/CRE staff.
- Lead generation staff.
- Admin/back-office users.
- Country-specific operational users.

## Main Files

```text
routes/backend.php
app/Http/Controllers/Admin/EmployeeController.php
app/Models/Employee.php
app/Models/User.php
app/Models/Role.php
resources/views/Admin/Employees/index.blade.php
resources/views/Admin/Employees/form.blade.php
resources/views/Admin/Employees/show.blade.php
resources/views/layouts/admin/navbar.blade.php
```

Camera/history related code:

```text
app/Models/CameraCheck.php
app/Http/Controllers/Admin/EmployeeCameraHistoryController.php
```

## Routes

```php
Route::resource('employees', EmployeeController::class);
Route::post('employees/data', [EmployeeController::class, 'data'])->name('employees.data');
Route::post('employees/list', [EmployeeController::class, 'list'])->name('employees.list');
Route::post('employees/change-status', [EmployeeController::class, 'changeStatus'])->name('employees.change.status');
```

Additional camera history route exists separately:

```php
Route::get('admin/employees/{employee}/camera-history', [EmployeeCameraHistoryController::class, 'index'])->name('admin.employees.camera.history');
Route::post('admin/employees/{employee}/camera-history/data', [EmployeeCameraHistoryController::class, 'data'])->name('admin.employees.camera.history.data');
```

## Permission

Sidebar uses:

```text
employee-view
```

Common permission names:

```text
employee-view
employee-store
employee-update
```

## Database Tables

### `users`

Stores the login account:

```text
first_name
last_name
email
mobile
password
status
student_fees_edit
batch_edit
component_tab
```

### `employees`

Stores employee-specific profile details:

```text
user_id
decrypt_password
camera_consented
camera_available
camera_snapshot_path
created_at
updated_at
```

### Permission Tables

Employee role/permission assignment uses:

```text
model_has_roles
model_has_permissions
role_has_permissions
```

## Employee Listing

Screen:

```text
Users > Employees
```

The DataTable shows:

- Row number.
- Edit/view actions.
- Status toggle.
- First name.
- Last name.
- Email.
- Mobile.

The table has export buttons:

```text
Copy
CSV
Excel
PDF
Print
```

The status dropdown filters:

```text
Active
Inactive
All
```

## Create/Edit Form

The form has three sections.

### Personal Info

```text
First Name
Last Name
Mobile
Role(s)
```

The role field is multi-select. Employees can have more than one role.

### Special Access Flags

```text
Student Fees Edit
Batch Edit
Component Tab
```

Meaning:

- `student_fees_edit = YES`: allows special editing actions inside fees screens.
- `batch_edit = YES`: allows special batch edit behavior where checked by batch views/controllers.
- `component_tab = YES`: shows the Component section in the sidebar.

The sidebar also allows Component access for hardcoded user IDs:

```text
1, 2, 4
```

### Login Info

```text
Email
Status
Password
Confirm Password
```

Status values:

```text
ACTIVE
INACTIVE
```

## Create Flow

When admin creates an employee:

1. Request is validated.
2. A new `User` is created.
3. Password is stored as bcrypt hash in `users.password`.
4. Special access flags are stored on the user.
5. Selected roles are assigned to the user.
6. Permissions from every selected role are copied to the user.
7. A new `Employee` row is created.
8. `employees.decrypt_password` stores the plain password.

Important security note: plain password storage is risky. It should be removed or encrypted before public/private repository push or production hardening.

## Update Flow

When admin edits an employee:

1. Email and mobile uniqueness are checked against the same user ID.
2. Password becomes optional.
3. User fields are updated.
4. Special flags are updated.
5. Existing roles are cleared.
6. New roles are assigned.
7. Permissions are re-synced from selected roles.
8. Employee fields are saved.
9. If password was entered, `decrypt_password` is updated.

Developer caution: in the current controller, `users.password` is assigned `bcrypt($request->password)` during update even when password is nullable. This should be reviewed before future changes, because an empty password request can unintentionally replace the existing password hash.

## Status Toggle

The status switch calls:

```text
admin.employees.change.status
```

It updates:

```text
users.status
```

not `employees.status`.

If an employee becomes inactive, their profile remains, but login/active behavior should be blocked wherever `users.status` is checked.

## Camera Snapshot

`EmployeeController::data()` has a `camera` column builder. It shows:

- Employee camera snapshot if `camera_snapshot_path` exists.
- A dash if no snapshot exists.

The current Employees table markup does not display the camera column in the visible column list, but the backend logic exists.

## Developer Notes

- Employee data is split between `users` and `employees`.
- Always update both sides carefully.
- Role assignment and user permissions are duplicated intentionally. If role permissions change later, `RoleController::permissionsUpdate()` re-syncs users for that role.
- `decrypt_password` should not be included in client-facing exports or screenshots.
- Employee delete is not implemented in the controller. Use inactive status for disabling users.

