# Tournaments Module

## Purpose

Tournaments define special chess tournament events for students. They can target students directly, by batch, by level, and by country.

The module also supports tournament certificate upload and PDF certificate viewing.

## Sidebar Path

```text
Master > Tournaments
```

## Main Routes

```php
Route::resource('tournaments', TournamentController::class);
Route::post('tournaments/data', [TournamentController::class, 'data'])->name('tournaments.data');
Route::post('tournaments/list', [TournamentController::class, 'list'])->name('tournaments.list');
Route::post('tournaments/change-status', [TournamentController::class, 'changeStatus'])->name('tournaments.change.status');
Route::get('tournaments/view/certificate/{tournament}', [TournamentController::class, 'viewCertificate']);
```

Student dashboard routes:

```php
Route::get('/student-tournaments', [StudentDashboardController::class, 'studentTournaments'])->name('student.tournaments');
Route::get('/student-tournaments/certificate/{tournament}', [StudentDashboardController::class, 'tournamentCertificate'])->name('student.tournament.certificate');
```

## Main Files

```text
app/Http/Controllers/Admin/TournamentController.php
app/Models/Tournament.php
app/Models/TournamentData.php
resources/views/Admin/Tournaments/index.blade.php
resources/views/Admin/Tournaments/form.blade.php
resources/views/Admin/Tournaments/certificate.blade.php
database/migrations/2024_11_14_192034_create_tournaments_table.php
database/migrations/2024_11_15_183602_create_tournaments_data_table.php
```

## Tables

```text
tournaments
tournaments_data
```

Tournament fields:

```text
batch_ids
student_ids
level_ids
name
date
time
link
status
country
certificate
created_by
updated_by
```

TournamentData fields:

```text
tournament_id
student_id
```

## Model

`Tournament` fillable:

```text
batch_ids
student_ids
level_ids
name
date
time
link
status
country
```

Casts:

```text
batch_ids array
student_ids array
level_ids array
country array
certificate array
```

## Listing Behavior

Main method:

```php
TournamentController::data()
```

Rows are ordered by tournament date descending.

Columns include:

```text
Name
Batches
Levels
Students
Certificate
Link
Date
Status
Action
```

Certificate column shows:

- Certificate image preview
- Download button opening the certificate PDF route

## Create Form Data

Main method:

```php
TournamentController::create()
```

Loads:

```text
active batches
active students
active levels
```

## Store Logic

Main method:

```php
TournamentController::store()
```

Validation:

```text
country required
name required
date required
time required
link required
```

Certificate upload:

```text
storage/app/public/certificate
```

Saved certificate structure:

```text
certificate.name
certificate.path
```

## Student Targeting Logic

The system builds a student collection from:

```text
direct selected students
students from selected batches
students from selected levels
students from selected countries
```

Inactive students are excluded.

Duplicates are removed.

Important current behavior:

```text
TournamentData creation is commented out in the current store/update flow.
```

So the tournament record stores selected IDs, but the `tournaments_data` participant rows may not be created by this controller.

## Mail Logic

`TournamentMail` is referenced, but mail sending is commented:

```php
// Mail::to($student->email)->send(new TournamentMail($student, $tournament));
```

There is also a `break` after the mail block, so if mail is re-enabled as-is it would stop after the first student with an email.

## Update Logic

Main method:

```php
TournamentController::update()
```

Updates the tournament and optionally replaces certificate data.

The participant sync code for `TournamentData` is also commented in update.

## Certificate PDF

Main method:

```php
TournamentController::viewCertificate()
```

Flow:

1. Check tournament has certificate.
2. Resolve file from `storage/app/public`.
3. Pick the first selected student as student name.
4. Render `Admin.Tournaments.certificate`.
5. Stream PDF in landscape A4.

If file is missing:

```text
Certificate file not found
```

If tournament has no certificate:

```text
Certificate not found
```

## Status Change

Main method:

```php
TournamentController::changeStatus()
```

Updates `tournaments.status`.

## Developer Notes

- `certificate` is cast as array but not listed in `$fillable`; direct assignment still works in controller.
- Participant linking exists historically but is currently commented.
- Mail is not sent in current behavior.
- Update response message says `Tournament Created Successfully`.
- Delete controller exists, but delete UI is commented.

