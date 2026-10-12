# Regular Batch Zoom Occurrences

## Purpose

Regular batch classes use one scheduled Zoom meeting for each class occurrence. An occurrence is uniquely identified by:

```text
batch_id + batch_schedule_id + class_date
```

This replaces the old assumption that one permanent Zoom meeting stored on `batchs` serves every class. Demo sessions, masterclasses, and cover-up classes keep their own independent meeting flows.

## Main Files

```text
app/Models/BatchClassSession.php
app/Services/BatchClassSessionService.php
app/Services/ZoomMeetingService.php
app/Console/Commands/PrepareBatchClassSessions.php
app/Console/Commands/CreateMeetingLinks.php
app/Console/Commands/GetZoomMeetingRecordings.php
database/migrations/2026_10_10_120000_create_batch_class_sessions_table.php
```

The coach and student dashboard controllers, attendance models, batch controller, holiday/leave/cover-up flows, and delayed-batch command also integrate with this model.

## Database Design

`batch_class_sessions` stores a snapshot of one planned regular class:

```text
batch_id
batch_schedule_id
coach_id
class_date
scheduled_start_at
scheduled_end_at
weekday
from_time
to_time
status
start_url
join_url
zoom_meeting_id
zoom_meeting_uuid
creation_attempts
last_attempted_at
last_error
```

The unique key on `batch_id`, `batch_schedule_id`, and `class_date` prevents duplicate occurrence meetings. Both `coach_attendances` and `student_attendances` have a nullable `batch_class_session_id` for exact attendance and recording matching.

Statuses are:

```text
PENDING
CREATING
READY
FAILED
BLOCKED
COMPLETED
```

`BLOCKED` is used when the regular occurrence should not run, including holidays, approved coach leave, cover-up replacement, inactive batch/schedule, or an occurrence outside the batch date range.

## Preparation Flow

Laravel schedules this command every five minutes with overlap protection:

```bash
php artisan batch-sessions:prepare --hours=24
```

The command scans active regular batch schedules from 15 minutes in the past through the configured future window. It creates or reconciles each occurrence, uses a cache lock to prevent duplicate creation, and retries failed Zoom creation after a cooldown.

The legacy command remains available but delegates to occurrence reconciliation:

```bash
php artisan create:meeting-links
```

## Batch Create, Edit, And Reassignment

- Batch create requires at least one valid schedule.
- Batch and schedule writes run in one database transaction.
- A new batch starts as `UPCOMING`; no synchronous Zoom API call is made while saving.
- Editing schedule, time, coach, date range, or status causes future occurrences to be reconciled.
- Invalidated future occurrence meetings are blocked and their Zoom meetings are deleted where possible.
- Reassigned batch versions do not create one permanent meeting; future occurrence meetings use the same service.

The `start_url`, `join_url`, `zoom_meeting_id`, and `zoom_meeting_uuid` columns on `batchs` are retained only as rollout compatibility fallback for legacy data. New regular classes use `batch_class_sessions`.

## Start And Join Flow

- The coach schedule includes the matching `batch_schedule_id` and `batch_class_session_id`.
- Starting a regular class resolves the occurrence on the server and uses its `start_url`.
- Student join resolves the current occurrence on the server and uses its `join_url`; the browser is not trusted to provide a Zoom URL.
- Attendance created for the class stores `batch_class_session_id`.
- A blocked occurrence has no usable start or join URL.

If an occurrence is missing when a valid start/join request is made, the service can create it on demand. During rollout only, a non-blocked occurrence may fall back to the old batch-level URL.

## Holidays, Leave, And Cover-Ups

Before meeting creation, the service checks the existing occurrence rules:

- A matching holiday blocks the regular occurrence.
- Approved coach leave blocks the regular occurrence.
- A cover-up for the same batch, schedule, and date blocks the original occurrence.
- Schedule deletion, deactivation, or time changes invalidate affected future occurrences.

Blocking clears the occurrence URLs and attempts to delete any already-created Zoom meeting. Cover-up meetings continue to use the cover-up flow.

## Penalty Protection

The delayed-batch command resolves the exact regular occurrence before applying late or cancellation penalties. A `BLOCKED` occurrence is skipped. A `FAILED` occurrence with no usable legacy start URL is also skipped, so a Zoom provisioning failure does not create an unfair coach penalty.

## Recording Matching

Regular batch recording sync first uses the occurrence meeting ID/UUID and `batch_class_session_id`. This avoids matching recordings only by approximate date/time. Attendance and student attendance rows linked to that occurrence receive the recording URL.

Legacy attendance without an occurrence reference still uses the previous date/time matching path as a compatibility fallback. The occurrence path groups work by coach credentials to reduce repeated Zoom API calls.

## Zoom API Behavior

`ZoomMeetingService`:

- Uses Zoom's OAuth response `api_url`, including regional API hosts.
- Includes the client secret in the token cache key so rotated credentials do not reuse an old token.
- Creates scheduled, non-PMI meetings with the occurrence date, time, timezone, and duration.
- Retries transient API failures and cleans up a newly created meeting if persistence fails.

## Deployment

Run the migration and clear Laravel caches after deploying:

```bash
cd /var/www/archerkids
git pull origin development_1
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan optimize:clear
sudo -u www-data php artisan batch-sessions:prepare --hours=24
sudo -u www-data php artisan schedule:list
```

The server cron must continue invoking `php artisan schedule:run` every minute.

## Verification

Check recent occurrence health:

```sql
SELECT
    id,
    batch_id,
    batch_schedule_id,
    coach_id,
    class_date,
    scheduled_start_at,
    status,
    zoom_meeting_id,
    creation_attempts,
    last_error
FROM batch_class_sessions
WHERE class_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 DAY)
ORDER BY scheduled_start_at, id;
```

Expected checks:

1. Valid active occurrences become `READY` and have unique Zoom meeting IDs.
2. Holidays, approved leave, and replaced classes become `BLOCKED`.
3. Coach and student attendance rows carry the same `batch_class_session_id`.
4. A Zoom creation failure becomes `FAILED`, records `last_error`, and does not trigger a false penalty when no usable fallback exists.
5. Demo, masterclass, and cover-up meeting creation remains unchanged.
