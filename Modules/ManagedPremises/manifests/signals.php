<?php

return [
    'events' => [
        'property.created',
        'property.updated',
        'property.visit.created',
        'property.document.uploaded',
        'property.approval.requested',
    ],
    'integration_signals' => [
        'quality_control.reclean_triggered',
    ],
];
