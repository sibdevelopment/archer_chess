# ArcherKids Documentation

## Start Here

1. `developer/DEVELOPER_MANUAL.md` - architecture and developer handover.
2. `operations/OPERATION-FLOW-README.md` - end-to-end business flow.
3. `PROJECT-CONTEXT.md` - implementation context and operational decisions.
4. `COMMITTED-CHANGES-2026-09-TO-10.md` - reconciliation of all 46 commits after the August documentation checkpoint.
5. `developer/REGULAR-BATCH-ZOOM-OCCURRENCES.md` - current uncommitted per-class Zoom implementation.

## Areas

```text
actions/       Reports, coach leave, employee leave, feedback
dashboards/    Student, coach, employee, and admin dashboards
database/      Tables and relationships
deployment/    Git, EC2, scheduler, and cron operations
developer/     Architecture and focused technical designs
frontend/      Tracking, UTM, cookie consent, landing behavior
master/        Levels, pricing, holidays, content, special classes
operations/    Leads, students, fees, batches, cover-ups
security/      Credential and security audits
users/         Roles, employees, coaches, availability
```

## Documentation State

Committed application behavior is reconciled through commit `8c59cf1` (10-Oct-2026). Working-tree features must be documented before commit; currently the regular-batch Zoom occurrence feature is documented but remains uncommitted.
