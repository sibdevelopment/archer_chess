# Cover Up Classes Module

## Purpose

Cover Up Classes manages temporary replacement coaches when the original batch coach is unavailable.

It is used for:

- Same-day coach replacement
- Approved leave coverage
- Cover-up coach reassignment
- Cover-up live class display
- Preventing auto-cancel when replacement exists

## Sidebar Path

```text
Operations > Cover Up Classes
```

## Main Routes

```php
Route::resource('coverupclasses', CoverupclassController::class);
Route::post('coverupclasses/data', [CoverupclassController::class, 'data'])->name('coverupclasses.data');
Route::post('coverupclasses/list', [CoverupclassController::class, 'list'])->name('coverupclasses.list');
Route::post('coverupclasses/get/coach', [CoverupclassController::class, 'getCoach'])->name('coverupclasses.change_coach.get.coach');
Route::post('coverupclasses/change/coach', [CoverupclassController::class, 'changeCoach'])->name('coverupclasses.change_coach');
```

Related routes:

```php
Route::get('/batches/get-coaches', [BatchController::class, 'changeCoaches'])->name('get.coaches');
Route::post('batches/change/coach', [BatchController::class, 'changeCoach'])->name('batchs.change.coach');
```

## Main Files

```text
app/Http/Controllers/Admin/CoverupclassController.php
app/Http/Controllers/Admin/BatchController.php
app/Http/Controllers/Admin/LeaveRequestController.php
app/Http/Controllers/Admin/DashboardController.php
app/Console/Commands/CancelDelayBatch.php
app/Models/Coverupclass.php
resources/views/Admin/Coverupclass/*
```

## Database Tables

```text
coverupclasses
batchs
batch_schedules
coachs
coach_availabilities
coach_availability_periods
coach_attendances
student_attendances
leave_requests
```

## Important Fields

```text
batch_id
batchschedule_id
old_coach_id
new_coach_id
date
start_url
join_url
zoom_meeting_id
zoom_meeting_uuid
```

## How Cover-Up Is Created

### Flow 1: Same-Day Coach Change

Main methods:

```php
BatchController::changeCoaches()
BatchController::changeCoach()
```

Rules:

- Batch must have a schedule today.
- Coach change is allowed only before 10 minutes after batch start.
- Replacement coach must be active and available.
- Replacement coach must not be busy in another batch/cover-up.

Created record:

```text
coverupclasses.batch_id
coverupclasses.batchschedule_id
coverupclasses.old_coach_id
coverupclasses.new_coach_id
coverupclasses.date
```

If the replacement coach has Zoom credentials, cover-up Zoom URLs are saved.

### Flow 2: Leave Approval

Main method:

```php
LeaveRequestController::changeStatus()
```

When leave is approved with affected batch data:

- Selected replacement coach becomes `new_coach_id`.
- Original batch coach becomes `old_coach_id`.
- Cover-up date becomes leave date.
- Cover-up Zoom is created if credentials exist.

If no replacement coach is selected, cancellation is not immediately created here because `cancelBatchLogic()` is currently commented.

## Available Coach Logic

The system checks:

- Coach is active
- Coach has availability for date/time
- Coach is not on approved leave
- Coach has no overlapping batch
- Coach has no overlapping demo
- Coach has no overlapping cover-up
- Country compatibility where applicable

## Change Cover Coach

Main methods:

```php
CoverupclassController::getCoach()
CoverupclassController::changeCoach()
```

This lets admin replace `new_coach_id` on an existing cover-up record.

## Coach Dashboard / Live Class Behavior

Main method:

```php
DashboardController::getSchedule()
```

If cover-up exists for the replacement coach:

```text
status = COVER UP
type = COVERUP
coverup = Yes
start_url = coverupclasses.start_url
```

If no cover-up exists:

```text
type = BATCH
start_url = batchs.start_url
```

If original coach is on approved leave and no cover-up exists, the normal session can be skipped for that coach's schedule.

## Attendance / Live Class Status

Main method:

```php
DashboardController::batchAttendance()
```

Allowed class statuses:

```text
COMPLETED
CANCELLED
```

When cover coach completes class:

```text
coach_attendances.status = COMPLETED
student_attendances.status = PRESENT / ABSENT
type = COVERUP
```

When cover class is cancelled:

```text
coach_attendances.status = CANCELLED
student_attendances.status = CANCELLED
```

## Auto Cancel If No Class Is Taken

Main command:

```text
app/Console/Commands/CancelDelayBatch.php
```

Scheduled:

```php
$schedule->command('cancel:delay-batch')->everyTenMinutes();
```

Behavior:

- Finds active batches scheduled today.
- Waits until start time plus cutoff.
- Cutoff currently uses 8 minutes in code.
- If no attendance exists, marks class cancelled.
- If a cover-up record exists for that date/schedule, auto-cancel skips it.

Important risk:

```text
If cover-up exists but cover coach also does not mark attendance, auto-cancel currently skips that schedule.
```

## Batch Extension On Cancellation

When a class is cancelled:

1. System reads `batch_schedules`.
2. Finds the next scheduled weekday after current batch end date.
3. Updates `batchs.end_date`.
4. Updates active `student_batches.end_date`.
5. Updates latest `student_fees.end_date`.

Example:

```text
Schedule: Monday, Wednesday
Current end date: Wednesday
Cancelled class compensation: next Monday
```

## Client Workflow

1. Go to Operations > Batches or leave approval screen.
2. Select replacement coach if original coach is unavailable.
3. Cover-up record is created.
4. Replacement coach sees cover class in dashboard.
5. Replacement coach marks attendance.
6. If class is cancelled, batch/fee dates are extended.

## Developer Notes

The cover-up module is strongly connected to dashboard attendance and cron auto-cancel behavior.

Before changing it, review:

```text
BatchController::changeCoach()
LeaveRequestController::changeStatus()
DashboardController::getSchedule()
DashboardController::batchAttendance()
CancelDelayBatch::handle()
```

