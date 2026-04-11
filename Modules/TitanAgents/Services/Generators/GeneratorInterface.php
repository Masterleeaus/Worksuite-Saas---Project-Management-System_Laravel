<?php

namespace Modules\TitanAgents\Services\Generators;

interface GeneratorInterface
{
    public function generate(array $messages, array $options = []): array;

    public function getName(): string;

    public function getSupportedModels(): array;
}
