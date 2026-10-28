<?php

return [
    'enabled' => env('META_CAPI_ENABLED', false),
    'dataset_id' => env('META_CAPI_DATASET_ID'),
    'access_token' => env('META_CAPI_ACCESS_TOKEN'),
    'test_event_code' => env('META_CAPI_TEST_EVENT_CODE'),
    'api_version' => env('META_CAPI_API_VERSION', 'v26.0'),
    'lead_event_source' => env('META_CAPI_LEAD_EVENT_SOURCE', 'Archer ERP'),
    'initial_event_name' => env('META_CAPI_INITIAL_EVENT_NAME', 'SubmitApplication'),
    'send_initial_lead' => env('META_CAPI_SEND_INITIAL_LEAD', false),
    'allow_legacy_leads' => env('META_CAPI_ALLOW_LEGACY_LEADS', false),
    'test_lead_ids' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('META_CAPI_TEST_LEAD_IDS', ''))
    ))),
    'batch_size' => (int) env('META_CAPI_BATCH_SIZE', 50),
    'max_attempts' => (int) env('META_CAPI_MAX_ATTEMPTS', 5),
    'timeout_seconds' => (int) env('META_CAPI_TIMEOUT_SECONDS', 15),

    'demo_status_events' => [
        'SCHEDULED' => 'Schedule',
        'RESCHEDULED' => 'DemoRescheduled',
        'DEMO DONE' => 'DemoCompleted',
        'JOINED' => 'DemoJoined',
        'INTERESTED' => 'QualifiedLead',
        'NOT INTERESTED' => 'UnqualifiedLead',
        'CANCELLED' => 'DemoCancelled',
        'CONVERTED' => 'CompleteRegistration',
    ],
];
