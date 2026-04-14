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
    | - signals: domain events this module emits (for AI/automation)
    | - actions: proposal/action types this module can handle
    | - autopilot: action classes used by policy engine (A/B/C)
    |
    */

    'signals' => [
        'service_management.service_created' => [
            'severity' => 'info',
            'facts' => ['service_id', 'company_id', 'name', 'category_id', 'sub_category_id', 'is_active'],
        ],
        'service_management.service_updated' => [
            'severity' => 'info',
            'facts' => ['service_id', 'company_id', 'changes'],
        ],
        'service_management.service_deleted' => [
            'severity' => 'warning',
            'facts' => ['service_id', 'company_id', 'name'],
        ],
        'service_management.pricing_changed' => [
            'severity' => 'info',
            'facts' => ['action', 'pricing_rule_id', 'service_id', 'company_id', 'zone_id', 'base_price_override'],
        ],
        'service_management.addon_changed' => [
            'severity' => 'info',
            'facts' => ['action', 'addon_id', 'service_id', 'company_id', 'name', 'price'],
        ],
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
];
