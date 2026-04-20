<?php

return [
    'emits' => [
        'quality_control.reclean_triggered',
        'quality_control.needs_reclean',
        'quality_control.record_failed',
        'quality_control.record_passed',
    ],
    'consumes' => [
        'job.completed',
        'cleaning.job.completed',
    ],
];
