<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Reverb Server
    |--------------------------------------------------------------------------
    |
    | This option controls the default server that will be used by Reverb to
    | start a WebSocket server. Reverb ships with a built-in Ratchet server
    | which is suitable for most applications. You may change this here.
    |
    */

    'default' => env('REVERB_SERVER', 'reverb'),

    /*
    |--------------------------------------------------------------------------
    | Reverb Servers
    |--------------------------------------------------------------------------
    |
    | Here you may define details for each of the servers that Reverb can use
    | to handle incoming WebSocket connections. The configuration includes the
    | host and port to listen on as well as any server specific options.
    |
    */

    'servers' => [

        'reverb' => [
            'host' => env('REVERB_SERVER_HOST', '0.0.0.0'),
            'port' => env('REVERB_SERVER_PORT', 8080),
            'hostname' => env('REVERB_HOST', 'localhost'),
            'options' => [
                'tls' => [],
            ],
            'max_request_size' => env('REVERB_MAX_REQUEST_SIZE', 10_000),
            'scaling' => [
                'enabled' => env('REVERB_SCALING_ENABLED', false),
                'channel' => env('REVERB_SCALING_CHANNEL', 'reverb'),
                'server' => [
                    'url' => env('REDIS_URL'),
                    'host' => env('REDIS_HOST', '127.0.0.1'),
                    'port' => env('REDIS_PORT', '6379'),
                    'username' => env('REDIS_USERNAME'),
                    'password' => env('REDIS_PASSWORD'),
                    'database' => env('REDIS_DB', '0'),
                ],
            ],
            'pulse_ingest_interval' => env('REVERB_PULSE_INGEST_INTERVAL', 15),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Reverb Applications
    |--------------------------------------------------------------------------
    |
    | Reverb is multi-tenant, meaning you can run WebSocket connections for
    | multiple applications on a single server. Here you may define each of
    | the applications that will be handled. A simple configuration array
    | is used by default, however, you can store the applications in a
    | database.
    |
    */

    'apps' => [

        'provider' => env('REVERB_APP_PROVIDER', 'config'),

        'apps' => [
            [
                'key'     => env('REVERB_APP_KEY', 'worksuite-reverb-key'),
                'secret'  => env('REVERB_APP_SECRET', 'worksuite-reverb-secret'),
                'app_id'  => env('REVERB_APP_ID', 'worksuite'),
                'options' => [
                    'host'   => env('REVERB_HOST', 'localhost'),
                    'port'   => env('REVERB_PORT', 8080),
                    'scheme' => env('REVERB_SCHEME', 'http'),
                    'useTLS' => env('REVERB_SCHEME', 'http') === 'https',
                ],
                'allowed_origins'   => explode(',', env('REVERB_ALLOWED_ORIGINS', '*')),
                'ping_interval'     => env('REVERB_APP_PING_INTERVAL', 60),
                'activity_timeout'  => env('REVERB_APP_ACTIVITY_TIMEOUT', 30),
                'max_message_size'  => env('REVERB_APP_MAX_MESSAGE_SIZE', 10_000),
            ],
        ],

    ],

];
