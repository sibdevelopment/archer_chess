# Batch DST Automation

## Scope

The DST adjustment changes regular batch names and active batch schedules for
`ACTIVE`, `STANDBY`, and `UPCOMING` batches. Historical inactive batches and
inactive schedule rows are not changed.

The configured regions are USA/Canada, UK/EU, Australia, and New Zealand. A
batch containing countries from more than one configured region is owned by
the first matching entry in `region_priority`, so it is never shifted twice.

## Safety controls

- Automation is disabled unless `BATCH_DST_AUTOMATION_ENABLED=true`.
- A confirmed adjustment is allowed only on its configured transition date.
- A unique transition key prevents duplicate execution.
- A shared lock and database transaction prevent concurrent or partial runs.
- The command blocks the entire transition if any batch name or schedule cannot
  be transformed consistently.
- Every changed batch stores exact before and after JSON snapshots.
- Rollback is refused if a batch has changed since the adjustment was applied.
- The legacy `shift:batch-one-hour` command is disabled.

## Commands

Always preview first:

```bash
php artisan batches:dst-adjust uk-eu 2026-autumn --dry-run
php artisan batches:dst-adjust usa-canada 2026-autumn --dry-run
```

Apply only on the configured date:

```bash
php artisan batches:dst-adjust uk-eu 2026-autumn --confirm
```

Preview and execute an emergency exact rollback:

```bash
php artisan batches:dst-rollback RUN_ID --dry-run
php artisan batches:dst-rollback RUN_ID --confirm
```

The daily scheduler calls `batches:dst-process` at 02:00 Asia/Kolkata. The
two-hour delay after midnight gives operations time to update WhatsApp group
names. On a normal day the command makes no changes. Australia and New Zealand
2026 are marked as manually handled.

## Approved transition schedule

| Region | Date | Run time (IST) | ERP shift |
| --- | --- | --- | --- |
| UK + EU | 2026-10-25 | 02:00 | +60 minutes |
| USA + Canada | 2026-11-01 | 02:00 | +60 minutes |
| USA + Canada | 2027-03-14 | 02:00 | -60 minutes |
| UK + EU | 2027-03-28 | 02:00 | -60 minutes |
| Australia | 2027-04-04 | 02:00 | +60 minutes |
| New Zealand | 2027-04-04 | 02:00 | +60 minutes |
| New Zealand | 2027-09-26 | 02:00 | -60 minutes |
| Australia | 2027-10-03 | 02:00 | -60 minutes |
| UK + EU | 2027-10-31 | 02:00 | +60 minutes |
| USA + Canada | 2027-11-07 | 02:00 | +60 minutes |

Each row is a separate seasonal transition and must not be implemented by
rolling back a prior transition. Dates are deliberately explicit rather than
calculated from calendar rules. Update `config/batch_dst.php` only when the
client confirms a revised schedule.

## Deployment sequence

1. Back up production and refresh the development database using the documented
   safe refresh process. Keep development integrations and notifications off.
2. Run the migration on development.
3. Run the relevant `--dry-run` and resolve every blocked row.
4. Apply on development using a controlled test date/configuration and have the
   client validate names, weekdays, times, dashboards, and class links.
5. Back up production again, deploy, run `--dry-run`, and enable automation only
   after approval.
