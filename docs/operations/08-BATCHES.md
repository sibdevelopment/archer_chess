# Batches Module

## Purpose

Batches are class groups. They connect coaches, schedules, students, levels, Zoom links, and attendance.

This is where real student-to-batch assignment happens.

## Sidebar Path

```text
Operations > Batches
```

## Main Routes

```php
Route::resource('batchs', BatchController::class);
Route::post('batchs/data', [BatchController::class, 'data'])->name('batchs.data');
Route::post('batchs/list', [BatchController::class, 'list'])->name('batchs.list');
Route::post('batchs/change-status', [BatchController::class, 'changeStatus'])->name('batchs.change.status');
Route::get('batch/get/coaches', [BatchController::class, 'getCoaches'])->name('batch.get.coaches');
Route::get('batch/get/students', [BatchController::class, 'getStudents'])->name('batch.get.students');
Route::post('batches/check-name', [BatchController::class, 'checkName'])->name('batchs.check.name');
Route::post('batches/attendance', [BatchController::class, 'batchAttendance'])->name('batchs.attendance');
```

Assignment routes:

```php
Route::get('batchs/{batch}/assign/student', [BatchController::class, 'assignBatchToStudent'])->name('batchs.assign.student');
Route::post('batchs/{batch}/assign/student/save', [BatchController::class, 'saveAssignedStudent'])->name('batchs.assigned.student.save');
Route::get('batchs/{batch}/reassign/student', [BatchController::class, 'reassignBatchToStudentModal'])->name('batchs.reassign.student');
Route::post('batchs/{batch}/reassign/student/save', [BatchController::class, 'saveReassignedStudent'])->name('batchs.reassigned.student.save');
Route::post('batchs/check/schedule', [BatchController::class, 'checkSchedule'])->name('batchs.check.schedule');
```

Schedule routes:

```php
Route::post('batch_schedules/add-weekday', [BatchController::class, 'addWeekday'])->name('batch_schedules.add_weekday');
Route::post('batch_schedules/edit-weekday', [BatchController::class, 'editWeekDay'])->name('batch_schedules.edit_weekday');
Route::post('batch_schedules/delete-weekday', [BatchController::class, 'deleteWeekDay'])->name('batch_schedules.delete_weekday');

Route::prefix('batchs/{batch}')->name('batchs.')->group(function () {
    Route::resource('batch_schedules', BatchScheduleController::class);
    Route::post('batch_schedules/data', [BatchScheduleController::class, 'data'])->name('batch_schedules.data');
    Route::post('batch_schedules/change-status', [BatchScheduleController::class, 'changeStatus'])->name('batch_schedules.change.status');
});
```

## Main Files

```text
app/Http/Controllers/Admin/BatchController.php
app/Http/Controllers/Admin/BatchScheduleController.php
app/Models/Batch.php
app/Models/BatchSchedule.php
app/Models/StudentBatch.php
resources/views/Admin/Batchs/*
resources/views/Admin/BatchSchedules/*
```

## Database Tables

```text
batchs
batch_schedules
student_batches
students
coachs
levels
coach_attendances
student_attendances
coverupclasses
```

## Important Batch Fields

```text
name
kids_zone_name
coach_id
level_id
country
status
version
parent_id
number_of_sessions
start_date
end_date
start_url
join_url
zoom_meeting_id
zoom_meeting_uuid
confirm_reassign
confirm_reassign_batch_id
```

## Batch Creation

Main method:

```php
BatchController::store()
```

Creates:

```text
batchs record
batch_schedules records
Zoom meeting if coach credentials exist
```

Batch starts as:

```text
status = ACTIVE
version = 1
parent_id = batch id
```

## Batch Schedule

Stored in:

```text
batch_schedules
```

Important fields:

```text
batch_id
weekday
from_time
to_time
status
start_date
end_date
```

## Student Assignment

Main methods:

```php
BatchController::assignBatchToStudent()
BatchController::saveAssignedStudent()
```

Real student assignment is stored in:

```text
student_batches
```

Fields:

```text
student_id
batch_id
coach_id
level_id
status
start_date
end_date
number_of_sessions
is_fees_due
```

Student selection rules:

- Student must be `ACTIVE`.
- Student country must match batch country.
- Student should not already be active in another batch.

## Reassign Batch

Main method:

```php
BatchController::saveReassignedStudent()
```

Reassignment:

- Copies old batch.
- Increments version.
- Keeps parent batch chain.
- Copies schedules.
- Copies active students.
- Creates new Zoom meeting if possible.
- Sends user to assignment screen for the new batch.

## Batch Status

Common statuses:

```text
ACTIVE
INACTIVE
STANDBY
```

If batch is marked `INACTIVE`, active student batch records are also marked inactive.

## Schedule Conflict Check

Main method:

```php
BatchController::checkSchedule()
```

Checks if selected coach already has the same weekday/time in another non-inactive batch.

## Cover-Up Connection

Batches connect to cover-up classes through:

```text
coverupclasses.batch_id
coverupclasses.batchschedule_id
```

Same-day coach change creates cover-up records from Batch module.

## Client Workflow

1. Go to Operations > Batches.
2. Create batch with coach/country/schedule.
3. Assign active students.
4. Review schedule and Zoom links.
5. Reassign batch if future batch version is needed.
6. Use cover-up coach if original coach is unavailable.

## Developer Notes

The table is named:

```text
batchs
```

This spelling is used throughout code and routes.

Do not confuse:

```text
new_enrollments.batch_id
student_batches.batch_id
```

Only `student_batches.batch_id` is the real active batch membership.

