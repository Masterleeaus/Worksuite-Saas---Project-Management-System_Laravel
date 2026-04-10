<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ClientPulse Configuration
    |--------------------------------------------------------------------------
    */

    // Send a rating prompt email to the client after job completion
    'send_rating_email' => true,

    // Send a rating prompt SMS (via Sms module) after job completion
    'send_rating_sms' => true,

    // Number of hours after job completion before sending the rating prompt
    'rating_prompt_delay_hours' => 1,

    // Allow clients to submit extras requests
    'allow_extras_requests' => true,

    // Maximum number of extras a client can include in one request
    'max_extras_per_request' => 10,
];
