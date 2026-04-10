<?php

return [
    'name' => 'CustomerFeedback',

    'module_settings' => [
        'enable_nps' => true,
        'enable_ai_insights' => true,
        'enable_email_sync' => true,
    ],

    'defaults' => [
        'priority' => 'medium',
        'status' => 'open',
        'feedback_type' => 'feedback',
    ],

    'survey' => [
        'nps' => [
            'default_question' => 'How likely are you to recommend us to a friend or colleague?',
            'score_min' => 0,
            'score_max' => 10,
        ],
        'star_rating' => [
            'min' => 1,
            'max' => 5,
        ],
    ],

    // How many days before a survey link expires (default: 7)
    'survey_expiry_days' => 7,

    'email' => [
        'sync_interval' => 5, // minutes
        'auto_reply_enabled' => true,
        'mark_as_read_on_reply' => true,
    ],

    'ai' => [
        'enabled' => true,
        'sentiment_analysis' => true,
        'category_suggestion' => true,
        'priority_suggestion' => true,
        'response_suggestion' => true,
        'min_confidence' => 0.7,
    ],

    'pagination' => [
        'per_page' => 20,
    ],
];
