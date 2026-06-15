# Flow Gaps and Optimization Plan

## Purpose

This document lists the main operational gaps found in the ArcherKids ERP flow and gives practical improvement solutions.

The goal is not only to document bugs. The goal is to make the ERP easier to operate, reduce repeated manual work, reduce mismatched data, and make the handover cleaner for future developers.

Project path:

```text
C:\xampp\htdocs\archerkids
```

Related docs:

```text
docs/operations/OPERATION-FLOW-README.md
docs/operations/08-BATCHES.md
docs/users/USER-MODULE-README.md
docs/dashboards/README.md
```

## Executive Summary

The ERP is functional, but several flows depend on repeated manual selections and duplicated state. The biggest improvement areas are:

```text
1. Country/location is duplicated across many modules.
2. Student-to-batch assignment is not automatically completed after enrollment.
3. Student status, fee status, and batch status are tightly linked but not controlled by one workflow.
4. Coach availability, batch schedule, leave, cover-up, and live attendance are separate checks instead of one scheduling engine.
5. Staff access is country-based, but country is read from different tables in different modules.
6. Payments and FEESDUE recovery recreate/restore batch membership in multiple places.
7. Important operational actions lack a single audit trail.
8. Credentials and sensitive values exist inside business records.
```

## 1. Country / Location Source Is Disjoint

### Current Finding

Country is stored in many places:

```text
students.country
students.fees_country
coachs.country
batchs.country
roles.countries
demoleads.country
demoleadenquiries.country
holidays.country
masterclasses.country
tournaments.country
```

This means the same location is selected multiple times across the flow.

Example:

```text
Student has country = UK
Coach has country = UK
Batch must still be manually created with country = UK
Student assignment then checks student.country against batch.country
```

If batch country is missing or wrong, the student may not appear for assignment even though the student and coach are valid.

### Where It Appears

Batch creation manually saves country:

```text
app/Http/Controllers/Admin/BatchController.php
batchs.country = request country
```

Student assignment filters by batch country:

```text
Student::where('status', 'ACTIVE')
    ->whereIn('country', $batch->country ?? [])
```

Coach listing filters by coach country:

```text
coachs.country
```

Student listing filters by student country:

```text
students.country
```

Staff access is based on role country:

```text
roles.countries
```

### Business Risk

- Staff may see students but not matching batches.
- Student may not appear while assigning to a batch.
- Coach may be assigned to a country but batch has another country.
- Reports may differ depending on whether they filter by student country, batch country, or role country.
- Country spelling mismatch can break filters.
- More manual work during batch, tournament, masterclass, holiday, and reporting setup.

### Recommended Solution

Create a clear country ownership model:

```text
Student actual country:
  source of truth = students.country

Coach working countries:
  source of truth = coachs.country

Staff access countries:
  source of truth = roles.countries

Batch country:
  derived from selected coach/student audience, not freely re-entered

Fees country:
  only separate if billing country can differ from student country

Event targeting country:
  keep on holidays/masterclasses/tournaments because those are broadcast/targeting modules
```

### Best Future Flow

```text
1. Assign country once on student profile.
2. Assign working countries once on coach profile.
3. During batch creation, select coach.
4. System auto-fills allowed batch countries from coach.country.
5. During student assignment, show students where:
   students.country is in batch/coach allowed countries.
6. If admin tries mismatch, show warning or block:
   "Student country does not match this batch/coach country."
```

### Implementation Plan

Phase 1:

```text
Add validation only.
Do not remove existing columns.
On batch create/update, validate batch.country is subset of coach.country.
On assign student, validate each student.country is in batch.country.
```

Phase 2:

```text
Auto-populate batch country from selected coach.
Make batch country read-only by default.
Allow manual override only for SuperAdmin with reason.
```

Phase 3:

```text
Normalize country values using a countries master table or enum.
Replace hardcoded country dropdowns with one central source.
```

## 2. Staff Country Access Depends On Duplicated Country Values

### Current Finding

Staff users manage data based on their role country scope:

```text
roles.countries
```

But each module filters different tables:

```text
Students screen -> students.country
Coaches screen -> coachs.country
Batches screen -> batchs.country
Demo Leads -> demoleads.country
New Enrollments -> student.country through relation
Reports -> mixed student/batch/demo country
```

### Business Risk

A staff user assigned to USA may correctly see USA students, but a batch wrongly tagged as Canada may be invisible or inconsistent.

### Recommended Solution

Create a standard `CountryScope` helper/service and use it everywhere.

Example:

```text
CountryScope::forUser(auth()->user())
CountryScope::applyToStudents($query)
CountryScope::applyToCoaches($query)
CountryScope::applyToBatches($query)
CountryScope::canManageStudent($user, $student)
CountryScope::canManageCoach($user, $coach)
```

This keeps all country access rules in one place.

### Implementation Plan

```text
1. Create app/Services/CountryScopeService.php.
2. Move repeated role-country parsing into this service.
3. Replace controller-specific country parsing gradually.
4. Add tests for SuperAdmin, USA staff, UK staff, multi-country staff.
```

## 3. Batch Assignment Is Separate From Enrollment

### Current Finding

When a demo lead converts to student:

```text
Student is created.
NewEnrollment is created.
batch_id may be stored in new_enrollments.
```

But actual class access depends on:

```text
student_batches
```

So `new_enrollments.batch_id` does not mean the student is actually assigned to a live batch.

### Business Risk

- Admin thinks student is assigned because enrollment has batch.
- Student cannot see class because `student_batches` row is missing.
- Reports and attendance do not include the student.
- Extra manual step is required after enrollment.

### Recommended Solution

After payment/enrollment confirmation, create or activate `student_batches` automatically.

### Best Future Flow

```text
Demo Lead Converted
        |
Student Created as INACTIVE
        |
New Enrollment Created
        |
Payment/Fee Confirmed
        |
Student becomes ACTIVE
        |
student_batches row is created automatically
        |
Student dashboard class becomes visible
```

### Implementation Plan

```text
1. Keep conversion creating student + enrollment only.
2. On fee activation, check if new_enrollments.batch_id exists.
3. If yes, create StudentBatch with:
   student_id
   batch_id
   coach_id from batch
   level_id from batch/student
   start_date
   end_date
   status ACTIVE
4. Add duplicate protection so one active student-batch row is not created twice.
```

## 4. Student Status, Fee Status, and Batch Status Are Too Coupled

### Current Finding

Student status is affected by fee and batch logic:

```text
students.status = ACTIVE
students.status = FEESDUE
students.status = INACTIVE
students.status = STANDBY
students.status = CHANGECLASS
```

Fee update can change student status.

Fee due can inactivate student batches.

Payment can reactivate the student and recreate batch membership.

### Business Risk

- One manual status change can accidentally remove class access.
- Fee recovery logic exists in more than one place.
- Hard to know why a student became inactive.
- Recreating batch membership after payment is fragile.

### Recommended Solution

Separate business states into clearer concepts:

```text
Student account status:
  ACTIVE / INACTIVE

Enrollment/payment status:
  PAID / FEES_DUE / TRIAL / PENDING

Class membership status:
  ACTIVE / PAUSED / ENDED / CHANGE_REQUESTED
```

Do not use one `students.status` field to represent every operational state.

### Short-Term Solution

Keep current statuses, but add a single service:

```text
StudentLifecycleService
```

Responsibilities:

```text
activateAfterPayment()
markFeesDue()
pauseForChangeClass()
restoreAfterFeesPayment()
inactivateStudent()
```

All controllers should call this service instead of duplicating status logic.

## 5. Coach Availability Is Not A Single Scheduling Engine

### Current Finding

Coach availability is checked across multiple places:

```text
coach_availabilities
coach_availability_periods
batch_schedules
demo_sessions
leave_requests
coverupclasses
coach_attendances
```

Each module checks availability differently.

### Business Risk

- A coach may look available in one flow but unavailable in another.
- Cover-up assignment can miss demo conflicts.
- Leave can hide normal classes but cover-up behavior has separate logic.
- Calendar, availability grid, and scheduling forms may not match exactly.

### Recommended Solution

Create one scheduling/availability service:

```text
CoachScheduleService
```

It should answer:

```text
isCoachWorkingAt(coach, date, from, to)
isCoachAvailableFor(coach, date, from, to)
getConflicts(coach, date, from, to)
getAvailableCoaches(date, from, to, country)
buildCoachCalendar(coach, dateRange)
```

Conflict types:

```text
Regular batch
Demo
Cover-up class
Leave
Masterclass
Holiday
Unavailable period
```

### Implementation Plan

```text
1. Start by moving repeated logic from DashboardController and BatchController.
2. Use the service for coach availability grid.
3. Use the same service for demo scheduling and cover-up coach selection.
4. Add conflict reason output so admin can see why a coach is unavailable.
```

## 6. Cover-Up Class Auto-Cancel Gap

### Current Finding

The operation flow already documents a risk:

```text
If cover-up exists, auto-cancel may skip normal cancellation.
But if cover coach also does not mark attendance, the class can remain unresolved.
```

### Business Risk

- Class was not actually conducted.
- Student/batch end date may not extend.
- Attendance records may remain incomplete.
- Admin may assume cover-up happened because a cover-up record exists.

### Recommended Solution

Cover-up should have its own lifecycle:

```text
ASSIGNED
STARTED
COMPLETED
CANCELLED
EXPIRED_NOT_MARKED
```

If cover coach does not mark attendance after cutoff:

```text
1. Mark cover-up as EXPIRED_NOT_MARKED.
2. Mark coach/student attendance CANCELLED or NOTMARKED based on business rule.
3. Extend batch/student fee end dates if class did not happen.
4. Notify admin.
```

## 7. Payment / FEESDUE Recovery Logic Is Duplicated

### Current Finding

Fee payment and HDFC/Razorpay-related flows contain logic to:

```text
Set student fee active
Set student active
Restore batch membership
Create/recreate student_batches
```

Similar logic appears in student fee handling and payment controller areas.

### Business Risk

- Payment through one path may restore student differently than another path.
- Bug fixes need to be made in multiple places.
- FEESDUE recovery can create unexpected student batch rows.

### Recommended Solution

Create:

```text
PaymentActivationService
```

Responsibilities:

```text
verifyPayment()
activateFee()
activateStudent()
restoreOrCreateStudentBatch()
recordPaymentAudit()
```

All payment gateways and manual fee entry should call the same service.

## 8. Country Values Are Hardcoded In Many Forms

### Current Finding

Country dropdown values are hardcoded in many blade files:

```text
Students
Coaches
Batches
Roles
Holidays
Masterclasses
Tournaments
Lead forms
Reports
```

### Business Risk

- Spelling mismatch.
- New country must be added in many files.
- Some files use `NEWZEALAND`, others may display `NEW ZEALAND`.
- Some forms reference `$model->countries` instead of `$model->country`, which can cause selected values not to display.

### Recommended Solution

Create a central country master:

```text
countries
id
code
name
slug
phone_code
timezone_group
currency
status
```

Then use:

```text
Country::active()->get()
```

for every dropdown.

### Short-Term Solution

If a table is too much right now, create one config file:

```text
config/countries.php
```

and replace hardcoded blade options with this config.

## 9. Timezone Is Not Fully Centralized

### Current Finding

Student timezone is captured, country timezone is used, and helper functions convert class times. Demo lead flow also stores timezone.

But scheduling is largely stored in IST and converted at display time.

### Business Risk

- Parent/student may see different local class date if timezone conversion is inconsistent.
- Country and timezone mismatch can create wrong reminders.
- DST countries need careful handling.

### Recommended Solution

Store:

```text
scheduled_at_ist
student_timezone
display_time_local
```

Use a single helper/service for all conversions:

```text
TimezoneService::toStudentLocal($date, $time, $student)
TimezoneService::toCoachLocal($date, $time, $coach)
TimezoneService::toCountryLocal($date, $time, $country)
```

## 10. Audit Trail Is Not Complete Enough

### Current Finding

Many models have:

```text
created_by
updated_by
```

But important changes need richer history:

```text
Student status changed
Fee due triggered
Student batch ended/reactivated
Coach changed
Cover-up assigned
Cover-up expired
Batch cancelled
Country overridden
Payment verified
```

### Business Risk

When client asks "why did this student stop seeing class?", developer/admin must inspect multiple tables manually.

### Recommended Solution

Create a shared audit log:

```text
activity_logs
id
actor_user_id
subject_type
subject_id
event
old_values
new_values
reason
created_at
```

Recommended events:

```text
student.status_changed
student.fees_due
student.payment_activated
student.batch_assigned
student.batch_removed
batch.coach_changed
batch.cancelled
coverup.assigned
coverup.completed
coverup.expired
country.override
```

## 11. Status Names Need Standardization

### Current Finding

Statuses are mixed across modules:

```text
ACTIVE
INACTIVE
STANDBY
FEESDUE
CHANGECLASS
ROWLEAD
DEMO DONE
SCHEDULED
RESCHEDULED
COMPLETED
CANCELLED
Student Absent
NOTMARKED
COVER UP
```

Some statuses use spaces, some uppercase, some mixed case.

### Business Risk

- Filtering becomes inconsistent.
- UI labels do not always match database values.
- New developers can accidentally use wrong status string.

### Recommended Solution

Create enums/constants:

```text
app/Enums/StudentStatus.php
app/Enums/DemoLeadStatus.php
app/Enums/AttendanceStatus.php
app/Enums/BatchStatus.php
app/Enums/CoverupStatus.php
```

Short-term:

```text
Create constants classes if PHP enum migration is risky.
Replace raw status strings slowly.
```

## 12. Credentials Are Stored In Operational Records

### Current Finding

Sensitive values are present in business records:

```text
coachs.zoom_api_key
coachs.zoom_client_secret
coachs.portal_password
coachs.decrypt_password
students.portal_password
employees.decrypt_password
```

### Business Risk

- Risk during repo push.
- Risk during client handover.
- Risk in exports/screenshots.
- Plain password storage makes security hard.

### Recommended Solution

```text
1. Remove plain password storage where possible.
2. Use encrypted casts for secrets that must remain.
3. Store Zoom integration credentials in environment/secrets manager, not per record unless truly necessary.
4. Mask sensitive fields in UI.
5. Rotate credentials before repo handover.
```

## 13. Reports Depend On Multiple Sources Of Truth

### Current Finding

Reports read from:

```text
students
student_batches
student_fees
batchs
coach_attendances
student_attendances
demo_sessions
coverupclasses
```

Because country/status/batch membership can be duplicated, reports may disagree with operational screens.

### Recommended Solution

Create reporting views/query services:

```text
StudentCurrentEnrollmentView
CoachScheduleView
BatchAttendanceSummaryView
FeeDueView
```

This can be database views or Laravel query services.

Purpose:

```text
One agreed query for current student state.
One agreed query for current batch membership.
One agreed query for fee due.
```

## Recommended Priority

### Phase 1: Low-Risk Cleanup

```text
1. Add country compatibility validation.
2. Add central country config or countries table.
3. Add CountryScopeService for staff role-country filtering.
4. Add status constants.
5. Mask sensitive fields in views/exports.
```

### Phase 2: Flow Stabilization

```text
1. Add StudentLifecycleService.
2. Add PaymentActivationService.
3. Auto-create student_batches after confirmed enrollment/payment.
4. Add audit log for status, fee, batch, and country changes.
```

### Phase 3: Scheduling Optimization

```text
1. Add CoachScheduleService.
2. Use it in demo scheduling, cover-up assignment, coach calendar, and availability grid.
3. Add cover-up lifecycle statuses.
4. Add admin alerts for unresolved classes.
```

## Ideal Future Flow

```text
Lead captures country/timezone once
        |
Demo lead inherits country/timezone
        |
Student inherits country/timezone after conversion
        |
Payment/enrollment confirms student
        |
Batch assignment validates against student country and coach working countries
        |
Coach schedule service checks availability/conflicts
        |
Student dashboard uses student_batches as source of class access
        |
Attendance/payment/status changes go through lifecycle services
        |
Reports read from normalized query services
```

## Client-Friendly Summary

The ERP currently works, but some information is entered more than once. The biggest example is country/location. A student, coach, and batch can each have their own country value, so if any one value is wrong, the system can hide records or create confusion.

The best improvement is to make student country and coach working country the main source, then let other flows inherit or validate from those values. Along with that, payments, student status, batch assignment, and coach availability should use shared services so every module follows the same rule.

This will reduce manual work, prevent mismatched data, and make the ERP easier for staff and future developers to manage.

