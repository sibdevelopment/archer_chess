# Component / Record View Module

## Purpose

The `Component` sidebar section is a private utility/reporting area used for data checking, finance exports, duplicate attendance cleanup, cancelled batch review, and inactive student auditing.

It is not a normal CRUD module like Master or Operations. Most items either:

- Open a new browser tab with a diagnostic table.
- Open a modal, collect filters, then download/open a report.
- Export CSV data.
- Help delete duplicate attendance rows.
- Help update a student fee end date in a special cancelled-attendance audit.

## Sidebar Path

```text
Component
  Multiple Std Attendance
  Same Day Attendance
  Coach Attendance
  Fees Record
  Fees Collection
  Cancel Batch List
  Student Inactive
  Student Cancelled Batch
```

## Access Rule

The sidebar section appears only when:

```php
auth()->user()->component_tab === 'YES'
```

or when the logged-in user ID is one of:

```text
13, 14, 15, 16, 17, 1
```

Main file:

```text
resources/views/layouts/admin/navbar.blade.php
```

This means access is controlled by a user flag and hardcoded allowed IDs, not by standard Spatie permissions.

## Main Routes

Routes are defined in:

```text
routes/backend.php
```

```php
Route::get('/fees_records/{year}/{month}/{country}', [DataController::class, 'feesList'])->name('fees.list');
Route::get('/cancel_batches/{fromdate}/{todate}', [DataController::class, 'cancelBatchList'])->name('cancel.batch.list');
Route::get('/fees_collection/{year}/{month}/{date}/{country?}', [DataController::class, 'feesCollection'])->name('fees.collection');

Route::get('/multiple_student_attendance', [DataController::class, 'multiple_student_attendance'])->name('multiple_student_attendance');
Route::get('/same_day_multiple_student_attendance', [DataController::class, 'same_day_multiple_student_attendance'])->name('same_day_multiple_student_attendance');
Route::get('/coach-attendance', [DataController::class, 'coachAttendance'])->name('coach.attendance');
Route::get('/student_inactive/{month}', [DataController::class, 'student_inactive'])->name('student_inactive');
Route::get('/student/cancelled/attendance', [DataController::class, 'student_cancelled_attendance'])->name('student_cancelled_attendance');
Route::post('/student-attendance/update-date', [DataController::class, 'updateFeeEndDate'])->name('students.updateFeeEndDate');
```

Related delete route:

```php
Route::post('students/delete/attendance', [StudentController::class, 'deleteStudentAtendance'])->name('students.delete.attendance');
```

## Main Files

```text
app/Http/Controllers/DataController.php
app/Http/Controllers/Admin/StudentController.php
resources/views/layouts/admin/navbar.blade.php
resources/views/layouts/admin.blade.php
resources/views/multiple_student_attendance.blade.php
resources/views/fees_list.blade.php
resources/views/Frontend/coach_attendance.blade.php
resources/views/Frontend/student_cancelled_attendance.blade.php
```

## Main Tables Used

```text
student_attendances
coach_attendances
student_fees
students
student_batches
batchs
coachs
users
leaverequests
```

## Shared CSV Helper

Main method:

```php
DataController::csvResponse()
```

Used by:

```text
Fees Collection
Cancel Batch List
Student Inactive
```

It creates a CSV in memory using:

```php
php://temp
```

and returns:

```text
Content-Type: text/csv
Content-Disposition: attachment; filename="{filename}"
```

## Multiple Std Attendance

Sidebar item:

```text
Component > Multiple Std Attendance
```

Route:

```php
route('multiple_student_attendance')
```

Controller:

```php
DataController::multiple_student_attendance()
```

View:

```text
resources/views/multiple_student_attendance.blade.php
```

Purpose:

```text
Find duplicate student attendance rows for the same student, batch, date, and coach.
```

Query:

```php
StudentAttendance::select(
    'student_id',
    'batch_id',
    'date',
    'coach_id',
    DB::raw('COUNT(*) as count')
)
->groupBy('student_id', 'batch_id', 'date', 'coach_id')
->where('type', 'Batch')
->having('count', '>', 1)
```

Grouped duplicate key:

```text
student_id + batch_id + date + coach_id
```

Shown columns:

```text
Student Name
Batch Name
Date
Coach Name
Record Count
```

For each duplicate group, the view fetches all matching attendance records and displays each record time with a delete button.

### Delete Duplicate Attendance

Delete action posts to:

```text
/admin/students/delete/attendance
```

Controller:

```php
StudentController::deleteStudentAtendance()
```

Validation:

```text
id required
id must exist in student_attendances
```

Behavior:

```text
Deletes the selected StudentAttendance row.
Reloads the page after successful deletion.
```

Important:

```text
This is a real delete. It removes the attendance record from the database.
```

## Same Day Attendance

Sidebar item:

```text
Component > Same Day Attendance
```

Route:

```php
route('same_day_multiple_student_attendance')
```

Controller:

```php
DataController::same_day_multiple_student_attendance()
```

View:

```text
resources/views/multiple_student_attendance.blade.php
```

Purpose:

```text
Detect duplicate batch attendance rows for the same student, batch, date, and coach.
```

Current behavior:

```text
Same query as Multiple Std Attendance.
```

There is a commented year filter:

```php
// ->whereYear('date', 2025)
```

If needed, this can be enabled to audit only one year.

## Coach Attendance

Sidebar item:

```text
Component > Coach Attendance
```

Route:

```php
route('coach.attendance')
```

Controller:

```php
DataController::coachAttendance()
```

View:

```text
resources/views/Frontend/coach_attendance.blade.php
```

Purpose:

```text
Find coach attendance records from the last month and visually flag duplicate completed batch attendances.
```

Base query:

```php
CoachAttendance::with(['coach.user'])
    ->where('created_at', '>=', Carbon::now()->subMonth())
    ->where('status', 'COMPLETED')
    ->whereNotNull('batch_id')
    ->orderByDesc('created_at')
```

Duplicate grouping key:

```text
coach first name
coach last name
batch_id
date
```

If a group has more than one record, each row in that group receives:

```php
$attendance->is_duplicate = true
```

View behavior:

```text
Duplicate rows are highlighted with a light red background.
```

Shown columns:

```text
Coach Name
Batch Name
Date
Time
Status
```

## Fees Record

Sidebar item:

```text
Component > Fees Record
```

This item opens a modal, not a direct page.

Modal file:

```text
resources/views/layouts/admin.blade.php
```

Modal ID:

```text
feesRecordModal
```

User selects:

```text
Year
Month
Country
```

Country dropdown includes:

```text
ALL
USA
CANADA
AUSTRALIA
NEWZEALAND
INDIA
UAE
UK
SINGAPORE
SOUTH AFRICA
QATAR
BAHRAIN
KUWAIT
EUROPEAN UNION
OMAN
```

JS opens:

```text
/fees_records/{year}/{month}/{country}
```

Controller:

```php
DataController::feesList()
```

View:

```text
resources/views/fees_list.blade.php
```

### Fees Record Logic

Inputs:

```text
year
month
country
```

If country is not `ALL`, it filters students by:

```text
students.country = selected country
```

The view contains four switchable tables:

```text
Fees Due & Inactive
Current Month Fees Entered
Current Month Fees Due
Fees Enter Creation Date
```

### Current Month Fees Due

Query:

```text
student_fees.end_date month/year equals selected month/year
```

Filtered by student country.

### Current Month Fees Entered

Query:

```text
student_fees.start_date month/year equals selected month/year
```

Filtered by student country.

### Fees Due And Inactive

Query:

```text
student_fees.end_date month/year equals selected month/year
student.status = INACTIVE
```

Filtered by student country.

### Fees Enter Creation Date

Query:

```text
student_fees.created_at month/year equals selected month/year
```

Filtered by student country.

Shown fee columns:

```text
Student Name
Currency
Start Date
End Date
Receive Date
Monthly Amount
Total Amount
```

Each table also calculates:

```text
Grand total monthly fees
Grand total paid amount
```

## Fees Collection

Sidebar item:

```text
Component > Fees Collection
```

This item opens a modal.

Modal ID:

```text
feesCollectionModal
```

User selects:

```text
Year
Month
Date
Country optional
```

JS opens:

```text
/fees_collection/{year}/{month}/{date}/{country?}
```

Controller:

```php
DataController::feesCollection()
```

Purpose:

```text
Download CSV of students whose fee period is active on a specific date.
```

Input date:

```text
{year}-{month}-{day}
```

Query:

```text
student_fees.start_date <= input date
student_fees.end_date >= input date
```

Optional country filter:

```text
students.country = selected country
```

CSV columns:

```text
Student Name
Start Date
End Date
Currency
Monthly Fees
Total Paid
Status
```

CSV filename:

```text
fees_collection_{year}-{month}-{day}.csv
fees_collection_{year}-{month}-{day}_{country}.csv
```

If no data exists:

```json
{
  "message": "No data found for the selected criteria."
}
```

with HTTP 404.

## Cancel Batch List

Sidebar item:

```text
Component > Cancel Batch List
```

This item opens a modal.

Modal ID:

```text
cancelBatchListModal
```

User selects:

```text
From Date
To Date
```

JS opens:

```text
/cancel_batches/{fromdate}/{todate}
```

Controller:

```php
DataController::cancelBatchList()
```

Purpose:

```text
Download CSV of cancelled batch coach attendance records in a date range, excluding sessions where coach was on approved leave.
```

Base query:

```text
coach_attendances.status = CANCELLED
coach_attendances.batch_id is not null
coach_attendances.date between from/to date
```

For each cancelled attendance, the system checks:

```text
Does this coach have APPROVED leave covering the attendance date?
```

Leave check:

```text
leaverequests.coach_id = attendance.coach_id
leaverequests.status = APPROVED
leaverequests.from_date <= attendance date
leaverequests.to_date >= attendance date
```

If approved leave exists, that cancelled class is skipped.

CSV columns:

```text
Date
Batch Name
Coach Name
```

CSV filename:

```text
Cancelled_Batches_{fromdate}_to_{todate}.csv
```

Possible 404 messages:

```text
No cancelled sessions found in the selected range.
No cancelled sessions found (excluding those with leave).
```

## Student Inactive

Sidebar item:

```text
Component > Student Inactive
```

This item opens a modal.

Modal ID:

```text
studentInactiveListModal
```

User selects:

```text
Month
```

JS opens:

```text
/student_inactive/{month}
```

Controller:

```php
DataController::student_inactive()
```

Purpose:

```text
Download CSV of students who became INACTIVE in the selected month of the current year.
```

Query:

```text
students.status = INACTIVE
students.updated_at year = current year
students.updated_at month = selected month
```

For each student, the report includes:

- Student name and student ID
- Latest batch coach
- Whether latest batch is active or previous
- Updated by user
- Updated date
- Student status

CSV columns:

```text
Student Name (ID)
Coach Name & Status
Updated By
Updated At
Status
```

CSV filename:

```text
Inactive_Students_{MonthName}_{CurrentYear}.csv
```

## Student Cancelled Batch

Sidebar item:

```text
Component > Student Cancelled Batch
```

Route:

```php
route('student_cancelled_attendance')
```

Controller:

```php
DataController::student_cancelled_attendance()
```

View:

```text
resources/views/Frontend/student_cancelled_attendance.blade.php
```

Purpose:

```text
Find students with repeated cancelled attendance records on a fixed list of special dates, then allow fee end date adjustment.
```

Base query:

```text
student_attendances.status = CANCELLED
student_attendances.type != Demo
student_attendances.date in fixed date list
```

Fixed date list in current code:

```text
2025-08-09
2025-08-15
2025-08-27
2025-10-02
2025-10-20
2025-10-21
2025-10-22
```

Then the system groups by:

```text
student_id
```

and keeps only students with:

```text
2 or more CANCELLED attendances
```

Shown columns:

```text
Student
Batch
Attendance Date
Fees Start Date
Fees End Date
Cancelled Count
View
```

The view button opens:

```text
/admin/students/{student_id}/student_fees
```

### Fee End Date Update

The fee end date cell contains a hidden date input.

When changed, it sends:

```php
POST route('students.updateFeeEndDate')
```

Controller:

```php
DataController::updateFeeEndDate()
```

Validation:

```text
student_id required and exists in students
end_date required and date
```

Behavior:

```text
Find latest StudentFee for the student ordered by end_date desc.
Update only end_date.
Return formatted date.
```

Important:

```text
This directly changes the latest student fee end date.
```

## Data Models Used

### StudentAttendance

Important fields:

```text
student_id
type
coach_id
demolead_id
level_id
batch_id
number_of_batch_sessions
status
remark
date
time
homework_link
recording_link
chapter_name
created_by
updated_by
```

Relationships:

```text
student
coach
demoLead
batch
level
```

### CoachAttendance

Important fields:

```text
coach_id
type
demolead_id
batch_id
date
time
status
number_of_batch_sessions
number_of_demo_sessions
chapter_name
created_by
updated_by
```

Relationships:

```text
coach
batch
```

### StudentFee

Important fields:

```text
student_id
start_date
end_date
receive_date
currency
monthly_fees
total_amount_paid
status
created_by
updated_by
```

Relationship:

```text
student
```

## Operational Risks

These screens are powerful and should be treated carefully.

### Real Deletion

`Multiple Std Attendance` lets the user delete attendance rows from `student_attendances`.

There is no soft-delete flow shown in the method.

### Real Fee Date Update

`Student Cancelled Batch` can update the latest fee end date for a student.

This directly changes billing/fee history.

### Hardcoded Date Audit

`Student Cancelled Batch` only checks the hardcoded cancelled dates listed in `DataController`.

If a new cancelled-date audit is needed, the code date list must be updated.

### Hardcoded Access IDs

Component tab access includes hardcoded user IDs:

```text
13, 14, 15, 16, 17, 1
```

This should be reviewed before production handover.

### Frontend Views Are Outside Admin Folder

Some component pages render from:

```text
resources/views/Frontend/*
resources/views/*.blade.php
```

Even though they are opened from the admin sidebar.

### CSV 404 Behavior

Some export routes return JSON 404 when no data is found instead of showing a styled page.

## Developer Notes

- `Multiple Std Attendance` and `Same Day Attendance` currently use effectively the same duplicate query.
- `feesList()` opens an HTML page with four tables, while `feesCollection()`, `cancelBatchList()`, and `student_inactive()` export CSV.
- `cancelBatchList()` excludes cancelled classes that overlap approved coach leave.
- `student_inactive()` uses current year only; it does not accept year as a parameter.
- `student_cancelled_attendance()` filters by fixed dates only.
- `updateFeeEndDate()` updates the latest fee record by latest `end_date`, not by the attendance date shown in the row.

