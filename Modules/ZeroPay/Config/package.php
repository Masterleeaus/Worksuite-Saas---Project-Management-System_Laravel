<?php

return [
    'default_enabled' => true,
    'tiers' => [
        'basic' => [
            'channels' => ['email'],
            'voice' => false,
        ],
        'pro' => [
            'channels' => ['email', 'sms', 'whatsapp', 'messenger', 'telegram'],
            'voice' => false,
        ],
        'enterprise' => [
            'channels' => ['email', 'sms', 'whatsapp', 'messenger', 'telegram'],
            'voice' => true,
        ],
    ],
];
