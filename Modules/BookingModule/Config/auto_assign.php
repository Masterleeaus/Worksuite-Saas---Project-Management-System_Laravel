<?php

return [
    // Master switch for auto assignment.
    'enabled' => true,

    // Strategy key: round_robin | least_busy | schedule_match
    'strategy' => 'schedule_match',

    // If true, only users that have an availability schedule matching the slot are eligible.
    'require_schedule_match' => false,

    // If true, assignment will only consider users who have the relevant permission.
    'require_permission' => true,

    // Permission that marks a user as eligible for assignments.
    'eligible_permission' => 'appointment.assign',

    // When no candidate is found, leave the appointment unassigned.
    'fallback_unassigned' => true,
];
