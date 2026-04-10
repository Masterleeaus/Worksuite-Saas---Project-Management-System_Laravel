<?php

namespace Modules\Aitools\Services;

use Illuminate\Support\Facades\DB;
use Modules\Aitools\Entities\AiKbChunk;
use Modules\Aitools\Entities\AiKbDocument;
use Modules\Aitools\Entities\AiModel;

class KbIngestionService
{
    public function __construct(
        private TextChunker $chunker,
        private EmbeddingService $embeddings,
        private AiClientFactory $clientFactory,
    ) {}

    /**
     * Ingest plain text into KB tables.
     *
     * @param array{company_id:int|null,user_id:int|null,source_id:int|null,title:string,doc_type:string,content:string,embed_now:bool} $payload
     */
    public function ingestText(array $payload): array
    {
        $text = trim((string)($payload['content'] ?? ''));
        if ($text === '') {
            return ['ok' => false, 'reason' => 'Empty content'];
        }

        $chunks = $this->chunker->chunk($text, 1200);
        if (!count($chunks)) {
            return ['ok' => false, 'reason' => 'No chunks created'];
        }

        return DB::transaction(function () use ($payload, $chunks) {
            $doc = AiKbDocument::create([
                'company_id' => $payload['company_id'],
                'user_id' => $payload['user_id'],
                'source_id' => $payload['source_id'],
                'title' => $payload['title'],
                'doc_type' => $payload['doc_type'],
                'content' => mb_substr($payload['content'] ?? '', 0, 65000),
                'status' => 'stored',
                'meta' => [
                    'chars' => mb_strlen($payload['content'] ?? ''),
                    'chunks' => count($chunks),
                ],
            ]);

            foreach ($chunks as $idx => $chunkText) {
                AiKbChunk::create([
                    'company_id' => $payload['company_id'],
                    'user_id' => $payload['user_id'],
                    'document_id' => $doc->id,
                    'chunk_index' => $idx,
                    'chunk_text' => $chunkText,
                    'embedding' => null,
                    'embedding_model' => null,
                ]);
            }

            if (!($payload['embed_now'] ?? true)) {
                $doc->status = 'chunked';
                $doc->save();
                return ['ok' => true, 'document_id' => $doc->id, 'chunks' => count($chunks)];
            }

            // Embed synchronously for now (Pass 6). If provider/model missing, keep chunks without vectors.
            $embeddingModel = AiModel::query()
                ->where('model_type', 'embedding')
                ->where('is_active', 1)
                ->where(function ($q) use ($payload) {
                    $q->whereNull('company_id');
                    if (!empty($payload['company_id'])) {
                        $q->orWhere('company_id', $payload['company_id']);
                    }
                })
                ->orderByDesc('is_default')
                ->orderByDesc('id')
                ->first();

            if (!$embeddingModel) {
                // No embedding model configured; do not fail ingestion.
                $doc->status = 'chunked';
                $doc->save();
                return ['ok' => true, 'document_id' => $doc->id, 'chunks' => count($chunks), 'reason' => 'No embedding model configured'];
            }

            $client = $this->clientFactory->makeForModel($embeddingModel);
            $this->clientFactory->setActiveClient($client);

            $docChunks = AiKbChunk::where('document_id', $doc->id)->orderBy('chunk_index')->get();
            foreach ($docChunks as $c) {
                $res = $this->embeddings->embedText($c->chunk_text, ['model' => $embeddingModel->name]);
                if (isset($res['error']) || empty($res['vector'])) {
                    // Skip embedding failures but keep going.
                    continue;
                }
                $c->embedding = $res['vector'];
                $c->embedding_model = $embeddingModel->name;
                $c->save();
            }

            $doc->status = 'embedded';
            $doc->save();

            return ['ok' => true, 'document_id' => $doc->id, 'chunks' => count($chunks)];
        });
    }
}
