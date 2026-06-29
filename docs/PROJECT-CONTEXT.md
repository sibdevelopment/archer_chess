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
```

Old region-wise commands were also hardened so one bad student/batch should not break the full command.

## Mid-Joiner Rule

Business rule:

```text
A student can be assigned to a batch before joining,
but must not be counted or allowed to join until the actual start date.
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

Old historical report counts may remain as they were if old wrong `student_attendances` already exist.

## Coach Availability Rules

Core intent:

```text
Only real teaching commitments should block coach availability.
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
- New batches are saved as UPCOMING.
- Batch becomes ACTIVE when students are assigned.
```

Coach reports/calendar:

```text
- Calendar/daily schedule should exclude INACTIVE.
- Calendar/daily schedule should exclude raw UPCOMING.
- Calendar/daily schedule includes ACTIVE and STANDBY.
- BATCH INACTIVE legend was removed from calendar UI.
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
- Full Laravel scheduler should not be enabled on development unless external side effects are reviewed.
- Hardcoded credentials exist in parts of the app; do not print secrets and move to env/config in future security work.
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
