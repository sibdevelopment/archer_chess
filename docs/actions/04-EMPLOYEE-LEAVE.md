# Employee Leave Requests

## Purpose

Employee Leave is separate from coach `Leave Requests`. It manages leave for internal employees and does not create class cover-ups or compensation.

## Access

- Coaches are explicitly denied by the controller.
- A normal employee sees and manages only their own requests.
- A user with `employeeleaverequests-update` can view, filter, approve, or reject all employee requests.
- Sidebar visibility is permission-gated and has a single menu entry.
- Role permissions are seeded under the Employee Leave Request permission group.

## Main Files

```text
app/Http/Controllers/Admin/EmployeeLeaveRequestController.php
app/Models/EmployeeLeaveRequest.php
resources/views/Admin/EmployeeLeaveRequests/index.blade.php
resources/views/Admin/EmployeeLeaveRequests/form.blade.php
database/migrations/2026_09_01_130000_create_employee_leave_requests_table.php
```

## Routes

```php
Route::resource('employeeleaverequests', EmployeeLeaveRequestController::class);
Route::post('employeeleaverequests/data', ...)->name('employeeleaverequests.data');
Route::post('employeeleaverequests/change-status', ...)->name('employeeleaverequests.change.status');
```

## Data

The `employee_leave_requests` table stores employee, date range, optional time range, leave type, reason, status, rejection reason, approval actor/time, and audit users.

Statuses:

```text
PENDING
APPROVED
REJECTED
```

Leave types:

```text
FULL DAY
HALF DAY
SHORT LEAVE
SICK LEAVE
EMERGENCY
OTHER
```

## Workflow

1. The logged-in employee creates a request; status is forced to `PENDING`.
2. Only pending requests can be edited.
3. Authorized reviewers approve or reject.
4. Rejection requires a reason.
5. The decision stores `approved_by` and `approved_at`.

Filters include employee, role, status, leave type, and date range.

## Validation

- `to_date` must be on or after `from_date`.
- Start/end time must be supplied together.
- On the same day, end time must be later than start time.
- Use 11:59 PM rather than 12:00 AM for a same-day end.

## Distinction From Coach Leave

`employee_leave_requests` has no batch, coach schedule, cover-up, Zoom, attendance, penalty, or fee-compensation side effects. Those belong to `leaverequests` and `BatchOccurrenceService`.
