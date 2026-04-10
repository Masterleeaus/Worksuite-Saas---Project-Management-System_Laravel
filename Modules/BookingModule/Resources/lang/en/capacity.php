<?php

return [
    'errors' => [
        'max_per_day' => 'Capacity exceeded: maximum bookings per day reached for this staff member.',
        'max_per_slot' => 'Capacity exceeded: maximum bookings per time slot reached for this staff member.',
        'conflict' => 'Schedule conflict: this staff member already has a booking that overlaps this time.',
    ],

    'bulk' => [
        'assign_to' => 'Assign to staff',
        'unassign' => 'Unassign (leave unassigned)',
        'note' => 'Note',
        'note_placeholder' => 'Optional note for assignment log',
        'btn' => 'Apply to selected',
        'done' => 'Bulk assignment complete. Assigned: :assigned, Skipped: :skipped',
    ],

    'assignee' => 'Assigned Staff',
    'appointment' => 'Appointment Type',

    'unassigned' => [
        'title' => 'Unassigned bookings',
        'heading' => 'Unassigned bookings queue',
        'btn' => 'Unassigned queue',
    ],

    'mine' => [
        'title' => 'My bookings',
        'heading' => 'My bookings workload',
        'btn' => 'My bookings',
    ],

    'staff' => [
        'title' => 'Staff capacity',
        'heading' => 'Staff capacity rules',
        'max_per_day' => 'Max bookings/day',
        'max_per_slot' => 'Max bookings/slot',
        'enforce_conflicts' => 'Conflict blocking',
        'enforce_conflicts_help' => 'Block overlapping bookings',
        'count_pending_too' => 'Count pending',
        'count_pending_too_help' => 'Count Pending + Approved',
        'saved' => 'Staff capacity saved.',
        'note' => 'These limits apply when approving or assigning bookings. Leave blank to use global defaults.',
    ],
];
