# New Enrollments Module

## Purpose

New Enrollments tracks students who have converted from Demo Leads and are waiting for enrollment/payment confirmation.

This module is the bridge between Demo Lead conversion and fully active Student/Fee status.

## Sidebar Path

```text
Operations > New Enrollments
```

## Main Routes

```php
Route::resource('newenrollments', NewEnrollmentController::class);
Route::post('newenrollments/data', [NewEnrollmentController::class, 'data'])->name('newenrollments.data');
Route::post('newenrollments/list', [NewEnrollmentController::class, 'list'])->name('newenrollments.list');
Route::get('newenrollments/excel/export', [NewEnrollmentController::class, 'export'])->name('newenrollments.export');
```

## Main Files

```text
app/Http/Controllers/Admin/NewEnrollmentController.php
app/Models/NewEnrollment.php
app/Models/Student.php
app/Models/StudentFee.php
resources/views/Admin/NewEnrollments/*
```

## Database Tables

```text
new_enrollments
students
student_fees
batchs
employees
paymentlevels
```

## Important Fields

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

## Listing And Filters

The module supports filtering by:

- Employee
- Batch
- Country
- Created by
- Payment level
- Date range

Non-SuperAdmin users may only see records connected to their employee ID or created by them.

## Update Modes

The update method supports two modes:

```text
type = new-enrollment
type = student-fee
```

## Mode 1: Save New Enrollment

When:

```text
type = new-enrollment
```

The system updates:

```text
employee_ids
student_id
batch_id
remark
start_date
end_date
receive_date
fees
received_fees
currency
```

It does not activate the student or create a student fee.

## Mode 2: Confirm Enrollment / Student Fee

When:

```text
type = student-fee
```

The system:

1. Sets student status to `ACTIVE`.
2. Updates the New Enrollment record.
3. Creates a `student_fees` record.
4. Sets student fee status to `ACTIVE`.

Database result:

```text
students.status = ACTIVE
student_fees.status = ACTIVE
```

## Important Clarification

`new_enrollments.batch_id` is not the real active batch assignment.

Real batch assignment is:

```text
student_batches.batch_id
```

So after confirming enrollment, the student still needs to be assigned from:

```text
Operations > Batches > Assign Student
```

## Client Workflow

1. Go to Operations > New Enrollments.
2. Open enrollment details.
3. Verify fees, received amount, currency and dates.
4. Select employee/batch info if required.
5. Save as new enrollment or confirm as student fee.
6. Assign the activated student to a batch from Batches.

## Developer Notes

If a converted student is not visible in batch assignment:

```text
Check students.status.
Only ACTIVE students appear in batch assignment.
```

