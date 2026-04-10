<?php
namespace Modules\Aitools\Services;

use Modules\Aitools\AI\ClientInterface;

class EmbeddingService
{
    public function __construct(private AiClientFactory $factory) {}

    /**
     * Embed a single text string.
     * Returns ['vector'=>array] on success, or ['error'=>string].
     */
    public function embedText(string $text, array $opts = []): array
    {
        /** @var ClientInterface $client */
        $client = $this->factory->getActiveClient();
        if (!$client) {
            return ['error' => 'No AI client available'];
        }

        $res = $client->embed([$text], $opts);
        if (!$res['ok']) {
            return ['error' => $res['reason'] ?? 'Embedding failed'];
        }

        return ['vector' => $res['vector'] ?? null, 'raw' => $res];
    }
}