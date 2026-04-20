<?php

return [
    'keys' => [
        'view_zeropay_dashboard',
        'manage_zeropay_settings',
        'manage_zeropay_sessions',
        'manage_zeropay_reconciliation',
        'manage_zeropay_followups',
    ],
    'roles' => [
        'admin' => [
            'view_zeropay_dashboard',
            'manage_zeropay_settings',
            'manage_zeropay_sessions',
            'manage_zeropay_reconciliation',
            'manage_zeropay_followups',
        ],
        'manager' => [
            'view_zeropay_dashboard',
            'manage_zeropay_sessions',
            'manage_zeropay_reconciliation',
            'manage_zeropay_followups',
        ],
    ],
];
