<?php

return [
    'queue' => env('SYNAPSE_DISPATCH_QUEUE', 'dispatch'),
    'auto_planning_enabled' => env('SYNAPSE_DISPATCH_AUTO_PLANNING', true),
];
