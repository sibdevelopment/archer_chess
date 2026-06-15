# Change Batches Module

## Purpose

Change Batches manages students who need to change class/batch.

This is triggered when a student's status is changed to:

```text
CHANGECLASS
```

## Sidebar Path

```text
Operations > Change Batches
```

## Main Routes

```php
Route::resource('changeclasses', ChangeclassController::class);
Route::post('changeclasses/data', [ChangeclassController::class, 'data'])->name('changeclasses.data');
Route::post('changeclasses/list', [ChangeclassController::class, 'list'])->name('changeclasses.list');
Route::get('changeclasses/excel/export', [ChangeclassController::class, 'export'])->name('changeclasses.export');
```

## Main Files

```text
app/Http/Controllers/Admin/StudentController.php
app/Http/Controllers/Admin/ChangeclassController.php
app/Models/Changeclass.php
app/Models/Student.php
app/Models/StudentBatch.php
app/Models/StudentFee.php
resources/views/Admin/ChangeClass/*
```

## Database Tables

```text
changeclasses
students
student_batches
student_fees
batchs
employees
```

## Important Fields

```text
student_id
current_batch_id
batch_id
employee_id
employee_ids
start_date
end_date
receive_date
fees
received_fees
currency
remark
is_submitted
```

## How Change Batch Is Created

Main method:

```php
StudentController::changeStatus()
```

When status is set to `CHANGECLASS`, the system:

1. Finds the student's latest batch.
2. Marks latest batch record `INACTIVE`.
3. Sets batch end date to today.
4. Sets `is_fees_due = 0`.
5. Creates a `changeclasses` record.
6. Stores selected employee and remark.
7. If previous student status was `ACTIVE`, copies latest fee details.

## Change Class Processing

Main method:

```php
ChangeclassController::update()
```

Two modes exist:

```text
type = changeclass
type = student-fee
```

### Save Change Class

When:

```text
type = changeclass
```

It updates the change class record but does not finalize payment/student fee.

### Confirm Student Fee

When:

```text
type = student-fee
```

The system:

1. Updates the change class record.
2. Marks `is_submitted = 1`.
3. Creates a new `student_fees` record.
4. Sets student status to `ACTIVE`.

## Important Clarification

Change Batches records the change request/payment side.

It does not by itself assign the student to the new batch.

Actual new batch membership must be created in:

```text
Operations > Batches > Assign Student
```

## Client Workflow

1. Go to Operations > Students.
2. Change student status to Change Class.
3. Select employee and enter remark.
4. Go to Operations > Change Batches.
5. Open the change request.
6. Update batch/payment details.
7. Confirm student fee if applicable.
8. Assign the student to a new batch from Batches.

## Developer Notes

If `CHANGECLASS` is selected without latest batch, code may depend on latest batch values. Validate this before changing status logic.

