<?php

return [
    'name'               => 'TitanTalk',
    'message_max_length' => 10000,
    'file_max_size_mb'   => 25,

    'allowed_mime_types' => [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp',
        'application/pdf', 'text/plain',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/zip',
    ],

    // -------------------------------------------------------------------------
    // Auto-create room on business object creation (via model observers).
    // Set to true to enable, false to disable per type.
    // Requires the corresponding module (BookingModule / FSMCore) to be installed.
    // -------------------------------------------------------------------------
    'auto_create_rooms' => [
        'booking'     => env('TITANTALK_AUTOROOM_BOOKING', false),
        'service_job' => env('TITANTALK_AUTOROOM_SERVICE_JOB', false),
        'project'     => env('TITANTALK_AUTOROOM_PROJECT', false),
        'issue'       => env('TITANTALK_AUTOROOM_ISSUE', false),
    ],

    // -------------------------------------------------------------------------
    // DM bridge: when true, DM room messages are also mirrored into the
    // Worksuite UserChat inbox so /messages stays in sync.
    // -------------------------------------------------------------------------
    'mirror_dm_to_userchat' => env('TITANTALK_MIRROR_DM', true),

    // -------------------------------------------------------------------------
    // SMS escalation: when true and Sms module is installed, @mentions in
    // dispatch/issue rooms trigger an SMS via CleaningNotificationService.
    // -------------------------------------------------------------------------
    'sms_escalation' => [
        'enabled'     => env('TITANTALK_SMS_ESCALATION', false),
        'room_types'  => ['dispatch', 'issue', 'service_job'],
    ],
];

