# Holidays Module

## Purpose

Holidays define non-working or special dates by country. Coach dashboards and scheduling logic can use these records to display upcoming holidays and avoid confusion around availability.

## Sidebar Path

```text
Master > Holidays
```

## Main Routes

```php
Route::resource('holidays', HolidayController::class);
Route::post('holidays/data', [HolidayController::class, 'data'])->name('holidays.data');
Route::post('holidays/list', [HolidayController::class, 'list'])->name('holidays.list');
Route::post('holidays/change-status', [HolidayController::class, 'changeStatus'])->name('holidays.change.status');
```

## Main Files

```text
app/Http/Controllers/Admin/HolidayController.php
app/Models/Holiday.php
resources/views/Admin/Holidays/index.blade.php
resources/views/Admin/Holidays/form.blade.php
database/migrations/2024_06_08_034712_create_holidays_table.php
```

## Table

```text
holidays
```

Fields:

```text
id
country
name
start_date
end_date
description
status
created_by
updated_by
created_at
updated_at
```

## Model

Fillable:

```text
name
start_date
end_date
description
status
created_by
updated_by
country
```

Casts:

```php
'country' => 'array'
```

The model auto-fills `created_by` and `updated_by`.

## Listing Behavior

Main method:

```php
HolidayController::data()
```

Rows are ordered by:

```text
start_date ASC
```

Filters:

```text
status
country
```

Country filter supports both JSON country values and older plain-string country values.

Columns:

```text
#
Action
Status
Name
Start Date
End Date
Country
Description
```

## Create And Store

Main methods:

```php
HolidayController::create()
HolidayController::store()
```

Validation:

```text
name required
start_date required
```

Optional fields:

```text
end_date
description
country
status
```

## Update

Main method:

```php
HolidayController::update()
```

Uses the same validation and fills the model from the request.

## Status Change

Main method:

```php
HolidayController::changeStatus()
```

Finds holiday by route key and updates status.

## Delete Logic

Delete exists in controller, but the delete UI is commented out for SuperAdmin. Normal behavior is edit or deactivate.

## Developer Notes

- Custom message key has a typo: `name .required` contains a space. Laravel will not match it to `name.required`.
- The DataTables controller has an `editColumn('date')` block even though the holiday table uses `start_date` and `end_date`. The visible listing uses start/end date columns from the view.
- Country is cast as array, so form data should submit country as an array when possible.

