# Meta Conversions API Integration

## Purpose

The integration sends downstream demo-lead lifecycle feedback from the ERP to
Meta without requiring an Excel upload. It uses a database outbox so Meta API
availability never blocks an ERP status update.

## Event flow

1. A website enquiry is created in `demoleadenquiries`.
2. The enquiry observer records available `_fbc`, `_fbp`, `fbclid`, landing URL,
   Meta Lead ID, client context, and marketing-consent state.
3. New demo leads retain an explicit link to their originating enquiry. Legacy
   records fall back to the existing user link.
4. When the linked `demoleads.status` changes, the demo-lead observer maps the
   status to a Meta event and writes an immutable payload to `meta_capi_events`.
5. Laravel Scheduler runs `meta-capi:process` every minute.
6. Successful and failed Meta responses remain available in the outbox for
   auditing. Failed events retry with exponential backoff up to the configured
   attempt limit.

Initial `Lead` events are disabled by default because the website Pixel/GTM may
already send that event. Enable `META_CAPI_SEND_INITIAL_LEAD` only after Pixel
and CAPI use the same event ID for deduplication.

## Default status mapping

| ERP status | Meta event |
| --- | --- |
| `SCHEDULED` | `Schedule` |
| `RESCHEDULED` | `DemoRescheduled` |
| `DEMO DONE` | `DemoCompleted` |
| `JOINED` | `DemoJoined` |
| `INTERESTED` | `QualifiedLead` |
| `NOT INTERESTED` | `UnqualifiedLead` |
| `CANCELLED` | `DemoCancelled` |
| `CONVERTED` | `CompleteRegistration` |

`ROWLEAD` is not sent because it represents the transition from enquiry to the
operational demo-lead record, not a new customer conversion.

## Configuration

```env
META_CAPI_ENABLED=false
META_CAPI_DATASET_ID=
META_CAPI_ACCESS_TOKEN=
META_CAPI_TEST_EVENT_CODE=
META_CAPI_API_VERSION=v26.0
META_CAPI_LEAD_EVENT_SOURCE="Archer ERP"
META_CAPI_SEND_INITIAL_LEAD=false
META_CAPI_ALLOW_LEGACY_LEADS=false
META_CAPI_TEST_LEAD_IDS=
META_CAPI_BATCH_SIZE=50
META_CAPI_MAX_ATTEMPTS=5
META_CAPI_TIMEOUT_SECONDS=15
```

Keep credentials in the server environment only. `META_CAPI_TEST_LEAD_IDS`
accepts comma-separated enquiry IDs, source IDs, or values such as
`enquiry:123`. An empty list allows all newly changed leads.

`META_CAPI_ALLOW_LEGACY_LEADS` permits records created before attribution and
consent fields existed to use hashed contact matching. Keep it off unless the
client has explicitly approved those existing CRM records for Meta feedback.

Every lifecycle payload identifies itself as a CRM event with
`event_source=crm` and `lead_event_source` from configuration. A Meta Instant
Form ID is accepted from `meta_lead_id`, `lead_id`, or `leadgen_id` when it is a
15-17 digit value. Website leads can omit it and continue matching through
click/browser identifiers and hashed customer information. Future imports can
write the optional `meta_lead_id` column directly.

## Commands

Send a synthetic event to Meta Test Events:

```bash
php artisan meta-capi:test
```

Process pending events manually:

```bash
php artisan meta-capi:process
php artisan meta-capi:process --limit=10
```

Inspect recent delivery outcomes:

```bash
php artisan tinker --execute="App\\Models\\MetaCapiEvent::latest()->take(10)->get(['id','event_name','erp_status','status','attempts','last_error']);"
```

## Consent and data handling

UK and EU landing-page leads require marketing consent. If consent is absent or
denied, Meta identifiers and client context are not stored and lifecycle events
are not created. Contact information is normalized and SHA-256 hashed before it
is written to the event payload. Access tokens are never stored in the database
or logs.

## Deployment

1. Deploy the code with `META_CAPI_ENABLED=false`.
2. Run `php artisan migrate --force`.
3. Add the Dataset ID, access token, and Test Event Code to `.env`.
4. Clear cached configuration.
5. Set `META_CAPI_ENABLED=true` only in the approved environment.
6. Run `php artisan meta-capi:test` and verify the event in Meta Events Manager.
7. Change an approved test lead through each ERP status and verify the outbox
   plus Events Manager before enabling production.
