<?php

namespace Modules\TitanAgents\Services\Generators;

use Illuminate\Support\Facades\Http;

class OpenAIGenerator implements GeneratorInterface
{
    public function generate(array $messages, array $options = []): array
    {
        $apiKey    = config('services.openai.api_key') ?: config('aicore.openai_key', '');
        $model     = $options['model'] ?? 'gpt-4o-mini';
        $temperature = $options['temperature'] ?? 0.7;
        $maxTokens = $options['max_tokens'] ?? 2000;

        $response = Http::withToken($apiKey)
            ->timeout(60)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model'       => $model,
                'messages'    => $messages,
                'temperature' => (float) $temperature,
                'max_tokens'  => (int) $maxTokens,
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('OpenAI API error: ' . $response->body());
        }

        $data = $response->json();

        return [
            'content'  => $data['choices'][0]['message']['content'] ?? '',
            'usage'    => $data['usage'] ?? [],
            'model'    => $model,
            'provider' => 'openai',
        ];
    }

    public function getName(): string
    {
        return 'openai';
    }

    public function getSupportedModels(): array
    {
        return ['gpt-4o', 'gpt-4o-mini', 'gpt-4-turbo', 'gpt-3.5-turbo'];
    }

    public function generateEmbedding(string $text, string $model = 'text-embedding-3-small'): array
    {
        $apiKey = config('services.openai.api_key') ?: config('aicore.openai_key', '');

        $response = Http::withToken($apiKey)
            ->post('https://api.openai.com/v1/embeddings', [
                'input' => $text,
                'model' => $model,
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('OpenAI embedding error: ' . $response->body());
        }

        return $response->json('data.0.embedding', []);
    }
}
