<?php

namespace Modules\Aitools\AI\Adapters;

use Modules\Aitools\AI\ClientInterface;
use Modules\Aitools\AI\Http\HttpClientFactory;

use Modules\Aitools\Services\AiLogger;

class OpenAIClient implements ClientInterface
{
    protected ?string $apiKey;
    protected string $base;
    protected string $provider = 'openai';

    public function __construct(?string $apiKey = null, ?string $baseUrl = null)
    {
        $this->apiKey = $apiKey ?: (config('ai.providers.openai.api_key') ?? env('OPENAI_API_KEY'));
        $base = rtrim($baseUrl ?: (config('ai.providers.openai.base_url') ?? 'https://api.openai.com'), '/');
        // Support stored base_url as either https://api.openai.com or https://api.openai.com/v1
        $this->base = str_ends_with($base, '/v1') ? substr($base, 0, -3) : $base;
    }

    public function chat(array $messages, array $opts = []): array
    {
        if (!$this->apiKey) {
            return ['ok' => false, 'content' => null, 'usage' => null, 'reason' => 'Missing OpenAI API key'];
        }

        $model = $opts['model'] ?? 'gpt-4o-mini';
        $payload = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => $opts['temperature'] ?? 0.3,
        ];

        $client = HttpClientFactory::make([
            'base_uri' => $this->base,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
        ]);

        $resp = $client->post('/v1/chat/completions', ['json' => $payload]);
        $body = (string) $resp->getBody();
        $json = json_decode($body, true);

        if (!is_array($json)) {
            return ['ok' => false, 'content' => null, 'usage' => null, 'reason' => 'Invalid OpenAI response'];
        }
        if (isset($json['error'])) {
            return ['ok' => false, 'content' => null, 'usage' => null, 'reason' => $json['error']['message'] ?? 'OpenAI error'];
        }

        $content = $json['choices'][0]['message']['content'] ?? '';
        $usage = $json['usage'] ?? null;

        // Optional: write request log (minimal) if enabled by module.
        if (config('aitools.feature_flags.request_logging_enabled')) {
            AiLogger::logRequest([
                'company_id' => optional(company())->id,
                'user_id' => optional(auth()->user())->id,
                'provider_id' => null,
                'model_id' => null,
                'operation' => 'chat',
                'status' => 'ok',
                'error_message' => null,
                'prompt_excerpt' => substr(json_encode($messages), 0, 2000),
                'response_excerpt' => substr((string)$content, 0, 2000),
                'latency_ms' => null,
                'request_meta' => ['model' => $model],
                'response_meta' => ['http_status' => $resp->getStatusCode(), 'usage' => $usage],
                'request_hash' => AiLogger::hash('chat', json_encode($messages)),
            ]);
        }

        return [
            'ok' => true,
            'content' => (string) $content,
            'usage' => [
                'prompt_tokens' => (int)($usage['prompt_tokens'] ?? 0),
                'completion_tokens' => (int)($usage['completion_tokens'] ?? 0),
                'total_tokens' => (int)($usage['total_tokens'] ?? 0),
            ],
        ];
    }

    public function embed(array $input, array $opts = []): array
    {
        if (!$this->apiKey) {
            return ['ok' => false, 'vector' => null, 'reason' => 'Missing OpenAI API key'];
        }

        $model = $opts['model'] ?? 'text-embedding-3-small';
        $payload = [
            'model' => $model,
            'input' => $input,
        ];

        $client = HttpClientFactory::make([
            'base_uri' => $this->base,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
        ]);

        $resp = $client->post('/v1/embeddings', ['json' => $payload]);
        $body = (string) $resp->getBody();
        $json = json_decode($body, true);
        if (!is_array($json)) {
            return ['ok' => false, 'vector' => null, 'reason' => 'Invalid OpenAI embedding response'];
        }
        if (isset($json['error'])) {
            return ['ok' => false, 'vector' => null, 'reason' => $json['error']['message'] ?? 'OpenAI error'];
        }

        $vec = $json['data'][0]['embedding'] ?? null;
        if (!is_array($vec)) {
            return ['ok' => false, 'vector' => null, 'reason' => 'Missing embedding vector'];
        }

        return ['ok' => true, 'vector' => $vec];
    }

    public function health(): array
    {
        return ['ok' => (bool)$this->apiKey, 'provider' => $this->provider, 'reason' => $this->apiKey ? null : 'Missing API key'];
    }
}
