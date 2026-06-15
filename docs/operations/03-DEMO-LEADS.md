# Demo Leads Module

## Purpose

Demo Leads manages trial-class candidates after they are converted from Lead Enquiries.

It handles:

- Demo lead listing
- Demo scheduling
- Demo status tracking
- Demo result/follow-up
- Conversion into Student

## Sidebar Path

```text
Operations > Demo Leads
```

## Main Routes

```php
Route::resource('demoleads', DemoLeadController::class);
Route::post('demoleads/data', [DemoLeadController::class, 'data'])->name('demoleads.data');
Route::post('demoleads/export', [DemoLeadController::class, 'export'])->name('demoleads.export');
Route::post('demoleads/list', [DemoLeadController::class, 'list'])->name('demoleads.list');
Route::post('demoleads/change-status', [DemoLeadController::class, 'changeStatus'])->name('demoleads.change.status');
Route::get('demoleads/{demolead}/convert', [DemoLeadController::class, 'convertToStudent'])->name('demoleads.convert');
Route::post('demoleads/{demolead}/convert/save', [DemoLeadController::class, 'saveConvertedStudent'])->name('demoleads.convert.save');
```

Demo session routes:

```php
Route::prefix('demoleads/{demolead}')->name('demoleads.')->group(function () {
    Route::resource('demo_sessions', DemoSessionsController::class);
    Route::post('demo_sessions/data', [DemoSessionsController::class, 'data'])->name('demo_sessions.data');
    Route::post('demo_sessions/change-status', [DemoSessionsController::class, 'changeStatus'])->name('demo_sessions.change.status');
});

Route::get('demo_sessions/coach_availability', [DemoSessionsController::class, 'getCoachAvailability'])->name('demo_sessions.coach_availability');
Route::get('demo_sessions/available_slots', [DemoSessionsController::class, 'getAvailableSlots'])->name('demo_sessions.available_slots');
```

## Main Files

```text
app/Http/Controllers/Admin/DemoLeadController.php
app/Http/Controllers/Admin/DemoSessionsController.php
app/Models/DemoLead.php
app/Models/DemoSession.php
resources/views/Admin/DemoLeads/*
resources/views/Admin/DemoSessions/*
```

## Database Tables

```text
demoleads
demo_sessions
coach_attendances
student_attendances
```

## Important Demo Lead Fields

```text
user_id
first_name
last_name
age
mobile
city
country
kids_time_zone
date
time
kids_date
kids_time
remark
reason
status
is_hide
created_by
updated_by
```

## Demo Lead Statuses

```text
ROWLEAD
SCHEDULED
RESCHEDULED
DEMO DONE
CANCELLED
CONVERTED
INTERESTED
NOT INTERESTED
```

## Status Meaning

### ROWLEAD

New/raw demo lead. Created after Lead Enquiry conversion or manual Demo Lead creation.

Next step:

```text
Schedule demo session.
```

### SCHEDULED

Demo session has been scheduled.

Set when:

```php
DemoSessionsController::store()
```

### RESCHEDULED

Previous demo session was made inactive and no active demo session remains.

Next step:

```text
Create/activate a new demo session.
```

### DEMO DONE

Demo class has been completed.

Usually set when demo session is updated with level/result.

Next step:

```text
Follow up and mark interested/not interested or convert to student.
```

### CANCELLED

Demo was cancelled.

This is manually set from status change modal.

### INTERESTED

Parent/student is interested after demo.

Next step:

```text
Follow up for payment/enrollment and convert to student.
```

### NOT INTERESTED

Parent/student is not interested.

Use `reason` to record why.

### CONVERTED

Demo Lead has been converted into Student.

Conversion creates:

```text
students
new_enrollments
```

## Demo Scheduling

Main method:

```php
DemoSessionsController::store()
```

Scheduling creates:

```text
demo_sessions record
Zoom meeting if coach credentials exist
demoleads.status = SCHEDULED
```

Coach availability checks include:

- Coach active status
- Coach country
- Coach availability
- Batch schedule conflicts
- Cover-up conflicts
- Approved leave
- Existing demo sessions

## Convert Demo Lead To Student

Main methods:

```php
DemoLeadController::convertToStudent()
DemoLeadController::saveConvertedStudent()
```

Creates:

```text
students record
new_enrollments record
```

Updates:

```text
demoleads.status = CONVERTED
students.status = INACTIVE
```

Important:

```text
This does not create student_batches.
Real batch assignment happens in the Batches module.
```

## Client Workflow

1. Go to Operations > Demo Leads.
2. Filter by status/country/coach/level/date/employee.
3. Open Demo Session for the lead.
4. Schedule coach and slot.
5. After demo, update status/result.
6. If interested, convert to student.
7. If not interested/cancelled, save reason.

## Developer Notes

If demo scheduling fails, check coach Zoom credentials first:

```text
zoom_id
zoom_api_key
zoom_client_secret
zoom_user_id
```

