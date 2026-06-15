# Master Module Documentation Index

This folder contains documentation for the Master sidebar modules.

Master modules define reference data, website content, pricing levels, holidays, and special activity records that are reused by Operations, Actions, dashboards, and frontend pages.

## Sidebar Modules

```text
Master > Levels
Master > Payment Level
Master > Holidays
Master > Master Classes
Master > Tournaments
Master > Blogs
Master > Meet Our Kids
Master > Meet Our Tutors
Master > Gallery
Master > Events
```

## Documents

```text
01-LEVELS.md
02-PAYMENT-LEVEL.md
03-HOLIDAYS.md
04-MASTER-CLASSES.md
05-TOURNAMENTS.md
06-BLOGS.md
07-MEET-OUR-KIDS.md
08-MEET-OUR-TUTORS.md
09-GALLERY.md
10-EVENTS.md
```

## Main Code Area

```text
routes/backend.php
app/Http/Controllers/Admin/*Controller.php
app/Models/*
resources/views/Admin/*
database/migrations/*
database/seeders/PermissionSeeder.php
```

## Common Pattern

Most Master modules follow this pattern:

```text
index screen
server-side DataTables data endpoint
create form
store action
edit form
update action
status toggle endpoint
optional show/modal screen
optional file upload
```

Status values are generally:

```text
ACTIVE
INACTIVE
```

The status toggle is usually an AJAX switch that passes:

```text
route_key
status
```

