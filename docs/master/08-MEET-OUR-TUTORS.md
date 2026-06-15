# Meet Our Tutors Module

## Purpose

Meet Our Tutors manages tutor profile content for the website.

It stores tutor name, photo, designation, rating, and status.

## Sidebar Path

```text
Master > Meet Our Tutors
```

## Main Routes

```php
Route::resource('meet-our-tutors', MeetOurTutorController::class);
Route::post('meet-our-tutors/data', [MeetOurTutorController::class, 'data'])->name('meet-our-tutors.data');
Route::post('meet-our-tutors/change-status', [MeetOurTutorController::class, 'changeStatus'])->name('meet-our-tutors.change.status');
```

## Main Files

```text
app/Http/Controllers/Admin/MeetOurTutorController.php
app/Models/MeetOurTutor.php
resources/views/Admin/MeetOurTutors/index.blade.php
resources/views/Admin/MeetOurTutors/form.blade.php
database/migrations/2025_07_08_150431_create_meet_our_tutors_table.php
```

## Table

```text
meet_our_tutors
```

Fields:

```text
id
status
name
image
designation
rating
created_by
updated_by
created_at
updated_at
```

## Listing Behavior

Main method:

```php
MeetOurTutorController::data()
```

Rows are ordered by latest ID first.

Columns:

```text
Image
Name
Designation
Rating
Status
Action
```

## Store Validation

Main method:

```php
MeetOurTutorController::store()
```

Required:

```text
name
image
designation
rating
```

Rating:

```text
numeric
```

Image:

```text
max 20 MB
```

Store path:

```text
images
```

## Update Logic

Main method:

```php
MeetOurTutorController::update()
```

Image becomes optional.

If a new image is uploaded:

- Old image is deleted.
- New image is saved under `images`.

## Status Change

Main method:

```php
MeetOurTutorController::changeStatus()
```

Updates:

```text
meet_our_tutors.status
```

## Developer Notes

- Image mime rule is written as `mimes:jpeg,png,jpg.svg`, which looks like a typo. Usually this should be `jpeg,png,jpg,svg`.
- The custom message mentions dimensions, but no dimension rule is applied in the controller.
- Destroy and show methods are empty.

