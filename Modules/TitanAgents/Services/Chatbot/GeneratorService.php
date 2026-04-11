<?php

namespace Modules\TitanAgents\Services\Chatbot;

use Modules\TitanAgents\Services\Generators\AnthropicGenerator;
use Modules\TitanAgents\Services\Generators\GeminiGenerator;
use Modules\TitanAgents\Services\Generators\GeneratorInterface;
use Modules\TitanAgents\Services\Generators\OpenAIGenerator;

class GeneratorService
{
    protected array $generators = [];

    public function __construct()
    {
        $this->generators = [
            'openai'    => new OpenAIGenerator(),
            'anthropic' => new AnthropicGenerator(),
            'gemini'    => new GeminiGenerator(),
        ];
    }

    public function getGenerator(string $provider): GeneratorInterface
    {
        if (! isset($this->generators[$provider])) {
            throw new \InvalidArgumentException("Unknown AI provider: {$provider}");
        }

        return $this->generators[$provider];
    }

    public function generate(string $provider, array $messages, array $options = []): array
    {
        return $this->getGenerator($provider)->generate($messages, $options);
    }

    public function getSupportedProviders(): array
    {
        return array_keys($this->generators);
    }
}
