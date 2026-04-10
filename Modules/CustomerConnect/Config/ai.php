<?php

return [
    /*
     | Titan Connect - AI Readiness (Pass 6)
     | No external AI calls. Storage + UI hooks only.
     */
    'enabled' => env('CUSTOMERCONNECT_AI_ENABLED', false),
    'show_suggestions' => env('CUSTOMERCONNECT_AI_SHOW_SUGGESTIONS', false),
    'max_suggestions_per_thread' => env('CUSTOMERCONNECT_AI_MAX_SUGGESTIONS', 20),
];
