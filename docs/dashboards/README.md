# Dashboard Access and Role-Based Experience

## Purpose

This document explains how dashboards work for different users in the ArcherKids admin application. It covers:

- Student dashboard.
- Coach dashboard.
- Employee/admin dashboard.
- SuperAdmin dashboard.
- Login and redirect behavior.
- Sidebar visibility.
- Actions each user type can perform.
- Important backend files, routes, and data dependencies.

Project path:

```text
C:\xampp\htdocs\archerkids
```

## Main Files

```text
routes/auth.php
routes/backend.php
app/Http/Controllers/Auth/AuthenticatedSessionController.php
app/Http/Controllers/HomeController.php
app/Http/Controllers/Admin/DashboardController.php
app/Http/Controllers/Admin/StudentDashboardController.php
resources/views/layouts/admin/navbar.blade.php
resources/views/Admin/Dashboard/SuperAdmin/index.blade.php
resources/views/Admin/Dashboard/Coach/coachindex.blade.php
resources/views/Admin/StudentDashboard/dashboard.blade.php
```

## Login and Redirect Flow

### Admin/Employee/Coach Login

Normal admin login uses:

```text
GET  /login
POST /login
```

After login, Laravel redirects to:

```text
/admin/dashboard/dashboard
```

This route is handled by:

```php
HomeController::index()
```

Then `HomeController::index()` checks permissions:

```text
If user has dashboard-view:
  redirect to admin.dashboard.index

Else:
  show Admin.Dashboard.index
```

`admin.dashboard.index` is:

```text
GET /admin/dashboard
DashboardController@index
```

Inside `DashboardController@index`:

```text
If logged-in user has Coach role:
  load coach dashboard

Else:
  load SuperAdmin/admin-style dashboard
```

### Student Login

Student login uses:

```text
GET  /student/login
POST /student/login/store
```

Student login checks:

```text
mobile
password/device_id
```

The controller first finds user by:

```php
User::where('device_id', $request->password)
    ->where('mobile', $request->mobile)
    ->first();
```

Then it also checks:

```php
Hash::check($request->password, $user->password)
```

After login, student goes to:

```text
/admin/student-dashboard
```

### Impersonation Login

Admin can login as student or coach from backend action links.

Student impersonation:

```text
GET /student/login?user_id={id}
```

Coach impersonation:

```text
GET /coach/login?user_id={id}
```

Behavior:

1. Current admin ID is stored in session as `impersonator_id`.
2. Admin is logged out.
3. Target student/coach user is logged in.
4. User is redirected to the matching dashboard.

## Sidebar Visibility

Sidebar is controlled mainly in:

```text
resources/views/layouts/admin/navbar.blade.php
```

Important rule:

```php
if ($roleName != 'Student') {
    show admin/employee/coach sidebar sections based on @can permissions
}

if ($roleName == 'Student') {
    show student sidebar only
}
```

So students do not see the admin modules. They only see student-specific menu items if they have the matching student permissions.

## Student Dashboard

### Who Sees It

Users with role:

```text
Student
```

and permission:

```text
student-dashboard-view
```

### Main Routes

```php
Route::get('/student-dashboard', [StudentDashboardController::class, 'studentDashboard'])->name('student.dashboard');
Route::get('/student-batches', [StudentDashboardController::class, 'studentBatches'])->name('student.batches');
Route::get('/student-certificates', [StudentDashboardController::class, 'studentCertificates'])->name('student.certificates');
Route::get('/student-certificates/download/pdf/{student}', [StudentDashboardController::class, 'studentCertificatesPdf'])->name('student.certificates.pdf');
Route::get('/student-homework', [StudentDashboardController::class, 'studentHomework'])->name('student.homework');
Route::get('/student-tournaments', [StudentDashboardController::class, 'studentTournaments'])->name('student.tournaments');
Route::get('/student-masterclasses', [StudentDashboardController::class, 'studentMasterclasses'])->name('student.masterclasses');
Route::get('/student-tournaments/certificate/{tournament}', [StudentDashboardController::class, 'tournamentCertificate'])->name('student.tournament.certificate');
Route::get('/student-fees', [StudentDashboardController::class, 'studentFees'])->name('student.fees');
Route::get('/student-recordings', [StudentDashboardController::class, 'studentRecordings'])->name('student.recordings');
Route::post('/student-dashboard/mark-attendance', [StudentDashboardController::class, 'markAttendance'])->name('student-dashboard.mark-attendance');
Route::get('/student-dashboard/join-class', [StudentDashboardController::class, 'joinClass'])->name('student-dashboard.join-class');
```

### Student Sidebar Items

```text
Dashboard
Class Details
Recordings
HomeWork
Certificates
Tournaments
Master Classes
Fees Details
```

Permission mapping:

```text
student-dashboard-view  Dashboard
student-batches         Class Details
student-recording       Recordings
student-homework        HomeWork
student-certificates    Certificates
student-tournaments     Tournaments
student-masterclasses   Master Classes
student-fees            Fees Details
```

### Student Dashboard Header

The top area shows student/profile data:

```text
Profile image
Student name
Mobile
Country
Level
Age
Chesslang ID
Chesslang password
Email
Join Chesslang button
```

If the user is still only a lead enquiry and not a converted student, the dashboard can show enquiry status messages:

```text
ACTIVE     Success! Your enquiry has been submitted.
CONVERTED  Congratulations! You have been converted to the demo.
REJECTED   Sorry! You are not applicable.
```

### Student Home Tab

The main Home tab shows:

- Upcoming class or upcoming cover-up class.
- Upcoming holidays.
- Upcoming masterclasses.
- Upcoming tournaments.
- Recent recordings.
- Demo session / lead enquiry information if applicable.
- Feedback tab/form.
- Fees due modal if student status is `FEESDUE`.

### Upcoming Class Logic

`StudentDashboardController::studentDashboard()` finds the student's active batch:

```text
student_batches.status = ACTIVE
batch.status = ACTIVE
student.status = ACTIVE
```

Then it reads active `batch_schedules` and finds the closest upcoming weekday.

If the normal coach has approved leave for that date and no cover-up class exists, the upcoming class is hidden.

If a cover-up class exists for the same batch, schedule, and date, the dashboard shows:

```text
Upcoming Coverup Class
```

and uses the cover-up coach/join URL.

### Student Join Class Action

The Join button is shown only around the class window:

```text
From 10 minutes before class start
Until 80 minutes after class start
```

When student clicks Join:

1. JavaScript calls `student-dashboard.mark-attendance`.
2. Backend checks if coach attendance exists for today and batch.
3. If coach has not started/marked class, student gets "Class not started yet please try again later".
4. If attendance exists, student attendance is marked `PRESENT`.
5. The student is redirected/opened to the batch or cover-up join URL.

Fallback route:

```text
/admin/student-dashboard/join-class
```

This route marks attendance if possible, then redirects to the meeting URL even when JavaScript fallback is used.

### Student Attendance Marking Details

`markAttendance()` checks:

```text
coach_attendances.batch_id = selected batch
coach_attendances.date = today
coach_attendances.status = COMPLETED
```

If existing `student_attendances` row exists:

```text
status = PRESENT
time = current time
```

If no student row exists but another attendance row exists for the batch/date, it creates a new row and copies:

```text
type
coach_id
batch_id
level_id
date
number_of_batch_sessions
homework_link
recording_link
chapter_name
```

### Student Class Details Page

Route:

```text
/admin/student-batches
```

Shows:

- Batches history.
- Class history.
- Student attendance status.
- Batch start/end timeline.
- Completed sessions count from coach attendance.

Data comes from:

```text
student_batches
student_attendances
student_fees
student_statuses
batchs
coach_attendances
```

### Student Recordings Page

Route:

```text
/admin/student-recordings
```

Shows attendance records ordered by latest date. If `recording_link` exists, student can open/download the recording link.

The dashboard also warns:

```text
The recording will be available for only 24 hours. Please download it.
```

### Student Homework Page

Route:

```text
/admin/student-homework
```

Shows homework data from attendance records. If `homework_link` exists, student sees a **Solve Quize** action.

Homework is populated by coach when marking batch/masterclass attendance.

### Student Fees Page

Route:

```text
/admin/student-fees
```

Shows:

- Student fee history.
- Next payment level if the student status is `FEESDUE`.
- Next three payment levels.
- Razorpay payment button.

Fee amount is selected by student country:

```text
usa_fees
canada_fees
australia_fees
newzealand_fees
india_fees
uae_fees
uk_fees
qatar_fees
singapore_fees
european_union_fees
oman_fees
```

HDFC code exists/commented in the view; Razorpay flow is active in the visible payment buttons.

### Student Certificates Page

Route:

```text
/admin/student-certificates
```

Certificates become downloadable based on completed/student batch levels. The current logic checks level IDs such as:

```text
Beginner
Intermediate
AL-1
AL-2
Expert-1 Module-1
Expert-1 Module-2
Expert-1 Module-3
```

Download route:

```text
/admin/student-certificates/download/pdf/{student}
```

It generates a PDF using DomPDF and certificate template images from storage paths.

### Student Tournaments Page

Route:

```text
/admin/student-tournaments
```

Tournament visibility checks:

- Tournament status is `ACTIVE`.
- Student country matches tournament country.
- Batch, level, or student ID matches.

Student can:

- Join tournament if link exists.
- Download tournament certificate if certificate file exists.

### Student Master Classes Page

Route:

```text
/admin/student-masterclasses
```

Masterclass visibility checks:

- Masterclass status is `ACTIVE`.
- Student country matches.
- Batch, level, or student ID matches.

Student can:

- Join if within allowed date/time window.
- Open homework link if coach has submitted it.
- Open recording link if coach has submitted it.

### Student Feedback

Dashboard has a Feedback tab with a form. It submits feedback from the logged-in student user. This is a student-facing action inside the dashboard rather than a separate sidebar page.

## Coach Dashboard

### Who Sees It

Users with role:

```text
Coach
```

and permission:

```text
dashboard-view
```

When `DashboardController@index()` detects the `Coach` role, it loads:

```php
DashboardController::indexCoach()
```

View:

```text
resources/views/Admin/Dashboard/Coach/coachindex.blade.php
```

### Coach Inactive Behavior

If the coach profile status is:

```text
INACTIVE
```

then the system logs the coach out and redirects to login with an inactive account message.

### Coach Dashboard Sections

The coach dashboard shows:

- Welcome message.
- Today's IST date.
- Today's schedules.
- Upcoming masterclasses.
- Calendar.
- Upcoming holidays for the coach's countries.
- Attendance modal.
- Homework/chapter modal.

### Coach Schedule Data

The schedule combines:

- Today's active batches assigned to the coach.
- Demo sessions assigned to the coach.
- Cover-up classes where this coach is the new coach.
- Yesterday batches for UK coaches in special cases.
- Leave request rules.

Schedule route:

```text
GET /admin/dashboard/{coachId}/schedule
```

The route rejects non-today date views:

```text
Viewing schedules other than today is not allowed.
```

### Schedule Item Types

Coach schedule items can have type:

```text
Batch
Demo
Coverup / COVERUP
Yesterday Batch
```

Each item carries:

```text
id
name
slot
status
type
active_students
start_url
homework_link
attendance_exists
attendance_time
```

### Coach Calendar

Calendar route:

```text
GET /admin/dashboard/{coachId}/calendar
```

Calendar combines:

- Active batch schedules, shown red.
- Approved leave requests, shown yellow.
- Demo sessions, shown blue.

The calendar is generated for the current year and future schedule range.

### Batch Attendance Action

Route:

```text
POST /admin/dashboard/{coachId}/batch-attendance
```

Coach can mark a batch as:

```text
COMPLETED
CANCELLED
```

For completed batch attendance, required data includes:

```text
coach_id
type
batch_id
date
time
student_ids
status
homework_link
chapter_name
studentStatus
studentRemark
```

Student statuses:

```text
PRESENT
ABSENT
```

Important validation:

```text
Batch attendance can only be marked until 7 minutes after scheduled start time.
```

If attendance is submitted late by more than 3 minutes, a `DelayedBatch` record is created.

### Batch Cancellation Behavior

If coach marks a batch as `CANCELLED`:

1. Coach attendance is saved as cancelled.
2. All selected student attendance records become `CANCELLED`.
3. The batch end date is extended to the next scheduled weekday.
4. Active student batch end dates are also extended.
5. Latest student fee end dates are extended using the batch's scheduled weekdays.

This is the same business logic that keeps cancelled class counts from reducing the student's paid schedule.

### Pre-Batch Attendance / Start Class Action

Route:

```text
POST /admin/dashboard/{batchId}/pre-batch-attendance
```

This is used around starting class. It:

1. Checks the batch exists.
2. Finds today's batch schedule.
3. Blocks coach change/start behavior after 10 minutes from batch start.
4. Prevents duplicate coach attendance for same batch/date.
5. Creates coach attendance as `COMPLETED`.
6. Creates student attendance rows as `ABSENT` by default.

Then students can later click Join and mark themselves present.

### Demo Attendance Action

Route:

```text
POST /admin/dashboard/{coachId}/demo-attendance
```

Coach can mark demo as:

```text
COMPLETED
CANCELLED
Student Absent
```

If status is `COMPLETED`, `level_id` is required.

Saving demo attendance:

- Upserts coach attendance by `demolead_id`.
- Updates the active demo session's level and `coach_attendance_status`.
- If completed, updates demo lead status to `DEMO DONE`.
- If cancelled, updates demo lead status to `CANCELLED`.
- Upserts student attendance for the demo lead.

### Masterclass Attendance Action

Routes:

```text
POST /admin/dashboard/get/coach/master/class
POST /admin/dashboard/mark/master/class/attendance
POST /admin/dashboard/mark/pre/master/class/attendance
```

Coach can:

- View upcoming masterclasses assigned to them.
- Start masterclass from start URL.
- Mark masterclass attendance.
- Submit homework link.
- Submit recording link.
- Submit chapter name.

For normal masterclass attendance:

```text
homework_link required
chapter_name required
recording_link optional in current validation
```

### Coach Holidays

Coach dashboard loads holidays matching coach countries:

```text
holiday.status = ACTIVE
holiday.country contains coach country
holiday date is current/future
```

Holiday description can be opened in a modal.

## Employee/Admin Dashboard

### Who Sees It

Employees are normal internal users created from:

```text
Users > Employees
```

They can have different roles, such as CRM, CRE, Sales, Lead Generation, or Admin roles.

If an employee has:

```text
dashboard-view
```

they land on:

```text
DashboardController@index
```

Because they do not have `Coach` role, they receive the SuperAdmin/admin dashboard view:

```text
Admin.Dashboard.SuperAdmin.index
```

But what they can see outside dashboard depends on their role permissions and country scope.

### Employee Sidebar

Employees see non-student sidebar sections only when they have permissions:

```text
Dashboard
Users
Operations
Actions
Master
Component
```

Examples:

```text
dashboard-view       Dashboard
role-view            Roles
employee-view        Employees
coachs-view          Coaches
students-view        Students
reports-view         Reports
leaverequests-view   Leave Requests
feedback-view        Feedbacks
levels-view          Levels
batchs-view          Batches
```

### Employee Country Scope

Many dashboard tables filter data by the countries assigned to the employee's role.

If employee is not SuperAdmin:

```text
Read countries from user role.
Show only data matching those countries.
```

This matters strongly for CRM/Sales users split by market:

```text
USA/CANADA
UK/EU
ASIA/AUSTRALIA
```

### Employee Special Access Flags

Employee user records can also have:

```text
student_fees_edit
batch_edit
component_tab
```

Meaning:

- `student_fees_edit = YES`: special edit ability in fee screens.
- `batch_edit = YES`: special batch edit behavior where checked.
- `component_tab = YES`: shows Component section in sidebar.

Component section is also visible for hardcoded user IDs:

```text
1, 2, 4
```

## SuperAdmin Dashboard

### Who Sees It

Role:

```text
SuperAdmin
```

Permission:

```text
dashboard-view
```

SuperAdmin sees all countries and all dashboard sections.

### Top Summary Counts

The top dashboard shows:

```text
Total Active Students
Total Active Coaches
Total Active Employees
```

Data comes from:

```text
Student::where('status', 'ACTIVE')
Coach::where('status', 'ACTIVE')
Employee with active user
```

### Dashboard Tabs

SuperAdmin/admin dashboard tabs:

```text
Students
Missed Sessions
Batches
Coaches
Employees
Student Payments
```

### Students Tab

Filters:

```text
Coach
Country
Status
```

Default status shown in the UI:

```text
FEESDUE
```

Columns:

```text
#
Action
Status
Full Name
ID
Mobile
Batch
```

Actions/data:

- Show student detail modal.
- View status badge.
- See latest fee due date when status is `FEESDUE`.
- WhatsApp link for student ID / fee due message for non-coach users.
- View active/previous batch labels.

### Missed Sessions Tab

This tab finds students who have missed consecutive classes. The current logic uses:

```text
2 consecutive missed sessions
```

It considers:

- Active coaches.
- Active batches.
- Active student batches.
- Student attendance not cancelled.
- Recent attendance status.

The purpose is to help admin/CRM follow up students who are absent repeatedly.

### Batches Tab

Filters:

```text
Status
Coach
Level
Student
```

Columns include:

```text
Action
Status
Batch
Total Kids
Kids Zone Name
Completed Session
Timeline
```

Batch status badge values:

```text
ACTIVE
INACTIVE
STANDBY
```

Completed sessions are counted from `coach_attendances` where status is `COMPLETED`.

### Coaches Tab

Shows active coaches as cards/list items. It is a quick overview from `Coach::where('status', 'ACTIVE')`.

### Employees Tab

Shows active employees and their assigned roles. It only includes employees whose linked user is active.

### Student Payments Tab

Shows student fee records where:

```text
student_id is not null
currency is null
status is ACTIVE
```

Each payment card can show:

- Student name.
- Start date.
- End date.
- Fees paid.
- Link to check fees details.

## Dashboard Data Permissions Summary

```text
Student:
  Only own dashboard, own batches, own homework, own recordings, own fees, own certificates, matching tournaments/masterclasses.

Coach:
  Own batches, own demos, own cover-up classes, own calendar, own holidays, own masterclasses.

Employee:
  Admin dashboard view if permitted, filtered by role permissions and role countries.

SuperAdmin:
  Full dashboard data across countries and modules.
```

## Important Statuses

### Student Status

```text
ACTIVE
INACTIVE
STANDBY
FEESDUE
```

`FEESDUE` triggers student fees messaging and the student dashboard fee modal.

### Coach/Batch Attendance Status

```text
COMPLETED
CANCELLED
```

Demo attendance additionally allows:

```text
Student Absent
```

### Student Attendance Status

```text
PRESENT
ABSENT
CANCELLED
NOTMARKED
```

`NOTMARKED` is excluded from student class history.

### Demo Lead Status Updates From Coach Dashboard

```text
COMPLETED demo attendance -> DemoLead status DEMO DONE
CANCELLED demo attendance -> DemoLead status CANCELLED
```

## Developer Notes

- Dashboard route naming is mixed: `/admin/dashboard/dashboard` goes through `HomeController`, while `/admin/dashboard` goes to `DashboardController@index`.
- Coach dashboard is selected by role, not by URL alone.
- Student sidebar is completely separate from admin sidebar.
- Student join attendance depends on coach attendance existing first.
- Cancelled batch attendance extends batch, student batch, and fee end dates.
- Employee dashboard visibility is not a separate dashboard implementation; employees use the admin dashboard with permission/country restrictions.
- Sensitive fields are visible in student/coach contexts, including Chesslang password and coach portal/Zoom data elsewhere. Be careful before sharing screenshots or repositories.

