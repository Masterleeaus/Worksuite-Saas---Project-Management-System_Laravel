<?php

namespace Modules\TitanAgents\Services\Generators;

use Illuminate\Support\Facades\Http;

class GeminiGenerator implements GeneratorInterface
{
    public function generate(array $messages, array $options = []): array
    {
        $apiKey = config('services.gemini.api_key', '');
        $model  = $options['model'] ?? 'gemini-1.5-flash';

        // Convert OpenAI-style messages to Gemini format
        $contents     = [];
        $systemPrompt = '';

        foreach ($messages as $msg) {
            if ($msg['role'] === 'system') {
                $systemPrompt = $msg['content'];
                continue;
            }
            $role       = $msg['role'] === 'assistant' ? 'model' : 'user';
            $contents[] = ['role' => $role, 'parts' => [['text' => $msg['content']]]];
        }

        $payload = ['contents' => $contents];

        if ($systemPrompt) {
            $payload['systemInstruction'] = ['parts' => [['text' => $systemPrompt]]];
        }

        $url      = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
        $response = Http::timeout(60)->post($url, $payload);

        if ($response->failed()) {
            throw new \RuntimeException('Gemini API error: ' . $response->body());
        }

        $data    = $response->json();
        $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

        return [
            'content'  => $content,
            'usage'    => $data['usageMetadata'] ?? [],
            'model'    => $model,
            'provider' => 'gemini',
        ];
    }

    public function getName(): string
    {
        return 'gemini';
    }

    public function getSupportedModels(): array
    {
        return ['gemini-1.5-pro', 'gemini-1.5-flash', 'gemini-1.0-pro'];
    }
}
