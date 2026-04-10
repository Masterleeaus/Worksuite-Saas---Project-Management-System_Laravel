<?php

namespace Modules\Aitools\AI\Adapters;

use Modules\Aitools\AI\ClientInterface;
use Modules\Aitools\AI\Http\HttpClientFactory;

use Modules\Aitools\Services\AiLogger;

class AnthropicClient implements ClientInterface
{
    protected ?string $apiKey;
    protected string $base;
    protected string $provider = 'anthropic';

    public function __construct(?string $apiKey = null, ?string $baseUrl = null)
    {
        $this->apiKey = $apiKey ?: (config('ai.providers.anthropic.api_key') ?? env('ANTHROPIC_API_KEY'));
        $this->base = rtrim($baseUrl ?: (config('ai.providers.anthropic.base_url') ?? 'https://api.anthropic.com'), '/');
    }

    public function chat(array $messages, array $opts = []): array
    {
        if (!$this->apiKey) {
            return ['ok' => false, 'content' => null, 'usage' => null, 'reason' => 'Missing Anthropic API key'];
        }

        // Convert OpenAI-style messages into a single user prompt.
        $prompt = '';
        foreach ($messages as $m) {
            $role = $m['role'] ?? 'user';
            $content = $m['content'] ?? '';
            $prompt .= strtoupper($role) . ": " . $content . "\n";
        }
        $model = $opts['model'] ?? 'claude-3-5-sonnet-20240620';
        $maxTokens = (int)($opts['max_tokens'] ?? 512);

        $payload = [
            'model' => $model,
            'max_tokens' => $maxTokens,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ];

        $client = HttpClientFactory::make([
            'base_uri' => $this->base,
            'headers' => [
                'x-api-key' => $this->apiKey,
                'anthropic-version' => $opts['anthropic_version'] ?? '2023-06-01',
                'Content-Type' => 'application/json',
            ],
        ]);

        $resp = $client->post('/v1/messages', ['json' => $payload]);
        $body = (string)$resp->getBody();
        $json = json_decode($body, true);
        if (!is_array($json)) {
            return ['ok' => false, 'content' => null, 'usage' => null, 'reason' => 'Invalid Anthropic response'];
        }
        if (isset($json['error'])) {
            return ['ok' => false, 'content' => null, 'usage' => null, 'reason' => $json['error']['message'] ?? 'Anthropic error'];
        }

        $text = '';
        if (!empty($json['content']) && is_array($json['content'])) {
            // Anthropics returns content blocks.
            foreach ($json['content'] as $block) {
                if (($block['type'] ?? '') === 'text') {
                    $text .= $block['text'] ?? '';
                }
            }
        }

        if (config('aitools.feature_flags.request_logging_enabled')) {
            AiLogger::logRequest([
                'company_id' => optional(company())->id,
                'user_id' => optional(auth()->user())->id,
                'provider_id' => null,
                'model_id' => null,
                'operation' => 'chat',
                'status' => 'ok',
                'error_message' => null,
                'prompt_excerpt' => substr($prompt, 0, 2000),
                'response_excerpt' => substr((string)$text, 0, 2000),
                'latency_ms' => null,
                'request_meta' => ['model' => $model],
                'response_meta' => ['http_status' => $resp->getStatusCode()],
                'request_hash' => AiLogger::hash('chat', $prompt),
            ]);
        }

        return ['ok' => true, 'content' => $text, 'usage' => null];
    }

    public function embed(array $input, array $opts = []): array
    {
        return ['ok' => false, 'vector' => null, 'reason' => 'Anthropic embeddings not supported in this adapter'];
    }

    public function health(): array
    {
        return ['ok' => (bool)$this->apiKey, 'provider' => $this->provider, 'reason' => $this->apiKey ? null : 'Missing API key'];
    }
}
