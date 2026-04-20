<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Automation & AI Integration Manifest (Module-local)
    |--------------------------------------------------------------------------
    |
    | This file is intentionally safe-by-default. It does not execute anything
    | by itself; it merely declares what this module *can* emit/accept.
    |
    | - signals:   domain events this module emits (for AI/automation)
    | - actions:   proposal/action types this module can handle
    | - autopilot: action classes used by policy engine (A/B/C)
    | - reminders: timing configuration for SendBookingReminderJob
    |
    */

    'signals' => [
        // Example:
        // 'booking_late' => [
        //     'severity' => 'amber',
        //     'facts' => ['minutes_late', 'booking_id', 'customer_id', 'provider_id'],
        // ],
    ],

    'actions' => [
        // Example:
        // 'send_delay_message' => [
        //     'risk' => 'green',
        //     'handler' => null, // set to FQCN when you implement a handler
        //     'schema' => [
        //         'booking_id' => 'int|required',
        //         'channel' => 'string|in:sms,email,push',
        //     ],
        // ],
    ],

    'autopilot' => [
        // Class A: safe auto-execute (tagging, drafting, internal tasks)
        'class_a' => [],
        // Class B: bounded auto-execute (customer comms, assignment within caps)
        'class_b' => [],
        // Class C: always approval (money, cancellation, destructive actions)
        'class_c' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Reminder Scheduling
    |--------------------------------------------------------------------------
    | lead_time_minutes: list of minutes before schedule start at which
    |                    SendBookingReminderJob is dispatched.
    |                    Default: 24 hours (1440) and 2 hours (120).
    |
    | tolerance_minutes: matching window width used by BookingReminderService
    |                    when scanning for due schedules.  A schedule whose
    |                    start_time falls within ±(tolerance/2) minutes of the
    |                    lead-time target will be included.
    |
    | triggers:          list of events that can also kick off a reminder.
    |                    'after_assignment'  — reminder sent immediately after
    |                                         a schedule is assigned.
    |                    'after_reschedule'  — reminder sent when date/time
    |                                         changes after assignment.
    */
    'reminders' => [
        'lead_time_minutes' => env('BOOKING_REMINDER_LEAD_TIMES', null)
            ? array_map('intval', explode(',', env('BOOKING_REMINDER_LEAD_TIMES')))
            : [1440, 120],

        'tolerance_minutes' => (int) env('BOOKING_REMINDER_TOLERANCE_MINUTES', 5),

        'triggers' => [
            'after_assignment' => (bool) env('BOOKING_REMINDER_ON_ASSIGN', true),
            'after_reschedule' => (bool) env('BOOKING_REMINDER_ON_RESCHEDULE', true),
        ],
    ],
];

