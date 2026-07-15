# ArcherKids Project Context

Use this file as the recovery handoff if a Codex/chat thread gets corrupted. If this file conflicts with the current repository, trust the repository and verify from code.

## Project

ArcherKids / Archer Chess Academy is a Laravel ERP/CRM for an online chess academy.

Main flow:

```text
Website enquiry / trial booking
-> demo lead enquiry
-> demo lead
-> demo session
-> student conversion
-> batch assignment
-> attendance / fees / reports / certificates
```

Important route files:

```text
routes/frontend.php
routes/backend.php
routes/api.php
```

Important table names are non-standard and should not be casually renamed:

```text
coachs
batchs
demoleads
demoleadenquiries
leaverequests
paymentlevels
```

## Branch And EC2 Setup

There are two EC2 machines:

```text
Production EC2:
- Branch: main
- Project path: /var/www/archerkids/
- Purpose: live production

Development EC2:
- Branch: development_1
- Project path: /var/www/archerkids/
- Purpose: testing/staging
```

Workflow:

```text
development_1 = working/testing branch
main = production branch
```

Only tested changes should move from `development_1` to `main`.

Current known live/main change:

```text
/paynow page is live on main.
```

Most fee-due, mid-joiner, report, and coach availability work is on `development_1`.

## Pay Now Page

The `/paynow` page was added for country-wise Razorpay payment links.

Verified code:

```text
routes/frontend.php
app/Http/Controllers/Frontend/HomeController.php
resources/views/Frontend/paynow.blade.php
```

This change is already considered live on `main`.

## Development Cron Setup

Development EC2 does not run the full Laravel scheduler.

Reason: the scheduler contains jobs that may hit live/external systems such as Razorpay, Zoom recordings, reminders, and other production-like integrations.

Development EC2 currently uses only a direct crontab entry under `www-data`:

```cron
*/30 * * * * cd /var/www/archerkids && php artisan fees:mark-due-by-schedule --buffer=15 >> storage/logs/fee-due-cron.log 2>&1
```

The old three region-wise fee-due crons are commented out in the development crontab:

```cron
#30 0 * * * cd /var/www/archerkids && php artisan check:fess-due-students >> storage/logs/fee-due-cron.log 2>&1
#5 21 * * * cd /var/www/archerkids && php artisan set:fess-due-in-usa-canada >> storage/logs/fee-due-cron.log 2>&1
#0 12 * * * cd /var/www/archerkids && php artisan set:fess-due-in-uk >> storage/logs/fee-due-cron.log 2>&1
```

Useful EC2 checks:

```bash
sudo crontab -u www-data -l
crontab -l
pgrep -af "artisan schedule:run|artisan schedule:work"
tail -100 /var/www/archerkids/storage/logs/fee-due-cron.log
```

Important: `app/Console/Kernel.php` may still contain old scheduler entries, but they are not active on development unless Laravel scheduler is running.

## Fee-Due Cron Merger

Old setup:

```text
check:fess-due-students
set:fess-due-in-usa-canada
set:fess-due-in-uk
```

These were separate region-wise crons with different schedules.

New development work:

```text
fees:mark-due-by-schedule
```

File:

```text
app/Console/Commands/MarkFeesDueBySchedule.php
```

Purpose:

```text
- Single unified fee-due cron for all regions.
- Runs every 30 minutes on development EC2 via crontab.
- Uses schedule/end-date based marking.
- Supports backfill.
- Uses a lock to prevent overlapping runs.
- Supports dry-run.
- Uses buffer/no-class cutoff behavior.
- Schedule lookup uses `StudentBatch::eligibleOn(fee_end_date)`, so mid-joiner/date-window rows do not incorrectly delay a no-class fee due backfill.
- When marking a student fee due, the cron inactivates all active fee rows for that student with `end_date <= fee_end_date`, so duplicate/stale active fee rows do not remain active.
- At the start of each run, invalid active fee rows are cleaned:
  - expired active fee rows are inactivated when the student is already `FEESDUE`;
  - duplicate/overlapping active fee rows are inactivated when the student has a later fee row by `student_fees.id`, including future end-date conflicts.
  The latest fee row by `student_fees.id` is the source of truth and is left for the normal schedule/no-class cutoff logic so the student can still be marked `FEESDUE` correctly.
  Cleanup is non-blocking: if invalid active fee cleanup fails, the error is logged and normal fee-due marking continues.
```

Old region-wise commands were also hardened so one bad student/batch should not break the full command.

## Mid-Joiner Rule

Business rule:

```text
A student can be assigned to a batch before joining,
but must not be counted or allowed to join until the actual start date.
```

## Demo Lead / New Enrollment Batch Selection

Demo lead conversion batch dropdown and New Enrollment batch dropdown must be constrained to the student/demo lead country. Valid statuses for these selection lists are:

```text
ACTIVE
STANDBY
UPCOMING
```

When New Enrollment confirmation selects an `UPCOMING` batch, the flow redirects to the batch assign-student page with the student preselected. On that redirected raw-batch assignment page, these fields must be intentionally blank so the admin fills the activation details:

```text
Level
Number of Sessions
Start Date
End Date
```

Eligibility rule:

```text
student_batches.status = ACTIVE
student_batches.start_date <= class/report date
student_batches.end_date >= class/report date
```

Code helper:

```text
app/Models/StudentBatch.php
scopeEligibleOn($query, $date)
```

Expected behavior before joining date:

```text
- Student should not see/join that batch class from student dashboard.
- Student should not appear in coach/admin attendance list.
- Student attendance should not be created for that session.
- Coach report should not count that student for that session.
```

Expected behavior on/after joining date:

```text
- Student can join/take class.
- Attendance can be created.
- Coach report counts the student normally.
```

Important: these changes are forward-looking only. Old `coach_attendances` and `student_attendances` are not rewritten automatically.

## Coach Report Counting

Coach report "Total Batch Student" is counted from actual saved `student_attendances` rows, not from current batch strength.

Code areas:

```text
app/Http/Controllers/Admin/ReportController.php
getCounts()
batchStudentCountryData()
batchAttendance()
```

Count logic:

```text
student_attendances.coach_id = selected coach
student_attendances.type = Batch
date between selected range
status != NOTMARKED
status != CANCELLED
```

The per-session attendance screen uses `StudentBatch::eligibleOn(date)` so mid-joiners are not submitted early.

Fee start/date sync for new changes:

```text
Reports, late badges, dashboards, and attendance eligibility use student_batches.start_date/end_date.
They do not read the student_fees date directly for every class/report calculation.

When an ACTIVE fee is created or updated, active student_batches dates are synced to the fee-valid window:

Start date is the later of:
- batchs.start_date
- student_fees.start_date

End date is the earlier of:
- batchs.end_date
- student_fees.end_date

This makes a student with fee start 10-Jul appear as late before 10-Jul and prevents early report/attendance counts.
It also stops future calendar/schedule student counts after fee end date unless the next paid fee is saved and extends the active batch window.
Existing wrong rows may need fee edit/resave or manual DB sync; old saved attendance rows are not rewritten.
```

Old historical report counts may remain as they were if old wrong `student_attendances` already exist.

## Coach Availability Rules

Core intent:

```text
Only real teaching commitments should block coach availability.
```

1-1 batch rule:

```text
batchs.is_one_to_one marks a batch as a 1-1 Batch.
1-1 batches use the same coach availability, country, schedule, demo, and coverup conflict rules as normal batches.
Only one active student can be assigned to a 1-1 batch.
Normal batch report counts exclude 1-1 batches; 1-1 classes have a separate coach report stat/details card.
Coach calendar/schedule shows 1-1 batches with a distinct teal color/badge.
Existing batches default to normal.
```

New Enrollment batch assignment rule:

```text
New Enrollment batch_id used to be only a showcase/reference.
After mid-joiner/report eligibility changes, Confirm Enrollment can assign the student to the selected batch.

If selected batch is ACTIVE/STANDBY:
- create active fee
- mark student active
- create/update active student_batches row directly
- clamp student_batches dates to paid fee window and batch window
- block if student already has another active batch
- block if 1-1 batch already has another active student

If selected batch is UPCOMING/raw:
- create active fee and mark student active
- redirect to Batch Assign Students page
- preselect the new enrollment student
- prefill assignment start/end dates from New Enrollment
- admin completes missing raw-batch details such as level/sessions before activation

New Enrollment batch dropdown should include ACTIVE, STANDBY, and UPCOMING batches, excluding INACTIVE.
Country filtering must handle batchs.country JSON arrays.
```

Raw batch:

```text
A raw batch is a batch created with country/schedule/coach but no assigned students yet.
New raw batches are intended to be UPCOMING.
```

Batch status behavior:

```text
UPCOMING/raw = non-blocking
INACTIVE = non-blocking
ACTIVE = blocking
STANDBY = blocking
```

Batch edit/status rules:

```text
UPCOMING = coach can be edited with raw-batch availability validation.
ACTIVE = coach cannot be edited from normal batch edit.
STANDBY = coach cannot be edited from normal batch edit.
INACTIVE = coach cannot be edited from normal batch edit unless a separate business rule is added.
UPCOMING can become ACTIVE only through Assign Students.
UPCOMING cannot be manually changed to ACTIVE/STANDBY from Manage Batches status modal.
UPCOMING can be manually changed to INACTIVE from Manage Batches.
```

For raw batch creation:

```text
- Check coach is ACTIVE.
- Check coach matches selected country/location.
- Check coach has base availability for every selected weekday/time.
- Check conflicts against ACTIVE/STANDBY batch slots.
- Ignore demo conflicts.
- Ignore coverup conflicts.
```

For demo and coverup flows:

```text
- Validate coach availability.
- Validate country where applicable.
- Block overlapping real batch slots.
- Block overlapping active demo slots.
- Block overlapping coverup slots.
```

New shared service:

```text
app/Services/CoachAvailabilityService.php
```

Important: this file has been untracked locally in prior checks, so remember to `git add app/Services/CoachAvailabilityService.php` before committing coach availability work.

## Coach Availability Changes

Batch creation/edit:

```text
- Coach dropdown is disabled until country and all schedule rows are complete.
- Form fetches available coaches from backend.
- Backend validates selected coach, not just frontend filtering.
- Existing batch edit passes current batch id to avoid self-conflict.
- Existing assigned coach is kept visible in edit mode if filtered list changes.
- Batch edit allows coach change only while status is UPCOMING. For ACTIVE/STANDBY/INACTIVE, the edit UI keeps coach disabled and backend update ignores posted coach_id.
- New batches are saved as UPCOMING.
- Batch becomes ACTIVE when students are assigned.
```

Coach reports/calendar:

```text
- Calendar/daily schedule should exclude INACTIVE.
- Calendar/daily schedule should exclude raw UPCOMING.
- Calendar/daily schedule includes ACTIVE and STANDBY.
- BATCH INACTIVE legend was removed from calendar UI.
- Calendar date range should use batchs.start_date/end_date first, with student_batches dates only as fallback.
- Calendar recurrence must start from the first scheduled weekday on or after batchs.start_date, not from the start of that week.
```

Availability/dashboard areas:

```text
- Attendance student lists use eligibleOn(date).
- Dashboard student/batch counts use eligible students.
- Demo availability was adjusted to block only ACTIVE/STANDBY batches that have eligible students for that date.
- Some dashboard availability grid logic still uses older direct helper methods, so verify before changing availability behavior there.
```

## Availability Blocking Factors

A coach is blocked if:

```text
1. Coach is not ACTIVE.
2. Coach country does not match selected country/location.
3. Coach has no base availability for the selected weekday/time.
4. Coach has overlapping ACTIVE/STANDBY real batch commitment.
5. In demo/coverup/single-event validation, coach has overlapping active demo.
6. In demo/coverup/single-event validation, coach has overlapping coverup.
```

Country rule currently used:

```text
Coach matches any selected country, not necessarily all selected countries.
```

Time conflict rule:

```text
overlap exists when existing_from < selected_to AND existing_to > selected_from
```

## Known Caveats

```text
- Old attendance/report rows are not automatically corrected.
- Existing old raw batches with ACTIVE/STANDBY status and missing students should be reviewed if they incorrectly block availability.
- Raw batches no longer reserve coach time until students are assigned; another real batch may take that slot.
- Coach dropdown may become empty if no coach matches selected country and full schedule.
- Demo Lead -> Convert To Student no longer collects Last Payment Level ID; new converted students may have lastpayment_level_id null.
- Student create/edit no longer collects Fees Country separately; backend sets fees_country from country.
- Full Laravel scheduler should not be enabled on development unless external side effects are reviewed.
- Hardcoded credentials exist in parts of the app; do not print secrets and move to env/config in future security work.
```

## Coach Dashboard Report Sync Notes

```text
- Super Admin Coach Report and Coach role Coach Report share ReportController/Admin.CoachReports logic.
- Coach dashboard monthly calendar intentionally remains coach-facing: future date schedule details are blocked and STANDBY batches are not shown.
- Coach dashboard monthly calendar should still mirror report display for ACTIVE 1-1 batches: 1-1 uses teal #0f766e, normal batch uses red, demo uses blue, leave uses yellow.
- Coach dashboard monthly calendar should use batch start_date/end_date first, with student-batch dates only as fallback, so batch date edits reflect like the report calendar.
- Coach dashboard schedule student counts use ACTIVE student_batches where start_date <= class date <= end_date, matching mid-joiner/mid-leaver report logic.
```

## Student Details Batch History Notes

```text
- Issue name: Student Batch History Should Show Clean Assignment Timeline.
- Student master -> Student Details popup should show student_batches assignment dates, not batchs start/end dates.
- Raw technical rows from fee-due/change-batch handling are collapsed for display: overlapping fees-due rows are hidden when a valid non-fees-due row exists for the same batch/coach/level.
- Overlapping duplicate assignment rows for the same batch/coach/level are represented by the final/latest valid assignment window.
- No database cleanup is performed; this is only a popup display timeline.
```

## Coach Deactivation Guard Notes

```text
- Coach master status switch must not deactivate a coach if the coach has assigned batches with status ACTIVE or STANDBY.
- Backend validation lives in CoachController@changeStatus and returns blocking batch details instead of changing status.
- Coach master UI should revert the switch and show a popup listing the active/standby batches that must be deactivated or reassigned first.
- Activating an inactive coach remains unchanged.
```

## Batch Student Transfer Notes

```text
- Issue/use case: when a coach leaves, admins can transfer all active students from a source batch to another country-matched batch.
- Transfer target rules are source/target country overlap based: at least one country must match; after successful transfer the target batch countries become the merged unique source + target country list.
- Active target batches are allowed only when they are normal batches; active 1-1 batches are never listed as transfer targets.
- Upcoming/raw target batches can be normal batches, or 1-1 batches only when exactly one student is being transferred.
- Transfer opens the existing batch assignment page with students, cutoff/start date, end date, level, and remaining sessions prefilled.
- No students are detached from the source batch at redirect time; source rows become INACTIVE only after the target assignment save succeeds.
- Transfer save skips coach schedule conflict validation because coach/schedule matching is intentionally not part of this transfer flow.
- Student history remains in student_batches: old source assignment is cut off and target assignment starts from the transfer cutoff date.
- After successful transfer, the source batch is marked INACTIVE and its schedules are marked INACTIVE once no active students remain, matching manual deactivation behavior for coach availability/report cleanup.
- If the target is already ACTIVE, the target batch master dates/sessions/level/schedules are preserved; only transferred student assignment rows are added/updated.
- If the target is UPCOMING/raw, the target batch is activated through the normal assignment save flow.
```

## New Enrollment Listing Notes

```text
- New Enrollment listing defaults to Pending Only, where pending means the linked student has no student_fees rows yet.
- Confirmed means the linked student has at least one student_fees row, matching the Confirm Enrollment flow that creates student_fees.
- Users can switch the listing filter between Pending Only, All Enrollments, and Confirmed Only for daily/weekly/monthly conversion checks.
- Student master remains unchanged and continues to list actual students independently.
```

## Student Dashboard Event Visibility Notes

```text
- Upcoming tournaments and upcoming masterclasses on the student dashboard are visible only when the logged-in student status is ACTIVE.
- FEESDUE and INACTIVE students should not receive upcoming tournament/masterclass data on the dashboard cards.
- The separate student tournament and student masterclass pages follow the same ACTIVE-only student rule.
- The existing fees-due modal/payment reminder remains unchanged.
```

## Masterclass Targeting Notes

```text
- Masterclass add/edit should allow the same flexible targeting UX as tournaments for country-based batch and individual student selection.
- Selecting a batch should not disable the individual student dropdown; admins can add a complete batch plus extra individual students.
- Backend student targeting already merges selected students from direct student, batch, level, and country sources and de-duplicates by student id.
```

## Coverup Coach Availability Notes

```text
- Coverup coach assignment from leave approval and coverup listing change-coach should use CoachAvailabilityService::validateCoachForSingleEvent.
- Both flows should follow the same country, base availability, real batch, demo, and coverup conflict rules.
- Leave approval also keeps the extra guard that the selected replacement coach must not be on approved leave for the coverup date.
- Leave approval revalidates the selected coach again while saving, not only while building the dropdown.
```

## SuperAdmin Dashboard Student Batch Column Notes

```text
- SuperAdmin dashboard Students section should show only one batch in the Batch column.
- Selection priority is the current ACTIVE student-batch first; if none exists, show the latest/last student-batch.
- The old all-history display remains available in detailed student history screens, not in the dashboard summary column.
```

## Change Batch Confirmation Notes

```text
- Change Batch Confirm Enrollment now closes any existing ACTIVE student_fees rows for that student before creating the new ACTIVE fee row.
- The selected target batch is now actually applied during confirmation: ACTIVE/STANDBY batches get a direct student_batches assignment, while UPCOMING/raw batches redirect to the batch assignment page with the student preselected.
- Change Batch target batch dropdown is filtered by the student's country and excludes INACTIVE batches; backend country validation remains as the safety guard.
- Direct assignment follows the New Enrollment rules for country match, required coach/level/date data, 1-1 capacity, and fee/batch date clipping.
- Direct assignment also inactivates any other active student_batch rows for that student so the student does not remain active in the old batch after confirmation.
```

## Razorpay Payment Notes

```text
- Razorpay credentials are environment-configurable through RAZORPAY_KEY and RAZORPAY_SECRET, exposed via config/services.php as services.razorpay.
- Student checkout JS, /razorpay/verify, and check:payment now read Razorpay credentials from config instead of hardcoded live/test values.
- Student dashboard Razorpay fee page uses a shared country-to-fee-column/currency map so OMAN and other configured countries calculate both single-level and next-3-level payment amounts safely.
- Payment Level master now includes kuwait_fees; Kuwait students map to kuwait_fees and KWD for next fee calculations.
- Client confirmed Razorpay payments should credit a 30-day inclusive fee window: payment date as start_date plus the next 29 days as end_date.
- Razorpay success now saves currency into both orders.currency and student_fees.currency.
- Razorpay checkout failures should be recorded into orders with status FAILED and error details in razorpay_data.
- Razorpay fee payment method rule: INDIA students can use normal Razorpay methods; non-India students should see card-only checkout and backend rejects non-card methods with order status REJECTED.
- SuperAdmin dashboard Student Payments tab should show today's student payment orders across statuses/gateways.
- SuperAdmin Payment Report tab should read from orders and support date/status filtering for operational review, including Razorpay and HDFC rows already stored in orders.
- Existing point about edited fee duration not reflecting in dashboard is resolved by moving dashboard/report display to orders and linked student_fee rows instead of missing-currency active fee rows.
```

## Student Listing Batch Display Notes

```text
- Student::latestBatch now resolves active student_batch rows first, then falls back to latest id.
- Student Master batch name, level badge, and batch schedule should therefore show the transferred/current batch after batch transfer, not the old inactive batch.
- This is display-side selection only; transfer save logic still controls the actual student_batches status history.
```

## Frontend Revamp Live Mapping Notes

```text
- Replaceable public website routes now render the new revamp views: home, about, contact, gallery, event, policy pages, and course pages.
- Missing replacement pages stay on the old/live flow: country landing pages, paynow, thank-you/payment pages, standalone trial booking page, and AJAX/backend submit routes.
- New blog list and blog detail pages are dynamic now; /blog reads ACTIVE blogs and /blog/{slug} renders the selected blog with recent blog links.
- Revamp trial forms use the existing confirm.trial.class backend route with country/timezone mapping, CSRF, AJAX submit, and old thank-you redirect behavior.
- Revamp trial forms must send duration=25_minutes like the old website; HomeController also defaults missing duration safely to avoid Undefined array key errors.
- Revamp contact page form uses the existing contact.submit route and keeps old backend handling.
- Revamp layout links point to live routes instead of /design preview URLs where those routes have replacements.
```

Known route/test caveats from prior analysis:

```text
- routes/backend.php references App\Http\Controllers\Admin\EmployeeCameraHistoryController.
- php artisan test/route checks may be affected by duplicate command class namespace issues under app/Console/Commands/Email.
```

## Before Commit / Push

Suggested checks:

```bash
git status --short --branch
git diff --check
php -l app/Services/CoachAvailabilityService.php
php -l app/Http/Controllers/Admin/BatchController.php
php -l app/Http/Controllers/Admin/DemoSessionsController.php
php -l app/Http/Controllers/Admin/CoverupclassController.php
php -l app/Http/Controllers/Admin/ReportController.php
php artisan optimize:clear
```

If committing coach availability work, ensure:

```bash
git add app/Services/CoachAvailabilityService.php
```

## Context Maintenance Rule

Whenever a meaningful implementation change is made, update this file in the same work session.

Update especially when changing:

```text
- branch/deployment assumptions
- crontab/scheduler behavior
- fee-due cron logic
- mid-joiner eligibility
- coach availability blocking rules
- coach report counting
- production/live status
```
