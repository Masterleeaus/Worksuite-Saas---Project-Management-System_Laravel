<?php
return [
    'quote_agent' => [
        'title' => 'Quoting Agent',
        'kb_collection_key' => 'kb_agent_quote',
        'system' => "You are the Quoting Agent for a cleaning business. Only use the provided knowledge context. If required info is missing, ask concise questions before proposing prices.",
        'output_schema' => [
            'quote' => [
                'line_items' => 'array',
                'subtotal' => 'number',
                'gst' => 'number|null',
                'total' => 'number',
                'assumptions' => 'array',
                'questions' => 'array',
            ]
        ],
        'must_ask' => ['property_type','bedrooms','bathrooms','condition','frequency','address_suburb'],
        'forbidden' => ['dispatch rostering', 'staff assignment decisions'],
    ],
    'dispatch_agent' => [
        'title' => 'Dispatch Agent',
        'kb_collection_key' => 'kb_agent_dispatch',
        'system' => "You are the Dispatch Agent. Build a roster/dispatch proposal using the provided knowledge context and the input consConfigurets. If critical info is missing, ask questions.",
        'output_schema' => [
            'dispatch' => [
                'assignments' => 'array',
                'route_notes' => 'array',
                'risks' => 'array',
                'questions' => 'array',
            ]
        ],
        'must_ask' => ['date','time_window','job_locations','available_staff'],
        'forbidden' => ['pricing', 'quote calculations'],
    ],
    'configuration_agent' => [
        'title' => 'configuration Agent',
        'kb_collection_key' => 'kb_agent_configuration',
        'system' => "You are the configuration Agent. Produce step-by-step SOP configuration plans and checklists grounded in the provided knowledge context.",
        'output_schema' => [
            'configuration' => [
                'plan' => 'array',
                'checklist' => 'array',
                'quiz' => 'array',
            ]
        ],
        'must_ask' => ['role','skill_level','task'],
        'forbidden' => ['pricing', 'dispatch decisions'],
    ],
];
