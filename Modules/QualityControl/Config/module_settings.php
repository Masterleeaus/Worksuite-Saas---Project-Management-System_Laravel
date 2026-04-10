<?php

return [
    'module_alias' => 'quality_control',
    'module_name' => 'Quality Control',
    'packages' => [
        'superadmin',
        'company',
    ],
    'roles' => [
        'admin',
        'employee',
    ],
    'permissions' => [
        'view_quality_control',
        'add_quality_control',
        'edit_quality_control',
        'delete_quality_control',
        'view_qc_reports',
        'manage_qc_templates',
        'trigger_reclean',
        'view_cleaner_ratings',
    ],
    'package_labels' => [
        'qualitycontrol' => 'Quality Control',
        'quality_control' => 'Quality Control',
    ],
];
