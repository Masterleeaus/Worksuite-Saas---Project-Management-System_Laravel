<?php

return [
    'name' => 'TitanAgents',
    'agents' => require __DIR__.'/agents.php',

    // ElevenLabs API key for Voice Chatbot platform
    // Set ELEVENLABS_API_KEY in .env or override per-tenant via settings
    'elevenlabs_api_key' => env('ELEVENLABS_API_KEY', ''),
];
