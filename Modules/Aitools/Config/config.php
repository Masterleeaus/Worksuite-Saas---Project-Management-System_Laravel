<?php

$addOnOf = 'worksuite-saas-new';

return [
    'name' => 'Aitools',
    'verification_required' => true,
    'envato_item_id' => 61563898,
    'parent_envato_id' => 23263417,
    'parent_min_version' => '5.5.14',
    'script_name' => $addOnOf . '-aitools-module',
    'parent_product_name' => $addOnOf,
    'setting' => \Modules\Aitools\Entities\AiToolsGlobalSetting::class,

    'feature_flags' => [
        'providers_enabled' => true,
        'request_logging_enabled' => true,
        'prompts_enabled' => true,
        'tools_enabled' => true,
    ],

    // Tool handler map (allowlist). You can override this in the host app if needed.
    'tools_map' => [
        // Built-in tool pack (Pass 8)
        'kb_search' => \Modules\Aitools\Tools\KbSearchTool::class,
        'summarise_text' => \Modules\Aitools\Tools\SummariseTextTool::class,
        'classify_intent' => \Modules\Aitools\Tools\ClassifyIntentTool::class,
        'extract_json' => \Modules\Aitools\Tools\ExtractJsonTool::class,
        'rewrite_with_tone' => \Modules\Aitools\Tools\RewriteWithToneTool::class,
    ],

];
