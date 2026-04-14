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
];
