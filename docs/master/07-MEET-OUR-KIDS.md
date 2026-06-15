# Meet Our Kids Module

## Purpose

Meet Our Kids manages student/kid highlight content for the website.

It stores an image, title, and active/inactive status.

## Sidebar Path

```text
Master > Meet Our Kids
```

## Main Routes

```php
Route::resource('meet-our-kids', MeetOurKidController::class);
Route::post('meet-our-kids/data', [MeetOurKidController::class, 'data'])->name('meet-our-kids.data');
Route::post('meetourkids/list', [MeetOurKidController::class, 'list'])->name('meetourkids.list');
Route::post('meetourkids/change-status', [MeetOurKidController::class, 'changeStatus'])->name('meetourkids.change.status');
```

## Main Files

```text
app/Http/Controllers/Admin/MeetOurKidController.php
app/Models/MeetOurKid.php
resources/views/Admin/MeetOurKids/index.blade.php
resources/views/Admin/MeetOurKids/form.blade.php
database/migrations/2025_05_12_161540_create_meet_our_kids_table.php
```

## Table

```text
meet_our_kids
```

Fields:

```text
id
status
title
image
created_at
updated_at
```

## Listing Behavior

Main method:

```php
MeetOurKidController::data()
```

Rows are ordered by latest ID first.

Columns:

```text
Image
Title
Status
Action
```

## Store Validation

Main method:

```php
MeetOurKidController::store()
```

Image rules:

```text
required
jpeg/png/jpg
exactly 304x304
max 20 MB
```

Store path:

```text
photos
```

## Update Validation

Main method:

```php
MeetOurKidController::update()
```

Image becomes optional:

```text
nullable jpeg/png/jpg exactly 304x304 max 20 MB
```

If a new image is uploaded:

- Old image is deleted.
- New image is saved under `images`.

## Status Change

Main method:

```php
MeetOurKidController::changeStatus()
```

Updates:

```text
meet_our_kids.status
```

## Developer Notes

- Model fillable includes `name`, `created_by`, and `updated_by`, but the migration shown for this table does not include those fields.
- Store uploads into `photos`, update uploads into `images`, so old and new records may use different storage folders.
- Destroy method is empty.

