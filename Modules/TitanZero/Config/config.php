<?php

return [
    // Intent confidence thresholds
    'intent' => [
        'auto_execute_min' => 90,   // >= 90 => can auto-execute low-risk actions (once wired)
        'confirm_min'      => 70,   // 70..89 => require confirmation
        'clarify_below'    => 70,   // < 70 => ask clarifying questions
    ],

    // Risk levels
    'risk' => [
        'low' => ['explain_page', 'help_fill_form', 'find_setting', 'summarize_standard'],
        'medium' => ['draft_note', 'prepare_quote_scope', 'generate_checklist'],
        'high' => ['create_invoice', 'send_message', 'delete_record', 'run_campaign'],
    ],

    // Cleaning business intelligence features
    'cleaning_features' => [
        'booking_slots',
        'cleaner_match',
        'auto_fill_instructions',
        'price_suggestion',
        'rebooking_suggestion',
        'sms_draft',
        'complaint_triage',
        'anomaly_detection',
        'automation_rules',
    ],

    // AIChatPro integration settings
    'aichatpro' => [
        // Default screen when opening the chat: 'new', 'last', 'pinned'
        'default_screen' => env('TITANZERO_AICHAT_DEFAULT_SCREEN', 'new'),
    ],

    // AiChatProFileChat v1.1.0 — file-grounded chat feature gate
    'file_chat' => [
        // Set to false to globally disable document upload in AI Chat Pro.
        'allowed' => (bool) env('TITANZERO_FILE_CHAT_ALLOWED', true),
    ],
];

