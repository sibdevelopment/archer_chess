# Cron / Scheduler Analysis

This application uses Laravel's task scheduler. The active schedule is defined in:

- `app/Console/Kernel.php`

The application timezone is:

- `Asia/Kolkata`, from `config/app.php`

In production, the server should run Laravel's scheduler every minute:

```cron
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

## Active Scheduled Commands

| Frequency | Command | Purpose |
| --- | --- | --- |
| Hourly | `get:coverupclass-recording` | Fetch cover-up class Zoom recordings. |
| Hourly | `get:masterclass-recording` | Fetch masterclass Zoom recordings. |
| Hourly | `get:meeting-recordings` | Fetch regular batch Zoom recordings. |
| Hourly | `get:demo-recordings` | Fetch demo session Zoom recordings. |
| Every minute | `cancel:delay-batch` | Apply late/cancel rules while respecting occurrence availability. |
| Hourly | `masterclass:reminder` | Send masterclass reminder emails. |
| Hourly | `tournament:reminder` | Send tournament reminder emails. |
| Every minute | `check:payment` | Poll Razorpay payment status. |
| Every 5 minutes | `batch-sessions:prepare --hours=24` | Prepare/reconcile regular batch occurrence meetings; overlap protected. |
| Daily at 01:15 | `batchs:sync-empty-to-upcoming` | Move qualifying empty batches back to upcoming status. |

## Server Crontab Command

Fee-due processing is intentionally outside Laravel `Kernel.php` and should run from the production crontab every 30 minutes:

```cron
*/30 * * * * cd /var/www/archerkids && php artisan fees:mark-due-by-schedule --buffer=15 >> storage/logs/fee-due-cron.log 2>&1
```

The old regional `check:fess-due-students`, `set:fess-due-in-usa-canada`, and `set:fess-due-in-uk` schedules are disabled.

## Command Details

### `batch-sessions:prepare --hours=24`

File:

- `app/Console/Commands/PrepareBatchClassSessions.php`

Runs every five minutes with `withoutOverlapping()`.

What it does:

- Scans active regular batch schedules from 15 minutes in the past through 24 hours ahead.
- Creates one `batch_class_sessions` row and Zoom meeting per batch/schedule/date.
- Uses cache locking and a database unique key to prevent duplicate meetings.
- Reconciles changed coach/time/date details and blocks holidays, approved leave, cover-ups, or invalid schedules.
- Records attempts/errors and retries failed creation after a cooldown.

Run it manually after deployment to prepare the first window:

```bash
php artisan batch-sessions:prepare --hours=24
```

### `get:coverupclass-recording`

File:

- `app/Console/Commands/GetCoverUpClassRecording.php`

Runs hourly.

What it does:

- Finds today's `CoachAttendance` records where `type = COVERUP`.
- Only processes records where `recording_link` is empty.
- Finds the matching `Coverupclass` by batch, coach, and date.
- Uses the assigned coach's Zoom credentials.
- Fetches Zoom cloud recordings from the last 10 days.
- Matches recordings by:
  - same date as the attendance record
  - recording start time within 15 minutes of attendance time
- Saves the Zoom `play_url` into:
  - `coach_attendances.recording_link`
  - matching `student_attendances.recording_link`

Important notes:

- It depends on `coach.zoom_api_key`, `coach.zoom_client_secret`, `coach.zoom_id`, and `coach.zoom_user_id`.
- It does not create attendance records. It only updates existing attendance records.

### `get:masterclass-recording`

File:

- `app/Console/Commands/GetMasterClassRecording.php`

Runs hourly.

What it does:

- Finds masterclasses scheduled for today.
- Gets the masterclass coach.
- Finds the latest `CoachAttendance` for:
  - the coach
  - `type = Masterclass`
  - today's date
- If that attendance has no recording link, it fetches Zoom cloud recordings.
- Matches recordings by:
  - same date as the masterclass
  - recording start time within 15 minutes of masterclass time
- Saves the Zoom `play_url` into `coach_attendances.recording_link`.

Important notes:

- It does not update student attendance records.
- It only works if a matching `CoachAttendance` record already exists.

### `get:meeting-recordings`

File:

- `app/Console/Commands/GetZoomMeetingRecordings.php`

Runs hourly.

What it does:

- Processes recent regular batch occurrences that have exact Zoom meeting IDs/UUIDs.
- Groups occurrence work by coach credentials to reduce repeated Zoom API calls.
- Matches a recording to `batch_class_sessions`, then updates coach and student attendance through `batch_class_session_id`.
- Keeps the old date/time matching path for legacy attendance that has no occurrence reference.

Important notes:

- Exact occurrence matching avoids attaching a recording from another class at a similar time.
- Legacy fallback remains more expensive and should reduce as occurrence-linked attendance becomes normal.

### `get:demo-recordings`

File:

- `app/Console/Commands/GetDemoRecording.php`

Runs hourly.

What it does:

- Finds today's `CoachAttendance` records where `type = Demo`.
- Skips records that already have a recording link.
- Finds the coach, demo lead, and demo session.
- Fetches Zoom cloud recordings for the coach.
- Matches recordings by:
  - today's date
  - recording start time within 15 minutes of the demo session time
- Saves the Zoom `play_url` into `coach_attendances.recording_link`.

Important notes:

- Updating `StudentAttendance` is present in the file but commented out.
- It assumes a demo session exists for the demo lead.

### `cancel:delay-batch`

File:

- `app/Console/Commands/CancelDelayBatch.php`

Runs every minute.

What it does:

- Finds active batches scheduled for today.
- For each schedule, calculates a cutoff time:
  - `from_time + 8 minutes`
- Skips the batch if a cover-up class exists for that schedule and date.
- After the cutoff, checks whether student attendance or coach attendance exists.
- If either is missing:
  - extends the batch end date to the next scheduled class day
  - creates `CANCELLED` student attendance records if missing
  - creates a `CANCELLED` coach attendance record if missing
  - updates active `StudentBatch.end_date`
  - extends each student's latest `StudentFee.end_date` to the next scheduled class day

Important notes:

- This job mutates attendance, batch dates, student batch dates, and fee dates.
- Regular occurrences marked `BLOCKED`, or `FAILED` without a usable legacy start URL, are skipped to avoid false coach penalties.
- The description says 10 minutes, but the code uses 8 minutes.
- It can extend fees and batches automatically.

### `masterclass:reminder`

File:

- `app/Console/Commands/Email/Hrs24/Reminder/MasterclassMail.php`

Runs hourly.

What it does:

- Finds active masterclasses where `is_reminder_sent = NO`.
- Calculates minutes until the masterclass date/time.
- Sends reminders if the masterclass is between 150 and 200 minutes away.
- Selects students by any configured masterclass filters:
  - country
  - student IDs
  - batch IDs
  - level IDs
- Sends email to each selected student's user email.

Important notes:

- The description says 8 hours before, but the code sends around 2.5 to 3.3 hours before.
- The command never sets `is_reminder_sent` to `YES`, so duplicate emails are possible while the event remains in the time window.

### `tournament:reminder`

File:

- `app/Console/Commands/Email/Hrs24/Reminder/TournamentMail.php`

Runs hourly.

What it does:

- Finds active tournaments where `is_reminder_sent = NO`.
- Calculates minutes until the tournament date/time.
- Sends reminders if the tournament is between 150 and 200 minutes away.
- Selects students by any configured tournament filters:
  - country
  - student IDs
  - batch IDs
  - level IDs
- Sends email to each selected student's user email.

Important notes:

- The description says 12 hours before, but the code sends around 2.5 to 3.3 hours before.
- The command never sets `is_reminder_sent` to `YES`, so duplicate emails are possible.

### `check:payment`

File:

- `app/Console/Commands/CheckPayment.php`

Runs every minute.

What it does:

- Finds orders where:
  - `status = authorized`
  - `created_at >= 21-11-2025`
  - `razorpay_payment_id` is not empty
- Skips invalid payment IDs that do not start with `pay_`.
- Calls Razorpay's payment API for each payment ID.
- If Razorpay says the payment is `captured`:
  - marks the order as `PAID`
  - creates a new active `StudentFee`
  - sets fee start date to today
  - sets fee end date to today + 15 days
  - sets paid amount from the order amount
  - if the student was `FEESDUE`, attempts to reassign/reactivate them into a batch
  - marks the student as `ACTIVE`
- If Razorpay says the payment is `failed`:
  - marks the order as `FAILED`
- If Razorpay says the payment is still `authorized`:
  - leaves the order as authorized

Important notes:

- Live Razorpay credentials are hard-coded in this command.
- This should be moved to `.env`, and the exposed live key should be rotated.
- The job runs every minute and has no `withoutOverlapping()`.

### `check:fess-due-students`

File:

- `app/Console/Commands/CheckFessDueStudents.php`

This legacy command is not scheduled in `Kernel.php`; production uses `fees:mark-due-by-schedule`.

Country scope:

- All countries except `CANADA`, `USA`, and `UK`.

What it does:

- Finds students whose fee ended yesterday.
- Confirms the student's latest fee entry also ended yesterday.
- Marks the student as `FEESDUE`.
- Marks the active `StudentFee` as `INACTIVE`.
- Marks the active `StudentBatch` as `INACTIVE`.
- Sets `StudentBatch.is_fees_due = 1`.
- Sets the student batch end date to yesterday.
- Calculates the student's next class date.
- Sends a `FeesDueMail` email to the student's user email.

Important notes:

- The command name contains `fess`, but it means `fees`.
- The command removes students from active batches by inactivating their `StudentBatch`.

### `set:fess-due-in-usa-canada`

File:

- `app/Console/Commands/UsaCanadaSetFessDue.php`

This legacy command is not scheduled in `Kernel.php`; production uses `fees:mark-due-by-schedule`.

Country scope:

- `USA`
- `CANADA`

What it does:

- Finds students whose fee end date is today.
- Confirms the latest fee entry also ends today.
- Marks the student as `FEESDUE`.
- Marks the active `StudentFee` as `INACTIVE`.
- Marks the active `StudentBatch` as `INACTIVE`.
- Sets `StudentBatch.is_fees_due = 1`.
- Sets the student batch end date to today.
- Calculates the student's next class date.
- Sends a `FeesDueMail` email to the student's user email.

Important notes:

- This differs from the other fee-due jobs by using today's fee end date instead of yesterday's.

### `set:fess-due-in-uk`

File:

- `app/Console/Commands/UkSetFessDue.php`

This legacy command is not scheduled in `Kernel.php`; production uses `fees:mark-due-by-schedule`.

Country scope:

- `UK`

What it does:

- Finds students whose fee ended yesterday.
- Confirms the latest fee entry also ended yesterday.
- Marks the student as `FEESDUE`.
- Marks the active `StudentFee` as `INACTIVE`.
- Finds a `StudentBatch` for the student.
- Marks that `StudentBatch` as `INACTIVE`.
- Sets `StudentBatch.is_fees_due = 1`.
- Sets the student batch end date to yesterday.
- Calculates the student's next class date.
- Sends a `FeesDueMail` email to the student's user email.

Important notes:

- The `StudentBatch` lookup does not filter by `status = ACTIVE`.
- The code assumes `$student_batch` exists before using `$student_batch->batch_id`, so it can fail if no student batch is found.

## Disabled Scheduler Entries

These commands exist but are commented out in `app/Console/Kernel.php`.

| Command | Intended behavior | Current state |
| --- | --- | --- |
| `demo:complete` | Send demo completion emails after completed demos. | Disabled. |
| `BatchAttendance:not-marked-attendance` | Create `NOTMARKED` attendance if coach does not submit attendance. | Disabled. |
| `check:fess-due-students` | Legacy fee-due handling outside USA/Canada/UK. | Disabled in Laravel scheduler. |
| `set:fess-due-in-usa-canada` | Legacy USA/Canada fee-due handling. | Disabled in Laravel scheduler. |
| `set:fess-due-in-uk` | Legacy UK fee-due handling. | Disabled in Laravel scheduler. |
| `reminder:feesdue-24hr-students` | Send 24-hour fee due reminders for other countries. | Disabled, and email sending is commented out. |
| `reminder:feesdue-24hr-usa-canada` | Send 24-hour fee due reminders for USA/Canada. | Disabled, and email sending is commented out. |
| `reminder:feesdue-24hr-uk` | Send 24-hour fee due reminders for UK. | Disabled, and email sending is commented out. |
| `set:reminder-onehour-other-countries` | Send final fee due reminder before due time for other countries. | Disabled, and email sending is commented out. |
| `set:onehour-reminder-in-usa-canada` | Send final fee due reminder before due time for USA/Canada. | Disabled, and email sending is commented out. |
| `set:onehour-reminder-in-uk` | Send final fee due reminder before due time for UK. | Disabled, and email sending is commented out. |

## Operational Risks

### 1. Hard-coded Razorpay live credentials

`check:payment` contains live Razorpay credentials directly in code.

Recommended action:

- Move credentials to `.env`.
- Read them through `config/services.php`.
- Rotate the exposed Razorpay secret.

### 2. Reminder emails can be duplicated

`masterclass:reminder` and `tournament:reminder` filter by `is_reminder_sent = NO`, but they never update that field after sending.

Recommended action:

- Set `is_reminder_sent = YES` after successful sends.
- Consider logging reminder send status per student if partial failures matter.

### 3. Limited overlap protection

`batch-sessions:prepare` uses Laravel's `withoutOverlapping()`. Other scheduled commands do not currently have overlap protection.

Recommended action:

- Add `withoutOverlapping()` to long-running jobs, especially:
  - `get:meeting-recordings`
  - `check:payment`
  - Zoom recording fetch jobs

### 4. Legacy Zoom polling

`get:meeting-recordings` now groups occurrence processing by coach and matches exact meeting IDs first. Its legacy fallback still scans recent attendance and can call Zoom repeatedly.

Recommended action:

- Continue reducing reliance on the legacy date/time fallback.
- Monitor API calls per coach and failed occurrence retries.
- Store last checked timestamps or retry only missing recordings.

### 5. Time descriptions do not match code

Some command descriptions are misleading:

- `cancel:delay-batch` says 10 minutes, code uses 8 minutes.
- `masterclass:reminder` says 8 hours, code uses 150-200 minutes.
- `tournament:reminder` says 12 hours, code uses 150-200 minutes.

Recommended action:

- Update command descriptions or adjust the time windows.

### 6. UK fees-due job can fail on missing student batch

`set:fess-due-in-uk` uses `$student_batch->batch_id` after a lookup that may return null.

Recommended action:

- Add a null check before using `$student_batch`.
- Decide whether only active student batches should be updated.

## Quick Verification Commands

List active scheduled tasks:

```bash
php artisan schedule:list
```

Run due scheduled tasks manually:

```bash
php artisan schedule:run
```

Run one command manually:

```bash
php artisan get:meeting-recordings
php artisan check:payment
php artisan cancel:delay-batch
```
