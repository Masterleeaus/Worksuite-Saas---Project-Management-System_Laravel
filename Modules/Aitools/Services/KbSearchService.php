<?php

namespace Modules\Aitools\Services;

use Modules\Aitools\Entities\AiKbChunk;
use Modules\Aitools\Entities\AiModel;

class KbSearchService
{
    public function __construct(
        private EmbeddingService $embeddings,
        private AiClientFactory $clientFactory,
    ) {}

    public function search(string $query, int $limit = 5, array $scope = []): array
    {
        $query = trim($query);
        if ($query === '') {
            return ['ok' => false, 'reason' => 'Empty query'];
        }

        $companyId = $scope['company_id'] ?? null;

        $embeddingModel = AiModel::query()
            ->where('model_type', 'embedding')
            ->where('is_active', 1)
            ->where(function ($q) use ($companyId) {
                $q->whereNull('company_id');
                if ($companyId) $q->orWhere('company_id', $companyId);
            })
            ->orderByDesc('is_default')
            ->orderByDesc('id')
            ->first();

        if (!$embeddingModel) {
            return ['ok' => false, 'reason' => 'No embedding model configured'];
        }

        $client = $this->clientFactory->makeForModel($embeddingModel);
        $this->clientFactory->setActiveClient($client);

        $qVec = $this->embeddings->embedText($query, ['model' => $embeddingModel->name]);
        if (isset($qVec['error']) || empty($qVec['vector']) || !is_array($qVec['vector'])) {
            return ['ok' => false, 'reason' => $qVec['error'] ?? 'Embedding failed'];
        }

        $chunks = AiKbChunk::query()
            ->whereNotNull('embedding')
            ->orderByDesc('id')
            ->limit(1000) // safety cap for now
            ->get(['document_id', 'chunk_index', 'chunk_text', 'embedding']);

        $scored = [];
        foreach ($chunks as $c) {
            $vec = $c->embedding;
            if (!is_array($vec) || !count($vec)) continue;
            $score = $this->cosineSimilarity($qVec['vector'], $vec);
            $scored[] = [
                'document_id' => $c->document_id,
                'chunk_index' => $c->chunk_index,
                'score' => $score,
                'text' => $c->chunk_text,
            ];
        }

        usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);
        $scored = array_slice($scored, 0, max(1, min(20, $limit)));

        return ['ok' => true, 'results' => $scored];
    }

    private function cosineSimilarity(array $a, array $b): float
    {
        $n = min(count($a), count($b));
        if ($n === 0) return 0.0;
        $dot = 0.0;
        $na = 0.0;
        $nb = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $x = (float)$a[$i];
            $y = (float)$b[$i];
            $dot += $x * $y;
            $na += $x * $x;
            $nb += $y * $y;
        }
        if ($na <= 0.0 || $nb <= 0.0) return 0.0;
        return $dot / (sqrt($na) * sqrt($nb));
    }
}
