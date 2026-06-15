# Levels Module

## Purpose

Levels define the chess course level structure. They are used by demo sessions, batches, students, payment levels, masterclasses, and tournaments.

Examples:

```text
Beginner
Intermediate
Advanced
```

## Sidebar Path

```text
Master > Levels
```

## Main Routes

```php
Route::resource('levels', LevelController::class);
Route::post('levels/data', [LevelController::class, 'data'])->name('levels.data');
Route::post('levels/list', [LevelController::class, 'list'])->name('levels.list');
Route::post('levels/change-status', [LevelController::class, 'changeStatus'])->name('levels.change.status');
```

## Main Files

```text
app/Http/Controllers/Admin/LevelController.php
app/Models/Level.php
resources/views/Admin/Levels/index.blade.php
resources/views/Admin/Levels/form.blade.php
database/migrations/2024_05_29_052226_create_levels_table.php
```

## Table

```text
levels
```

Fields:

```text
id
name
index
status
created_by
updated_by
created_at
updated_at
```

## Model

```php
protected $table = 'levels';
```

Fillable:

```text
status
name
index
created_by
updated_by
```

Relationships:

```php
demoSessions()
```

The model automatically sets `created_by` and `updated_by` from the logged-in user.

## Listing Behavior

Main method:

```php
LevelController::data()
```

The listing excludes level IDs:

```text
0
22
23
```

Rows are ordered by latest ID first.

Columns:

```text
#
Action
Status
Name
Index
```

Status filter:

```text
ACTIVE / INACTIVE
```

## Create Logic

Main method:

```php
LevelController::create()
```

The next `index` is calculated from the max existing level index:

```text
nextIndex = max(levels.index) + 1
```

## Store Logic

Main method:

```php
LevelController::store()
```

Validation:

```text
name required
name unique in levels table
```

Response:

```text
Level Created Successfully
```

## Edit And Update Logic

Main methods:

```php
LevelController::edit()
LevelController::update()
```

Update uses the same validation as create.

Developer note:

```text
The unique validation does not ignore the current level ID during update.
Editing an existing level without changing the name may fail with "name already taken".
```

## Status Change

Main method:

```php
LevelController::changeStatus()
```

The controller finds the record by route key:

```php
Level::findByKey($request->route_key)
```

Then sets:

```text
levels.status = request status
```

## Delete Logic

Main method:

```php
LevelController::destroy()
```

Delete exists in controller, but the delete button is commented in the DataTables action column. In normal UI, users edit and toggle status instead of deleting.

## Used By

```text
Demo sessions
Batches
Payment levels
Student level tracking
Masterclasses
Tournaments
Reports
```

