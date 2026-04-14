<?php

$addOnOf = 'worksuite-saas-new';

return [
    'name' => 'Recruit',
    'verification_required' => true,
    'envato_item_id' => 43314875,
    'parent_envato_id' => 23263417,
    'parent_min_version' => '5.2.5',
    'script_name' => $addOnOf . '-recruit-module',
    'parent_product_name' => $addOnOf,
    'setting' => \Modules\Recruit\Entities\RecruitGlobalSetting::class,

    // Compliance expiry alert thresholds in days (Police Check, WWCC, Insurance)
    // Used by: recruit:compliance-expiry-reminder artisan command
    'compliance_expiry_alert_days' => [30, 14, 7],
];
