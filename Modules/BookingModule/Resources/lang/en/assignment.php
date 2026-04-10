<?php

return [
    'labels' => [
        'assigned_to' => 'Assigned to',
        'assigned_by' => 'Assigned by',
        'assigned_at' => 'Assigned at',
        'status'      => 'Status',
        'unassigned'  => 'Unassigned',
        'assigned'    => 'Assigned',
        'appointment' => 'Appointment',
        'note'        => 'Note',
    ],
    'actions' => [
        'assign'   => 'Assign',
        'reassign' => 'Reassign',
        'unassign' => 'Unassign',
        'save'     => 'Save',
        'cancel'   => 'Cancel',
        'history'  => 'Assignment history',
    ],
    'mail' => [
        'assigned_subject'   => 'New appointment assigned',
        'assigned_line'      => 'You have been assigned an appointment: :name',
        'reassigned_subject' => 'Appointment reassigned',
        'reassigned_line'    => 'An appointment has been reassigned to you: :name',
        'view'               => 'View appointment',

        // Schedule (booking) assignment notifications
        'schedule_assigned_subject'   => 'New booking assigned',
        'schedule_assigned_line1'     => 'A booking for :name was assigned to you.',
        'schedule_assigned_line2'     => 'When: :date at :start',
        'schedule_reassigned_subject' => 'Booking reassigned',
        'schedule_reassigned_line1'   => 'A booking for :name was reassigned to you.',
    ],
];
