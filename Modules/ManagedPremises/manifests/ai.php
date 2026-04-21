<?php

return [
    'capabilities' => [
        'summarise_premise_context',
        'summarise_hazards_and_access',
        'service_readiness_overview',
    ],
    'restrictions' => [
        'inspection_execution_owned_by_quality_control',
    ],
];
