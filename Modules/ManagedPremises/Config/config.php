<?php

return [
    'name' => 'ManagedPremises',

    // If enabled, show a quick "Create Job" link on site pages (only if the core Jobs/Projects module exists).
    'visit_generation' => [
        'default_days' => 30,
        'max_per_plan' => 60,
    ],

    'integrations' => [
        'jobs' => true,
        'documents' => true,
        'titan_zero' => false,
        'quality_control_owner' => true,
    ],

    'ownership' => [
        'premises_master' => 'managedpremises',
        'inspection_engine' => 'quality_control',
    ],

    'defaults' => [
        'approval_mode' => 'manual',
        'hazard_severity' => 'medium',
        'service_window' => 'business_hours',
        'service_plan_state' => 'active',
    ],
];
