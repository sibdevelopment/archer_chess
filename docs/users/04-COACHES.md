# Coaches Module

## Purpose

Coaches are teaching users. A coach has a login account in `users` and a coach profile in `coachs`. Coaches are used across demo scheduling, batch formation, live classes, attendance, reports, leave requests, and cover-up classes.

This module is one of the most connected parts of the application.

## Main Files

```text
routes/backend.php
app/Http/Controllers/Admin/CoachController.php
app/Models/Coach.php
app/Models/User.php
app/Models/Role.php
app/Models/CoachAvailability.php
app/Models/CoachAvailabilityPeriod.php
resources/views/Admin/Coachs/index.blade.php
resources/views/Admin/Coachs/form.blade.php
resources/views/Admin/Coachs/show.blade.php
resources/views/layouts/admin/navbar.blade.php
```

Related modules:

```text
app/Models/Batch.php
app/Models/BatchSchedule.php
app/Models/DemoSession.php
app/Models/CoachAttendance.php
app/Models/LeaveRequest.php
app/Models/Coverupclass.php
```

## Routes

```php
Route::resource('coaches', CoachController::class);
Route::post('coaches/data', [CoachController::class, 'data'])->name('coaches.data');
Route::post('coaches/list', [CoachController::class, 'list'])->name('coaches.list');
Route::post('coaches/change-status', [CoachController::class, 'changeStatus'])->name('coaches.change.status');
```

Coach availability is managed through nested routes:

```php
Route::prefix('coaches/{coach}')->name('coaches.')->group(function () {
    Route::resource('coach_availabilities', CoachAvailabilityController::class);
});
```

Coach impersonation/login link in the listing uses:

```text
coach.login
```

## Permission

Sidebar uses:

```text
coachs-view
```

Common permission names:

```text
coachs-view
coachs-store
coachs-update
```

Note the spelling is `coachs` in several route/permission places because the database table is also named `coachs`.

## Database Tables

### `users`

Stores login and shared identity:

```text
first_name
last_name
email
mobile
password
status
```

### `coachs`

Stores coach-specific profile:

```text
user_id
status
country
pan_number
zoom_id
zoom_user_id
zoom_api_key
zoom_client_secret
zoom_password
zoom_link
portal_id
portal_password
decrypt_password
created_by
updated_by
created_at
updated_at
```

`country` is cast as an array in `App\Models\Coach`.

## Coach Listing

Screen:

```text
Users > Coaches
```

Filters:

```text
Country
Day
Status
```

DataTable columns:

```text
#
Action
Status
Full Name
Mobile
Email
Country
Availability
```

Export buttons:

```text
Copy
CSV
Excel
PDF
Print
```

## Country Filtering

The coach listing has two layers of country filtering.

### Logged-in user country scope

If the logged-in admin is not SuperAdmin, the system reads countries from the user's roles and limits coaches to those countries.

### Page filter

The selected Country filter further narrows the result.

The code handles both JSON country values and older plain-string country values.

## Day Filtering

The Day filter checks:

```php
whereHas('coach_availabilities', function ($query) {
    $query->where('day_of_week', $request->day_of_week);
});
```

This means the filter depends on the coach's saved availability rows. If a coach has no availability row for the day, they will not appear for that day filter even if they are active.

## Action Buttons

Each coach row can show:

- Edit.
- View details.
- Login as Coach.
- Availability.

Delete logic exists in the controller, but the delete button is commented out for SuperAdmin in the listing.

## Create/Edit Form

The form has three sections.

### Personal Info

```text
First Name
Last Name
Mobile
PAN
Country
```

Country is multi-select.

### Zoom and Portal Details

```text
Zoom Account Id
Zoom User Id
Zoom Api Key
Zoom Client Secret Key
Portal Id
Portal Password
```

Some older fields are present in code but commented in the form:

```text
Zoom Password
Zoom Link
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

When admin creates a coach:

1. Request is validated.
2. A `User` row is created.
3. Password is bcrypt-hashed in `users.password`.
4. The user is assigned the `Coach` role.
5. Permissions of the `Coach` role are copied to the user.
6. A `Coach` row is created with profile/Zoom/portal/country fields.
7. Plain password is stored in `coachs.decrypt_password`.

Security note: Zoom keys, portal credentials, and plain password storage are sensitive. These should be protected before pushing code/data or giving repo access.

## Update Flow

When admin edits a coach:

1. Email and mobile uniqueness ignore the current user.
2. Password is optional.
3. User fields are updated.
4. Password hash is updated only if a password was entered.
5. Coach profile fields are updated.
6. `decrypt_password` is updated only if password was entered.

## Status Toggle

The coach status switch calls:

```text
admin.coaches.change.status
```

It updates:

```text
coachs.status
```

Important detail: the form displays status using `coach->user->status`, but the status toggle updates `coachs.status`. Developers should be careful because coach account status and coach profile status can drift if not handled consistently.

## Coach Detail Page

The show page loads:

- Coach availability with periods.
- Completed batch attendance data.
- Batches connected to completed coach attendance.
- Active student count per batch.
- Level names.
- Batch timeline.
- Completed dates and times.
- Batch status badge.

This page is useful for handover because it gives a compact view of coach work history and schedule context.

## Business Usage

Coach records are used by:

- Demo lead assignment.
- Demo sessions.
- Batch creation.
- Batch schedules.
- Student batch allocation.
- Coach dashboard.
- Live class attendance.
- Cover-up class assignment.
- Leave requests.
- Coach reports.
- Availability grid.

If a coach is inactive or missing availability, scheduling and reports can become confusing.

## Developer Notes

- The database table is named `coachs`, not `coaches`.
- The model is `Coach`, but view folder is `Admin/Coachs`.
- Keep `country` as a valid array/JSON value for filtering.
- Be careful with `zoom_api_key`, `zoom_client_secret`, `portal_password`, and `decrypt_password`; they are credentials.
- Do not delete a coach casually. Batches, attendance, demos, leave, and reports may reference that coach.

