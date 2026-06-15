# Master Classes Module

## Purpose

Master Classes are special classes hosted by a coach for selected students, batches, levels, or countries.

They are separate from regular batch classes and are shown in student/coach dashboard flows.

## Sidebar Path

```text
Master > Master Classes
```

## Main Routes

```php
Route::resource('masterclasses', MasterclassController::class);
Route::post('masterclasses/data', [MasterclassController::class, 'data'])->name('masterclasses.data');
Route::post('masterclasses/list', [MasterclassController::class, 'list'])->name('masterclasses.list');
Route::post('masterclasses/change-status', [MasterclassController::class, 'changeStatus'])->name('masterclasses.change.status');
```

Student dashboard route:

```php
Route::get('/student-masterclasses', [StudentDashboardController::class, 'studentMasterclasses'])->name('student.masterclasses');
```

## Main Files

```text
app/Http/Controllers/Admin/MasterclassController.php
app/Models/Masterclass.php
app/Models/MasterclassData.php
resources/views/Admin/Masterclasses/index.blade.php
resources/views/Admin/Masterclasses/form.blade.php
resources/views/Admin/Masterclasses/show.blade.php
database/migrations/2024_11_14_190234_create_masterclasses_table.php
database/migrations/2024_11_15_183453_create_masterclasses_data_table.php
```

## Tables

```text
masterclasses
masterclasses_data
```

Masterclass fields:

```text
id
coach_id
batch_ids
student_ids
level_ids
name
date
time
status
country
start_url
join_url
zoom_meeting_id
zoom_meeting_uuid
created_by
updated_by
created_at
updated_at
```

MasterclassData fields:

```text
id
masterclass_id
student_id
created_by
updated_by
created_at
updated_at
```

## Model

`Masterclass` fillable:

```text
batch_ids
student_ids
level_ids
name
date
time
coach_id
status
country
```

Casts:

```text
batch_ids array
student_ids array
level_ids array
country array
```

Relationship:

```php
coach()
```

`MasterclassData` connects a masterclass to students:

```text
masterclass_id
student_id
```

## Listing Behavior

Main method:

```php
MasterclassController::data()
```

Rows are ordered by date descending.

Filters:

```text
coach_id
country
date range
```

Columns include:

```text
Name
Batches
Levels
Students
Coach
Date
Status
Start URL
Join URL
Action
```

Start and join URLs show copy-link icons when Zoom links exist.

## Create Form Data

Main method:

```php
MasterclassController::create()
```

The form loads:

```text
active batches
active students
active levels
active coaches
```

## Store Logic

Main method:

```php
MasterclassController::store()
```

Validation:

```text
name required
date required
time required
coach_id required
country required
```

Flow:

1. Validate request.
2. Create `masterclasses` record.
3. If coach has Zoom credentials, create Zoom meeting.
4. Save Zoom start/join URLs and meeting IDs.
5. Build the invited student collection.
6. Save `masterclasses_data` records for matched students.

## Student Selection Logic

The system merges students from multiple sources:

```text
direct selected students
students from selected batches
students from selected levels
students from selected countries
```

Inactive students are excluded.

Duplicates are removed by student ID.

Each unique student gets a `masterclasses_data` record.

## Zoom Meeting Logic

The coach must have:

```text
zoom_id
zoom_api_key
zoom_client_secret
zoom_user_id
```

If available, the controller uses:

```php
ZoomMeetingService::createNewUserMeeting()
```

Stored on masterclass:

```text
start_url
join_url
zoom_meeting_id
zoom_meeting_uuid
```

## Show Logic

Main method:

```php
MasterclassController::show()
```

Loads:

```text
masterclass
masterclass_logs from masterclasses_data
```

## Update Logic

Main method:

```php
MasterclassController::update()
```

The masterclass is updated from request data.

Zoom meeting is recreated if coach has Zoom credentials.

Developer note:

```text
The MasterclassData sync logic in update is currently commented out.
Editing selected students/batches/levels may not refresh masterclasses_data.
```

## Status Change

Main method:

```php
MasterclassController::changeStatus()
```

Updates `masterclasses.status`.

## Emails

`MasterclassMail` is referenced, but sending is commented:

```php
// Mail::to($student->email)->send(new MasterclassMail($student, $masterclass));
```

Current behavior:

```text
Masterclass data is saved.
Masterclass invite email is not sent from this controller.
```

## Reports Connection

Reports reads masterclass attendance from:

```text
coach_attendances.type = Masterclass
masterclass_id is not null
```

## Developer Notes

- The listing builds batch/level/student names by looping IDs and querying records individually.
- Update recreates Zoom details instead of updating the old meeting.
- The action column currently returns edit only, even though show link code exists.
- Student linking is strong on create but incomplete on update because sync code is commented.

