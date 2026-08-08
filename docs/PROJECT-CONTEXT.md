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

## Leave, Coverup, Cancel, And Penalty Flow

- Coach leave is treated as a single `from_date` with `from_time` to `to_time` interval in the active UI.
- Leave approval now handles each affected occurrence by `batch_id + batchschedule_id + date`.
- If an approved leave occurrence has a coverup coach, a `coverupclasses` row is created/updated and any delayed penalty for that occurrence is cleared.
- If approved leave has no coverup, the original coach gets no penalty; coach attendance is marked `ON LEAVE`, eligible student attendance is marked cancelled with approved-leave remark, and batch/student-batch/latest-fee end dates shift once to the next scheduled class day.
- `cancel:delay-batch` skips occurrences covered by coverup or approved leave. Normal missed classes still follow late/cancel penalty logic, and cancellation remains exclusive over late.
- Coach dashboard shows approved leave occurrences as `ON LEAVE` or `COVERED`, hides start links for those rows, and backend start/attendance endpoints reject normal starts when approved leave blocks that class.
- Regional holidays are also handled as official no-class occurrences. If an active holiday date covers all countries on an active batch, and the batch has an active schedule within its start/end window that day, `cancel:delay-batch` marks the coach attendance `HOLIDAY`, clears delayed penalties, shifts eligible batch/student/latest-fee dates once, and coach dashboard shows `HOLIDAY` with no start link.

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
- Old region-wise fee due schedules are commented in `app/Console/Kernel.php`; production should run only the new single command from direct `www-data` crontab unless the full scheduler is intentionally reviewed/enabled later.
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

## Coach Late / Auto-Cancel Penalty Notes

```text
- Existing command `cancel:delay-batch` now owns the delayed-class lifecycle and should run every minute wherever this feature is expected to work.
- If coach attendance is still missing after 3 minutes from scheduled batch start, a delayed_batches row is created/updated with penalty_type=LATE, fine_amount=150, fine_currency=INR.
- If the same class reaches the 8-minute auto-cancel path without coach attendance, the same delayed_batches row is upgraded to penalty_type=CANCELLED, fine_amount=350, fine_currency=INR; fines are exclusive, not additive.
- Auto-cancel still writes CANCELLED coach/student attendance and extends batch/student/fee end dates as before.
- Super Admin Coach Report and Coach role Coach Report show the Late/Cancel Fine stat/details through the shared CoachReports getcount/detail flow.
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
- Country normalization is now centralized through helpers so frontend lead country, demo lead, new enrollment, student, batch country matching, coach availability, and payment-level lookup use canonical ERP country values.
- Student checkout JS, /razorpay/verify, and check:payment now read Razorpay credentials from config instead of hardcoded live/test values.
- Student dashboard Razorpay fee page uses a shared country-to-fee-column/currency map so OMAN and other configured countries calculate both single-level and next-3-level payment amounts safely.
- Payment Level master now includes kuwait_fees; Kuwait students map to kuwait_fees and KWD for next fee calculations.
- Payment Level master also includes bahrain_fees and south_africa_fees so lead-form countries Bahrain and South Africa have payment amounts/currency mappings (BHD/ZAR).
- Student dashboard disables Razorpay payment buttons when the mapped country fee amount is missing/zero, and shows an admin-facing configuration message instead of attempting Pay 0.
- Razorpay checkout amount conversion uses 1000 subunits for three-decimal currencies BHD/KWD/OMR; other configured currencies still use 100 subunits.
- Client confirmed Razorpay payments should credit a 30-day inclusive fee window: payment date as start_date plus the next 29 days as end_date.
- Razorpay success now saves currency into both orders.currency and student_fees.currency.
- Razorpay checkout failures should be recorded into orders with status FAILED and error details in razorpay_data.
- Razorpay checkout initiation now creates a local orders row with status CREATED before opening the existing checkout flow; success/failure/rejection updates that same row when local_order_id is returned, preserving current Razorpay behavior.
- Razorpay fee payment method rule: INDIA students can use normal Razorpay methods; non-India students should see card-only checkout and backend rejects non-card methods with order status REJECTED.
- SuperAdmin dashboard Student Payments tab should show today's student payment orders across statuses/gateways.
- SuperAdmin Payment Report tab should read from orders and support date/status filtering for operational review, including Razorpay and HDFC rows already stored in orders.
- SuperAdmin Payment Report tab now uses a single custom date-range picker defaulted to Today; it replaces fixed range dropdown while still offering Today/Last Week/This Month/Last Month quick selections.
- SuperAdmin Payment Report status dropdown now reads distinct statuses from orders so CREATED/captured/FAILED/REJECTED and future stored statuses can all be filtered without changing payment flow.
- Student dashboard home is blurred/blocked when the logged-in student is FEESDUE; the left navigation remains usable, the existing fee due popup still shows, and the lock clears automatically once payment/status makes the student active again.
- Existing point about edited fee duration not reflecting in dashboard is resolved by moving dashboard/report display to orders and linked student_fee rows instead of missing-currency active fee rows.
- Admin dashboard now keeps Admin/SuperAdmin global, but non-admin employee/CRE roles are scoped to assigned role countries for counts, students, missed sessions, coach dropdowns, and payment data.
- Dashboard Coaches and Employees tabs/cards are intentionally removed for every role; Coach/Employee master modules remain separate and permission-controlled from their own screens.
- SuperAdmin still sees top summary counts for Total Active Coaches and Total Active Employees; those are counts only, not dashboard tabs/lists.
- Dashboard Batches tab is visible by `batchs-view` (Admin/SuperAdmin always visible), and non-admin/CRE users see only batches matching their assigned role countries; batch coach/student filter dropdowns follow the same region scope.
- Dashboard Student Payments and Payment Report tabs use dedicated Dashboard permissions (`dashboard-student-payments-view`, `dashboard-payment-report-view`); when visible for non-admin roles, both today's payments and report filters are restricted to the user's assigned countries.
- Dashboard Student Payments shows today's payments with a status filter; default status is `captured`, while All Status can be selected to include created/failed/other order statuses.
- Dashboard Students/Missed Sessions tabs follow `students-view`; their AJAX endpoints return no data if permission is missing.
- Coach late/cancel penalty rule is exclusive: late class is `LATE`/INR 150, cancelled class is `CANCELLED`/INR 350, and cancelled overrides late for the same batch/date/schedule in reports.
- Coach late popup is server-rendered on coach dashboard home for today's unacknowledged `LATE` penalties and acknowledged through a normal POST under `dashboard-view` permission, so it is not blocked by calendar/Bootstrap JS issues; cancelled penalties remain report-only.
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
- Revamp header Login links to the old login page; Register and trial CTAs still open the lead registration popup.
- Trial booking thank-you route now renders a revamp-style page while keeping the old student login context/functionality.
- Revamp layout now carries the same GTM/Meta Pixel base tags as the old website and yields page-specific head code, so thank-you conversion tags can render.
- Revamp register popup country option values are normalized to the same keys used by the timezone/dial-code maps, fixing country/timezone mismatch in the popup.
- Revamp contact AJAX success now redirects to the old thank-you route to preserve thank-you URL based tracking.
- Revamp trial thank-you page now fires only the CompleteRegistration event on top of the layout's base Pixel PageView, avoiding duplicate Pixel bootstrap/PageView code.
- Revamp editor-rendered HTML uses a common .cms-content wrapper to restore normal paragraphs/lists/links/tables/images and neutralize sticky/fixed classes inside admin-authored content.
- Blog details description is rendered inside an auto-height iframe sourced from a template, so admin-authored blog HTML/CSS stays isolated from the revamp page header/sidebar while keeping the blog's own design.
- Revamp header/nav JS uses .js-revamp-site-header as its scope so page-wide header/nav scripts do not mutate editor-rendered blog content.
- Revamp homepage Meet Our Archer Kids now reads ACTIVE Meet Our Kids records instead of hardcoded child cards.
- Revamp homepage Testimonials now read ACTIVE Testimonial master records instead of hardcoded cards; the migration seeds the current three static testimonials as initial editable records and the Blade keeps the same static cards as fallback if the table is empty.
- Revamp homepage Latest Blogs and footer Recent Posts now read ACTIVE/home-featured Blog records instead of hardcoded post cards.
- Revamp gallery and event pages now render ACTIVE Gallery/Gallery Image and Event master records; /design preview routes use the same dynamic data.
- Revamp upload validation ratios: Meet Our Tutors 4:5 portrait, Gallery Images 1:1 square, Blogs 16:9, Events 16:9; Meet Our Kids already requires 304x304 square.
- Admin upload helper text is synced with frontend template ratios: Tutors 4:5 portrait, Kids/Gallery/Testimonials 1:1 square, Blogs/Events 16:9 landscape. Meet Our Kids validation now accepts any 1:1 square image instead of only exact 304x304.
- Frontend GTM coverage is standardized: revamp and old frontend layouts carry GTM head + noscript, standalone country pages now include missing GTM noscript, and the standalone India template includes GTM head + noscript.
- Revamp header now exposes Events under About and Blog after Contact; homepage Intro-Video keeps the small CTA UI but opens the same local intro video popup used by the landing pages.
- Revamp trial/register forms now include EUROPEAN UNION and OMAN with timezone/dial-code mappings; homepage testimonial videos play inline like landing pages instead of forcing fullscreen.
- Oman timezone display is aligned with UAE as Gulf Standard Time (UTC+4) in frontend/register and ERP timezone mappings.
- New Zealand country landing page is enabled through the existing /online-chess/{country} route at /online-chess/new-zealand, using the shared country landing template and NEWZEALAND/NZD mappings.
```

## Enrollment / Currency UI Notes

```text
- Admin currency entry is now a dropdown backed by availableCurrencyCodes() for demo conversion, new enrollment details, change batch confirmation, and student fee edit.
- Supported dropdown currencies currently mirror ERP country/payment setup: USD, CAD, AUD, NZD, INR, AED, GBP, SGD, ZAR, QAR, EUR, OMR, KWD, BHD.
- Demo lead conversion still creates the technical student row for New Enrollment linkage, but Students master listing only shows students with fee rows so pending/unconfirmed conversions do not appear there.
- New Enrollment and Change Batch manage screens no longer show Payment Level filter/column because that field is no longer used in those flows.
- New Enrollment manage now has an additional batch start-date range filter, separate from the enrollment created-date range filter.
```

## Student Payment Action Queue Notes

```text
- Portal/payment-gateway-created student fee rows now use a 25-day default credit window: start_date = payment date, end_date = payment date + 24 days.
- Admin dashboard Student Payments tab is now an action queue, not a today-only payment list: it shows captured orders with a linked student fee row whose updated_at still equals created_at.
- Once admin edits the linked student fee row/start-end date/status/amount, updated_at changes and the payment leaves the Student Payments tab.
- Full payment history and date/status filtering remains in the Payment Report tab.
- Student fee rows now support an optional remark. Saving the fee edit form marks the row as actioned even if no visible value changed, so already-correct payment rows can be cleared from the dashboard queue by review/save.
```

## Batch Availability / Empty Batch Sync Notes

```text
- Coach availability validation ignores all old/current versions from the same batch parent_id family when a current batch id is supplied, so hidden old STANDBY history does not block saving the current active batch.
- Demo and coverup availability blocking is limited to pending/current or future one-time commitments. Past demo/coverup records, and completed/cancelled one-time attendance, do not block batch edit or student assignment availability checks.
- Coach Availability roster uses the same overlap-based demo/coverup visibility rule; demo display is no longer exact-start only.
- Batch Manage listing displays batch names in uppercase for UI uniformity only; stored batch names are unchanged.
- ACTIVE/STANDBY batches with no ACTIVE student_batch rows and no linked student currently FEESDUE are moved back to UPCOMING.
- Empty-batch sync runs immediately after batch assignment/transfer save paths and is also available as php artisan batchs:sync-empty-to-upcoming for old data cleanup.
- The empty-batch cleanup command is scheduled daily at 01:15 as a safety net.
```

## Batch Standby / Reassignment Notes

```text
- Batch master active-student badge uses today eligibility for live ACTIVE batches, but STANDBY batches show assigned active student rows so ended batches do not display as empty.
- Reassigned/versioned batches ignore both the current new batch id and confirm_reassign_batch_id during coach availability validation, preventing the previous STANDBY version from blocking the same coach/timing reassignment.
```

## Leave / Holiday / Cancel Compensation Notes

```text
- Approved leave with coverup creates/keeps the coverup occurrence and does not shift batch dates, student-batch dates, or student fee dates.
- Approved leave without coverup is compensated server-side for every active batch schedule that overlaps the approved leave slot, even if the UI affected-data payload is empty/missing.
- Leave approval preview now uses the same active batch/date/schedule slot-overlap rule as backend compensation, so coverup choices are shown for the same affected classes that would otherwise be shifted.
- Holidays and unapproved missed classes use the same shared shift path as approved leave without coverup.
- Compensation is idempotent per batch/date once cancelled student attendance exists, so the same missed occurrence should not shift dates twice.
- Only ACTIVE students with an eligible ACTIVE student_batch on the missed class date are compensated; FEESDUE/INACTIVE students are not extended.
- Only the student's latest ACTIVE fee row is shifted, and fee/batch end dates move to the nearest next active batch schedule day.
```

## Demo Late / Cancel Penalty Notes

```text
- Normal batch penalty remains unchanged: late after 3 minutes = INR 150, cancelled after 8 minutes = INR 350, and cancelled replaces late.
- Demo penalty uses the same delayed_batches reporting pipeline with occurrence_type = DEMO, demo_session_id, and demolead_id.
- Demo late is marked after 5 minutes with INR 100 fine.
- Demo cancelled is marked after 9 minutes with INR 100 fine and replaces any late demo penalty for the same demo session/date.
- Masterclass attendance report now treats `coach_attendances.time` as the coach's actual Start click time and stores final attendance/homework submission separately in `coach_attendances.attendance_submitted_at`.
- Masterclass attendance report popup includes the scheduled masterclass country/region from `masterclasses.country`.
- Demo penalties are included in coach Late/Cancel Fine report counts and detail modal.
```

## Saudi Arabia Country / Payment Support Notes

```text
- Saudi Arabia is added as a supported ERP/frontend country with canonical value SAUDI ARABIA.
- Frontend registration, old fallback forms, and Middle East landing page forms include Saudi Arabia with Arabian Standard Time and +966 dial code.
- ERP country filters/forms now include Saudi Arabia across student, batch, coach, holiday, timezone, role, lead, demo lead, tournament, and masterclass surfaces.
- Payment Level master has a new saudi_arabia_fees column/input mapped to SAR for student dashboard Razorpay payments.
- Deployment needs php artisan migrate, then admins must fill Saudi Arabia fee values in Payment Level master before Saudi students can pay a non-zero amount.
- Razorpay account/currency support for SAR still needs to be confirmed/enabled from Razorpay side before live SAR checkout is considered final.
```

## Student Certificate Notes

```text
- Student certificate templates are image-backed and are stored in both storage/certificates for PDF generation and public/backend/tcul-imgs for dashboard preview.
- Current certificate templates are portrait images; PDF generation uses A4 portrait.
- Student full name and issue date are printed dynamically on the certificate template; issue date comes from the latest matching student batch end date for that certificate level.
- Certificate grid labels are mapped to the actual templates: Beginner, Intermediate A, Intermediate B, Advanced 1, Advanced 2, and Expert Level.
- Expert Level now uses the latest Expert_Certificate artwork in the Advanced_level_3 certificate slot.
- Certificate unlock is based on historical student_batches.level_id values.
- Beginner certificate unlocks for level IDs 1 or 2; the old overwrite bug that ignored level ID 1 is fixed.
- PDF download now re-checks that the logged-in student owns the requested certificate and that the certificate level is unlocked before streaming the file.
```

## Coach Availability Fee-Due Reservation Notes

```text
- Coach availability must stay blocked for real active/standby batches until students are truly inactive and the batch can become UPCOMING.
- Fee-due students still reserve the batch coach slot because they may pay and continue in the same batch.
- CoachAvailabilityService now treats student_batches with is_fees_due = 1 and students.status = FEESDUE as reserved when checking dated demo/coverup/batch conflicts.
- Active students still use StudentBatch::eligibleOn(date), so mid-joiner date rules remain intact.
- Empty/no-active/no-fee-due active or standby batches are still handled by batchs:sync-empty-to-upcoming through BatchStatusService.
```

## Demo Penalty Start Tracking Notes

```text
- Demo Start from the coach dashboard now records a STARTED coach_attendances row before redirecting the coach to Zoom.
- The demo cancel cron uses this attendance record to avoid wrongly marking a demo as CANCELLED when the coach has already opened/taken the demo.
- If the coach starts the demo after the 5-minute demo late threshold, the same Start action records the DEMO late penalty; cancelled demo penalty remains reserved for demos that are not started.
- Final demo attendance submission still updates the same record to COMPLETED, CANCELLED, or Student Absent as before.
- Demo start is now first-start-safe: repeated Start clicks do not overwrite the original STARTED attendance time or create a late penalty after a timely first start.
- Demo attendance lookups now use demolead_id + coach_id + date, and attendance submission passes demo_session_id so rescheduled/multiple demo sessions for the same lead do not mix records across dates.
- Demo Start route authorization allows the assigned coach id, the logged-in coach id, or a coach record with the same underlying user_id. This avoids false 403s when duplicate/alternate coach records exist for the same coach user while still blocking unrelated users.
- Dashboard permission methods include startDemoSession under dashboard-view; deploys must run PermissionSeeder and permission cache reset after this change to avoid middleware 403 on the demo Start route.
- Demo completion now preserves the original STARTED coach_attendances.time and stores final submission in attendance_submitted_at, so a demo started on time is not wrongly fined late just because the coach submitted final attendance after the class.
- Demo late penalty cleanup on completion removes an existing LATE penalty if the preserved start time is within the 5-minute demo grace window.
```

## Student Feedback Coach Scope Notes

```text
- Student dashboard feedback no longer lists all coaches.
- Feedback coach dropdown is limited to the coach/coaches from the logged-in student's current ACTIVE eligible student_batches whose linked batch is ACTIVE.
- This respects mid-joiner/current-date eligibility through StudentBatch::eligibleOn(today).
- Feedback submit now validates the selected coach against the same current-coach list, so students cannot post feedback for unrelated coaches by changing the request.
- Feedback rows now save the logged-in user_id correctly and attach student_id for student dashboard submissions.
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
