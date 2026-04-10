<?php

namespace Modules\Aitools\AI\Adapters;

use Modules\Aitools\AI\ClientInterface;
use Modules\Aitools\AI\Http\HttpClientFactory;

use Modules\Aitools\Services\AiLogger;

class OpenAIHttpClient implements ClientInterface
{
    protected $client;
    protected string $apiKey;
    protected string $base;
    protected string $provider = 'openai';

    public function __construct()
    {
        $this->apiKey = config('ai.providers.openai.api_key') ?? env('OPENAI_API_KEY');
        $this->base = rtrim(config('ai.providers.openai.base', 'https://api.openai.com'), '/');
        $this->client = HttpClientFactory::make([
            'base_uri' => $this->base,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    public function chat(array $messages, array $opts = []): array
    {
        if (!$this->apiKey) return ['ok'=>false, 'content'=>null, 'usage'=>null, 'reason'=>'Missing OPENAI_API_KEY'];
        $model = $opts['model'] ?? config('ai.providers.openai.model', 'gpt-4o-mini');
        $payload = ['model'=>$model, 'messages'=>$messages, 'temperature'=>$opts['temperature'] ?? 0.3];
        // Deprecated stub (kept only for backward compatibility).
        if (config('aitools.feature_flags.request_logging_enabled')) {
            AiLogger::logRequest([
                'company_id' => optional(company())->id,
                'user_id' => optional(auth()->user())->id,
                'operation' => 'chat',
                'status' => 'ok',
                'prompt_excerpt' => substr(json_encode($messages), 0, 2000),
                'response_excerpt' => '(deprecated stub)',
                'request_meta' => ['model' => $model],
                'response_meta' => ['note' => 'OpenAIHttpClient is deprecated'],
                'request_hash' => AiLogger::hash('chat', json_encode($messages)),
            ]);
        }
        return ['ok'=>true, 'content'=>'(deprecated stub) Use OpenAIClient for real HTTP calls.', 'usage'=>['prompt_tokens'=>0,'completion_tokens'=>0]];
    }

    public function embed(array $input, array $opts = []): array
    {
        if (!$this->apiKey) return ['ok'=>false, 'vector'=>null, 'reason'=>'Missing OPENAI_API_KEY'];
        return ['ok'=>true, 'vector'=>[0.0,0.0,0.0]];
    }

    public function health(): array
    {
        return ['ok'=>(bool)$this->apiKey, 'provider'=>$this->provider, 'reason'=>$this->apiKey?null:'Missing API key'];
    }
}
