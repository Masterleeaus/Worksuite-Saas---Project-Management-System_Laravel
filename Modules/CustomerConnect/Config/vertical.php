<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Vertical Profile
    |--------------------------------------------------------------------------
    |
    | Titan Connect is designed to work across industries. For Clean Smart and
    | cleaning business deployments, we ship sensible defaults here.
    |
    */

    'industry' => env('CUSTOMERCONNECT_INDUSTRY', 'cleaning'),

    // Common cleaning service types (used for UI labels, segmentation, and templates)
    'service_types' => [
        'regular_home',
        'deep_clean',
        'end_of_lease',
        'airbnb',
        'office',
        'carpet',
        'pressure',
        'pool',
        'car',
    ],

    // Default campaign types to show for this vertical
    'campaign_types' => [
        'broadcast',
        'job_reminder',
        'arrival_notice',
        'end_of_lease_check',
        'review_request',
        'payment_nudge',
    ],
];
