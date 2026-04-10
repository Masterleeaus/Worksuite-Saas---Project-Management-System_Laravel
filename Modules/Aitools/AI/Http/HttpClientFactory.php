<?php

namespace Modules\Aitools\AI\Http;

use GuzzleHttp\Client;

class HttpClientFactory
{
    public static function make(array $config = []): Client
    {
        $defaults = [
            'timeout' => (float) (config('ai.http.timeout', 15)),
            'connect_timeout' => (float) (config('ai.http.connect_timeout', 5)),
            'http_errors' => false,
            'headers' => [
                'User-Agent' => 'AICore/1.0',
                'Accept' => 'application/json',
            ],
        ];
        return new Client(array_replace_recursive($defaults, $config));
    }
}
