<?php

return [
    'name'              => 'TitanTalk',
    'message_max_length' => 10000,
    'file_max_size_mb'  => 25,
    'allowed_mime_types' => [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp',
        'application/pdf', 'text/plain',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/zip',
    ],
    'auto_create_rooms' => [
        'booking' => false,
        'project' => false,
        'issue'   => false,
    ],
];
