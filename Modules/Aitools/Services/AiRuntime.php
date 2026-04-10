<?php

namespace Modules\Aitools\Services;

use Modules\Aitools\AI\ClientInterface;
use Modules\Aitools\AI\Adapters\OpenAIClient;
use Modules\Aitools\AI\Adapters\AnthropicClient;
use Modules\Aitools\Entities\AiProvider;

class AiRuntime
{
    public static function clientForProvider(?AiProvider $provider): ClientInterface
    {
        $driver = strtolower((string) optional($provider)->driver);
        $apiKey = optional($provider)->api_key;
        $baseUrl = optional($provider)->base_url;

        return match ($driver) {
            'anthropic' => new AnthropicClient($apiKey, $baseUrl),
            default => new OpenAIClient($apiKey, $baseUrl),
        };
    }
}
