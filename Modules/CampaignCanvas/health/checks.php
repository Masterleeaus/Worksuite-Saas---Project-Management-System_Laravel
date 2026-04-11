<?php
return [
    ['id' => 'campaigncanvas:module_json',     'label' => 'module.json present',     'severity' => 'error', 'ok' => file_exists(__DIR__ . '/../module.json'),              'hint' => 'module.json is required for module discovery.'],
    ['id' => 'campaigncanvas:service_provider','label' => 'ServiceProvider present',  'severity' => 'error', 'ok' => (bool) glob(__DIR__ . '/../Providers/*ServiceProvider.php'), 'hint' => 'Ensure Providers/CampaignCanvasServiceProvider.php exists.'],
    ['id' => 'campaigncanvas:routes_web',      'label' => 'Routes/web.php present',  'severity' => 'warn',  'ok' => file_exists(__DIR__ . '/../Routes/web.php'),          'hint' => 'Routes/web.php required.'],
    ['id' => 'campaigncanvas:migration',       'label' => 'Migration present',       'severity' => 'warn',  'ok' => (bool) glob(__DIR__ . '/../Database/Migrations/*.php'), 'hint' => 'Migration file missing.'],
    ['id' => 'campaigncanvas:views',           'label' => 'Views present',           'severity' => 'warn',  'ok' => is_dir(__DIR__ . '/../Resources/views'),              'hint' => 'Resources/views directory missing.'],
];
