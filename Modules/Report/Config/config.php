<?php

return [
    'name' => 'Report',

    /*
    |--------------------------------------------------------------------------
    | Scheduled Report Settings
    |--------------------------------------------------------------------------
    */
    'weekly_booking_summary_day'  => 'Monday',   // day of week to send weekly summary
    'monthly_financial_day'       => 1,           // day of month to send monthly summary
    'daily_schedule_time'         => '06:00',     // time to dispatch daily cleaner schedule

    /*
    |--------------------------------------------------------------------------
    | Export Settings
    |--------------------------------------------------------------------------
    */
    'export_chunk_size' => 500,  // rows per chunk when queuing large exports

    /*
    |--------------------------------------------------------------------------
    | Feature Flags (subscription gating)
    |--------------------------------------------------------------------------
    | These feature keys are checked via hasFeature() / subscription_package_features.
    | 'basic_reports'    — daily bookings table, included in all plans
    | 'advanced_reports' — heatmap, scorecard, export (premium plans only)
    */
    'feature_basic'    => 'basic_reports',
    'feature_advanced' => 'advanced_reports',

    /*
    |--------------------------------------------------------------------------
    | Data Retention
    |--------------------------------------------------------------------------
    | Number of months reports query back by default (per plan tier).
    */
    'retention_standard_months' => 12,
    'retention_premium_months'  => 0,  // 0 = unlimited
];
