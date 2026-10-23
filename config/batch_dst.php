<?php

return [
    'automation_enabled' => env('BATCH_DST_AUTOMATION_ENABLED', false),
    'timezone' => env('BATCH_DST_TIMEZONE', 'Asia/Kolkata'),
    'eligible_batch_statuses' => ['ACTIVE', 'STANDBY', 'UPCOMING'],
    'eligible_schedule_statuses' => ['ACTIVE'],

    // The first matching region owns a batch that contains countries from multiple regions.
    'region_priority' => ['usa-canada', 'uk-eu', 'australia', 'new-zealand'],

    'regions' => [
        'usa-canada' => [
            'label' => 'USA + Canada',
            'countries' => ['USA', 'CANADA'],
            'transitions' => [
                '2026-autumn' => ['date' => '2026-11-01', 'minutes' => 60, 'automatic' => true],
                '2027-spring' => ['date' => '2027-03-14', 'minutes' => -60, 'automatic' => true],
            ],
        ],
        'uk-eu' => [
            'label' => 'UK + EU',
            'countries' => ['UK', 'EU'],
            'transitions' => [
                // Temporary development-only rehearsal. Remove after validation and rollback.
                'development-test-2026-10-22' => ['date' => '2026-10-22', 'minutes' => 60, 'automatic' => false],
                'development-forward-roundtrip-2026-10-23' => ['date' => '2026-10-23', 'minutes' => 60, 'automatic' => false],
                'development-reverse-test-2026-10-23' => ['date' => '2026-10-23', 'minutes' => -60, 'automatic' => false],
                '2026-autumn' => ['date' => '2026-10-25', 'minutes' => 60, 'automatic' => true],
                '2027-spring' => ['date' => '2027-03-28', 'minutes' => -60, 'automatic' => true],
            ],
        ],
        'australia' => [
            'label' => 'Australia',
            'countries' => ['AUSTRALIA'],
            'transitions' => [
                // The client confirmed this transition was applied manually.
                '2026-spring' => ['date' => '2026-10-04', 'minutes' => -60, 'automatic' => false],
                '2027-autumn' => ['date' => '2027-04-04', 'minutes' => 60, 'automatic' => true],
            ],
        ],
        'new-zealand' => [
            'label' => 'New Zealand',
            'countries' => ['NEWZEALAND'],
            'transitions' => [
                // Keep disabled until the current ERP baseline is confirmed by the client.
                '2026-spring' => ['date' => '2026-09-27', 'minutes' => -60, 'automatic' => false],
                '2027-autumn' => ['date' => '2027-04-04', 'minutes' => 60, 'automatic' => true],
            ],
        ],
    ],
];
