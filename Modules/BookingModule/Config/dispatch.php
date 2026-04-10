<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Dispatch Board Settings
    |--------------------------------------------------------------------------
    | These settings control the standalone Dispatch Board shipped with the
    | Appointment module.
    */
    'enabled' => env('APPOINTMENT_DISPATCH_ENABLED', true),

    // Default timeslot lane size in minutes for UI grouping (client-side).
    'lane_minutes' => env('APPOINTMENT_DISPATCH_LANE_MINUTES', 60),

    // Allow quick-edit modal for rescheduling within Dispatch.
    'allow_quick_edit' => env('APPOINTMENT_DISPATCH_QUICK_EDIT', true),

    // Rate limit key for dispatch move endpoint (middleware name in RouteServiceProvider).
    'move_throttle' => env('APPOINTMENT_DISPATCH_MOVE_THROTTLE', 'throttle:120,1'),
];
