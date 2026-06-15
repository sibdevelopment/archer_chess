# Payment Level Module

## Purpose

Payment Level defines fee pricing for each course level and country/region.

It is used when creating student fees, renewals, and payment-related enrollment data.

## Sidebar Path

```text
Master > Payment Level
```

## Main Routes

```php
Route::resource('paymentlevels', PaymentLevelController::class);
Route::post('paymentlevels/data', [PaymentLevelController::class, 'data'])->name('paymentlevels.data');
Route::post('paymentlevels/{id}/edit', [PaymentLevelController::class, 'edit'])->name('paymentlevels.editt');
Route::post('paymentlevels/list', [PaymentLevelController::class, 'list'])->name('paymentlevels.list');
Route::post('paymentlevels/change-status', [PaymentLevelController::class, 'changeStatus'])->name('paymentlevels.change.status');
```

## Main Files

```text
app/Http/Controllers/Admin/PaymentLevelController.php
app/Models/Paymentlevel.php
resources/views/Admin/PaymentLevels/index.blade.php
resources/views/Admin/PaymentLevels/form.blade.php
database/migrations/2025_01_14_160354_create_paymentlevels_table.php
```

## Table

```text
paymentlevels
```

Fields used by model/controller:

```text
id
name
level_id
sequence
usa_fees
canada_fees
australia_fees
newzealand_fees
india_fees
uae_fees
uk_fees
qatar_fees
singapore_fees
european_union_fees
oman_fees
status
created_at
updated_at
```

## Model

```php
protected $table = 'paymentlevels';
```

Fillable:

```text
id
name
level_id
sequence
usa_fees
canada_fees
australia_fees
newzealand_fees
india_fees
uae_fees
uk_fees
qatar_fees
singapore_fees
european_union_fees
oman_fees
status
```

Relationship:

```php
level()
```

This connects each payment level to a course level.

## Listing Behavior

Main method:

```php
PaymentLevelController::data()
```

Rows are ordered by:

```text
sequence ASC
```

Filter:

```text
level_id
```

Columns:

```text
#
Action
Status
Name
Fees
Level
Sequence
```

The controller formats the level name from:

```text
paymentlevel -> level -> name
```

## Create Logic

Main method:

```php
PaymentLevelController::create()
```

The form loads only active levels:

```text
Level::where('status', 'ACTIVE')
```

## Store Validation

Main method:

```php
PaymentLevelController::store()
```

Required:

```text
name
level_id
sequence
canada_fees
usa_fees
australia_fees
newzealand_fees
india_fees
uae_fees
uk_fees
qatar_fees
singapore_fees
european_union_fees
```

Numeric:

```text
sequence
all fee fields
```

Unique:

```text
sequence unique in paymentlevels
```

## Update Validation

Main method:

```php
PaymentLevelController::update()
```

Same validation as store, but `sequence` ignores the current record:

```text
unique:paymentlevels,sequence,{current id}
```

## Status Change

Main method:

```php
PaymentLevelController::changeStatus()
```

Finds by numeric ID from `route_key`, then updates `status`.

## JSON List Endpoint

Main method:

```php
PaymentLevelController::list()
```

Returns all payment level rows as JSON.

## Developer Notes

- The model includes `oman_fees`, but the create/update validation shown in the controller does not require Oman fees.
- The create/update forms should be checked whenever adding a new country fee field, because validation, fillable fields, migrations, and frontend form inputs must stay aligned.
- Sequence controls display/order behavior and must remain unique.

