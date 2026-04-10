<?php

return [
    /*
    |--------------------------------------------------------------------------
    | FSM Core Configuration
    |--------------------------------------------------------------------------
    */
    'order_reference_prefix' => env('FSMCORE_ORDER_PREFIX', 'ORD'),
    'default_priority' => env('FSMCORE_DEFAULT_PRIORITY', '0'), // 0=normal, 1=urgent
];
