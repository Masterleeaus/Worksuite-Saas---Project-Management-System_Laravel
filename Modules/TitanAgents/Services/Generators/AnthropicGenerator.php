<?php

namespace Modules\TitanAgents\Services\Generators;

use Illuminate\Support\Facades\Http;

class AnthropicGenerator implements GeneratorInterface
{
    public function generate(array $messages, array $options = []): array
    {
        $apiKey    = config('services.anthropic.api_key', '');
        $model     = $options['model'] ?? 'claude-3-5-sonnet-20241022';
        $maxTokens = $options['max_tokens'] ?? 2000;

        // Separate system message from conversation messages
        $systemPrompt         = '';
        $conversationMessages = [];

        foreach ($messages as $msg) {
            if ($msg['role'] === 'system') {
                $systemPrompt = $msg['content'];
            } else {
                $conversationMessages[] = $msg;
            }
        }

        $payload = [
            'model'      => $model,
            'max_tokens' => (int) $maxTokens,
            'messages'   => $conversationMessages,
        ];

        if ($systemPrompt) {
            $payload['system'] = $systemPrompt;
        }

        $response = Http::withHeaders([
            'x-api-key'         => $apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->timeout(60)->post('https://api.anthropic.com/v1/messages', $payload);

        if ($response->failed()) {
            throw new \RuntimeException('Anthropic API error: ' . $response->body());
        }

        $data = $response->json();

        return [
            'content'  => $data['content'][0]['text'] ?? '',
            'usage'    => $data['usage'] ?? [],
            'model'    => $model,
            'provider' => 'anthropic',
        ];
    }

    public function getName(): string
    {
        return 'anthropic';
    }

    public function getSupportedModels(): array
    {
        return ['claude-3-5-sonnet-20241022', 'claude-3-opus-20240229', 'claude-3-haiku-20240307'];
    }
}
