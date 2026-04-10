<?php

return [
    // Pass 8: Cleaning Operations Pack
    'enabled' => env('CUSTOMERCONNECT_CLEANING_OPS_ENABLED', true),

    'arrival_notice' => [
        'enabled' => env('CUSTOMERCONNECT_CLEANING_ARRIVAL_NOTICE', true),
        'trigger_statuses' => ['on_the_way', 'en_route', 'arriving'],
    ],

    'post_clean_review' => [
        'enabled' => env('CUSTOMERCONNECT_CLEANING_REVIEW_REQUEST', true),
        'trigger_statuses' => ['completed', 'done', 'finished'],
        // Delay in minutes before sending
        'delay_minutes' => (int) env('CUSTOMERCONNECT_CLEANING_REVIEW_DELAY_MIN', 30),
    ],

    'quality_followup' => [
        'enabled' => env('CUSTOMERCONNECT_CLEANING_QUALITY_FOLLOWUP', true),
        'trigger_statuses' => ['completed', 'done', 'finished'],
        // Delay in hours before asking if anything was missed
        'delay_hours' => (int) env('CUSTOMERCONNECT_CLEANING_QUALITY_DELAY_HRS', 4),
    ],
];
