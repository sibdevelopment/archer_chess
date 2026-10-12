# Students Module

## Purpose

Students are the actual learners in the system.

This module manages:

- Student profile
- Student status
- Fee records
- Batch visibility
- Attendance history
- Fee due handling
- Change class initiation

## Sidebar Path

```text
Operations > Students
```

## Main Routes

```php
Route::resource('students', StudentController::class);
Route::post('students/data', [StudentController::class, 'data'])->name('students.data');
Route::post('students/export', [StudentController::class, 'export'])->name('students.export');
Route::post('students/list', [StudentController::class, 'list'])->name('students.list');
Route::post('students/change-status', [StudentController::class, 'changeStatus'])->name('students.change.status');
Route::get('students/get/coaches', [StudentController::class, 'getCoaches'])->name('students.get.coaches');
Route::get('students/get/batches', [StudentController::class, 'getBatches'])->name('students.get.batches');
```

Student fee routes:

```php
Route::prefix('students/{student}')->name('students.')->group(function () {
    Route::resource('student_fees', StudentFeeController::class);
    Route::post('student_fees/data', [StudentFeeController::class, 'data'])->name('student_fees.data');
    Route::post('student_fees/change-status', [StudentFeeController::class, 'changeStatus'])->name('student_fees.change.status');
    Route::get('student_fees/{student_fee}/invoice', [StudentFeeController::class, 'downloadInvoice'])->name('student_fees.invoice');
});
```

## Main Files

```text
app/Http/Controllers/Admin/StudentController.php
app/Http/Controllers/Admin/StudentFeeController.php
app/Models/Student.php
app/Models/StudentFee.php
app/Models/StudentBatch.php
app/Models/StudentStatus.php
resources/views/Admin/Students/*
resources/views/Admin/StudentFees/*
```

## Database Tables

```text
students
student_fees
student_batches
student_statuses
student_attendances
users
batchs
batch_schedules
```

## Important Student Fields

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

## Student Statuses

```text
ACTIVE
INACTIVE
STANDBY
FEESDUE
CHANGECLASS
CURRENT_DAY
```

Important:

```text
CURRENT_DAY is a filter only. It is not saved in students.status.
```

## Status Meaning

### ACTIVE

Student is active and can be assigned to batches.

Usually set by enrollment/fee confirmation.

### INACTIVE

Student is not active.

When manually set, active student batch records are marked inactive.

### STANDBY

Student is paused/on hold.

When manually set, active batch records are marked inactive.

### FEESDUE

Student fee period expired or payment was not submitted.

Fee due flow marks:

```text
students.status = FEESDUE
student_fees.status = INACTIVE
student_batches.status = INACTIVE
student_batches.is_fees_due = 1
```

### CHANGECLASS

Student wants to change batch/class.

This creates a `changeclasses` record and ends the current/latest batch record.

### CURRENT_DAY

Shows students whose fee end date is today.

This is only a filter.

## Student Filters

Students can be filtered by:

- Start fees date
- End fees date
- Coach
- Batch
- Country
- Weekday
- Status
- User ID

## Fee Due Processing

Production uses the server crontab command:

```bash
php artisan fees:mark-due-by-schedule --buffer=15
```

It runs every 30 minutes, waits until the last class on the fee end date plus the buffer, backfills expired rows, and uses a no-class cutoff. Old region-specific fee-due commands are disabled in `Kernel.php`.

When a later active advance fee exists, the student remains active and the student-batch window moves to that fee. Otherwise the student becomes `FEESDUE` and active membership is closed.

## Student Batch Relationship

Real batch membership is stored in:

```text
student_batches
```

Relationship:

```text
students.id -> student_batches.student_id
batchs.id -> student_batches.batch_id
```

## Client Workflow

1. Go to Operations > Students.
2. Search/filter student.
3. View student profile, fee records and batch history.
4. Add/update student fee if payment is received.
5. Change status only when operationally required.
6. Use Change Class when the student needs another batch.

## Developer Notes

If a student is missing from batch assignment:

```text
students.status must be ACTIVE.
student country must match batch country.
student must not already be active in another batch.
```


## Payment Levels And Advance Fees

Every new fee requires `payment_level_id`. `PaymentLevelService` allows the next one or next three levels and provides country price/currency; a three-level plan gets 10 percent discount. An advance fee may be created during the final five days of the current fee period.

Cancelled-class compensation extends the fee that covered the occurrence and shifts later advance fee rows so paid windows remain sequential.

## Fee-Due WhatsApp History

For a `FEESDUE` student, authorized non-coach admins see a green WhatsApp action. Sending records one `fee_due_whatsapp_notifications` row per student/fee cycle. The icon then becomes grey but remains clickable to show sender and time.

For manual status changes to `INACTIVE` or `STANDBY`, `status_reason` stores and displays the operational reason.
