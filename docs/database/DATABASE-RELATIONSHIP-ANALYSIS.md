# Database Relationship Analysis

This document explains the database structure of the ArcherKids Laravel ERP from the Laravel migrations, models, and controller usage.

Important note: this analysis is based on code and migrations, not a live database dump. Some relationships are enforced by database foreign keys, while many business relationships are only implied by `*_id` columns, Eloquent models, JSON columns, and controller queries.

## 1. Big Picture

The ERP database is built around these main business areas:

```text
Authentication / RBAC
    users
    roles
    permissions
    model_has_roles
    model_has_permissions
    role_has_permissions
    permissiongroups

People
    employees
    coachs
    students

Lead To Student Flow
    enquiries
    demoleadenquiries
    demoleads
    demo_sessions
    new_enrollments

Batch And Class Flow
    levels
    batchs
    batch_schedules
    student_batches
    coach_availabilities
    coach_availability_periods
    leaverequests
    coverupclasses
    changeclasses
    delayed_batches

Attendance And Records
    student_attendances
    coach_attendances
    student_statuses
    feedback
    camera_checks

Fees And Payments
    paymentlevels
    student_fees
    orders

Website / Master Content
    blogs
    holidays
    masterclasses
    masterclasses_data
    tournaments
    tournaments_data
    meet_our_kids
    meet_our_tutors
    galleries
    gallery_images
    events
    timezones

System / Laravel
    password_resets
    failed_jobs
    personal_access_tokens
    oauth_* tables
    activity_log
    cruds
```

## 2. Naming Notes

Some table names use non-standard plural names. Developers must use the exact names:

| Model | Table |
|---|---|
| `Coach` | `coachs` |
| `Batch` | `batchs` |
| `DemoLead` | `demoleads` |
| `DemoLeadEnquiry` | `demoleadenquiries` |
| `LeaveRequest` | `leaverequests` |
| `Paymentlevel` | `paymentlevels` |

Do not rename these tables casually, because many controllers and relationships directly depend on these names.

## 3. Core ER Map

High-level relationship map:

```text
users
  -> employees
  -> coachs
  -> students
  -> demoleads
  -> demoleadenquiries
  -> created_by / updated_by on most admin tables

roles / permissions
  -> users through model_has_roles and model_has_permissions
  -> permissions through role_has_permissions

coachs
  -> batchs
  -> coach_availabilities
      -> coach_availability_periods
  -> leaverequests
  -> demo_sessions
  -> coach_attendances
  -> student_batches
  -> coverupclasses as old/new coach

students
  -> user account
  -> level
  -> student_batches
      -> batchs
      -> coachs
      -> levels
  -> student_fees
  -> student_attendances
  -> student_statuses
  -> orders
  -> feedback

batchs
  -> coachs
  -> levels
  -> batch_schedules
  -> student_batches
  -> student_attendances
  -> coach_attendances
  -> coverupclasses
  -> delayed_batches
  -> parent batchs through parent_id

demoleads
  -> user account
  -> demo_sessions
      -> coachs
      -> levels
  -> student_attendances
  -> coach_attendances

student_fees
  -> students
  -> orders

paymentlevels
  -> levels
```

## 4. Authentication And User Tables

### `users`

Purpose: central login identity for admin, employees, coaches, students, and API users.

Important fields:

| Field | Meaning |
|---|---|
| `id` | Primary user ID |
| `parent_id` | JSON-like hierarchy field used by `User::getchildrens()` |
| `first_name`, `last_name` | User name |
| `email`, `mobile`, `country_code` | Login/contact identity |
| `device_id` | Used as generated student password/device credential in some flows |
| `password` | Laravel hashed password |
| `status` | Account active/inactive |
| `student_fees_edit` | Permission-like flag for fee editing |
| `component_tab` | Permission-like flag for component tab access |
| `batch_edit` | Permission-like flag for batch editing |
| `created_by`, `updated_by` | Audit trail to `users.id` |

Relationships:

| Relationship | Type |
|---|---|
| User has one Coach | `users.id -> coachs.user_id` |
| User may have one Employee | `users.id -> employees.user_id` |
| User may have one Student | `users.id -> students.user_id` |
| User can create/update most records | `created_by`, `updated_by` |
| User has roles | Spatie `model_has_roles` |
| User has direct permissions | Spatie `model_has_permissions` |

Developer note: `parent_id` is cast as array and queried using `JSON_CONTAINS`. This is a hierarchy relation but not a normal FK.

### `roles`, `permissions`, and pivot tables

Purpose: access control through Spatie Permission.

Tables:

| Table | Purpose |
|---|---|
| `roles` | Role records, with custom `countries` JSON/text field |
| `permissions` | Permission records grouped by `permissiongroup_id` |
| `permissiongroups` | UI/controller permission grouping |
| `model_has_roles` | Assign roles to users |
| `model_has_permissions` | Assign direct permissions to users |
| `role_has_permissions` | Assign permissions to roles |

Important relation:

```text
permissiongroups.id -> permissions.permissiongroup_id
roles.id -> role_has_permissions.role_id
permissions.id -> role_has_permissions.permission_id
users.id -> model_has_roles.model_id where model_type = App\Models\User
```

Country-based staff filtering uses `roles.countries`. Controllers read the logged-in user's role countries and filter students, coaches, demo leads, and reports by country.

## 5. People Tables

### `employees`

Purpose: staff profile connected to a login user.

Important fields:

| Field | Meaning |
|---|---|
| `user_id` | Links employee profile to `users.id` |
| `decrypt_password` | Plain password storage field |
| `camera_consented`, `camera_available`, `camera_snapshot_path` | Camera check/consent fields |
| `created_by`, `updated_by` | Audit trail |

Relationships:

```text
employees.user_id -> users.id
employees.id -> camera_checks.employee_id
employees.id -> new_enrollments.employee_id
employees.id -> changeclasses.employee_id
```

Security note: `decrypt_password` stores readable password data. This should be removed or encrypted in a future security cleanup.

### `coachs`

Purpose: coach profile connected to a login user.

Important fields:

| Field | Meaning |
|---|---|
| `user_id` | Links coach profile to `users.id` |
| `status`, `index` | Active state and ordering |
| `country` | JSON/array list of coach countries |
| `zoom_id`, `zoom_password`, `zoom_link` | Zoom access/meeting information |
| `zoom_api_key`, `zoom_client_secret`, `zoom_user_id` | Zoom integration credentials |
| `portal_id`, `portal_password` | External portal credentials |
| `pan_number` | Coach PAN details |
| `decrypt_password` | Plain password storage field |

Relationships:

```text
coachs.user_id -> users.id
coachs.id -> batchs.coach_id
coachs.id -> coach_availabilities.coach_id
coachs.id -> student_batches.coach_id
coachs.id -> demo_sessions.coach_id
coachs.id -> leaverequests.coach_id
coachs.id -> coach_attendances.coach_id
coachs.id -> student_attendances.coach_id
coachs.id -> coverupclasses.old_coach_id
coachs.id -> coverupclasses.new_coach_id
coachs.id -> delayed_batches.coach_id
```

Security note: Zoom and portal credentials are sensitive. Do not export or commit database dumps containing this table.

### `students`

Purpose: enrolled student profile connected to a login user.

Important fields:

| Field | Meaning |
|---|---|
| `user_id` | Student login account |
| `first_name`, `last_name`, `age`, `mobile`, `email` | Student identity |
| `timezone`, `city`, `country`, `fees_country` | Location and fee-country mapping |
| `student_id` | External/portal student ID |
| `level_id` | Current level |
| `lastpayment_level_id` | Payment level reference |
| `monthly_fees`, `currency` | Fee values copied to student |
| `portal_password` | External Chesslang/portal password |
| `image` | Student image |
| `status` | Active/inactive/standby style state |

Relationships:

```text
students.user_id -> users.id
students.level_id -> levels.id
students.lastpayment_level_id -> paymentlevels.id
students.id -> student_batches.student_id
students.id -> student_fees.student_id
students.id -> student_attendances.student_id
students.id -> student_statuses.student_id
students.id -> orders.student_id
students.id -> feedback.student_id
students.id -> masterclasses_data.student_id
students.id -> tournaments_data.student_id
```

Important gap: country/location appears in multiple places (`students.country`, `students.fees_country`, `batchs.country`, `coachs.country`, role countries, masterclass/tournament countries). There is no normalized `countries` table.

## 6. Lead And Enrollment Tables

### `enquiries`

Purpose: website enquiries/contact form records.

Fields:

```text
first_name, last_name, email, mobile, country, message, remark
```

Relationships: no direct Eloquent relation to student or demo lead. Conversion is handled by admin flow, not a strict FK.

### `demoleadenquiries`

Purpose: incoming lead enquiry before or around demo booking.

Important fields:

| Field | Meaning |
|---|---|
| `user_id` | Linked user account when generated |
| `kids_first_name`, `kids_last_name`, `parent_name` | Lead identity |
| `mobile`, `email`, `email_otp` | Verification/contact |
| `country`, `city`, `timezone` | Location |
| `date`, `time` | User-selected demo date/time |
| `ist_date`, `ist_time` | Converted IST date/time |
| `available_device`, `enrollment_plan`, `language_preference` | Qualification data |
| `level`, `duration`, `age`, `dob` | Student/demo preference |
| `utm_source`, `utm_medium` | Marketing attribution |
| `lead_status`, `status`, `is_hide` | Lead processing state |

Relationships:

```text
demoleadenquiries.user_id -> users.id
```

Business relation: a lead enquiry can become a `demoleads` record, but this is not enforced by a direct FK.

### `demoleads`

Purpose: managed demo lead record inside admin panel.

Important fields:

| Field | Meaning |
|---|---|
| `user_id` | Student/demo login user |
| `first_name`, `last_name`, `age`, `mobile` | Lead identity |
| `city`, `country`, `kids_time_zone` | Location/timezone |
| `date`, `time` | Demo date/time in system context |
| `kids_date`, `kids_time` | Demo date/time in student timezone |
| `status` | Demo lead stage |
| `remark`, `reason` | Admin notes/status reason |

Relationships:

```text
demoleads.user_id -> users.id
demoleads.id -> demo_sessions.demolead_id
demoleads.id -> student_attendances.demolead_id
demoleads.id -> coach_attendances.demolead_id
```

Common statuses in UI/business flow:

```text
Scheduled
Rescheduled
Demo Done
Cancelled
Converted
Rowlead
Interested
Not Interested
```

### `demo_sessions`

Purpose: actual scheduled demo class instance for a demo lead.

Important fields:

| Field | Meaning |
|---|---|
| `demolead_id` | Lead being scheduled |
| `coach_id` | Coach assigned for demo |
| `level_id` | Demo level |
| `date`, `time`, `slot` | Scheduled time |
| `coach_attendance_status` | Whether coach attendance is handled |
| `start_url`, `join_url` | Zoom URLs |
| `zoom_meeting_id`, `zoom_meeting_uuid` | Zoom meeting identifiers |
| `status` | Demo session state |

Relationships:

```text
demo_sessions.demolead_id -> demoleads.id
demo_sessions.coach_id -> coachs.id
demo_sessions.level_id -> levels.id
```

Important model issue: `DemoSession::coachattendance()` uses `hasOne(CoachAttendance::class, 'demolead_id', 'id')`. That maps `coach_attendances.demolead_id` to `demo_sessions.id`, while the column name suggests it should map to `demoleads.id`. This relationship should be reviewed.

### `new_enrollments`

Purpose: admin record used when a demo/lead becomes a paid/enrolled student and is assigned to a batch.

Important fields:

| Field | Meaning |
|---|---|
| `student_id` | Enrolled student |
| `batch_id` | Assigned batch |
| `employee_id` / `employee_ids` | Staff who handled the enrollment |
| `start_date`, `end_date` | Fee/class period |
| `receive_date` | Payment received date |
| `fees`, `received_fees`, `currency` | Payment collection values |
| `remark` | Admin notes |

Relationships:

```text
new_enrollments.student_id -> students.id
new_enrollments.batch_id -> batchs.id
new_enrollments.employee_id -> employees.id
```

Important note: model casts `employee_id` and `employee_ids` as arrays, but also declares `belongsTo(Employee::class, 'employee_id')`. If `employee_id` stores JSON array values, the `belongsTo` relation will not behave correctly for multiple employees.

## 7. Batch And Scheduling Tables

### `levels`

Purpose: academic/game level master.

Fields:

```text
name, index, status
```

Relationships:

```text
levels.id -> students.level_id
levels.id -> batchs.level_id
levels.id -> student_batches.level_id
levels.id -> demo_sessions.level_id
levels.id -> student_attendances.level_id
levels.id -> paymentlevels.level_id
```

### `batchs`

Purpose: class batch/master class group.

Important fields:

| Field | Meaning |
|---|---|
| `name` | Internal batch name |
| `kids_zone_name` | Kids-facing batch name |
| `coach_id` | Primary coach |
| `level_id` | Batch level |
| `status` | Active/inactive/standby/cancelled-like states |
| `country` | JSON array of countries attached to batch |
| `start_date`, `end_date` | Batch period |
| `number_of_sessions` | Planned sessions |
| `version`, `parent_id` | Batch lineage/versioning |
| `confirm_reassign`, `confirm_reassign_batch_id` | Reassignment workflow flags |
| `start_url`, `join_url`, `zoom_meeting_id`, `zoom_meeting_uuid` | Zoom live class meeting data |

Relationships:

```text
batchs.coach_id -> coachs.id
batchs.level_id -> levels.id
batchs.parent_id -> batchs.id
batchs.id -> batch_schedules.batch_id
batchs.id -> student_batches.batch_id
batchs.id -> student_attendances.batch_id
batchs.id -> coach_attendances.batch_id
batchs.id -> coverupclasses.batch_id
batchs.id -> delayed_batches.batch_id
```

Business meaning:

- Batch is the main class container.
- Batch schedules define weekly class days/times.
- Students are assigned through `student_batches`, not directly on `batchs`.
- Cover-up, leave, delayed batch, and attendance flows all read from batch and schedule records.

### `batch_schedules`

Purpose: day/time rows for a batch.

Fields:

```text
batch_id, weekday, from_time, to_time, start_date, end_date, status, confirm_reassign
```

Relationships:

```text
batch_schedules.batch_id -> batchs.id
batch_schedules.id -> coverupclasses.schedule_id
```

Business meaning:

- One batch can have multiple weekly schedule rows.
- Dashboard and reports use schedule weekday/time to decide which classes happen today.
- If `student_batches.start_date/end_date` changes, the model updates related `batch_schedules.start_date/end_date`.

### `student_batches`

Purpose: assignment/history table connecting students to batches, coaches, and levels.

Fields:

```text
student_id, batch_id, coach_id, level_id,
status, start_date, end_date,
number_of_sessions,
confirm_reassign,
is_fees_due
```

Relationships:

```text
student_batches.student_id -> students.id
student_batches.batch_id -> batchs.id
student_batches.coach_id -> coachs.id
student_batches.level_id -> levels.id
```

Business meaning:

- This table is the real student-to-batch mapping.
- A student can have many historical batch assignments.
- Active batch is usually found by `status = ACTIVE` and/or latest created row.
- Fees due can also be reflected here through `is_fees_due`.

### `changeclasses`

Purpose: records student batch change requests/operations.

Fields:

```text
student_id, current_batch_id, batch_id,
employee_id, employee_ids,
start_date, end_date, receive_date,
fees, received_fees, currency, remark
```

Relationships:

```text
changeclasses.student_id -> students.id
changeclasses.batch_id -> batchs.id
changeclasses.current_batch_id -> batchs.id (business relation)
changeclasses.employee_id -> employees.id
```

Business meaning:

- `current_batch_id` stores the old/current batch.
- `batch_id` stores the requested/new batch.
- `employee_ids` can store multiple staff as JSON.

### `coverupclasses`

Purpose: cover-up class assignment when another coach handles a class.

Fields:

```text
batch_id, schedule_id, old_coach_id, new_coach_id, date,
start_url, join_url, zoom_meeting_id, zoom_meeting_uuid
```

Relationships:

```text
coverupclasses.batch_id -> batchs.id
coverupclasses.schedule_id -> batch_schedules.id
coverupclasses.old_coach_id -> coachs.id
coverupclasses.new_coach_id -> coachs.id
```

Business meaning:

- `old_coach_id` is the original batch coach.
- `new_coach_id` is the cover coach.
- Live class status and Zoom join/start URLs can come from this row for the cover-up date.

### `delayed_batches`

Purpose: reporting/audit table for batches/classes delayed or cancelled because no coach took the class or another class issue happened.

Fields:

```text
batch_id, coach_id, coach_attendance_id,
batch_name, country, batch_status, level_name,
timeline, canceled_date, canceled_time,
date, time
```

Relationships:

```text
delayed_batches.batch_id -> batchs.id
delayed_batches.coach_id -> coachs.id
delayed_batches.coach_attendance_id -> coach_attendances.id
```

Business meaning:

- Stores snapshot/report fields, not only FK references.
- Useful when batch name/status/level may later change but report should keep historical detail.

## 8. Availability And Leave Tables

### `coach_availabilities`

Purpose: weekly availability header for a coach.

Fields:

```text
coach_id, day_of_week, status, index
```

Relationships:

```text
coach_availabilities.coach_id -> coachs.id
coach_availabilities.id -> coach_availability_periods.availability_id
```

### `coach_availability_periods`

Purpose: time ranges under a coach availability day.

Fields:

```text
availability_id, from_period, to_period, status
```

Relationships:

```text
coach_availability_periods.availability_id -> coach_availabilities.id
```

Business meaning:

- Availability is two-level: day first, then periods.
- Coach availability grid reads both tables.
- Demo assignment and cover-up assignment use these records to find possible coaches.

### `leaverequests`

Purpose: coach leave requests.

Fields:

```text
coach_id, from_date, to_date, from_time, to_time, reason, status
```

Relationships:

```text
leaverequests.coach_id -> coachs.id
```

Business meaning:

- Leave can be full-day or time-bound.
- Leave drives cover-up class logic when coach cannot take assigned batch.

## 9. Attendance And Record Tables

### `student_attendances`

Purpose: student attendance for batch/demo classes.

Fields:

```text
student_id, type, coach_id, demolead_id, level_id, batch_id,
number_of_batch_sessions, status, remark,
date, time, homework_link, recording_link, chapter_name
```

Relationships:

```text
student_attendances.student_id -> students.id
student_attendances.coach_id -> coachs.id
student_attendances.demolead_id -> demoleads.id
student_attendances.batch_id -> batchs.id
student_attendances.level_id -> levels.id
```

Business meaning:

- `type` separates batch class attendance from demo-type attendance.
- `number_of_batch_sessions` tracks session count.
- Recording/homework/chapter fields are used for class records and dashboards.

### `coach_attendances`

Purpose: coach attendance for batch/demo classes.

Fields:

```text
coach_id, type, demolead_id, batch_id,
date, time, status,
number_of_batch_sessions, number_of_demo_sessions,
chapter_name, recording_link
```

Relationships:

```text
coach_attendances.coach_id -> coachs.id
coach_attendances.demolead_id -> demoleads.id
coach_attendances.batch_id -> batchs.id
coach_attendances.id -> delayed_batches.coach_attendance_id
```

Model issue: `CoachAttendance::demo()` currently points to `DemoLead::class` using `demo_id`, but the fillable and migrations use `demolead_id`. This should be corrected to avoid broken relation calls.

### `student_statuses`

Purpose: student status history.

Fields:

```text
student_id, status, remark
```

Relationships:

```text
student_statuses.student_id -> students.id
```

Business meaning:

- Useful for tracking inactive/standby/cancelled states separately from the main `students.status`.

### `feedback`

Purpose: feedback submitted by student/user about coach/class.

Fields:

```text
user_id, student_id, coach_id, full_name, feedback, status
```

Relationships:

```text
feedback.user_id -> users.id
feedback.student_id -> students.id
feedback.coach_id -> coachs.id
```

### `camera_checks`

Purpose: employee camera consent/availability audit.

Fields:

```text
employee_id, user_id, consented, available, snapshot_path, ip_address, user_agent
```

Relationships:

```text
camera_checks.employee_id -> employees.id
camera_checks.user_id -> users.id (model relation, DB FK appears commented in migration)
```

## 10. Fees And Payment Tables

### `paymentlevels`

Purpose: fee master per level and country/region.

Fields:

```text
name, level_id, sequence, status,
usa_fees, canada_fees, australia_fees, newzealand_fees,
india_fees, uae_fees, uk_fees, qatar_fees,
singapore_fees, european_union_fees, oman_fees
```

Relationships:

```text
paymentlevels.level_id -> levels.id
students.lastpayment_level_id -> paymentlevels.id
```

Business meaning:

- Used to determine fees by level and fee country.
- Current design stores country fees as columns, not rows.

### `student_fees`

Purpose: fee cycle records for each student.

Fields:

```text
student_id, start_date, end_date, receive_date,
currency, monthly_fees, total_amount_paid, status
```

Relationships:

```text
student_fees.student_id -> students.id
student_fees.id -> orders.student_fee_id
```

Business meaning:

- Tracks each fee period.
- Fee due reports compare fee end dates/status.
- Activity logging is enabled on important fee fields.

### `orders`

Purpose: payment gateway order/payment record.

Fields:

```text
student_id, student_fee_id,
razorpay_payment_id, razorpay_data,
amount, currency,
hdfc_order_id, hdfc_status, hdfc_data
```

Relationships:

```text
orders.student_id -> students.id
orders.student_fee_id -> student_fees.id
```

Business meaning:

- One payment order belongs to a student.
- It may also connect to a specific fee cycle.
- It supports Razorpay and later HDFC fields.

## 11. Master Content Tables

### `holidays`

Purpose: holiday master by date range and country.

Fields:

```text
name, start_date, end_date, description, status, country
```

`country` is cast as array. It is not connected to a country master table.

### `timezones`

Purpose: conversion/availability map between Indian time and country time.

Fields:

```text
country, weekday, timezone,
india_start_time, india_end_time,
country_start_time, country_end_time,
status
```

No FK relation. This is a lookup/master table.

### `masterclasses`

Purpose: masterclass event/class.

Fields:

```text
batch_ids, student_ids, level_ids,
name, date, time, coach_id, country,
status, is_reminder_sent,
start_url, join_url, zoom_meeting_id, zoom_meeting_uuid
```

Relationships:

```text
masterclasses.coach_id -> coachs.id
masterclasses.id -> masterclasses_data.masterclass_id
```

Important note: `batch_ids`, `student_ids`, `level_ids`, and `country` are JSON arrays, not relational pivot tables. `masterclasses_data` partially normalizes selected students.

### `masterclasses_data`

Purpose: student mapping table for masterclass participation.

Fields:

```text
masterclass_id, student_id
```

Relationships:

```text
masterclasses_data.masterclass_id -> masterclasses.id
masterclasses_data.student_id -> students.id
```

### `tournaments`

Purpose: tournament event.

Fields:

```text
batch_ids, student_ids, level_ids,
name, date, time, link, country,
certificate, status, is_reminder_sent
```

Important note: `batch_ids`, `student_ids`, `level_ids`, `country`, and `certificate` are JSON arrays.

### `tournaments_data`

Purpose: student mapping table for tournament participation.

Fields:

```text
tournament_id, student_id
```

Relationships:

```text
tournaments_data.tournament_id -> tournaments.id
tournaments_data.student_id -> students.id (business relation; model only defines tournament relation)
```

### Website content tables

| Table | Purpose | Main Relations |
|---|---|---|
| `blogs` | Blog content | none |
| `meet_our_kids` | Website student showcase | audit users only |
| `meet_our_tutors` | Website tutor showcase | audit users only |
| `galleries` | Gallery category/album | has many `gallery_images` |
| `gallery_images` | Images inside gallery | belongs to `galleries` |
| `events` | Website events | none |

## 12. System Tables

### Laravel auth/API tables

| Table | Purpose |
|---|---|
| `password_resets` | Password reset tokens |
| `personal_access_tokens` | Laravel Sanctum-style personal tokens if used |
| `oauth_auth_codes` | Passport auth codes |
| `oauth_access_tokens` | Passport access tokens |
| `oauth_refresh_tokens` | Passport refresh tokens |
| `oauth_clients` | Passport clients |
| `oauth_personal_access_clients` | Passport personal clients |

### Queue/log tables

| Table | Purpose |
|---|---|
| `failed_jobs` | Failed Laravel jobs |
| `activity_log` | Spatie activity log |
| `cruds` | CRUD generator metadata/config |

## 13. Audit Pattern

Most business tables include:

```text
created_by
updated_by
created_at
updated_at
```

The model boot methods usually set:

```text
created_by = Auth::id()
updated_by = Auth::id()
```

on create, and update `updated_by` on update.

BaseModel also exposes:

```text
createdBy()
updatedBy()
creatorName()
updatorName()
```

So the common audit relation is:

```text
<table>.created_by -> users.id
<table>.updated_by -> users.id
```

## 14. Main Business Flows Through Tables

### Website enquiry to demo

```text
enquiries or demoleadenquiries
    -> user may be created
    -> demoleads
    -> demo_sessions
    -> coach_attendances / student_attendances for demo records
```

### Demo lead to student

```text
demoleads status becomes Converted
    -> users record for student login
    -> students record
    -> student_fees record
    -> new_enrollments record
    -> student_batches record
```

### Student assigned to batch

```text
students
    -> student_batches
        -> batchs
            -> batch_schedules
            -> coachs
            -> levels
```

### Batch class attendance

```text
batchs + batch_schedules decide class occurrence
    -> coach_attendances records coach status
    -> student_attendances records student status
    -> recording/homework/chapter data gets attached
```

### Fees and payment

```text
students
    -> paymentlevels through level/lastpayment_level_id
    -> student_fees
        -> orders
```

### Batch change

```text
students current student_batches row
    -> changeclasses record
    -> old student_batches row may become inactive
    -> new student_batches row created/activated
```

### Cover-up class

```text
coachs leave / unavailable
    -> batchs + batch_schedules identify affected class
    -> coverupclasses stores old_coach_id and new_coach_id
    -> class join/start URL may be generated for cover coach
    -> attendance/reporting reads cover-up context
```

### No coach / delayed class

```text
batchs + coach_attendances
    -> delayed_batches stores delayed/cancelled snapshot
    -> reporting uses delayed_batches for unresolved class issues
```

## 15. Country And Location Data

Country/location is repeated in many places:

| Table | Field |
|---|---|
| `students` | `country`, `fees_country`, `timezone`, `city` |
| `coachs` | `country` JSON |
| `batchs` | `country` JSON |
| `roles` | `countries` JSON/text |
| `demoleads` | `country`, `kids_time_zone`, `city` |
| `demoleadenquiries` | `country`, `timezone`, `city` |
| `holidays` | `country` JSON |
| `masterclasses` | `country` JSON |
| `tournaments` | `country` JSON |
| `timezones` | `country`, `timezone` |
| `paymentlevels` | country fees as separate columns |

Current issue:

- There is no single normalized location/country master.
- Country is sometimes a string and sometimes JSON array.
- Student country, fee country, coach country, batch country, and role country can drift apart.
- Staff access depends on role countries, while batch/student/coach assignment also uses country fields.

Recommended future schema improvement:

```text
countries
    id
    name
    code
    timezone
    currency
    status

student_country_assignments or students.country_id
coach_country_assignments
batch_country_assignments
role_country_assignments
```

At minimum:

- create one `countries` master,
- use `country_id` or normalized pivot rows,
- keep `fees_country_id` separate only if fee country can differ from physical country,
- stop storing mixed comma/string/JSON country values.

## 16. Known Relationship/Schema Gaps

### 1. Mixed JSON arrays and relational pivots

Examples:

```text
masterclasses.batch_ids
masterclasses.student_ids
masterclasses.level_ids
tournaments.batch_ids
tournaments.student_ids
tournaments.level_ids
roles.countries
batchs.country
coachs.country
holidays.country
```

This makes reporting and validation harder.

Better pattern:

```text
masterclass_batches
masterclass_students
masterclass_levels
tournament_batches
tournament_students
tournament_levels
role_countries
batch_countries
coach_countries
```

### 2. Plain password/secret fields

Risk fields:

```text
employees.decrypt_password
coachs.decrypt_password
coachs.zoom_api_key
coachs.zoom_client_secret
coachs.portal_password
students.portal_password
```

These are not safe for casual exports or Git-tracked SQL dumps.

### 3. Broken or suspicious model relations

Review these:

```text
CoachAttendance::demo()
    uses demo_id but table/model uses demolead_id

DemoSession::coachattendance()
    maps coach_attendances.demolead_id to demo_sessions.id
    likely should map to demoleads.id or use demo_session_id if such column is added

NewEnrollment::employee()
    belongsTo employee_id while employee_id may be cast as array
```

### 4. Missing normalized master tables

There is no normalized master for:

```text
countries
currencies
student statuses
demo statuses
batch statuses
payment statuses
```

Many statuses are stored as raw strings.

### 5. Cascade delete risk

Many FKs use `onDelete('cascade')`.

That means deleting a user/coach/student/batch can delete linked operational records. For ERP history, soft deletes or restricted deletes may be safer.

Examples:

```text
students -> student_fees
students -> student_batches
batchs -> batch_schedules
batchs -> student_batches
coachs -> batchs
users -> employees/coachs
```

## 17. Recommended Database Improvements

Priority 1:

1. Add database indexes for high-use filters:
   ```text
   students.status
   students.country
   students.fees_country
   student_batches.student_id/status
   student_batches.batch_id/status
   student_batches.coach_id/status
   batch_schedules.batch_id/weekday/status
   student_attendances.student_id/date
   student_attendances.batch_id/date
   coach_attendances.coach_id/date
   demoleads.status/country
   demo_sessions.coach_id/date
   orders.student_id/student_fee_id
   ```

2. Fix suspicious model relations:
   ```text
   CoachAttendance::demo()
   DemoSession::coachattendance()
   NewEnrollment employee relation/cast mismatch
   ```

3. Stop storing plain passwords.

Priority 2:

1. Normalize countries.
2. Normalize masterclass/tournament selection arrays into pivot tables.
3. Add status master/enums or constants.
4. Add soft deletes to operational tables.
5. Add unique constraints where needed:
   ```text
   students.user_id
   coachs.user_id
   employees.user_id
   active student_batches per student/batch
   paymentlevels.level_id + sequence/name
   ```

Priority 3:

1. Move user-uploaded content to S3.
2. Add audit logs for credential views/updates.
3. Add data retention rules for demo leads and camera snapshots.
4. Add DB-level constraints for currently soft relationships.

## 18. Developer Handover Summary

For any developer taking over this ERP:

- Start with `users`; every person type links back to it.
- `coachs`, `employees`, and `students` are profile tables, not standalone auth tables.
- `batchs` is the main class container.
- `batch_schedules` defines class days and time.
- `student_batches` is the true student-to-batch assignment history.
- `student_fees` and `orders` handle payment history.
- `demoleadenquiries`, `demoleads`, and `demo_sessions` handle the lead/demo pipeline.
- `student_attendances` and `coach_attendances` are the record/reporting backbone.
- Country/location is currently duplicated across flows and should be normalized in a future schema cleanup.
- Do not push DB dumps because several tables contain credentials or sensitive student data.
