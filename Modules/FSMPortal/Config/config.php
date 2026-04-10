<?php

return [
    /*
    |--------------------------------------------------------------------------
    | FSMPortal Configuration
    |--------------------------------------------------------------------------
    */

    // Show evidence photos to the client only when the order is in a completion stage
    'show_photos_on_completion_only' => true,

    // Allow clients to request a re-clean
    'allow_reclean_request' => true,

    // Allow clients to download a PDF job report
    'allow_pdf_download' => true,

    // Polling interval in seconds for live status updates (0 = disabled, use Laravel Echo)
    'status_poll_interval' => 30,
];
