<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'razorpay' => [
        'key' => env('RAZORPAY_KEY'),
        'secret' => env('RAZORPAY_SECRET'),
    ],

    'wati' => [
        'enabled' => filter_var(env('WATI_ENABLED', false), FILTER_VALIDATE_BOOL),
        'audience_mode' => strtolower(trim((string) env('WATI_AUDIENCE_MODE', 'allowlist'))),
        'allowed_student_ids' => array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env('WATI_ALLOWED_STUDENT_IDS', ''))
        ))),
        'base_url' => env('WATI_API_BASE_URL', 'https://live-mt-server.wati.io'),
        'token' => env('WATI_API_TOKEN'),
        'channel_number' => env('WATI_CHANNEL_NUMBER'),
        'tenant_id' => env('WATI_TENANT_ID'),
        'webhook_secret' => env('WATI_WEBHOOK_SECRET'),
        'queue' => env('WATI_QUEUE', 'default'),
        'timeout' => (int) env('WATI_HTTP_TIMEOUT', 15),
        'class_reminder_minutes' => (int) env('WATI_CLASS_REMINDER_MINUTES', 60),
        'class_reminder_window' => (int) env('WATI_CLASS_REMINDER_WINDOW', 5),
        'templates' => [
            'class_reminder' => [
                'name' => env('WATI_CLASS_REMINDER_TEMPLATE'),
                'language' => env('WATI_CLASS_REMINDER_LANGUAGE', 'en'),
            ],
            'fee_due' => [
                'name' => env('WATI_FEE_DUE_TEMPLATE'),
                'language' => env('WATI_FEE_DUE_LANGUAGE', 'en'),
            ],
        ],
    ],

];
