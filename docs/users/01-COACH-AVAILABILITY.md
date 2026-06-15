# Coach Availability Module

## Purpose

Coach Availability defines when a coach can be used for demos, batches, reports, leave coverage, and admin visibility. It has two related screens:

- **Users > Coach Availability**: read-only style weekly grid for checking a coach's available/class/N/A slots.
- **Users > Coaches > Availability button**: management screen where admin creates or edits a coach's weekly available days and time periods.

## Main Files

```text
routes/backend.php
app/Http/Controllers/Admin/DashboardController.php
app/Http/Controllers/Admin/CoachAvailabilityController.php
app/Models/Coach.php
app/Models/CoachAvailability.php
app/Models/CoachAvailabilityPeriod.php
resources/views/Admin/Dashboard/availability-index.blade.php
resources/views/Admin/Dashboard/availability.blade.php
resources/views/Admin/CoachAvailabilities/index.blade.php
resources/views/Admin/CoachAvailabilities/form.blade.php
resources/views/Admin/CoachAvailabilities/dayofweek.blade.php
resources/views/Admin/CoachAvailabilities/period.blade.php
```

## Routes

```php
Route::get('availability/index', [DashboardController::class, 'availabilityIndex'])->name('availability.index');
Route::get('availability', [DashboardController::class, 'availability'])->name('availability');

Route::prefix('coaches/{coach}')->name('coaches.')->group(function () {
    Route::resource('coach_availabilities', CoachAvailabilityController::class);
    Route::post('coach_availabilities/data', [CoachAvailabilityController::class, 'data'])->name('coach_availabilities.data');
    Route::post('coach_availabilities/change-status', [CoachAvailabilityController::class, 'changeStatus'])->name('coach_availabilities.change.status');
    Route::get('edit_availabilities', [CoachAvailabilityController::class, 'editAll'])->name('coach_availabilities.editAll');
    Route::put('update_availabilities', [CoachAvailabilityController::class, 'updateAll'])->name('coach_availabilities.updateAll');
});

Route::post('coach_availabilities/list', [CoachAvailabilityController::class, 'list'])->name('coach_availabilities.list');
Route::post('coach_availabilities/add-day', [CoachAvailabilityController::class, 'addDay'])->name('coach_availabilities.add_day');
Route::post('coach_availabilities/edit-day', [CoachAvailabilityController::class, 'editDay'])->name('coach_availabilities.edit_day');
Route::post('coach_availabilities/delete-day', [CoachAvailabilityController::class, 'deleteDay'])->name('coach_availabilities.delete_day');
Route::post('coach_availabilities/add-day-period', [CoachAvailabilityController::class, 'addPeriod'])->name('coach_availabilities.add_day_period');
Route::post('coach_availabilities/edit-period', [CoachAvailabilityController::class, 'editPeriod'])->name('coach_availabilities.edit_period');
Route::post('coach_availabilities/delete-period', [CoachAvailabilityController::class, 'deletePeriod'])->name('coach_availabilities.delete_period');
```

## Permission

The sidebar item uses:

```text
availability-view
```

The editable availability management area uses the coach availability permission group:

```text
coachavailability-view
coachavailability-store
coachavailability-update
```

## Database Tables

### `coach_availabilities`

Stores one row per coach and weekday.

```text
id
coach_id
day_of_week
status
created_by
updated_by
created_at
updated_at
```

### `coach_availability_periods`

Stores one or more available time ranges under a weekday.

```text
id
availability_id
from_period
to_period
status
created_by
updated_by
created_at
updated_at
```

## Data Relationship

```text
Coach
  has many CoachAvailability

CoachAvailability
  belongs to Coach
  has many CoachAvailabilityPeriod

CoachAvailabilityPeriod
  belongs to CoachAvailability
```

In code:

```php
$coach->coach_availabilities;
$availability->periods;
$period->coachAvailability;
```

## Admin Grid Screen

Screen:

```text
Users > Coach Availability
```

This screen lets admin select:

- Coach
- Date

After selection, AJAX calls:

```text
admin.availability
```

The returned table shows the selected week around that date. The grid uses fixed time slots and overlays the coach's real usage.

Color meaning:

```text
Green  = Available
Red    = Class or booked slot
Yellow = N/A / not available
```

Important point: the grid is not just the saved availability table. It combines saved availability with existing class/demo/booking records so admin can understand the real working calendar.

## Availability Management Screen

Screen path:

```text
Users > Coaches > Availability button
```

The coach list has an **Availability** action. It opens:

```text
/admin/coaches/{coach_route_key}/coach_availabilities
```

Admin can:

- Add a weekday.
- Add multiple time periods under that weekday.
- Edit existing weekday rows.
- Edit existing time periods.
- Remove a weekday.
- Remove a period.
- Activate or deactivate a weekday availability row.

## Create Flow

1. Admin opens the coach availability form.
2. Admin clicks **Add Day**.
3. JavaScript calls `/admin/coach_availabilities/add-day`.
4. The backend returns the `dayofweek.blade.php` partial.
5. Admin selects day of week.
6. Admin clicks **Add Period**.
7. JavaScript calls `/admin/coach_availabilities/add-day-period`.
8. The backend returns the `period.blade.php` partial.
9. Admin enters `from_period` and `to_period`.
10. On save, `store()` creates `coach_availabilities` and `coach_availability_periods`.

## Edit Flow

1. The edit form loads the coach.
2. Existing days are loaded by AJAX through `editDay()`.
3. Existing periods are loaded by AJAX through `editPeriod()`.
4. Removed day IDs are stored in hidden input `deleted_days`.
5. Removed period IDs are stored in hidden input `deleted_periods`.
6. On save, `updateAll()` updates or creates day rows and period rows.
7. It deletes valid removed day/period IDs from the database.

## Status Behavior

Each weekday availability has a status:

```text
ACTIVE
INACTIVE
```

The toggle calls:

```text
admin.coaches.coach_availabilities.change.status
```

Inactive availability should not be treated as usable availability by scheduling/list APIs that filter active records.

## List API Behavior

`CoachAvailabilityController::list()` returns:

- Active availability for one coach if `coach_id` is sent.
- Active availability for multiple coaches if `client_ids` is sent.
- All coach availability records if no filter is sent.

This is useful for dynamic forms that need to populate coach/day options without loading the full management UI.

## Business Usage

Coach availability affects:

- Demo lead scheduling.
- Batch creation and batch schedule planning.
- Coach availability report.
- Cover-up class assignment.
- Leave request handling.
- Coach workload visibility.

When a coach is unavailable for a day or period, the system should not treat that coach as a clean option for new assignments.

## Developer Notes

- The weekday values must stay consistent across the system. Some UI values are uppercase in filters, while availability rows are commonly stored as weekday names. Be careful when comparing values.
- Deleting a weekday can leave business history in other modules. Existing classes/attendance are not automatically removed just because an availability row is deleted.
- Time period overlap logic in reports and cover-up flows depends on exact `from_period` and `to_period` values.
- Availability means "coach can work"; it does not mean "coach is free". The grid must also consider classes, demos, leave, and cover-up assignments.

