# Operations Flow Documentation - ArcherKids

## 1. Project Context

This document explains the **Operations module flow** of the ArcherKids Laravel application.

Project directory:

```text
C:\xampp\htdocs\archerkids
```

The Operations area covers the business journey from enquiry to paid student and class/batch allocation.

Main sidebar items covered:

- Website Enquiries
- Lead Enquiries
- Demo Leads
- New Enrollments
- Students
- Change Batches
- Cover Up Classes
- Batches

## 2. High-Level Business Flow

The normal journey is:

```text
Website trial/contact form
        |
        v
Lead Enquiry / Website Enquiry
        |
        v
Demo Lead
        |
        v
Demo Session scheduled with coach
        |
        v
Demo completed / lead followed up
        |
        v
Student created
        |
        v
New Enrollment created
        |
        v
Student activated after fee confirmation
        |
        v
Student assigned to Batch
        |
        v
Student attends scheduled classes
```

Important clarification:

```text
New Enrollment may store a batch_id, but the real active batch assignment is stored in student_batches.
```

So for reporting, dashboards, attendance, and class access, the important table is:

```text
student_batches
```

## 3. Main Route File

Operations routes are defined in:

```text
routes/backend.php
```

All admin routes are inside the admin route group:

```php
Route::middleware(['auth', 'admin', 'preventBackHistory'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // Admin routes
    });
});
```

This means the admin user must be logged in and must pass the admin middleware.

## 4. Main Controllers

```text
app/Http/Controllers/Frontend/HomeController.php
app/Http/Controllers/Admin/EnquiryController.php
app/Http/Controllers/Admin/LeadEnquiryController.php
app/Http/Controllers/Admin/DemoLeadController.php
app/Http/Controllers/Admin/DemoSessionsController.php
app/Http/Controllers/Admin/NewEnrollmentController.php
app/Http/Controllers/Admin/StudentController.php
app/Http/Controllers/Admin/BatchController.php
app/Http/Controllers/Admin/BatchScheduleController.php
app/Http/Controllers/Admin/ChangeclassController.php
app/Http/Controllers/Admin/CoverupclassController.php
```

## 5. Main Models

```text
app/Models/Enquiry.php
app/Models/DemoLeadEnquiry.php
app/Models/DemoLead.php
app/Models/DemoSession.php
app/Models/Student.php
app/Models/NewEnrollment.php
app/Models/StudentFee.php
app/Models/Batch.php
app/Models/BatchSchedule.php
app/Models/StudentBatch.php
app/Models/Changeclass.php
app/Models/Coverupclass.php
app/Models/User.php
app/Models/Coach.php
app/Models/Level.php
app/Models/Paymentlevel.php
```

## 6. Main Database Tables

```text
enquiries
demoleadenquiries
demoleads
demo_sessions
students
new_enrollments
student_fees
batchs
batch_schedules
student_batches
changeclasses
coverupclasses
users
coachs
levels
paymentlevels
employees
```

## 7. Website Enquiries

### Purpose

Website Enquiries are general enquiry/contact records from the website. These are separate from trial-class lead enquiries.

### Routes

```php
Route::resource('enquiries', EnquiryController::class);
Route::post('enquiries/data', [EnquiryController::class, 'data'])->name('enquiries.data');
Route::post('enquiries/list', [EnquiryController::class, 'list'])->name('enquiries.list');
```

### Main Files

```text
app/Http/Controllers/Admin/EnquiryController.php
app/Http/Controllers/Frontend/HomeController.php
app/Models/Enquiry.php
resources/views/Admin/Enquiries/*
```

### Functional Explanation

Website contact/general forms create records in `enquiries`.

Admin can:

- View enquiry list
- Search/filter by date
- View enquiry details
- Delete enquiry

This module is mostly informational. It does not directly create a demo lead or student in the current admin flow.

## 8. Lead Enquiries

### Purpose

Lead Enquiries are trial-class enquiries. These records are stored in:

```text
demoleadenquiries
```

This is usually the first proper sales/operations record before a demo lead is created.

### Routes

```php
Route::resource('leadenquiries', LeadEnquiryController::class);
Route::post('leadenquiries/data', [LeadEnquiryController::class, 'data'])->name('leadenquiries.data');
Route::post('leadenquiries/list', [LeadEnquiryController::class, 'list'])->name('leadenquiries.list');
Route::post('leadenquiries/change-status', [LeadEnquiryController::class, 'changeStatus'])->name('leadenquiries.change.status');
Route::get('leadenquiries/convert/{leadenquiry}', [LeadEnquiryController::class, 'convertToDemoLead'])->name('leadenquiries.convert');
Route::post('leadenquiries/reject/{leadenquiry}', [LeadEnquiryController::class, 'rejectTheDemoLead'])->name('leadenquiries.reject');
Route::get('/admin/leadenquiries/{id}/convert-form', [LeadEnquiryController::class,'convertForm'])->name('leadenquiries.convert.form');
Route::post('/admin/leadenquiries/{id}/convert-store', [LeadEnquiryController::class,'convertStore'])->name('leadenquiries.convert.store');
```

### Main Files

```text
app/Http/Controllers/Frontend/HomeController.php
app/Http/Controllers/Admin/LeadEnquiryController.php
app/Models/DemoLeadEnquiry.php
resources/views/Admin/LeadEnquiries/*
```

### How Lead Enquiry Is Created

Trial booking logic is in:

```text
app/Http/Controllers/Frontend/HomeController.php
```

Important method:

```php
storeBookATrailForm()
```

The frontend validates:

- Country
- City
- Timezone
- Child first name
- Age
- Mobile
- Email
- Language preference

Then the system creates:

- `DemoLeadEnquiry`
- linked `User`
- assigns `Student` role to that user
- sets password/device id like `archer@{user_id}`

### Important Lead Fields

```text
kids_first_name
kids_last_name
parent_name
age
mobile
email
city
country
timezone
date
time
ist_date
ist_time
available_device
enrollment_plan
language_preference
duration
status
lead_status
email_verified
mobile_verified
utm_source
utm_medium
user_id
is_hide
```

### Lead Status Meaning

Common statuses used:

```text
ACTIVE
CONVERTED
REJECTED
```

### Convert Lead Enquiry To Demo Lead

Main method:

```php
LeadEnquiryController::convertStore()
```

What it does:

1. Loads the `DemoLeadEnquiry`.
2. Creates or updates a linked `User`.
3. Ensures the user has the `Student` role.
4. Creates a `DemoLead`.
5. Sets demo lead status to `ROWLEAD`.
6. Marks lead enquiry as `CONVERTED`.

Database result:

```text
demoleadenquiries.status = CONVERTED
demoleadenquiries.user_id = users.id
demoleads.user_id = users.id
demoleads.status = ROWLEAD
```

### Reject Lead Enquiry

Main method:

```php
LeadEnquiryController::rejectTheDemoLead()
```

What it does:

```text
demoleadenquiries.status = REJECTED
demoleadenquiries.remark = rejection remark
```

### Soft Delete Lead Enquiry

Lead enquiry delete does not remove the database row. It marks:

```text
is_hide = 1
```

## 9. Demo Leads

### Purpose

Demo Leads are trial candidates after they have moved from lead enquiry into the demo pipeline.

Stored in:

```text
demoleads
```

### Routes

```php
Route::resource('demoleads', DemoLeadController::class);
Route::post('demoleads/data', [DemoLeadController::class, 'data'])->name('demoleads.data');
Route::post('demoleads/export', [DemoLeadController::class, 'export'])->name('demoleads.export');
Route::post('demoleads/list', [DemoLeadController::class, 'list'])->name('demoleads.list');
Route::post('demoleads/change-status', [DemoLeadController::class, 'changeStatus'])->name('demoleads.change.status');
Route::get('demoleads/{demolead}/convert', [DemoLeadController::class, 'convertToStudent'])->name('demoleads.convert');
Route::post('demoleads/{demolead}/convert/save', [DemoLeadController::class, 'saveConvertedStudent'])->name('demoleads.convert.save');
Route::post('demoleads/processDateTimeZone', [DemoLeadController::class, 'processDateTimeZone'])->name('demoleads.processDateTimeZone');
Route::post('demoleads/check-mobile', [DemoLeadController::class, 'checkMobileUniqueness'])->name('demoleads.check.mobile');
Route::get('demoleads/get/timezones', [DemoLeadController::class, 'getTimezones'])->name('demoleads.get.timezones');
```

### Main Files

```text
app/Http/Controllers/Admin/DemoLeadController.php
app/Models/DemoLead.php
resources/views/Admin/DemoLeads/*
```

### Important Demo Lead Fields

```text
user_id
first_name
last_name
age
mobile
city
country
kids_time_zone
date
time
kids_date
kids_time
remark
reason
status
created_by
updated_by
```

### Demo Lead Statuses

Statuses shown in the Demo Leads screen:

```text
ROWLEAD
SCHEDULED
RESCHEDULED
DEMO DONE
CANCELLED
CONVERTED
INTERESTED
NOT INTERESTED
```

### Demo Lead Status Meaning

#### ROWLEAD

Meaning:

```text
Raw lead / newly moved lead.
```

This is the first demo lead status after a Lead Enquiry is converted into a Demo Lead.

Set by:

```php
LeadEnquiryController::convertStore()
LeadEnquiryController::convertToDemoLead()
DemoLeadController::store()
```

Operations meaning:

- Lead exists in Demo Leads.
- Demo may not be properly scheduled yet.
- Team should contact parent/student and schedule a demo session.

#### SCHEDULED

Meaning:

```text
Demo class has been scheduled.
```

Set by:

```php
DemoSessionsController::store()
DemoSessionsController::changeStatus()
```

When a demo session is created, the system creates an active `demo_sessions` record and marks the linked demo lead as `SCHEDULED`.

Operations meaning:

- Coach/date/time/slot should be assigned.
- Zoom meeting details should exist if coach Zoom credentials are configured.
- Student/parent can be reminded about the demo.

#### RESCHEDULED

Meaning:

```text
Previous demo session was made inactive and the demo needs a new schedule.
```

Set by:

```php
DemoSessionsController::changeStatus()
```

If a demo session is marked `INACTIVE`, and no active demo sessions remain for that demo lead, the demo lead becomes `RESCHEDULED`.

Operations meaning:

- Demo did not happen at the previous time or needs another slot.
- Team should create/activate a new demo session.

#### DEMO DONE

Meaning:

```text
Demo class has been completed.
```

Set by:

```php
DemoSessionsController::update()
```

When demo session details are updated with a level, the linked demo lead can be marked `DEMO DONE`.

Operations meaning:

- Demo is completed.
- Team should follow up with the parent/student.
- Lead can be marked `INTERESTED`, `NOT INTERESTED`, or converted to student.
- The Demo Leads list shows a WhatsApp follow-up option for `DEMO DONE` leads.

#### CANCELLED

Meaning:

```text
Demo lead or demo process was cancelled.
```

Set by:

```php
DemoLeadController::changeStatus()
```

This is a manual status from the status change modal/dropdown.

Operations meaning:

- Demo is no longer active.
- Team should add a clear reason in the reason field.
- It can be used when the parent cancels, duplicate lead is found, or the demo should not continue.

#### CONVERTED

Meaning:

```text
Demo lead has been converted into a student.
```

Set by:

```php
DemoLeadController::saveConvertedStudent()
```

When a demo lead is converted:

- A `students` record is created.
- A `new_enrollments` record is created.
- Demo lead status becomes `CONVERTED`.

Operations meaning:

- Lead has left the demo pipeline.
- Next work happens in New Enrollments, Students, Fees, and Batch Assignment.
- By default, the Demo Leads list excludes converted leads unless status filter is used.

#### INTERESTED

Meaning:

```text
Parent/student is interested after demo but not yet converted.
```

Set by:

```php
DemoLeadController::changeStatus()
```

This is a manual status from the status change modal/dropdown.

Operations meaning:

- Sales/admission follow-up is required.
- Team should discuss fees, batch options, enrollment date, and payment.
- Once confirmed, convert the demo lead to student.

#### NOT INTERESTED

Meaning:

```text
Parent/student is not interested after demo or follow-up.
```

Set by:

```php
DemoLeadController::changeStatus()
```

This is a manual status from the status change modal/dropdown.

Operations meaning:

- Lead is not moving forward currently.
- Team should enter the reason, such as fees issue, timing issue, not reachable, joined elsewhere, not suitable, or parent declined.
- This status helps avoid repeated unnecessary follow-ups.

### Demo Lead Status Change Modal

Status can be changed manually from the Demo Leads table status button.

Main method:

```php
DemoLeadController::changeStatus()
```

It saves:

```text
demoleads.status
demoleads.reason
```

This means the `reason` field is especially important for manual statuses like:

```text
CANCELLED
INTERESTED
NOT INTERESTED
```

### Demo Lead Status Filters

The Demo Leads screen has quick filter buttons:

```text
All
Scheduled
Rescheduled
Demo Done
Cancelled
Converted
Rowlead
Interested
Not Interested
```

Filter behavior:

- If no status is selected, converted leads are excluded by default.
- If `CONVERTED` is selected, converted leads are shown.
- Coach, country, level, employee, date range, and active/deleted filters can also be applied.

### Demo Lead List Behavior

The list filters by:

- Employee / created by
- Country
- Status
- Date range
- Coach
- Level
- Role country restrictions

Non-SuperAdmin users see records based on their role country permissions.

## 10. Demo Sessions

### Purpose

Demo Sessions schedule the actual trial class with a coach.

Stored in:

```text
demo_sessions
```

### Routes

```php
Route::prefix('demoleads/{demolead}')->name('demoleads.')->group(function () {
    Route::resource('demo_sessions', DemoSessionsController::class);
    Route::post('demo_sessions/data', [DemoSessionsController::class, 'data'])->name('demo_sessions.data');
    Route::post('demo_sessions/change-status', [DemoSessionsController::class, 'changeStatus'])->name('demo_sessions.change.status');
});

Route::get('demo_sessions/coach_availability', [DemoSessionsController::class, 'getCoachAvailability'])->name('demo_sessions.coach_availability');
Route::get('demo_sessions/available_slots', [DemoSessionsController::class, 'getAvailableSlots'])->name('demo_sessions.available_slots');
```

### Main Files

```text
app/Http/Controllers/Admin/DemoSessionsController.php
app/Models/DemoSession.php
resources/views/Admin/DemoSessions/*
```

### Scheduling Logic

When a demo session is created:

1. Admin selects coach/date/time/slot/level.
2. System checks selected coach details.
3. If Zoom credentials exist, a Zoom meeting is created.
4. `demo_sessions` record is created.
5. `demoleads.status` becomes `SCHEDULED`.
6. Demo lead IST time and child timezone time are updated.

Important method:

```php
DemoSessionsController::store()
```

### Coach Availability Logic

Demo scheduling checks:

- Coach availability
- Existing batch schedules
- Cover-up class conflicts
- Approved leave requests
- Existing demo sessions

Important methods:

```php
getCoachAvailability()
getAvailableSlots()
```

## 11. Convert Demo Lead To Student

### Purpose

After a demo lead is ready for admission, admin converts it into a student.

Main methods:

```php
DemoLeadController::convertToStudent()
DemoLeadController::saveConvertedStudent()
```

### What The Convert Form Loads

The form loads:

- Demo lead
- Active levels
- Active payment levels
- Employees
- Active batches

### What Save Conversion Does

When saving conversion:

1. Validates student ID, employee, payment and remark fields.
2. Creates or updates linked `User`.
3. Assigns `Student` role.
4. Creates a row in `students`.
5. Sets student status as `INACTIVE`.
6. Sets demo lead status as `CONVERTED`.
7. Creates row in `new_enrollments`.
8. Sends converted student email.

Important database writes:

```text
students
new_enrollments
demoleads
users
```

### Student Created Fields

```text
first_name
age
mobile
city
country
lastpayment_level_id
status = INACTIVE
student_id
level_id
portal_password
currency
monthly_fees
timezone
user_id
```

### Important Gap

The conversion stores `batch_id`, `start_date`, and `end_date` in `new_enrollments`, but it does not create the actual active batch assignment in `student_batches`.

So this:

```text
new_enrollments.batch_id
```

is not the same as this:

```text
student_batches.batch_id
```

The actual batch assignment must happen through the Batches module.

## 12. New Enrollments

### Purpose

New Enrollments are records of students who have converted from demo lead and are waiting for enrollment/payment confirmation.

Stored in:

```text
new_enrollments
```

### Routes

```php
Route::resource('newenrollments', NewEnrollmentController::class);
Route::post('newenrollments/data', [NewEnrollmentController::class, 'data'])->name('newenrollments.data');
Route::post('newenrollments/list', [NewEnrollmentController::class, 'list'])->name('newenrollments.list');
Route::get('newenrollments/excel/export', [NewEnrollmentController::class, 'export'])->name('newenrollments.export');
```

### Main Files

```text
app/Http/Controllers/Admin/NewEnrollmentController.php
app/Models/NewEnrollment.php
resources/views/Admin/NewEnrollments/*
```

### Important Fields

```text
student_id
batch_id
employee_ids
start_date
end_date
receive_date
fees
received_fees
currency
remark
created_by
updated_by
```

### New Enrollment Update Modes

The update method supports two flows:

```text
type = new-enrollment
type = student-fee
```

### Save As New Enrollment

When `type = new-enrollment`:

- Updates enrollment details
- Does not activate the student
- Does not create student fee

### Confirm Enrollment / Student Fee

When `type = student-fee`:

1. Student status becomes `ACTIVE`.
2. New enrollment details are updated.
3. A `student_fees` record is created.

Important result:

```text
students.status = ACTIVE
student_fees.status = ACTIVE
```

### Important Note

New Enrollment confirmation activates the student and records fees. It does not automatically assign the student into `student_batches`.

## 13. Students

### Purpose

Students are the actual learners in the system.

Stored in:

```text
students
```

### Routes

```php
Route::resource('students', StudentController::class);
Route::post('students/data', [StudentController::class, 'data'])->name('students.data');
Route::post('students/export', [StudentController::class, 'export'])->name('students.export');
Route::post('students/list', [StudentController::class, 'list'])->name('students.list');
Route::post('students/change-status', [StudentController::class, 'changeStatus'])->name('students.change.status');
Route::get('students/get/coaches', [StudentController::class, 'getCoaches'])->name('students.get.coaches');
Route::get('students/get/batches', [StudentController::class, 'getBatches'])->name('students.get.batches');
```

### Main Files

```text
app/Http/Controllers/Admin/StudentController.php
app/Models/Student.php
app/Models/StudentBatch.php
app/Models/StudentFee.php
resources/views/Admin/Students/*
```

### Important Student Fields

```text
user_id
first_name
last_name
age
mobile
email
timezone
city
country
fees_country
student_id
level_id
monthly_fees
currency
status
portal_password
lastpayment_level_id
```

### Student Statuses

Common statuses:

```text
ACTIVE
INACTIVE
FEESDUE
STANDBY
CHANGECLASS
```

### Student Status Meaning

#### ACTIVE

Meaning:

```text
Student is active and can be part of ongoing classes/batches.
```

Usually set by:

```php
NewEnrollmentController::update()
StudentFeeController::store()
StudentFeeController::update()
StudentFeeController::changeStatus()
```

Operations meaning:

- Student has completed enrollment/payment flow.
- Student can appear in batch assignment if country and other filters match.
- Student can be counted in active batch/student reports.

Important note:

The student status modal in the Students screen does not currently expose `ACTIVE` as a manual option. In the Blade file it is commented out. So active status usually comes from fee/enrollment processing.

#### INACTIVE

Meaning:

```text
Student is not active in classes.
```

Set by:

```php
StudentController::changeStatus()
```

Also initial student creation from demo conversion creates the student as:

```text
INACTIVE
```

Operations meaning:

- Student should not be treated as currently active.
- If set manually, all student batch records are marked `INACTIVE`.
- Latest batch record gets end date as today and end time as current time.

Database effect when manually marked inactive:

```text
students.status = INACTIVE
student_batches.status = INACTIVE
student_batches.end_date = today
student_batches.end_time = current time
```

#### STANDBY

Meaning:

```text
Student is paused/on hold, but not necessarily permanently inactive.
```

Set by:

```php
StudentController::changeStatus()
StudentFeeController::changeStatus()
```

Operations meaning:

- Student is temporarily stopped.
- Batch participation is stopped.
- Can be useful when the student is pausing classes, waiting for confirmation, or not attending for now.

Database effect when manually marked standby:

```text
students.status = STANDBY
student_batches.status = INACTIVE
student_batches.is_fees_due = 0
student_batches.end_date = today
student_batches.end_time = current time
```

#### FEESDUE

Meaning:

```text
Student has not submitted fees / latest fee period has expired.
```

Usually set by:

```php
CheckFessDueStudents command
UsaCanadaSetFessDue command
UkSetFessDue command
StudentFeeController::store()
StudentFeeController::update()
```

Operations meaning:

- Student has payment due.
- Student is removed from active batch participation by fee-due cron.
- Follow-up/payment collection is required.
- After payment is added, student can become `ACTIVE` again.

Database effect from fee due cron:

```text
students.status = FEESDUE
student_fees.status = INACTIVE
student_batches.status = INACTIVE
student_batches.is_fees_due = 1
```

Important note:

The Students status modal does not currently expose `FEESDUE` as a manual option. In the Blade file it is commented out. Fee due is primarily system/fee controlled.

#### CHANGECLASS

Meaning:

```text
Student wants to change class/batch.
```

Set by:

```php
StudentController::changeStatus()
```

Operations meaning:

- Student is requesting or being moved to another class/batch.
- Current/latest batch is ended.
- A `changeclasses` record is created.
- Operations team should process the change from Change Batches and then assign the student to a new batch.

Required when selected from status modal:

```text
employee_id
remark
```

Database effect:

```text
students.status = CHANGECLASS
latest student_batches.status = INACTIVE
latest student_batches.is_fees_due = 0
latest student_batches.end_date = today
latest student_batches.end_time = current time
changeclasses record created
```

If the previous status was `ACTIVE`, the system also copies latest fee details into the `changeclasses` record.

#### CURRENT_DAY

Meaning:

```text
Current Day Fees Due is a filter only, not a real student status.
```

Used by:

```php
StudentController::data()
StudentController::export()
```

Filter behavior:

```text
Shows students whose student_fees.end_date is today.
```

Operations meaning:

- These students are due today.
- Team should follow up before they become fully fee due/inactive through cron.
- This value is not saved into `students.status`.

### Student Status Filter Buttons

The Students screen has quick filters:

```text
All
Active
Inactive
StandBy
Fees Due
Change Class
Current Day Fees Due
```

Filter behavior:

- `All` means no status filter, but the controller excludes `INACTIVE` students by default.
- `ACTIVE` filters `students.status = ACTIVE`.
- `INACTIVE` filters `students.status = INACTIVE`.
- `STANDBY` filters `students.status = STANDBY`.
- `FEESDUE` filters `students.status = FEESDUE` and additionally checks latest fee end date is before today.
- `CHANGECLASS` filters `students.status = CHANGECLASS`.
- `CURRENT_DAY` checks student fee end date is today and does not check `students.status`.

### Student Status Change Modal

The status modal on the Students table currently allows:

```text
INACTIVE
STANDBY
CHANGECLASS
```

The modal does not expose:

```text
ACTIVE
FEESDUE
CURRENT_DAY
```

So:

- `ACTIVE` usually comes from confirmed enrollment or valid student fee.
- `FEESDUE` usually comes from fee expiry/cron or fee end date logic.
- `CURRENT_DAY` is only a report/filter view.

### Student List Uses Batch Data

The student list displays the latest batch using:

```php
Student::latestBatch()
```

Relationship:

```php
public function latestBatch()
{
    return $this->hasOne(StudentBatch::class, 'student_id')->latest('created_at')->with('batch');
}
```

### Student Status Change

Main method:

```php
StudentController::changeStatus()
```

Important behavior:

- If status becomes `INACTIVE` or `STANDBY`, active `student_batches` are marked inactive.
- If status becomes `CHANGECLASS`, the latest active batch is marked inactive and a `changeclasses` record is created.
- Every status change creates a `student_statuses` history record.

## 13.1 Student Fees And Fee Due Handling

### Purpose

Student Fees control whether a student is currently paid, active, and eligible to continue in their batch.

Stored in:

```text
student_fees
```

Fee status directly affects:

- Student status
- Student dashboard access behavior
- Batch membership in `student_batches`
- Fee reminder emails
- Whether a student is counted as active or fees due

### Routes

```php
Route::prefix('students/{student}')->name('students.')->group(function () {
    Route::resource('student_fees', StudentFeeController::class);
    Route::post('student_fees/data', [StudentFeeController::class, 'data'])->name('student_fees.data');
    Route::post('student_fees/change-status', [StudentFeeController::class, 'changeStatus'])->name('student_fees.change.status');
    Route::get('student_fees/{student_fee}/invoice', [StudentFeeController::class, 'downloadInvoice'])->name('student_fees.invoice');
});

Route::post('student_fees/list', [StudentFeeController::class, 'list'])->name('student_fees.list');
```

### Main Files

```text
app/Http/Controllers/Admin/StudentFeeController.php
app/Models/StudentFee.php
app/Models/Student.php
app/Models/StudentBatch.php
app/Console/Kernel.php
app/Console/Commands/CheckFessDueStudents.php
app/Console/Commands/UsaCanadaSetFessDue.php
app/Console/Commands/UkSetFessDue.php
app/Console/Commands/CheckPayment.php
```

### Important Fee Fields

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

### Manual Fee Creation

Main method:

```php
StudentFeeController::store()
```

When a fee record is created:

- If `end_date` is today or in the future, fee status becomes `ACTIVE`.
- Student status becomes `ACTIVE`.
- Invoice email is sent to the student user email.
- If the student was previously `FEESDUE`, the system tries to restore/recreate the student's batch membership.

If `end_date` is already in the past:

```text
student_fees.status = INACTIVE
students.status = FEESDUE
```

### Manual Fee Update

Main method:

```php
StudentFeeController::update()
```

When fee end date is updated:

- Past end date marks fee `INACTIVE` and student `FEESDUE`.
- Current/future end date marks fee `ACTIVE` and student `ACTIVE`.

### Automatic Fee Due Cron Flow

Fee due handling is scheduled in:

```text
app/Console/Kernel.php
```

Scheduled commands:

```php
$schedule->command('check:fess-due-students')->dailyAt('00:30');
$schedule->command('set:fess-due-in-usa-canada')->dailyAt('21:05');
$schedule->command('set:fess-due-in-uk')->dailyAt('12:00');
$schedule->command('check:payment')->everyMinute();
```

Country-specific behavior:

```text
Other countries: check:fess-due-students
USA/CANADA: set:fess-due-in-usa-canada
UK: set:fess-due-in-uk
```

### What Happens When Student Does Not Submit Fees

When the latest active `student_fees.end_date` expires and no newer payment is found:

1. Student status becomes `FEESDUE`.
2. Active fee record becomes `INACTIVE`.
3. Active `student_batches` record becomes `INACTIVE`.
4. `student_batches.is_fees_due` becomes `1`.
5. `student_batches.end_date` is set to the fee due cutoff date.
6. `student_batches.end_time` is set to the current time.
7. Fee due email is sent if the student user has an email.

Important database result:

```text
students.status = FEESDUE
student_fees.status = INACTIVE
student_batches.status = INACTIVE
student_batches.is_fees_due = 1
```

This means the student is effectively removed from active batch participation until payment is handled.

### How A Fee Due Student Comes Back

When a new valid student fee is created for a `FEESDUE` student:

1. New `student_fees` record is created as `ACTIVE`.
2. Student status becomes `ACTIVE`.
3. System checks the student's latest batch.
4. System creates a new active `student_batches` record where possible.
5. `is_fees_due` is reset to `0` for the restored batch record.

Important result:

```text
students.status = ACTIVE
student_batches.status = ACTIVE
student_batches.is_fees_due = 0
```

### Fee Due In Student List

The Students list supports filtering by:

```text
FEESDUE
```

The code checks the student's latest fee record and filters students whose latest fee `end_date` is before today.

### Fee Due WhatsApp Message

Fee due message text is generated in:

```php
StudentFee::generateFeeDueMessage()
```

It includes student name, previous module end date, due amount, currency, and payment reminder text.

## 14. Batches

### Purpose

Batches represent class groups. A batch has:

- Name
- Coach
- Country/countries
- Level
- Start date
- End date
- Number of sessions
- Weekly schedules
- Assigned students
- Zoom meeting details

Stored in:

```text
batchs
```

The table name is intentionally spelled `batchs` in this project.

### Routes

```php
Route::resource('batchs', BatchController::class);
Route::post('batchs/data', [BatchController::class, 'data'])->name('batchs.data');
Route::post('batchs/list', [BatchController::class, 'list'])->name('batchs.list');
Route::post('batchs/change-status', [BatchController::class, 'changeStatus'])->name('batchs.change.status');
Route::get('batch/get/coaches', [BatchController::class, 'getCoaches'])->name('batch.get.coaches');
Route::get('batch/get/students', [BatchController::class, 'getStudents'])->name('batch.get.students');
Route::post('batches/check-name', [BatchController::class, 'checkName'])->name('batchs.check.name');
Route::post('batches/attendance', [BatchController::class, 'batchAttendance'])->name('batchs.attendance');
Route::post('batchs/check/schedule', [BatchController::class, 'checkSchedule'])->name('batchs.check.schedule');
```

### Main Files

```text
app/Http/Controllers/Admin/BatchController.php
app/Models/Batch.php
resources/views/Admin/Batchs/*
```

### Important Batch Fields

```text
name
kids_zone_name
coach_id
level_id
country
status
version
parent_id
number_of_sessions
start_date
end_date
start_url
join_url
zoom_meeting_id
zoom_meeting_uuid
confirm_reassign
confirm_reassign_batch_id
```

### Batch Creation Flow

Main method:

```php
BatchController::store()
```

What it does:

1. Validates batch name as unique among non-inactive batches.
2. Validates coach and country.
3. Creates `Batch`.
4. Sets `version = 1`.
5. Sets `parent_id = batch id`.
6. Sets `status = ACTIVE`.
7. Creates Zoom meeting if coach Zoom credentials exist.
8. Creates weekly schedule rows in `batch_schedules`.

### Batch Schedule Creation During Batch Create

Request arrays:

```text
weekday[]
from_time[]
to_time[]
```

For each weekday/time row, system creates:

```text
batch_schedules.batch_id
batch_schedules.weekday
batch_schedules.from_time
batch_schedules.to_time
batch_schedules.status = ACTIVE
```

### Batch Update Flow

Main method:

```php
BatchController::update()
```

What it does:

- Updates batch data
- Recreates Zoom meeting if coach has Zoom credentials
- Updates existing schedule rows
- Creates new schedule rows
- Deletes removed schedule rows

### Batch Status Change

Main method:

```php
BatchController::changeStatus()
```

If a batch is marked `INACTIVE`, all active `student_batches` for that batch are also marked `INACTIVE`.

## 15. Batch Schedules

### Purpose

Batch schedules define the weekly class timing.

Stored in:

```text
batch_schedules
```

### Routes

```php
Route::post('batch_schedules/add-weekday', [BatchController::class, 'addWeekday'])->name('batch_schedules.add_weekday');
Route::post('batch_schedules/edit-weekday', [BatchController::class, 'editWeekDay'])->name('batch_schedules.edit_weekday');
Route::post('batch_schedules/delete-weekday', [BatchController::class, 'deleteWeekDay'])->name('batch_schedules.delete_weekday');

Route::prefix('batchs/{batch}')->name('batchs.')->group(function () {
    Route::resource('batch_schedules', BatchScheduleController::class);
    Route::post('batch_schedules/data', [BatchScheduleController::class, 'data'])->name('batch_schedules.data');
    Route::post('batch_schedules/change-status', [BatchScheduleController::class, 'changeStatus'])->name('batch_schedules.change.status');
});

Route::post('batch_schedules/list', [BatchScheduleController::class, 'list'])->name('batch_schedules.list');
```

### Main Files

```text
app/Http/Controllers/Admin/BatchScheduleController.php
app/Models/BatchSchedule.php
resources/views/Admin/BatchSchedules/*
resources/views/Admin/Batchs/scheduleform.blade.php
```

### Important Schedule Fields

```text
batch_id
weekday
from_time
to_time
status
start_date
end_date
confirm_reassign
```

### Schedule Conflict Check

Main method:

```php
BatchController::checkSchedule()
```

It checks if the selected coach already has the same weekday/time schedule in another active/non-inactive batch.

## 16. Assign Students To Batch

### Purpose

This is the real student-to-batch allocation flow.

Stored in:

```text
student_batches
```

### Routes

```php
Route::get('batchs/{batch}/assign/student', [BatchController::class, 'assignBatchToStudent'])->name('batchs.assign.student');
Route::post('batchs/{batch}/assign/student/save', [BatchController::class, 'saveAssignedStudent'])->name('batchs.assigned.student.save');
```

### Main Files

```text
app/Http/Controllers/Admin/BatchController.php
app/Models/StudentBatch.php
resources/views/Admin/Batchs/assignbatchform.blade.php
```

### Assign Form Logic

Main method:

```php
BatchController::assignBatchToStudent()
```

It loads:

- Active levels
- Active coaches
- Active students
- Existing assigned students
- Batch schedules

Student selection rules:

- Student must be `ACTIVE`.
- Student country must match batch country.
- Students already assigned to another active batch are excluded.
- Students already in the current batch remain selectable.

### Save Assignment Logic

Main method:

```php
BatchController::saveAssignedStudent()
```

Required fields:

```text
student_ids[]
coach_id
level_id
batch_id
start_date
end_date
number_of_sessions
```

What it updates on the batch:

```text
batchs.status = ACTIVE
batchs.level_id
batchs.coach_id
batchs.start_date
batchs.end_date
batchs.number_of_sessions
```

What it creates/updates in `student_batches`:

```text
student_id
batch_id
coach_id
level_id
status = ACTIVE
start_date
end_date
number_of_sessions
```

If a selected student already has an active record for this batch, it updates that record.

If a student is removed from the assignment form, their active `student_batches` record is marked:

```text
status = INACTIVE
end_date = today
end_time = current time
```

## 17. Reassign Batch

### Purpose

Reassigning a batch creates a new version of the batch while keeping history of the previous batch.

### Routes

```php
Route::get('batchs/{batch}/reassign/student', [BatchController::class, 'reassignBatchToStudentModal'])->name('batchs.reassign.student');
Route::post('batchs/{batch}/reassign/student/save', [BatchController::class, 'saveReassignedStudent'])->name('batchs.reassigned.student.save');
```

### Main Method

```php
BatchController::saveReassignedStudent()
```

What it does:

1. Copies the old batch.
2. Increments `version`.
3. Keeps same `parent_id`.
4. Sets `confirm_reassign = CANCEL`.
5. Stores old batch id in `confirm_reassign_batch_id`.
6. Creates new Zoom meeting if coach has Zoom credentials.
7. Copies active `student_batches` to the new batch.
8. Copies existing `batch_schedules` to the new batch.
9. Redirects to Assign Student screen for the new batch.

This is how the system handles batch history while allowing a changed future batch.

## 18. Change Batches / Change Class

### Purpose

Change Batches is used when a student needs to change class or move out from the current batch flow.

Stored in:

```text
changeclasses
```

### Routes

```php
Route::resource('changeclasses', ChangeclassController::class);
Route::post('changeclasses/data', [ChangeclassController::class, 'data'])->name('changeclasses.data');
Route::post('changeclasses/list', [ChangeclassController::class, 'list'])->name('changeclasses.list');
Route::get('changeclasses/excel/export', [ChangeclassController::class, 'export'])->name('changeclasses.export');
```

### How Change Class Is Created

Change class is triggered from:

```php
StudentController::changeStatus()
```

When student status is set to:

```text
CHANGECLASS
```

The system:

1. Finds the latest student batch.
2. Marks that student batch `INACTIVE`.
3. Sets its end date to today.
4. Creates a `changeclasses` record.
5. Stores current batch id, employee ids, remark, and fee data if available.

### Change Class Confirmation

Main method:

```php
ChangeclassController::update()
```

When `type = student-fee`:

1. Change class record is marked submitted.
2. Student fee record is created.
3. Student status becomes `ACTIVE`.

Important note:

The change class flow records the payment/status side. Actual new batch membership still depends on creating/updating `student_batches` through the Batch assignment flow.

## 19. Cover Up Classes

### Purpose

Cover Up Classes are used when the original coach is unavailable and another coach covers the class.

Stored in:

```text
coverupclasses
```

### Routes

```php
Route::resource('coverupclasses', CoverupclassController::class);
Route::post('coverupclasses/data', [CoverupclassController::class, 'data'])->name('coverupclasses.data');
Route::post('coverupclasses/list', [CoverupclassController::class, 'list'])->name('coverupclasses.list');
Route::post('coverupclasses/get/coach', [CoverupclassController::class, 'getCoach'])->name('coverupclasses.change_coach.get.coach');
Route::post('coverupclasses/change/coach', [CoverupclassController::class, 'changeCoach'])->name('coverupclasses.change_coach');
```

### Main Files

```text
app/Http/Controllers/Admin/CoverupclassController.php
app/Http/Controllers/Admin/BatchController.php
app/Http/Controllers/Admin/LeaveRequestController.php
app/Http/Controllers/Admin/DashboardController.php
app/Console/Commands/CancelDelayBatch.php
app/Models/Coverupclass.php
resources/views/Admin/Coverupclass/*
```

### Cover Up Class Fields

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

### How Cover Up Classes Are Created

Cover-up records can be created from:

- Batch-related coach replacement logic
- Leave request approval logic

Important files:

```text
app/Http/Controllers/Admin/BatchController.php
app/Http/Controllers/Admin/LeaveRequestController.php
```

### Flow 1: Same-Day Batch Coach Replacement

Main methods:

```php
BatchController::changeCoaches()
BatchController::changeCoach()
```

This is used when the admin wants to replace the coach for today's batch.

The system first checks:

1. Batch exists.
2. Batch has a schedule for today's weekday.
3. Coach change is still allowed.
4. Current time is not more than 10 minutes after the batch start time.

Important validation:

```text
Coach change is not allowed after 10 minutes from batch start.
```

Available cover coaches are selected based on:

- Coach is `ACTIVE`.
- Coach has availability for today's weekday.
- Coach availability period covers the batch start time.
- Coach is not already teaching another batch at that time.
- Coach is not already assigned to another cover-up class today.

When admin selects a replacement coach, the system creates:

```text
coverupclasses.batch_id
coverupclasses.batchschedule_id
coverupclasses.old_coach_id
coverupclasses.new_coach_id
coverupclasses.date
```

Then, if the new coach has Zoom credentials, the system creates a cover-up Zoom meeting and saves:

```text
coverupclasses.start_url
coverupclasses.join_url
coverupclasses.zoom_meeting_id
coverupclasses.zoom_meeting_uuid
```

### Flow 2: Cover Coach From Leave Approval

Main method:

```php
LeaveRequestController::changeStatus()
```

When a coach leave request is approved, the system can receive `affectedData`.

For each affected batch/schedule:

- If `coach_id` is selected, a cover-up class is created.
- Original batch coach becomes `old_coach_id`.
- Selected replacement coach becomes `new_coach_id`.
- Cover-up date is the leave date.
- Cover-up Zoom meeting is created if the replacement coach has Zoom credentials.

Important behavior:

```text
If a replacement coach is selected, the class is covered.
If no replacement coach is selected, the existing cancelBatchLogic call is currently commented out in this controller.
```

So for leave approval without replacement coach, cancellation is mainly handled later by attendance/auto-cancel logic rather than immediately inside `LeaveRequestController::changeStatus()`.

### How Available Cover Coaches Are Found

Main methods:

```php
LeaveRequestController::getAvailableCoaches()
CoverupclassController::getAvailableCoaches()
BatchController::changeCoaches()
```

The system checks:

- Coach is active.
- Coach has availability for the date and time.
- Coach is not on approved leave.
- Coach does not already have another overlapping batch schedule.
- Coach does not already have an active demo at that time.
- Coach does not already have another cover-up assignment at that time/date.
- In leave flow, coach country compatibility is also considered.

This prevents assigning a coach who is already busy or unavailable.

### Change Cover Coach

Main methods:

```php
CoverupclassController::getCoach()
CoverupclassController::changeCoach()
```

This is used from Operations > Cover Up Classes.

Admin can change the replacement coach for an existing cover-up record.

The system finds available coaches based on:

- Coach availability
- Leave status
- Existing batch schedule
- Existing demo assignment

Then admin can replace `new_coach_id`.

### How Cover-Up Appears In Coach Live Class / Schedule

Main method:

```php
DashboardController::getSchedule()
```

On the coach dashboard schedule/live class list, the system checks:

```php
Coverupclass::where('batchschedule_id', $schedule->id)
    ->where('new_coach_id', $coachId)
    ->where('date', $date)
    ->first();
```

If cover-up exists:

```text
status = COVER UP
type = COVERUP
coverup = Yes
start_url = coverupclasses.start_url
```

If no cover-up exists:

```text
status = normal batch schedule / latest attendance status
type = BATCH
coverup = No
start_url = batchs.start_url
```

Important behavior:

If the original coach is on approved leave, the normal batch is skipped for that coach unless a cover-up class exists.

For the replacement coach, the cover-up batch appears in the schedule with the cover-up Zoom URL.

### Live Class Status For Cover-Up Classes

Live class/attendance status is controlled by coach attendance.

Main method:

```php
DashboardController::batchAttendance()
```

The request accepts:

```text
status = COMPLETED
status = CANCELLED
type = BATCH / COVERUP
```

When the cover coach takes the class and submits attendance as `COMPLETED`:

```text
coach_attendances.status = COMPLETED
student_attendances.status = PRESENT / ABSENT
coach_attendances.type = COVERUP
student_attendances.type = COVERUP
```

The live class/schedule status then shows the latest coach attendance status.

When the class is marked `CANCELLED`:

```text
coach_attendances.status = CANCELLED
student_attendances.status = CANCELLED
```

The system also extends the batch and fee dates to compensate for the missed class.

### What Happens If No Coach Takes The Cover Class

Auto-cancel logic is handled by:

```text
app/Console/Commands/CancelDelayBatch.php
```

Scheduled in:

```php
$schedule->command('cancel:delay-batch')->everyTenMinutes();
```

What the command does:

1. Finds active batches scheduled for today.
2. Checks each schedule after the batch start time plus cutoff.
3. Cutoff currently uses 8 minutes in code.
4. If a cover-up record exists for that batch schedule/date, the command skips auto-cancel.
5. If no cover-up exists and attendance was not marked, it cancels the class.

Important skip rule:

```text
If coverupclasses exists for the schedule/date, auto-cancel is skipped.
```

If no cover-up exists and attendance is missing:

```text
coach_attendances.status = CANCELLED
student_attendances.status = CANCELLED
student_attendances.remark = Batch Cancelled
```

### How Batch Day Is Extended Weekly-Wise

When a batch class is cancelled, the system extends the batch end date to the next valid scheduled weekday.

Used in:

```php
DashboardController::batchAttendance()
CancelDelayBatch::handle()
```

The logic:

1. Read all weekdays from `batch_schedules` for the batch.
2. Take the current `batchs.end_date`.
3. Find the next schedule weekday after that end date.
4. If no later weekday exists in the same week, roll over to the first scheduled weekday in the next week.
5. Save the new date into `batchs.end_date`.
6. Update active `student_batches.end_date`.
7. Update latest `student_fees.end_date` to the next scheduled weekday too.

Example:

```text
Batch schedule: Monday, Wednesday
Current batch end date: Wednesday, 20-May-2026
Cancelled class: any class in this batch
New batch end date: Monday, 25-May-2026
```

Another example:

```text
Batch schedule: Tuesday, Friday
Current batch end date: Tuesday, 19-May-2026
Next scheduled weekday after Tuesday: Friday
New batch end date: Friday, 22-May-2026
```

Database effect:

```text
batchs.end_date = next scheduled class date
student_batches.end_date = batchs.end_date
student_fees.end_date = next scheduled class date
```

### Cover-Up And Auto-Cancel Difference

```text
Cover-up exists:
    Replacement coach is expected to take class.
    Auto-cancel command skips that schedule.
    Cover coach attendance controls COMPLETED/CANCELLED status.

No cover-up exists:
    Original batch must be marked by attendance.
    If attendance is missing after cutoff, command marks class CANCELLED.
    Batch/student fee dates are extended to the next scheduled weekday.
```

### Important Risk / Improvement Note

The auto-cancel command skips cancellation when a cover-up record exists, even if the cover coach also does not mark attendance.

Current behavior:

```text
coverupclasses record exists -> cancel:delay-batch skips that schedule
```

Future improvement:

```text
If cover-up exists but cover coach has not submitted attendance after cutoff,
auto-cancel should check cover-up attendance and either notify admin or cancel/extend the class.
```

## 20. Key Data Relationships

### Lead To Demo Lead

```text
demoleadenquiries.user_id -> users.id
demoleads.user_id -> users.id
```

### Demo Lead To Student

```text
demoleads.user_id -> users.id
students.user_id -> users.id
new_enrollments.student_id -> students.id
```

### Student To Batch

```text
students.id -> student_batches.student_id
batchs.id -> student_batches.batch_id
coachs.id -> student_batches.coach_id
levels.id -> student_batches.level_id
```

### Batch To Schedule

```text
batchs.id -> batch_schedules.batch_id
```

### Student To Fees

```text
students.id -> student_fees.student_id
```

### Cover Up Class

```text
coverupclasses.batch_id -> batchs.id
coverupclasses.batchschedule_id -> batch_schedules.id
coverupclasses.old_coach_id -> coachs.id
coverupclasses.new_coach_id -> coachs.id
```

## 21. Client Workflow Guide

### A. Handle New Trial Lead

1. Go to Operations > Lead Enquiries.
2. Review lead details.
3. If valid, click convert.
4. Confirm date/time/timezone details.
5. Save.
6. Lead moves into Demo Leads.

### B. Schedule Demo

1. Go to Operations > Demo Leads.
2. Open Demo Sessions for the lead.
3. Select coach, date, time, slot, and level.
4. Save.
5. System creates Zoom meeting if coach Zoom credentials are configured.
6. Demo lead status becomes `SCHEDULED`.

### C. Convert Demo Lead To Student

1. Go to Operations > Demo Leads.
2. Click Convert for the lead.
3. Enter student ID, level, fees, currency, employee, and remark.
4. Save.
5. Student is created as `INACTIVE`.
6. New Enrollment is created.

### D. Confirm New Enrollment

1. Go to Operations > New Enrollments.
2. Open the new enrollment.
3. Confirm fee details.
4. Submit as student fee.
5. Student becomes `ACTIVE`.
6. Student fee record is created.

### E. Assign Student To Batch

1. Go to Operations > Batches.
2. Create or open a batch.
3. Click Assign Student.
4. Select active students.
5. Select coach, level, start date, end date, and session count.
6. Save.
7. System creates/updates `student_batches`.

### F. Change Student Batch

1. Go to Operations > Students.
2. Change student status to `CHANGECLASS`.
3. System ends current active student batch.
4. Change Class record is created.
5. Process payment/status from Change Batches.
6. Assign student to the new batch from Batches > Assign Student.

### G. Add Cover Up Class

1. Cover-up may be created during leave/batch replacement flow.
2. Go to Operations > Cover Up Classes.
3. Review old coach, new coach, batch, schedule, and date.
4. Change cover coach if needed.

## 22. Developer Change Guide

### Change Lead Form Or Lead Creation

Check:

```text
app/Http/Controllers/Frontend/HomeController.php
app/Models/DemoLeadEnquiry.php
resources/views/frontend/*
```

### Change Lead Enquiry Conversion

Check:

```text
app/Http/Controllers/Admin/LeadEnquiryController.php
resources/views/Admin/LeadEnquiries/*
app/Models/DemoLeadEnquiry.php
app/Models/DemoLead.php
```

### Change Demo Lead Listing Or Conversion

Check:

```text
app/Http/Controllers/Admin/DemoLeadController.php
resources/views/Admin/DemoLeads/*
app/Models/DemoLead.php
app/Models/Student.php
app/Models/NewEnrollment.php
```

### Change Demo Scheduling

Check:

```text
app/Http/Controllers/Admin/DemoSessionsController.php
resources/views/Admin/DemoSessions/*
app/Models/DemoSession.php
app/Services/ZoomMeetingService.php
```

### Change Enrollment Confirmation

Check:

```text
app/Http/Controllers/Admin/NewEnrollmentController.php
resources/views/Admin/NewEnrollments/*
app/Models/NewEnrollment.php
app/Models/StudentFee.php
```

### Change Student Status Logic

Check:

```text
app/Http/Controllers/Admin/StudentController.php
app/Models/Student.php
app/Models/StudentBatch.php
app/Models/StudentStatus.php
app/Models/Changeclass.php
```

### Change Batch Creation Or Assignment

Check:

```text
app/Http/Controllers/Admin/BatchController.php
resources/views/Admin/Batchs/*
app/Models/Batch.php
app/Models/BatchSchedule.php
app/Models/StudentBatch.php
```

### Change Cover Up Class Logic

Check:

```text
app/Http/Controllers/Admin/CoverupclassController.php
app/Http/Controllers/Admin/LeaveRequestController.php
resources/views/Admin/Coverupclass/*
app/Models/Coverupclass.php
```

## 23. Important Technical Notes

### Real Batch Assignment

Use `student_batches` to know which batch a student is actually assigned to.

Do not rely only on:

```text
new_enrollments.batch_id
```

### Student Must Be Active Before Batch Assignment

The batch assignment form loads:

```text
Student::where('status', 'ACTIVE')
```

So a converted student with status `INACTIVE` will not appear for batch assignment until enrollment/fee confirmation activates the student.

### Country Matching

Batch assignment filters students by:

```text
student.country in batch.country
```

If a student is not visible in the assignment form, check:

- Student status
- Student country
- Batch country
- Existing active batch assignment

### Batch Reassignment Creates New Batch Version

Reassigning does not simply edit the same batch. It clones the batch and increments:

```text
version
```

This preserves previous batch history.

### Zoom Dependency

Demo sessions and batches can create Zoom meetings using coach Zoom credentials.

Check coach fields:

```text
zoom_id
zoom_api_key
zoom_client_secret
zoom_user_id
```

If Zoom credentials are missing, demo session creation can fail.

## 24. Current Functional Gap / Improvement Opportunity

During demo lead conversion, the system collects batch-related fields and stores them in `new_enrollments`.

However, it does not create `student_batches`.

Current behavior:

```text
Demo Lead Convert -> Student created
Demo Lead Convert -> New Enrollment created
Demo Lead Convert -> student_batches not created
```

Better future behavior could be:

```text
After enrollment is confirmed and student becomes ACTIVE,
create or update student_batches automatically if batch_id/start_date/end_date are present.
```

This would reduce manual work and avoid confusion between enrollment batch and actual assigned batch.

## 25. Summary

The Operations module is the core business pipeline of ArcherKids.

- Website Enquiries store general contact requests.
- Lead Enquiries store trial-class enquiries.
- Lead Enquiries convert into Demo Leads.
- Demo Leads get Demo Sessions with coaches.
- Demo Leads convert into Students.
- Conversion creates New Enrollment records.
- New Enrollment confirmation activates students and creates fee records.
- Batches are created with coach, country, level, Zoom, and weekly schedules.
- Real student batch assignment happens through `student_batches`.
- Change Batches handles student class change requests.
- Cover Up Classes handle temporary coach replacement.

For any future developer, the most important tables to understand are:

```text
demoleadenquiries
demoleads
demo_sessions
students
new_enrollments
student_fees
batchs
batch_schedules
student_batches
```
