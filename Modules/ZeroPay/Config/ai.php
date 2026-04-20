<?php

return [
    'privacy_first' => true,
    'device_first' => true,
    'byo_key' => true,
    'allowed_operations' => [
        'draft_followup',
        'recommend_channel',
        'recommend_rail',
        'suggest_bank_match',
        'suggest_escalation',
        'queue_voice_followup',
    ],
    'forbidden_operations' => [
        'move_money',
        'auto_settle_without_confirmation',
    ],
];
