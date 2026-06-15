# Leave Requests Module

## Purpose

Leave Requests allows coaches to request leave and allows admin users to approve, reject, or change the request status.

The most important business impact is this:

```text
When a coach leave is approved, the system checks which batch sessions are affected and lets admin assign replacement cover-up coaches.
```

## Sidebar Path

```text
Actions > Leave Requests
```

## Main Routes

```php
Route::resource('leaverequests', LeaveRequestController::class);
Route::post('leaverequests/data', [LeaveRequestController::class, 'data'])->name('leaverequests.data');
Route::post('leaverequests/list', [LeaveRequestController::class, 'list'])->name('leaverequests.list');
Route::post('leaverequests/change-status', [LeaveRequestController::class, 'changeStatus'])->name('leaverequests.change.status');
Route::post('leaverequests/get-affected-data', [LeaveRequestController::class, 'getAffectedData'])->name('leaverequests.get.affected.data');
```

## Main Files

```text
app/Http/Controllers/Admin/LeaveRequestController.php
app/Models/LeaveRequest.php
resources/views/Admin/LeaveRequests/index.blade.php
resources/views/Admin/LeaveRequests/form.blade.php
```

Connected files:

```text
app/Http/Controllers/Admin/ReportController.php
app/Http/Controllers/Admin/CoverupclassController.php
app/Http/Controllers/Admin/DashboardController.php
app/Console/Commands/CancelDelayBatch.php
app/Models/Coverupclass.php
app/Services/ZoomMeetingService.php
```

## Database Tables

```text
leaverequests
coachs
users
batchs
batch_schedules
student_batches
coach_availabilities
coach_availability_periods
demo_sessions
coverupclasses
coach_attendances
student_attendances
student_fees
```

## Leave Request Fields

Table:

```text
leaverequests
```

Important fields:

```text
coach_id
from_date
to_date
from_time
to_time
reason
status
created_by
updated_by
created_at
updated_at
```

Model:

```text
app/Models/LeaveRequest.php
```

Fillable fields:

```text
coach_id
from_time
to_time
from_date
to_date
reason
status
created_by
updated_by
```

The model automatically fills `created_by` and `updated_by` from the logged-in user when creating or updating a request.

## Status Values

The module uses these statuses:

```text
ACTIVE
INACTIVE
APPROVED
REJECTED
```

### ACTIVE

This is the default selectable status from the leave request form.

Meaning:

```text
Leave request exists and is active, but not yet approved.
```

### INACTIVE

This means the request is not active for processing.

It can be selected from the form or status modal.

### APPROVED

This means the leave is accepted.

Important effects:

- The leave appears in coach reports.
- The leave appears in calendar data.
- The leave blocks affected coach schedule slots.
- The system can create cover-up class records if replacement coaches are selected.
- Approved leave cannot be edited from the listing.

### REJECTED

This means admin declined the leave request.

No cover-up records are created.

## Listing Screen

View:

```text
resources/views/Admin/LeaveRequests/index.blade.php
```

The listing uses server-side DataTables.

Columns:

```text
#
Action
Status
Coach
Timeline
Reason
```

Filters:

```text
Coach
From Date
To Date
```

Export buttons:

```text
Copy
CSV
Excel
PDF
Print
```

## Role Visibility

Main method:

```php
LeaveRequestController::data()
```

Coach users:

- See only their own leave requests.

SuperAdmin:

- Can see all leave requests.

Other role-based users:

- Are filtered by countries assigned to their role.
- The system compares the role countries with coach countries.

## Create Leave Request

Main method:

```php
LeaveRequestController::create()
```

Only users connected to a coach profile can create a leave request from this module.

If the logged-in user has no coach profile, the controller redirects back with:

```text
You are not authorized to create a leave request.
```

Form:

```text
resources/views/Admin/LeaveRequests/form.blade.php
```

Fields:

```text
Coach
Status
From Date
From Time
To Time
Reason
```

Important UI note:

- The form contains JavaScript that references `to_date`, but the visible `to_date` input is not currently present in the form markup.
- The label for `to_time` currently displays as `From Time`.

## Store Leave Request

Main method:

```php
LeaveRequestController::store()
```

Validation:

```text
coach_id required
from_date required
from_time required
to_time required
reason optional
status optional
```

The request data is filled into the `LeaveRequest` model and saved.

Response:

```json
{
  "status": "success",
  "message": "LeaveRequest Created Successfully"
}
```

## Edit Leave Request

Main method:

```php
LeaveRequestController::edit()
```

The edit form loads the coach from the leave request.

Approved leave requests are disabled from edit in the listing.

## Update Leave Request

Main method:

```php
LeaveRequestController::update()
```

It uses the same validation and fill/save behavior as create.

Response message currently says:

```text
LeaveRequest Created Successfully
```

Even during update.

## Status Change Flow

Main method:

```php
LeaveRequestController::changeStatus()
```

Status changes happen from the listing status modal.

If status is not `APPROVED`, the status is changed directly.

If status is `APPROVED`, the frontend first calls:

```php
LeaveRequestController::getAffectedData()
```

Then it opens the confirmation modal showing affected batches and replacement coach dropdowns.

## Affected Data Flow

Main method:

```php
LeaveRequestController::getAffectedData()
```

This method calls:

```php
LeaveRequestController::handleApprovedLeaveRequest()
```

It returns affected batch data only when requested status is `APPROVED`.

Data returned for each affected batch:

```text
batch id
batch name
total missed session count
schedules
available replacement coaches per schedule
```

Schedule data contains:

```text
schedule id
weekday
from_time
to_time
missedSessions
coaches
```

## How Affected Batches Are Detected

Main method:

```php
LeaveRequestController::handleApprovedLeaveRequest()
```

The system checks:

- Leave coach
- Leave date
- Leave from time
- Leave to time
- Active batches for that coach
- Active batch schedules
- Active student batches
- Batch end date
- Matching schedule weekday
- Schedule time overlap with leave time

Then it separates affected batches into:

```text
batchesAfterLeave
batchesDuringLeave
```

Finally both lists are merged into one affected batch list.

## Available Cover Coach Logic

Main method:

```php
LeaveRequestController::getAvailableCoaches()
```

A coach is available for cover-up only if:

- Coach is active.
- Coach has active availability on that day.
- Coach availability period covers the full class time.
- Coach is not already on approved leave for that date.
- Coach has no overlapping active batch schedule.
- Coach has no overlapping active demo session.
- Coach has no overlapping cover-up class.

The logic uses:

```text
coach_availabilities
coach_availability_periods
batch_schedules
demo_sessions
coverupclasses
leaverequests
```

## Cover-Up Creation On Approval

Main method:

```php
LeaveRequestController::changeStatus()
```

When admin approves leave and selects a replacement coach for an affected schedule, the system creates:

```text
coverupclasses record
```

Fields saved:

```text
batch_id
batchschedule_id
old_coach_id
new_coach_id
date
```

Where:

- `old_coach_id` is the original batch coach.
- `new_coach_id` is the selected cover-up coach.
- `date` is the leave `from_date`.

## Cover-Up Zoom Creation

After creating the cover-up record, the system checks the selected coach's Zoom credentials:

```text
zoom_id
zoom_api_key
zoom_client_secret
zoom_user_id
```

If all are present, it calls:

```php
ZoomMeetingService::createCoverUpClassMeeting()
```

Then it stores:

```text
start_url
join_url
zoom_meeting_id
zoom_meeting_uuid
```

on the `coverupclasses` record.

## If No Replacement Coach Is Selected

The code path exists for no replacement coach, but the cancellation call is commented:

```php
// $this->cancelBatchLogic(...)
```

Current behavior:

```text
No cover-up class is created.
No immediate batch cancellation attendance is created by LeaveRequestController.
```

That means missing cover-up handling depends on other operational flows, such as dashboard attendance, cover-up module behavior, or the auto-cancel/delay command.

## Cancel Batch Logic In This Controller

There is a private method:

```php
LeaveRequestController::cancelBatchLogic()
```

It is designed to:

- Create cancelled coach attendance.
- Create cancelled student attendance.
- Mark student remarks as `Batch Cancelled`.
- Calculate the next scheduled day.
- Adjust batch/student/fee dates.

However, key end-date updates inside this method are commented, and the method is not called during leave approval in the current code.

## Date Extension Logic In This Controller

There are helper methods:

```php
adjustStudentBatchEndDate()
adjustBatchesEndDateAndStudentFees()
```

These contain logic to calculate missed sessions and extend student batch or fee dates by scheduled weekdays.

Current important detail:

```text
Several actual assignment lines are commented out.
```

So this controller mostly calculates affected data and creates cover-up assignments, but it does not reliably extend student fees/batch end dates from these helpers in the current code.

## Leave In Reports

Approved leave is used by:

```php
ReportController::getCounts()
ReportController::coachLeaveData()
ReportController::getSchedule()
ReportController::getCalendarData()
```

Effects:

- Leave count appears in report cards.
- Leave rows appear in leave report modal.
- Leave blocks appear in calendar.
- Leave is considered when building schedule data.

## Important Developer Notes

- `handleApprovedLeaveRequest()` currently sets leave end date from `from_date`, not `to_date`. This makes the affected-data calculation behave like a single-day leave flow.
- The form references a `to_date` JavaScript field, but the field is not visible in the current form.
- The `to_time` label says `From Time` in the form.
- No-cover-coach cancellation is currently disabled because `cancelBatchLogic()` is commented in `changeStatus()`.
- Leave approval creates cover-up records only for affected schedules where admin selects a replacement coach.
- Approved leaves cannot be edited from the listing.

