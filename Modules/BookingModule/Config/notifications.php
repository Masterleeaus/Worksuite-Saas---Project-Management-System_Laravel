<?php

return [
    /*
    |--------------------------------------------------------------------------
    | BookingModule Notification Defaults
    |--------------------------------------------------------------------------
    | Controls which notification types are enabled by default.  These values
    | are used when no per-company or per-user override is present.
    |
    | All notification classes already implement ShouldQueue, so when a real
    | queue worker is running they are processed asynchronously.  If no worker
    | is running Laravel falls back to sync execution (QUEUE_CONNECTION=sync).
    */

    // Appointment-level notifications
    'appointment_assigned'   => true,
    'appointment_reassigned' => true,
    'appointment_cancelled'  => true,

    // Schedule-level notifications
    'schedule_assigned'      => true,
    'schedule_reassigned'    => true,
    'schedule_rescheduled'   => true,
    'schedule_unassigned'    => true,
    'schedule_cancelled'     => true,

    // Reminder notifications (also controlled by reminders.lead_time_minutes in automation.php)
    'reminder_24h'           => true,
    'reminder_2h'            => true,

    // Digest notifications (daily / weekly summaries)
    'daily_digest'           => false,
    'schedule_digest'        => false,

    // Verification flow
    'verification_required'  => true,

    /*
    |--------------------------------------------------------------------------
    | Queue Connection
    |--------------------------------------------------------------------------
    | Notifications are dispatched on this queue connection.  Defaults to the
    | application's default queue (QUEUE_CONNECTION env variable).
    | Set to 'sync' to disable async delivery for notifications only.
    */
    'queue_connection' => env('BOOKING_NOTIFICATION_QUEUE', null),
];

