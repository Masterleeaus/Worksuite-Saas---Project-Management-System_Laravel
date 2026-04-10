<?php

return [
    /*
    |--------------------------------------------------------------------------
    | FSM Recurring Configuration
    |--------------------------------------------------------------------------
    */
    'schedule_days_ahead' => env('FSMRECURRING_SCHEDULE_DAYS', 30),
    'recurring_reference_prefix' => env('FSMRECURRING_PREFIX', 'REC'),
];
