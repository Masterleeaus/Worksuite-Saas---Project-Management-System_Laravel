<?php

namespace Modules\TitanAgents\Services\Chatbot;

use Modules\TitanAgents\Models\Chatbot;
use Modules\TitanAgents\Models\ChatbotKnowledgeBaseArticle;

class TrainingService
{
    public function __construct(protected EmbeddingService $embeddingService) {}

    public function trainArticle(ChatbotKnowledgeBaseArticle $article): void
    {
        $article->update(['embedding_status' => 'processing']);

        try {
            $this->embeddingService->generateForArticle($article);
        } catch (\Throwable $e) {
            $article->update(['embedding_status' => 'failed']);
            throw $e;
        }
    }

    public function trainAll(Chatbot $chatbot): array
    {
        $articles = $chatbot->articles()
            ->where('status', 'active')
            ->where('embedding_status', 'pending')
            ->get();

        $results = ['success' => 0, 'failed' => 0];

        foreach ($articles as $article) {
            try {
                $this->trainArticle($article);
                $results['success']++;
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Article training failed', [
                    'article_id' => $article->id,
                    'error'      => $e->getMessage(),
                ]);
                $results['failed']++;
            }
        }

        return $results;
    }

    public function retrainAll(Chatbot $chatbot): array
    {
        $chatbot->articles()->update(['embedding_status' => 'pending']);

        return $this->trainAll($chatbot);
    }
}
