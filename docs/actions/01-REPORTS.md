# Reports Module

## Purpose

Reports is the action screen for reviewing coach activity and marking class attendance from one place.

It connects many operational records:

- Coach schedule
- Coach availability
- Demo classes
- Batch classes
- Cover-up classes
- Coach leaves
- Masterclass attendance
- Student attendance
- Student country-wise attendance count
- Delayed batches
- Downloadable report PDF

## Sidebar Path

```text
Actions > Reports
```

## Main Routes

```php
Route::resource('reports', ReportController::class);
Route::post('reports/data', [ReportController::class, 'data'])->name('reports.data');
Route::post('reports/list', [ReportController::class, 'list'])->name('reports.list');
Route::post('reports/change-status', [ReportController::class, 'changeStatus'])->name('reports.change.status');
Route::post('reports/{coachId}/download', [ReportController::class, 'downloadReport'])->name('reports.download');
Route::get('reports/{coachId}/get-counts', [ReportController::class, 'getCounts'])->name('reports.get.counts');
Route::get('reports/{coachId}/schedule', [ReportController::class, 'getSchedule'])->name('reports.getSchedule');
Route::get('reports/{coachId}/calendar', [ReportController::class, 'getCalendarData'])->name('reports.calendar');
Route::get('reports/{coachId}/get/availability', [ReportController::class, 'getAvailability'])->name('reports.getAvailability');
Route::get('reports/schedule/batch/demo/data', [ReportController::class, 'reportsScheduleData'])->name('reports.scheduleData');
Route::get('reports/{coachId}/attendance', [ReportController::class, 'getAttendanceData'])->name('reports.getAttendanceData');
Route::post('reports/{coachId}/demo-attendance', [ReportController::class, 'demoAttendance'])->name('reports.demoAttendance');
Route::post('reports/{coachId}/batch-attendance', [ReportController::class, 'batchAttendance'])->name('reports.batchAttendance');
Route::get('reports/batchstudent/countrydata', [ReportController::class, 'batchStudentCountryData'])->name('batchstudent.countrydata');
Route::get('reports/batch/completed/data', [ReportController::class, 'batchCompletedData'])->name('batch.completed.data');
Route::get('reports/delayed-batches/data', [ReportController::class, 'delayedBatchesReportData'])->name('reports.delayed.batches.data');
Route::get('reports/coverupclass/completed/data', [ReportController::class, 'coverupclassCompletedData'])->name('coverupclass.completed.data');
Route::get('reports/demo/completed/data', [ReportController::class, 'demoCompletedData'])->name('demo.completed.data');
Route::get('reports/coach/leave/data', [ReportController::class, 'coachLeaveData'])->name('coach.leave.data');
Route::get('reports/coach/masterclass/attendance/data', [ReportController::class, 'coachMasterClassAttendanceData'])->name('coach.masterclass.attendance.data');
```

## Main Files

```text
app/Http/Controllers/Admin/ReportController.php
resources/views/Admin/CoachReports/index.blade.php
resources/views/Admin/CoachReports/getcount.blade.php
resources/views/Admin/CoachReports/schedule.blade.php
resources/views/Admin/CoachReports/calendar.blade.php
resources/views/Admin/CoachReports/coachavailability.blade.php
resources/views/Admin/CoachReports/batchshow.blade.php
resources/views/Admin/CoachReports/demoshow.blade.php
resources/views/Admin/CoachReports/demoattendance.blade.php
resources/views/Admin/CoachReports/reportpdf.blade.php
resources/views/Admin/CoachReports/BatchAttendances/activebatchattendance.blade.php
resources/views/Admin/CoachReports/BatchAttendances/inactivebatchattendance.blade.php
resources/views/Admin/CoachReports/BatchAttendances/standbybatchattendance.blade.php
```

## Main Tables Used

```text
coachs
users
batchs
batch_schedules
student_batches
students
demo_leads
demo_sessions
coverupclasses
leaverequests
coach_availabilities
coach_availability_periods
coach_attendances
student_attendances
student_fees
masterclasses
delayed_batches
levels
```

## Page Layout

The report page has these main parts:

- Coach dropdown
- Date range picker
- Count cards
- Full calendar view
- Daily schedule section
- Hidden coach availability section
- Detail modals
- Attendance modal
- Report-specific data modals

The screen is rendered by:

```php
ReportController::index()
```

View:

```text
resources/views/Admin/CoachReports/index.blade.php
```

## Coach Selection Logic

The page loads active coaches.

SuperAdmin can see all active coaches.

Coach users see only their own coach profile.

Other role-based users are filtered by countries stored in their role. The system reads role countries, merges them, and keeps coaches whose country list intersects with those allowed countries.

## Date Range Logic

Default range:

```text
start = first day of current month
end = today
```

The date range is used by count cards and modal report endpoints.

## Leave Date Normalization

When the report index loads, old leave requests with empty `to_date` are normalized:

```text
if leaverequests.to_date is null
set to_date = from_date
```

This keeps calendar and leave reports from breaking when old single-day leave records do not have `to_date`.

## Count Cards

Main method:

```php
ReportController::getCounts()
```

The count card area is loaded through AJAX into:

```text
#getcounts
```

Counts shown:

```text
completedDemosCount
approvedLeavesCount
completedBatchesCount
totalStudentsBatchesCount
masterclassCount
coverupclassCount
```

### Completed Demos Count

Source:

```text
coach_attendances
```

Conditions:

```text
coach_id = selected coach
type = Demo
status = COMPLETED
date between selected start and end
```

### Approved Leaves Count

Source:

```text
leaverequests
```

Conditions:

```text
coach_id = selected coach
status = APPROVED
from_date between selected start and end
```

### Completed Batches Count

Source:

```text
coach_attendances
```

Conditions:

```text
coach_id = selected coach
type = Batch
status = COMPLETED
date between selected start and end
```

### Total Students In Batches Count

Source:

```text
student_attendances
```

Conditions:

```text
coach_id = selected coach
type = Batch
date between selected start and end
status is not NOTMARKED
status is not CANCELLED
```

This count is attendance-based, not unique-student-based. If the same student attends multiple classes in the selected range, each attendance record can count.

### Masterclass Count

Source:

```text
coach_attendances
```

Conditions:

```text
coach_id = selected coach
type = Masterclass
masterclass_id is not null
date between selected start and end
```

### Cover-Up Class Count

Source:

```text
coach_attendances
```

Conditions:

```text
coach_id = selected coach
type = COVERUP
date between selected start and end
```

## Student Batch Country Report

Main method:

```php
ReportController::batchStudentCountryData()
```

This report opens from the count card area.

It returns:

- Total batch attendance count
- Attendance count grouped by student country
- Students missing country
- Detailed student attendance rows

Important behavior:

- It reads from `student_attendances`.
- It ignores `NOTMARKED` and `CANCELLED`.
- It groups by country from `students.country`.
- If country is missing, it shows `Unknown`.

## Completed Demo Report

Main method:

```php
ReportController::demoCompletedData()
```

This report lists completed demo attendance in the selected date range.

Typical data shown:

- Student name
- Age
- Mobile
- Country
- Time zone
- Demo status
- Level
- Attendance date
- Attendance time

It is based on `coach_attendances` and connected demo lead/session data.

## Completed Batch Report

Main method:

```php
ReportController::batchCompletedData()
```

This report lists batch sessions completed by the coach.

Typical data shown:

- Batch name
- Version
- Country
- Status
- Level names
- Batch timeline
- Completed session count
- Completed dates
- Completed times

It is based on `coach_attendances` where the batch attendance is completed.

## Delayed Batches Report

Main method:

```php
ReportController::delayedBatchesReportData()
```

This report uses the `delayed_batches` table.

It is used to review batches that were delayed by the system. This connects with the auto-cancel and delay logic described in the Operations cover-up and batches documentation.

## Completed Cover-Up Report

Main method:

```php
ReportController::coverupclassCompletedData()
```

This report shows cover-up classes completed by the selected coach.

Source:

```text
coach_attendances.type = COVERUP
```

It helps identify when a coach handled another coach's class.

## Coach Leave Report

Main method:

```php
ReportController::coachLeaveData()
```

This returns approved leave rows for the selected coach and date range.

Data shown:

- Coach name
- From date
- To date
- From time
- To time
- Reason
- Status

Only approved leaves are counted and listed.

## Masterclass Attendance Report

Main method:

```php
ReportController::coachMasterClassAttendanceData()
```

This returns completed masterclass attendance for the coach.

Data shown:

- Coach name
- Masterclass name
- Date
- Time
- Status

## Coach Availability Panel

Main method:

```php
ReportController::getAvailability()
```

This calculates free slots for the selected coach on the selected date.

It reads:

- Active coach availability
- Availability periods
- Active batch schedules for that day
- Active demo sessions for that date

The method builds occupied slots from batch and demo records, then subtracts those occupied slots from the coach availability periods.

Output:

```text
available hourly slots
```

The available slots are rendered by:

```text
resources/views/Admin/CoachReports/coachavailability.blade.php
```

## Daily Schedule

Main method:

```php
ReportController::getSchedule()
```

This builds the selected coach's schedule for a selected day.

It combines:

- Active batches
- Standby batches
- Inactive batches when attendance is still relevant
- Demo sessions
- Cover-up classes
- Approved leave blocks
- Existing coach attendance status

Schedule rows can be opened for details or attendance depending on permissions.

## Calendar Data

Main method:

```php
ReportController::getCalendarData()
```

The calendar combines records into FullCalendar events.

Common event types:

```text
Batch
Demo
Leave
Cover-up
Availability slots
```

Leave blocks are shown from approved leave requests.

## Schedule Detail Modal

Main method:

```php
ReportController::reportsScheduleData()
```

When a schedule item is clicked for details:

- `type = Batch` or `Coverup` loads batch detail.
- `type = Demo` loads demo detail.

Views:

```text
resources/views/Admin/CoachReports/batchshow.blade.php
resources/views/Admin/CoachReports/demoshow.blade.php
```

## Attendance Modal

Main method:

```php
ReportController::getAttendanceData()
```

Allowed types:

```text
Batch
Demo
Coverup
```

Permission check:

```text
SuperAdmin can access
OR user must have reports-attedance permission
```

Note: the permission name in code is spelled `reports-attedance`.

For batch or cover-up attendance, the system checks the batch status and loads the matching attendance form.

Views:

```text
resources/views/Admin/CoachReports/BatchAttendances/activebatchattendance.blade.php
resources/views/Admin/CoachReports/BatchAttendances/inactivebatchattendance.blade.php
resources/views/Admin/CoachReports/BatchAttendances/standbybatchattendance.blade.php
```

For demo attendance:

```text
resources/views/Admin/CoachReports/demoattendance.blade.php
```

## Batch Attendance Save

Main method:

```php
ReportController::batchAttendance()
```

Allowed users:

```text
SuperAdmin
users with reports-attedance permission
```

Required request fields:

```text
coach_id
type
batch_id
date
time
student_ids
status
studentStatus
```

Allowed class status:

```text
COMPLETED
CANCELLED
```

Allowed student status:

```text
PRESENT
ABSENT
```

### When Batch Attendance Is Completed

The system:

- Creates or updates `coach_attendances`.
- Sets coach attendance status to `COMPLETED`.
- Saves homework link, recording link, and chapter name.
- Increments `number_of_batch_sessions` when needed.
- Creates or updates `student_attendances`.
- Uses the submitted per-student status, usually `PRESENT` or `ABSENT`.
- Saves student remarks if entered.

### When Batch Attendance Is Cancelled

The system:

- Creates or updates `coach_attendances`.
- Sets coach attendance status to `CANCELLED`.
- Forces every submitted student attendance to `CANCELLED`.
- Uses the remark `Batch Cancelled`.

There is a helper named:

```php
ReportController::adjustEndDatesBySchedule()
```

This helper contains logic to shift batch, student batch, and fee end dates to the next scheduled weekday. However, the actual date updates inside that helper are currently commented out. Because of that, this Reports controller records the cancellation attendance, but does not actively extend batch or fee dates from this helper in the current code.

## Demo Attendance Save

Main method:

```php
ReportController::demoAttendance()
```

Allowed demo attendance status:

```text
COMPLETED
CANCELLED
Student Absent
```

Required fields:

```text
coach_id
type
demolead_id
date
time
status
```

If status is `COMPLETED`, `level_id` is also required.

### When Demo Is Completed

The system:

- Creates or updates `coach_attendances` for the demo lead.
- Updates the active `demo_sessions` record.
- Saves selected level into the demo session.
- Sets demo session coach attendance status.
- Updates `demo_leads.status` to `DEMO DONE`.
- Creates or updates `student_attendances`.

### When Demo Is Cancelled

The system:

- Creates or updates demo coach attendance.
- Updates demo session coach attendance status.
- Updates `demo_leads.status` to `CANCELLED`.
- Creates or updates student attendance for the demo lead.

### Demo Completion Email

There is a `DemoCompleteMail` reference in the controller, but the mail send line is commented.

Current behavior:

```text
Demo attendance is saved.
Demo completion email is not sent from this method.
```

## PDF Download

Main method:

```php
ReportController::downloadReport()
```

Inputs:

```text
fromDate
toDate
coachId
```

The method builds combined batch and demo schedule data for the date range, renders:

```text
resources/views/Admin/CoachReports/reportpdf.blade.php
```

Output folder:

```text
public/backend/reports_pdfs/
```

Output filename format:

```text
report_{timestamp}.pdf
```

The response returns a public `pdf_url`.

## Permission Notes

Main permissions:

```text
reports-view
reports-store
reports-update
reports-attedance
```

Important:

- Viewing reports is controlled by report permissions and sidebar access.
- Marking attendance is controlled by `reports-attedance` or SuperAdmin.
- The permission spelling is `reports-attedance` in code and seeders.

## Developer Notes

- Reports is tightly coupled with Operations because it reads operational records and can write attendance.
- Most report modals are AJAX-based.
- Count cards are not only display cards; several cards open deeper modal reports.
- Batch attendance from Reports can alter attendance history and should be treated as operational data entry.
- Some date extension logic exists but has actual update lines commented out. Confirm expected business behavior before re-enabling it.
- `ReportController` is large and mixes reporting, calendar, and attendance write logic. Future refactoring should split read-only reports from attendance mutation methods.

