<?php

namespace Modules\TitanCore\AI\Adapters;

use Modules\TitanCore\AI\ClientInterface;

use Modules\TitanCore\Services\UsageLogger;

class AnthropicClient implements ClientInterface
{
    protected string $apiKey;
    protected string $provider = 'anthropic';

    public function __construct()
    {
        $this->apiKey = config('ai.providers.anthropic.api_key') ?? env('ANTHROPIC_API_KEY');
    }

    public function chat(array $messages, array $opts = []): array
    {
        if (!$this->apiKey) {
            return ['ok' => false, 'content' => null, 'usage' => null, 'reason' => 'Missing ANTHROPIC_API_KEY'];
        }
        $key = optional(auth()->user())->tenant_id ? ('tenant:' . auth()->user()->tenant_id) : 'global';
        UsageLogger::add($key, 100, 1);
        return ['ok' => true, 'content' => '(stubbed) hello from Anthropic adapter', 'usage' => ['prompt_tokens'=>0,'completion_tokens'=>0]];
    }

    public function embed(array $input, array $opts = []): array
    {
        if (!$this->apiKey) {
            return ['ok' => false, 'vector' => null, 'reason' => 'Missing ANTHROPIC_API_KEY'];
        }
        return ['ok' => true, 'vector' => [0.0, 0.0, 0.0]];
    }

    public function health(): array
    {
        return ['ok' => (bool)$this->apiKey, 'provider' => $this->provider, 'reason' => $this->apiKey ? null : 'Missing API key'];
    }
}
