<?php

return [
    /*
     | Titan AI Bridge
     | This module can emit signals and request drafts via TitanZero.
     | Safe by default: bridge is inert if TitanZero isn't installed/enabled.
     */
    'titan' => [
        'enabled' => true,
        'service' => \Modules\TitanZero\Services\ZeroGateway::class,
    ],

    'tools' => [
        [
            'tool_name' => 'promotion_signal_ingest',
            'title' => 'Promotion Signal Ingest',
            'description' => 'Ingest PromotionManagement lifecycle signals into TitanZero.',
            'risk_level' => 'low',
            'input_schema' => [
                'required' => ['type', 'payload'],
                'properties' => [
                    'type' => ['type' => 'string'],
                    'payload' => ['type' => 'object'],
                ],
            ],
            'meta' => [
                'module' => 'PromotionManagement',
                'provider' => 'titanzero',
            ],
        ],
        [
            'tool_name' => 'promotion_propose_actions',
            'title' => 'Promotion Propose Actions',
            'description' => 'Generate bounded action proposals for promotion lifecycle events.',
            'risk_level' => 'medium',
            'input_schema' => [
                'required' => ['event', 'entity_type', 'entity_id'],
                'properties' => [
                    'event' => ['type' => 'string'],
                    'entity_type' => ['type' => 'string'],
                    'entity_id' => ['type' => ['string', 'integer']],
                    'facts' => ['type' => 'object'],
                ],
            ],
            'meta' => [
                'module' => 'PromotionManagement',
                'provider' => 'titanzero',
            ],
        ],
    ],

    'bridges' => [
        'instantads' => [
            'enabled' => true,
            'entity_map' => [
                'promotion_advertisement' => [
                    'source_model' => \Modules\PromotionManagement\Entities\Advertisement::class,
                    'target_model' => \Modules\InstantAds\Models\AiImagePro::class,
                    'source_key' => 'id',
                    'target_key' => 'promotion_advertisement_id',
                ],
            ],
        ],
    ],
];
