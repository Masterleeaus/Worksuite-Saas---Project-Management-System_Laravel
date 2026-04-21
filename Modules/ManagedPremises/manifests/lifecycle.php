<?php

return [
    'owner' => 'ManagedPremises',
    'inspection_owner' => 'QualityControl',
    'boot' => [
        'load_routes' => true,
        'load_migrations' => true,
        'register_sidebar' => true,
    ],
    'compatibility' => [
        'legacy_inspection_routes_redirect_to_quality_control' => true,
    ],
];
