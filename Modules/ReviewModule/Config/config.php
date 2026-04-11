<?php

return [
    'name' => 'ReviewModule',

    /*
     * When true, review requests are sent immediately when a booking is marked
     * completed (via BookingObserver). When false (default), the cron command
     * `reviews:send-requests` handles the 2-hour delay.
     */
    'immediate_review_request' => env('REVIEW_REQUEST_IMMEDIATE', false),

    /*
     * Number of hours after booking completion before sending the review request.
     * Used by the SendReviewRequestsCommand.
     */
    'request_delay_hours' => env('REVIEW_REQUEST_DELAY_HOURS', 2),
];
