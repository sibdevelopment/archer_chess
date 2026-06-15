# Roles Module

## Purpose

Roles control what an internal user can see and do inside the admin panel. This project uses Spatie Laravel Permission, with a custom `Role` model that also stores country access.

Roles affect:

- Sidebar visibility.
- Page access.
- Create/update buttons.
- Country-based filtering in modules like coaches, leads, students, batches, reports, and enrollments.
- Employee permissions copied to users after role assignment.

## Main Files

```text
routes/backend.php
app/Http/Controllers/Admin/RoleController.php
app/Models/Role.php
app/Models/Permission.php
app/Models/Permissiongroup.php
app/Models/User.php
resources/views/Admin/Roles/index.blade.php
resources/views/Admin/Roles/form.blade.php
resources/views/Admin/Roles/show.blade.php
resources/views/Admin/Roles/permissions.blade.php
database/seeders/PermissionSeeder.php
```

## Routes

```php
Route::resource('roles', RoleController::class);
Route::post('roles/data', [RoleController::class, 'data'])->name('roles.data');
Route::post('roles/list', [RoleController::class, 'list'])->name('roles.list');
Route::get('roles/{role}/permission/show', [RoleController::class, 'permissionsShow'])->name('roles.permissions.show');
Route::post('roles/{role}/permission/update', [RoleController::class, 'permissionsUpdate'])->name('roles.permissions.update');
```

## Permission

The sidebar uses:

```text
role-view
```

Common permission names:

```text
role-view
role-store
role-update
```

The permissions page itself is reached through the role listing's **Permissions** button.

## Database Tables

Important tables:

```text
roles
permissions
permissiongroups
role_has_permissions
model_has_roles
model_has_permissions
```

The `roles` table also has:

```text
countries
```

`App\Models\Role` casts `countries` as an array.

## Role Listing

Screen:

```text
Users > Roles
```

The listing is server-side DataTables. It shows:

- Row number.
- Edit action.
- View action.
- Role name.
- Permissions button.

System roles are excluded from normal listing by:

```php
$systemRoles = getSystemRoles();
Role::whereNotIn('name', $systemRoles)
```

This protects core roles like SuperAdmin/Admin/Student/Coach depending on the helper output.

## Create/Edit Form

Fields:

```text
Role Name
Countries
```

Validation:

```text
name      required, letters/spaces/hyphen only, unique
countries required
```

Country options currently include:

```text
USA
CANADA
AUSTRALIA
NEWZEALAND
INDIA
UAE
UK
SINGAPORE
SOUTH AFRICA
QATAR
BAHRAIN
KUWAIT
```

The form uses Select2 for multi-country selection.

## Country Scope

Country scope is one of the most important hidden details of this module.

For non-SuperAdmin users, many controllers read the user's role countries and filter data accordingly. Example behavior from the coach listing:

```text
If user is SuperAdmin:
  Show all coaches.

If user is not SuperAdmin:
  Read countries from user's role.
  Show only coaches whose country matches those countries.
```

This means a role is not only a permission group. It is also a geographic data-access rule.

## Permissions Screen

Screen path:

```text
Users > Roles > Permissions button
```

The page groups permissions by `permissiongroups`.

Columns:

```text
Module
View
Create
Update
Other
```

Important behavior:

- SuperAdmin can see almost every permission group.
- Non-SuperAdmin does not see internal groups like `Crud` and `User`.
- Permission names are normalized in the UI by removing the group prefix.
- The `levels-view` permission is skipped in the current permissions blade.

## Permission Save Behavior

When permissions are saved:

```php
$role->syncPermissions($request->permissions);
```

Then every existing user with that role is updated:

```php
$users = User::role($role->name)->get();
foreach ($users as $user) {
    $user->syncPermissions($request->permissions);
}
```

This means changing a role immediately updates assigned employees and coaches/users who have that role.

## Role List API

`roles/list` returns roles except:

```text
SuperAdmin
Admin
```

This API is useful for dropdowns where system-level roles should not be assigned manually.

## Developer Notes

- Do not rename permission strings casually. Sidebar gates and controllers depend on exact permission names.
- If a new admin module is added, update `PermissionSeeder`, role permission screen labels, and sidebar gates together.
- Country selection is required, so create a role with the correct market/country scope from the beginning.
- Role delete is not implemented in the controller. Existing roles are meant to be edited, not removed through the current UI.

