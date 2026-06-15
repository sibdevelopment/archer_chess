# ArcherKids Developer Manual

Last reviewed: 2026-04-28

## 1. Project Summary

ArcherKids is a Laravel 10 application for an online chess academy, branded in several places as Archer Kids / Archer Chess Academy.

The project is not just a public website. It contains:

- A public marketing website.
- Trial class and enquiry capture.
- Admin CRM-style lead management.
- Demo class scheduling.
- Student enrollment and batch assignment.
- Coach availability and attendance.
- Student attendance, recordings, homework, certificates, fees, and dashboard.
- Masterclasses and tournaments.
- Razorpay and HDFC payment flows.
- Zoom meeting creation and recording collection.
- Scheduled jobs for recordings, reminders, fee due status, delayed classes, and payment checks.

The default `README.md` is still the GitLab template, so this file is intended to be the practical handover document for developers.

## 2. Technology Stack

Backend:

- PHP `^8.0.2`
- Laravel `^10.0`
- MySQL
- Laravel Breeze auth scaffolding
- Laravel Passport and Sanctum are both installed, but the API routes currently use Passport-style API auth.

Frontend/build:

- Blade templates
- Vite
- Tailwind CSS
- Alpine.js
- Axios

Main Composer packages:

- `laravel/framework`
- `laravel/passport`
- `laravel/sanctum`
- `spatie/laravel-permission`
- `spatie/laravel-activitylog`
- `yajra/laravel-datatables`
- `maatwebsite/excel`
- `barryvdh/laravel-dompdf`
- `tecnickcom/tcpdf`
- `setasign/fpdf`
- `setasign/fpdi`
- `razorpay/razorpay`
- `mobiledetect/mobiledetectlib`
- `vinkla/hashids`

## 3. Important Directories

```text
app/Http/Controllers/Frontend    Public website, booking, contact, country pages
app/Http/Controllers/Admin       Admin panel, CRM, batch, student, coach, reports
app/Http/Controllers/Api         Mobile/API endpoints
app/Models                       Eloquent models
app/Console/Commands             Scheduled and manual Artisan commands
app/Mail                         Email classes
app/Services                     External service code, mainly Zoom
resources/views/Frontend         Public website views
resources/views/Admin            Admin and dashboard views
resources/views/Email            Email templates
routes/frontend.php              Public website routes
routes/backend.php               Admin, student dashboard, payments, data tools
routes/api.php                   API routes
routes/auth.php                  Login/register/password routes
database/migrations              Database schema history
public/frontend1                 Static frontend assets
public/reports_pdfs              Generated report PDFs
```

## 4. Routing Structure

`routes/web.php` is a small entry point. It loads:

```php
require __DIR__ . '/frontend.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/backend.php';
```

### Public Website Routes

Defined in `routes/frontend.php`.

Key routes:

- `/` home
- `/about`
- `/contact`
- `/gallery`
- `/event`
- `/blog`
- `/blog/{slug}`
- `/privacy`
- `/terms`
- `/refund/policy`
- `/shipping/policy`
- `/book/trial/class`
- `/confirm/trial/class`
- `/online-chess/{country}`
- `/confirm/trial/class/country`

Main controller:

```text
app/Http/Controllers/Frontend/HomeController.php
```

### Auth Routes

Defined in `routes/auth.php`.

This is mostly Laravel Breeze style auth:

- `/login`
- `/register`
- `/forgot-password`
- `/reset-password/{token}`
- `/logout`

Custom routes:

- `/student/login`
- `/student/login/store`
- `/coach/login`

Student login is handled by `AuthenticatedSessionController`.

### Admin and Internal Routes

Defined in `routes/backend.php`.

Admin routes are protected by:

```php
Route::middleware(['auth', 'admin', 'preventBackHistory'])
```

Most admin routes are under:

```text
/admin/...
```

Major admin modules:

- Dashboard
- Roles and permissions
- Employees
- Coaches
- Coach availability
- Enquiries
- Lead enquiries
- Demo leads
- Demo sessions
- New enrollments
- Students
- Student fees
- Batches
- Batch schedules
- Change classes
- Cover-up classes
- Reports
- Leave requests
- Feedback
- Levels
- Payment levels
- Holidays
- Masterclasses
- Tournaments
- Blogs
- Meet Our Kids
- Meet Our Tutors
- Galleries and gallery images
- Events
- Student dashboard
- Coach dashboard/schedule/attendance
- Timezone lookup
- Camera check experiments

There are also authenticated data/report utility routes outside the `/admin` prefix:

- `/fees_records/{year}/{month}/{country}`
- `/fees_collection/{year}/{month}/{date}/{country?}`
- `/cancel_batches/{fromdate}/{todate}`
- `/multiple_batch`
- `/multiple_student_in_batch`
- `/multiple_student_attendance`
- `/same_users`
- `/coach-attendance`
- `/get/zoom/users`
- `/student_inactive/{month}`
- `/student/cancelled/attendance`

Payment routes:

- `POST /razorpay/verify`
- `POST /create/order`
- `POST /hdfc/payment`
- `POST /student/order/thankyou`

## 5. Main Business Flow

The core operational flow appears to be:

```text
Visitor opens website
-> Visitor submits enquiry or books trial class
-> System creates lead/demo lead enquiry and user
-> Admin converts lead into demo lead
-> Admin schedules demo session with coach
-> Coach conducts demo session
-> Admin converts demo lead into student
-> Admin assigns student to batch
-> Coach teaches regular classes
-> Coach/admin/student attendance is tracked
-> Student sees dashboard, recordings, homework, fees, certificates
-> Fee due jobs mark students and reminders are sent
-> Student pays through Razorpay or HDFC
-> Payment creates StudentFee and reactivates student if needed
```

## 6. Public Website and Trial Booking

Controller:

```text
app/Http/Controllers/Frontend/HomeController.php
```

Views:

```text
resources/views/Frontend
resources/views/Frontend/BookTrialClass
resources/views/Frontend/CourseDetails
resources/views/Frontend/Design
```

Important methods:

- `home()` loads home page data, featured blogs, active kids, and tutors.
- `blog()` lists active blogs.
- `blogDetails($slug)` shows one blog and similar blogs.
- `gallery()` loads active galleries and active gallery images.
- `event()` loads active events.
- `contactSubmit()` stores a contact enquiry in `enquiries`.
- `bookTrialClass()` renders the trial class page.
- `storeBookATrailForm()` validates and stores a trial booking.
- `countryView()` shows country-specific landing pages.
- `storeBookATrailFormForCountry()` stores country-page trial bookings.
- `enquirySubmit()` stores a general enquiry from modal/popup forms.

Trial booking creates:

- A `DemoLeadEnquiry` record.
- A `User` record.
- A generated password in the pattern `archer@{user_id}` stored in `users.device_id`.
- A `Student` role assignment.
- An email using `DemoBookingMail` in the normal trial flow.

Country/timezone support exists in `app/helpers.php`:

- `getTimezones()`
- `convertTimeZoneString()`
- `convertTimeZoneWiseTime()`
- `convertTimeZomeWiseDate()`
- `convertToStudentLocalTime()`
- `convertToKidsTime()`

Note: There are duplicate old/new design routes. `/design/...` routes use `resources/views/Frontend/Design/...` and appear to be a newer design version.

## 7. Roles, Permissions, and Admin Access

Roles and permissions use Spatie Permission.

Important files:

```text
app/Http/Middleware/Admin.php
app/Models/User.php
app/Models/Role.php
app/Models/Permission.php
app/Models/Permissiongroup.php
database/seeders/PermissionSeeder.php
```

`Admin` middleware works differently from simple role checks:

1. It checks that the user exists.
2. It checks `Auth::user()->status == 'ACTIVE'`.
3. It reads the current controller and method.
4. It checks the logged-in user's permissions.
5. It allows the request only when one permission group matches the controller and allowed method.

That means adding a new admin controller method usually requires updating permissions/permission groups, not just adding a route.

User hierarchy support exists in `User::getchildrens()` and `User::getDescendantIds()`, but the global `HierarchyScope` is commented out in `BaseModel`.

## 8. Core Data Model

Important models:

```text
User
Employee
Coach
CoachAvailability
CoachAvailabilityPeriod
DemoLeadEnquiry
DemoLead
DemoSession
Student
StudentBatch
StudentAttendance
CoachAttendance
StudentFee
Batch
BatchSchedule
Level
Paymentlevel
Order
Changeclass
Coverupclass
LeaveRequest
Holiday
Masterclass
MasterclassData
Tournament
TournamentData
Blog
Gallery
GalleryImage
Event
Feedback
MeetOurKid
MeetOurTutor
DelayedBatch
CameraCheck
```

### User

Table: `users`

Used for login and roles. Fillable fields include:

- `first_name`
- `last_name`
- `email`
- `country_code`
- `mobile`
- `password`
- `status`
- `device_id`

Relationships:

- `coach()`
- Spatie roles/permissions

### Student

Table: `students`

Represents enrolled students.

Important fields:

- `user_id`
- `first_name`
- `last_name`
- `age`
- `mobile`
- `email`
- `timezone`
- `city`
- `country`
- `fees_country`
- `student_id`
- `level_id`
- `monthly_fees`
- `currency`
- `image`
- `portal_password`
- `lastpayment_level_id`
- `status`

Relationships:

- `user()`
- `level()`
- `studentFees()`
- `studentBatches()`
- `studentAttendances()`
- `paymentlevel()`
- `latestBatch()`

Student has helper message generation for WhatsApp-style enrollment messages, including dashboard and Chesslang credentials.

### Coach

Table: `coachs`

Represents chess coaches.

Important fields:

- `user_id`
- `country`
- `zoom_id`
- `zoom_password`
- `zoom_link`
- `zoom_api_key`
- `zoom_client_secret`
- `zoom_user_id`
- `portal_id`
- `portal_password`
- `pan_number`
- `status`

Relationships:

- `user()`
- `coach_availabilities()`
- `batches()`
- `studentBatches()`
- `leaves()`

### Batch

Table: `batchs`

Represents a recurring class group.

Important fields:

- `name`
- `kids_zone_name`
- `coach_id`
- `level_id`
- `country`
- `version`
- `parent_id`
- `number_of_sessions`
- `start_date`
- `end_date`
- `start_url`
- `join_url`
- `zoom_meeting_id`
- `zoom_meeting_uuid`
- `status`

Relationships:

- `level()`
- `coach()`
- `batchSchedules()`
- `studentBatches()`
- `parent()`
- `ancestors()`
- `coachAttendances()`

Batch versioning/reassignment is important. A batch can have `parent_id`, `version`, and `confirm_reassign` fields. Student reassignment code often looks for latest non-inactive batches under a parent batch.

### BatchSchedule

Table: `batch_schedules`

Stores recurring day/time slots for a batch, such as weekday, from time, and to time.

Used heavily for:

- Coach schedule.
- Student next class.
- Attendance.
- Fee duration calculations.
- Conflict checking.

### StudentBatch

Table: `student_batches`

Join/history table between students and batches.

Important fields seen across code:

- `student_id`
- `batch_id`
- `coach_id`
- `level_id`
- `number_of_sessions`
- `start_date`
- `end_date`
- `confirm_reassign`
- `is_fees_due`
- `status`

This table is central. A student may have multiple student-batch records over time.

### StudentAttendance

Table: `student_attendances`

Tracks class attendance and class artifacts.

Important fields:

- `student_id`
- `type`
- `coach_id`
- `demolead_id`
- `level_id`
- `batch_id`
- `number_of_batch_sessions`
- `status`
- `remark`
- `date`
- `time`
- `homework_link`
- `recording_link`
- `chapter_name`

Used by student dashboard, coach dashboard, recordings, homework, and reports.

### StudentFee

Table: `student_fees`

Tracks fee periods and payments.

Important fields:

- `student_id`
- `start_date`
- `end_date`
- `receive_date`
- `currency`
- `monthly_fees`
- `total_amount_paid`
- `status`

Uses Spatie Activitylog and `Hashidable`.

### DemoLead and DemoSession

`DemoLead` table: `demoleads`

Represents a trial/demo candidate after lead conversion.

`DemoSession` table: `demo_sessions`

Represents a scheduled demo class with:

- lead
- date
- time/slot
- coach
- level
- status

Demo sessions are used in lead conversion, coach schedules, demo attendance, and recording fetch jobs.

### Masterclass and Tournament

Used for special classes/events beyond regular batches.

`Masterclass` stores:

- coach
- batch ids
- student ids
- level ids
- name
- date/time
- status

`Tournament` and `TournamentData` support tournament participation and certificates.

### Order

Table: `orders`

Used for payment flows.

Razorpay fields:

- `razorpay_payment_id`
- `razorpay_data`

HDFC fields added in later migrations:

- `hdfc_order_id`
- `hdfc_data`

Common fields:

- `student_id`
- `student_fee_id`
- `amount`
- `currency`
- `status`

## 9. Admin Modules

### Dashboard

Controller:

```text
app/Http/Controllers/Admin/DashboardController.php
```

Responsibilities:

- Super admin dashboard.
- Coach dashboard.
- Today's/yesterday's schedules.
- Calendar data.
- Student data DataTables.
- Batch data DataTables.
- Missed sessions.
- Coach demo attendance.
- Coach batch attendance.
- Masterclass attendance.
- Coach availability lookup.
- Unmarked attendance checks.

This controller is large and contains core scheduling/attendance logic. Be careful when editing it.

### Leads and Demo Leads

Controllers:

```text
app/Http/Controllers/Admin/LeadEnquiryController.php
app/Http/Controllers/Admin/DemoLeadController.php
app/Http/Controllers/Admin/DemoSessionsController.php
```

Flow:

1. Website creates enquiry or demo lead enquiry.
2. Admin views lead enquiries.
3. Admin can convert/reject lead.
4. Admin creates demo lead and demo session.
5. Demo session can be scheduled with available coach slots.
6. Demo lead can be converted to student.

Important routes:

- `admin.leadenquiries.*`
- `admin.demoleads.*`
- `admin.demoleads.demo_sessions.*`

### Students

Controller:

```text
app/Http/Controllers/Admin/StudentController.php
```

Responsibilities:

- List/filter/export students.
- Create and update students.
- Assign roles/users.
- Show student record.
- Manage status.
- Show related batches/fees/attendance.
- Delete attendance entries.
- Camera-check experimental methods are also present here.

### Batches

Controller:

```text
app/Http/Controllers/Admin/BatchController.php
```

Responsibilities:

- Create/update/list batches.
- Attach coach and level.
- Maintain batch schedules.
- Assign/reassign students.
- Change coaches.
- Check schedule conflicts.
- Mark batch attendance.
- Handle batch versioning and parent-child batches.

Important views:

```text
resources/views/Admin/Batchs
resources/views/Admin/BatchSchedules
resources/views/Admin/StudentBatches
```

### Coach Availability

Controller:

```text
app/Http/Controllers/Admin/CoachAvailabilityController.php
```

Stores days and periods when a coach is available.

Used by:

- Demo session scheduling.
- Batch scheduling.
- Coach reports.
- Availability dashboard.

### Reports

Controller:

```text
app/Http/Controllers/Admin/ReportController.php
```

Responsibilities include:

- Coach schedule reports.
- Coach calendar.
- Coach availability.
- Demo attendance reports.
- Batch attendance reports.
- Completed batch/demo/cover-up class data.
- Delayed batch reports.
- Coach leave reports.
- Masterclass attendance data.
- PDF report generation.

Report views live under:

```text
resources/views/Admin/CoachReports
```

Generated PDFs are stored under:

```text
public/reports_pdfs
```

### Student Dashboard

Controller:

```text
app/Http/Controllers/Admin/StudentDashboardController.php
```

Routes are under admin prefix, but functionally this is the student's logged-in area.

Features:

- Dashboard
- Batches/classes
- Fees
- Recordings
- Homework
- Certificates
- Tournaments
- Masterclasses
- Join class
- Mark attendance

Views:

```text
resources/views/Admin/StudentDashboard
```

### CMS Modules

Admin modules for public website content:

- Blogs
- Galleries
- Gallery images
- Events
- Meet Our Kids
- Meet Our Tutors
- Feedback

Public frontend reads active records from these modules.

## 10. Payment Flows

### Razorpay

Route:

```text
POST /razorpay/verify
```

Defined directly in `routes/backend.php` as a closure.

Current behavior:

1. Fetch Razorpay payment by `payment_id`.
2. Capture payment if it is only authorized.
3. Verify captured status.
4. Create `Order`.
5. Create `StudentFee`.
6. Link order to student fee.
7. Set student status to `ACTIVE`.
8. If student was `FEESDUE`, attempt to restore/reassign batch membership.

Important warning: Razorpay credentials are currently hardcoded in this route. Move them to `.env` and `config/services.php`.

### HDFC

Controller:

```text
app/Http/Controllers/HdfcPaymentController.php
```

Routes:

```text
POST /create/order
POST /hdfc/payment
POST /student/order/thankyou
```

Flow:

1. `createOrder()` validates student and amount.
2. It determines the student's next payment level.
3. It checks amount against the correct next module amount or next three-module total.
4. It creates an `Order` with `PENDING` status.
5. `createSession()` calls HDFC SmartGateway and returns a redirect URL.
6. HDFC returns to `/student/order/thankyou`.
7. `studentOrderThankyou()` fetches order status from HDFC.
8. If status is `CHARGED`, order becomes `COMPLETED`, `StudentFee` is created, and student becomes `ACTIVE`.
9. If status is not charged, order becomes `FAILED`.

Important warning: HDFC credentials are currently hardcoded in the controller. Move them to `.env` and `config/services.php`.

## 11. Zoom Integration

Service:

```text
app/Services/ZoomMeetingService.php
```

Responsibilities:

- Generate OAuth access token.
- Create regular batch meetings.
- Create demo session meetings.
- Create meetings for specific Zoom users.
- Create cover-up class meetings.
- Fetch Zoom users.
- Fetch recording links.

Related command jobs:

- `create:meeting-links`
- `create:demo-session-link`
- `get:meeting-recordings`
- `get:demo-recordings`
- `get:masterclass-recording`
- `get:coverupclass-recording`

Coach records also store Zoom-related credentials/identifiers:

- `zoom_api_key`
- `zoom_client_secret`
- `zoom_user_id`
- `zoom_id`
- `zoom_password`
- `zoom_link`

The code has both app-level and coach-level Zoom concepts. Before changing Zoom behavior, trace the exact controller/command path being used.

## 12. Emails and Notifications

Mail classes:

```text
app/Mail
```

Important mails:

- `DemoBookingMail`
- `DemoScheduleMail`
- `DemoCompleteMail`
- `EnrollmentMail`
- `ConvertedStudentMail`
- `StudentFeeInvoiceMail`
- `FeesDueMail`
- `Before24HrFeesDueMail`
- `Before6HrFeesDueMail`
- `MasterclassMail`
- `TournamentMail`
- `OtpMail`
- `EnquiryMail`
- `EnquiryConvertedMail`
- `EnquiryRejectedMail`
- `ThankYouMail`
- `ConfirmUserMail`

Email templates:

```text
resources/views/Email
```

SMS/WhatsApp helpers:

- `sendSms()` in `app/helpers.php`
- `sendWhatsAppMessage()` in `app/helpers.php`

Important warning: some API tokens are hardcoded in helpers. Move them into `.env`.

## 13. Artisan Commands and Scheduler

Commands live in:

```text
app/Console/Commands
```

Scheduler lives in:

```text
app/Console/Kernel.php
```

Currently scheduled commands:

```text
get:coverupclass-recording          hourly
get:masterclass-recording           hourly
get:meeting-recordings              hourly
get:demo-recordings                 hourly
cancel:delay-batch                  every 10 minutes
masterclass:reminder                hourly
tournament:reminder                 hourly
check:payment                       every minute
check:fess-due-students             daily at 00:30
set:fess-due-in-usa-canada          daily at 21:05
set:fess-due-in-uk                  daily at 12:00
```

Other command signatures found:

```text
assign:role-to-student
BatchAttendance:not-marked-attendance
cleanup:camera-snapshots
hdfc:order-status {order_id}
create:meeting-links
create:demo-session-link
convert:old-batches-into-new
mark:batch-attendance
shift:batch-one-hour
send:fee-due-reminder-24hrs
reminder:feesdue-24hr-students
reminder:feesdue-24hr-usa-canada
reminder:feesdue-24hr-uk
set:reminder-onehour-other-countries
set:onehour-reminder-in-usa-canada
set:onehour-reminder-in-uk
demo:complete {demo_id?}
test:command
masterclass:reminderaa
```

To make scheduled jobs run in production, the server needs a cron entry like:

```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

On Windows/XAMPP, use Task Scheduler to run:

```powershell
php artisan schedule:run
```

every minute from the project directory.

## 14. API

Routes:

```text
routes/api.php
```

All API routes are wrapped in `basic_auth` middleware. The request must include:

```text
authtoken=1234
```

Public API endpoints:

```text
POST /api/get-app-version
POST /api/register
POST /api/verify-otp
POST /api/login
POST /api/resend-otp
```

Token-protected API endpoints:

```text
POST /api/user
POST /api/user/update
POST /api/sliders
```

Important warning: `basic_auth` uses a hardcoded token `1234`. Move this to `.env` if the API is actually used.

Also note: `app/Http/Controllers/Api/HomeController.php` contains many methods and model references that are not currently wired in `routes/api.php` and appear to be legacy/copied code from another app. Do not assume those methods are active.

## 15. Environment Setup

### Required PHP Extensions

Based on Composer packages and current runtime warnings, make sure these are enabled:

- `curl`
- `fileinfo`
- `gd`
- `mbstring`
- `mysqli`
- `openssl`
- `pdo_mysql`
- `zip`
- `exif`
- `bz2`
- `gettext`

The current local PHP tried to load extensions from `C:\xampp\php\ext` and failed. Fix XAMPP/PHP extension setup before relying on Artisan commands.

### Install Backend Dependencies

```bash
composer install
```

### Install Frontend Dependencies

```bash
npm install
```

### Configure Environment

Copy env file:

```bash
copy .env.example .env
```

or on Linux/macOS:

```bash
cp .env.example .env
```

Then configure:

```text
APP_NAME
APP_ENV
APP_KEY
APP_DEBUG
APP_URL
DB_CONNECTION
DB_HOST
DB_PORT
DB_DATABASE
DB_USERNAME
DB_PASSWORD
MAIL_*
RAZORPAY_*
HDFC_*
ZOOM_*
WHATSAPP_*
FAST2SMS_*
API_BASIC_AUTH_TOKEN
```

Generate app key:

```bash
php artisan key:generate
```

Run migrations:

```bash
php artisan migrate
```

Seed permissions/roles if seeders are valid:

```bash
php artisan db:seed
```

Build frontend:

```bash
npm run build
```

Run locally:

```bash
php artisan serve
npm run dev
```

For XAMPP, the site may also be served through Apache with the document root pointing to `public`.

## 16. Database Notes

The migration history starts with standard Laravel/Passport tables and then grows into academy-specific tables from 2023 onward.

Important table groups:

Auth:

- `users`
- `password_resets`
- `personal_access_tokens`
- Passport OAuth tables
- Spatie permission tables

Operations:

- `employees`
- `coachs`
- `coach_availabilities`
- `coach_availability_periods`
- `leaverequests`
- `holidays`

Lead/demo:

- `enquiries`
- `demoleadenquiries`
- `demoleads`
- `demo_sessions`

Student/class:

- `students`
- `student_batches`
- `student_attendances`
- `student_fees`
- `student_statuses`
- `batchs`
- `batch_schedules`
- `levels`
- `paymentlevels`

Special classes/events:

- `masterclasses`
- `masterclasses_data`
- `tournaments`
- `tournaments_data`
- `coverupclasses`
- `changeclasses`
- `delayed_batches`

CMS:

- `blogs`
- `galleries`
- `gallery_images`
- `events`
- `feedback`
- `meet_our_kids`
- `meet_our_tutors`

Payments:

- `orders`

Logs/experiments:

- `activity_log`
- `camera_checks`

Naming note: several table names are non-standard English/pluralization, for example `batchs` and `coachs`. Do not rename them casually; models explicitly depend on those names.

## 17. Views and Layouts

Main layouts:

```text
resources/views/layouts/frontend.blade.php
resources/views/layouts/revamp.blade.php
resources/views/layouts/admin.blade.php
resources/views/layouts/admin/navbar.blade.php
```

Public frontend:

```text
resources/views/Frontend
resources/views/Frontend/Design
resources/views/Frontend/BookTrialClass
resources/views/Frontend/CourseDetails
resources/views/Frontend/BlogDetails
```

Admin:

```text
resources/views/Admin
```

Emails:

```text
resources/views/Email
```

PDF/invoice/certificate:

```text
resources/views/Admin/StudentFees/invoicePdf.blade.php
resources/views/Design/invoicePdf.blade.php
resources/views/Admin/Tournaments/certificate.blade.php
resources/views/Admin/StudentDashboard/Form-Sections
resources/views/Admin/CoachReports/reportpdf.blade.php
```

## 18. Exports and PDFs

Excel exports:

```text
app/Exports/StudentsExport.php
app/Exports/NewEnrollmentExport.php
app/Exports/DemoleadsExport.php
```

PDF tooling:

- DomPDF
- TCPDF
- FPDF/FPDI

PDF use cases:

- Student fee invoice.
- Coach reports.
- Student certificates.
- Tournament certificates.

## 19. Known Risks and Cleanup Items

These are not accusations, just practical handover notes.

### Secrets in Code

Secrets or real-looking credentials currently appear in:

- `.env.example`
- `routes/backend.php`
- `app/Http/Controllers/HdfcPaymentController.php`
- `app/helpers.php`

Action:

- Rotate exposed credentials if this repo has been shared.
- Move secrets to `.env`.
- Reference them through `config/services.php`.
- Replace `.env.example` with safe placeholders.

### Hardcoded API Auth

`BasicAuth` middleware checks `authtoken` against hardcoded `1234`.

Action:

- Move token to `.env`.
- Consider replacing this with Sanctum/Passport if this API is actively used.

### Route Closure Contains Business Logic

`/razorpay/verify` is a large closure in `routes/backend.php`.

Action:

- Move it into a controller/service.
- Add request validation.
- Add tests around payment state transitions.

### PHP/XAMPP Extension Problems

Local `php artisan route:list` failed because many PHP extensions were not loading.

Action:

- Fix `php.ini` extension paths.
- Ensure XAMPP PHP version matches Composer requirements.

### Mixed Legacy Code

Some API controller code references models like `Package`, `Slider`, `Quote`, `Frameposter`, `Stickerimage`, etc. These do not appear central to ArcherKids and are not currently wired by `routes/api.php`.

Action:

- Confirm whether any mobile app depends on them.
- Remove or isolate if unused.

### Naming and Typos

Examples:

- `batchs`
- `coachs`
- `FessDue`
- `Tounament`
- `dummu.php`
- `Untitled-1`
- `MasterclassMailaa`

Action:

- Avoid broad renames unless you are prepared for migrations, data changes, and route/view updates.
- Fix names gradually only when touching a module.

### Very Large Controllers

`DashboardController`, `BatchController`, `StudentController`, and payment code contain a lot of business logic.

Action:

- When adding features, prefer extracting services for scheduling, attendance, payments, and notifications.

### Inconsistent Payment Duration Logic

Some code calculates fee end dates as 15 days, some 29/30 days, and some comments mention 10 upcoming sessions.

Action:

- Confirm business rules with the client.
- Centralize fee duration calculation.

### Timezone Complexity

Timezone conversion is split across helpers and controller logic. Some timezone labels are aliases, some are PHP timezone IDs.

Action:

- Normalize all stored timezones to PHP timezone IDs where possible.
- Keep display labels separate from stored values.

## 20. How to Add a New Admin Module

Recommended process:

1. Create migration and model.
2. Create controller under `app/Http/Controllers/Admin`.
3. Add views under `resources/views/Admin/{ModuleName}`.
4. Add routes in `routes/backend.php` under the admin group.
5. Add permission group and permissions in the seeder/database.
6. Add menu item in admin navbar/layout.
7. Use existing DataTables patterns if the module needs listing/filtering.
8. Add status change endpoint if matching existing modules.
9. Test as super admin and as a restricted role.

Important: Because `Admin` middleware checks controller/method permissions, routes may return 403 until permissions are configured.

## 21. How to Work Safely on This Codebase

Before changing code:

```bash
git status
```

After changing routes/config:

```bash
php artisan route:list
php artisan config:clear
php artisan cache:clear
```

After changing migrations/models:

```bash
php artisan migrate
```

After changing views/assets:

```bash
npm run build
```

For scheduler-related changes:

```bash
php artisan schedule:list
php artisan schedule:run
```

For a specific command:

```bash
php artisan command:name
```

Before payment changes:

- Use test credentials.
- Confirm exact expected status transitions.
- Check orders and student fees after callbacks.
- Do not test production payment routes with real cards unless the client approves.

Before Zoom changes:

- Confirm whether meetings are created per batch, per coach user, or per account-level user.
- Check command logs.
- Check related DB fields: `start_url`, `join_url`, `zoom_meeting_id`, `zoom_meeting_uuid`.

## 22. Quick File Map by Feature

Public home:

```text
routes/frontend.php
app/Http/Controllers/Frontend/HomeController.php
resources/views/Frontend/home.blade.php
```

Trial booking:

```text
routes/frontend.php
app/Http/Controllers/Frontend/HomeController.php
resources/views/Frontend/BookTrialClass
app/Mail/DemoBookingMail.php
```

Admin login:

```text
routes/auth.php
app/Http/Controllers/Auth/AuthenticatedSessionController.php
resources/views/auth/login.blade.php
resources/views/Admin/Auth/login.blade.php
```

Student login/dashboard:

```text
routes/auth.php
routes/backend.php
app/Http/Controllers/Auth/AuthenticatedSessionController.php
app/Http/Controllers/Admin/StudentDashboardController.php
resources/views/Admin/StudentDashboard
```

Coach dashboard:

```text
routes/backend.php
app/Http/Controllers/Admin/DashboardController.php
resources/views/Admin/Dashboard/Coach
```

Students:

```text
app/Http/Controllers/Admin/StudentController.php
app/Models/Student.php
resources/views/Admin/Students
```

Batches:

```text
app/Http/Controllers/Admin/BatchController.php
app/Models/Batch.php
app/Models/BatchSchedule.php
resources/views/Admin/Batchs
resources/views/Admin/BatchSchedules
```

Payments:

```text
routes/backend.php
app/Http/Controllers/HdfcPaymentController.php
app/Models/Order.php
app/Models/StudentFee.php
```

Zoom:

```text
app/Services/ZoomMeetingService.php
app/Console/Commands/GetZoomMeetingRecordings.php
app/Console/Commands/GetDemoRecording.php
app/Console/Commands/GetMasterClassRecording.php
app/Console/Commands/GetCoverUpClassRecording.php
```

Reports:

```text
app/Http/Controllers/Admin/ReportController.php
resources/views/Admin/CoachReports
public/reports_pdfs
```

Roles/permissions:

```text
app/Http/Middleware/Admin.php
app/Http/Controllers/Admin/RoleController.php
database/seeders/PermissionSeeder.php
```

## 23. First Things a New Developer Should Do

1. Fix local PHP/XAMPP extensions so Artisan works.
2. Sanitize `.env.example`.
3. Move hardcoded secrets to `.env`.
4. Run `php artisan route:list` and save the output for reference.
5. Check production cron/Task Scheduler is running `schedule:run`.
6. Confirm which payment gateway is actually live: Razorpay, HDFC, or both.
7. Confirm which frontend design is live: old `Frontend/*` or new `Frontend/Design/*`.
8. Confirm whether API/mobile app is used.
9. Confirm business rules for fee due, module duration, and batch reassignment.
10. Document admin roles and permission setup after checking real database seed/data.

