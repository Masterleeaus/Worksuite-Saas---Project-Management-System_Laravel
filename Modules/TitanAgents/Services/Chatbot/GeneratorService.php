<?php

namespace Modules\TitanAgents\Services\Chatbot;

use Modules\TitanAgents\Services\Generators\AnthropicGenerator;
use Modules\TitanAgents\Services\Generators\GeminiGenerator;
use Modules\TitanAgents\Services\Generators\GeneratorInterface;
use Modules\TitanAgents\Services\Generators\OpenAIGenerator;

class GeneratorService
{
    /** @var GeneratorInterface[] */
    protected array $generators = [];

    /**
     * Generators are resolved from the container to allow
     * easy overriding in tests or application service providers.
     */
    public function __construct(
        OpenAIGenerator    $openai,
        AnthropicGenerator $anthropic,
        GeminiGenerator    $gemini,
    ) {
        $this->generators = [
            'openai'    => $openai,
            'anthropic' => $anthropic,
            'gemini'    => $gemini,
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
