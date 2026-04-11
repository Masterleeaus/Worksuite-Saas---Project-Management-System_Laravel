<?php

namespace Modules\TitanAgents\Services\Chatbot;

use Modules\TitanAgents\Models\ChatbotEmbedding;
use Modules\TitanAgents\Models\ChatbotKnowledgeBaseArticle;
use Modules\TitanAgents\Services\Generators\EmbeddingCapableInterface;

class EmbeddingService
{
    public function __construct(protected EmbeddingCapableInterface $embeddingProvider) {}

    public function generateForArticle(ChatbotKnowledgeBaseArticle $article): ChatbotEmbedding
    {
        $text     = $article->title . "\n\n" . $article->content;
        $vector   = $this->embeddingProvider->generateEmbedding($text);
        $checksum = md5($text);

        $embedding = ChatbotEmbedding::updateOrCreate(
            ['chatbot_id' => $article->chatbot_id, 'source_type' => 'article', 'source_id' => $article->id],
            [
                'embedding_model' => 'text-embedding-3-small',
                'vector_data'     => json_encode($vector),
                'checksum'        => $checksum,
            ]
        );

        $article->update(['embedding_status' => 'done']);

        return $embedding;
    }

    public function cosineSimilarity(array $a, array $b): float
    {
        $dotProduct = 0;
        $normA      = 0;
        $normB      = 0;

        $length = count($a);
        for ($i = 0; $i < $length; $i++) {
            $dotProduct += $a[$i] * ($b[$i] ?? 0);
            $normA      += $a[$i] ** 2;
            $normB      += ($b[$i] ?? 0) ** 2;
        }

        $denom = sqrt($normA) * sqrt($normB);

        return $denom > 0 ? $dotProduct / $denom : 0.0;
    }

    public function findSimilarArticles(int $chatbotId, string $query, int $topK = 3): array
    {
        $queryVector = $this->embeddingProvider->generateEmbedding($query);

        $embeddings = ChatbotEmbedding::where('chatbot_id', $chatbotId)
            ->where('source_type', 'article')
            ->whereNotNull('vector_data')
            ->get();

        $scores = [];

        foreach ($embeddings as $emb) {
            $vector = json_decode($emb->vector_data, true);

            if ($vector) {
                $scores[] = [
                    'source_id' => $emb->source_id,
                    'score'     => $this->cosineSimilarity($queryVector, $vector),
                ];
            }
        }

        usort($scores, fn ($a, $b) => $b['score'] <=> $a['score']);

        return array_slice($scores, 0, $topK);
    }
}
