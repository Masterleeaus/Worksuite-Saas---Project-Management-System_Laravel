<?php

namespace Modules\TitanCore\AI\Adapters;

use Modules\TitanCore\AI\ClientInterface;

use Modules\TitanCore\Services\UsageLogger;

class OpenAIClient implements ClientInterface
{
    protected string $apiKey;
    protected string $provider = 'openai';

    public function __construct()
    {
        // Expect config/ai.php merged under 'ai'
        $this->apiKey = config('ai.providers.openai.api_key') ?? env('OPENAI_API_KEY');
    }

    public function chat(array $messages, array $opts = []): array
    {
        if (!$this->apiKey) {
            return ['ok' => false, 'content' => null, 'usage' => null, 'reason' => 'Missing OPENAI_API_KEY'];
        }
        // Stub: no external call in module; host should bind real HTTP client.
        $key = optional(auth()->user())->tenant_id ? ('tenant:' . auth()->user()->tenant_id) : 'global';
        UsageLogger::add($key, 100, 1);
        return ['ok' => true, 'content' => '(stubbed) hello from OpenAI adapter', 'usage' => ['prompt_tokens'=>0,'completion_tokens'=>0]];
    }

    public function embed(array $input, array $opts = []): array
    {
        if (!$this->apiKey) {
            return ['ok' => false, 'vector' => null, 'reason' => 'Missing OPENAI_API_KEY'];
        }
        return ['ok' => true, 'vector' => [0.0, 0.0, 0.0]];
    }

    public function health(): array
    {
        return ['ok' => (bool)$this->apiKey, 'provider' => $this->provider, 'reason' => $this->apiKey ? null : 'Missing API key'];
    }
}
