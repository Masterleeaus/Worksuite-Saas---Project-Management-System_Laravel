<?php

namespace Modules\TitanAgents\Services\Generators;

interface EmbeddingCapableInterface
{
    /**
     * Generate a vector embedding for the given text.
     *
     * @return float[]
     */
    public function generateEmbedding(string $text, string $model = ''): array;
}
