<?php

declare(strict_types=1);

namespace Modules\TitanZero\Services;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;
use stdClass;
use Throwable;

/**
 * FileSearchService — TitanZero adapter for OpenAI FileSearch / vector store API.
 *
 * Mirrors the contract used by App\Services\Ai\OpenAI\FileSearchService in the
 * upstream MagicAI package so that AIFileChatService stays portable.
 *
 * Supported file types (per OpenAI): PDF, DOCX, TXT (and others accepted by the API).
 */
class FileSearchService
{
    private Client $http;
    private string $apiKey;
    private string $base;

    public function __construct()
    {
        $this->apiKey = config('ai.providers.openai.api_key') ?? env('OPENAI_API_KEY', '');
        $this->base   = rtrim(config('ai.providers.openai.base', 'https://api.openai.com'), '/');

        $this->http = new Client([
            'base_uri'    => $this->base,
            'timeout'     => 120.0,
            'http_errors' => false,
            'headers'     => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'OpenAI-Beta'   => 'assistants=v2',
            ],
        ]);
    }

    /**
     * Upload a file to the OpenAI Files API (purpose: assistants).
     *
     * @param  string  $filePath  Absolute path to the file on disk.
     * @return string             Uploaded file ID (e.g. "file-abc123").
     *
     * @throws \RuntimeException  On API error or missing API key.
     */
    public function uploadFile(string $filePath): string
    {
        if (! $this->apiKey) {
            throw new \RuntimeException('[TitanZero] Missing OPENAI_API_KEY — cannot upload file.');
        }

        $response = $this->http->post('/v1/files', [
            RequestOptions::MULTIPART => [
                [
                    'name'     => 'purpose',
                    'contents' => 'assistants',
                ],
                [
                    'name'     => 'file',
                    'contents' => fopen($filePath, 'rb'),
                    'filename' => basename($filePath),
                ],
            ],
        ]);

        $body = $this->decodeResponse($response, 'file upload');

        if (! isset($body['id'])) {
            throw new \RuntimeException('[TitanZero] File upload failed: ' . json_encode($body));
        }

        return (string) $body['id'];
    }

    /**
     * Create a vector store and attach the given file ID to it.
     *
     * @param  string  $name    Human-readable name for the vector store.
     * @param  string  $fileId  File ID returned by uploadFile().
     * @return stdClass         Object with `->id` set to the vector store ID.
     *
     * @throws \RuntimeException  On API error.
     */
    public function createVectorStore(string $name, string $fileId): stdClass
    {
        if (! $this->apiKey) {
            throw new \RuntimeException('[TitanZero] Missing OPENAI_API_KEY — cannot create vector store.');
        }

        // 1. Create the vector store
        $createResponse = $this->http->post('/v1/vector_stores', [
            RequestOptions::JSON    => ['name' => $name],
        ]);

        $store = $this->decodeResponse($createResponse, 'vector store creation');

        if (! isset($store['id'])) {
            throw new \RuntimeException('[TitanZero] Vector store creation failed: ' . json_encode($store));
        }

        $vectorStoreId = (string) $store['id'];

        // 2. Attach the file to the vector store
        $attachResponse = $this->http->post("/v1/vector_stores/{$vectorStoreId}/files", [
            RequestOptions::JSON => ['file_id' => $fileId],
        ]);

        $this->decodeResponse($attachResponse, 'vector store file attach');

        $result     = new stdClass();
        $result->id = $vectorStoreId;

        return $result;
    }

    // ──────────────────────────────────────────────────────────────────────────

    private function decodeResponse(\GuzzleHttp\Psr7\Response|\Psr\Http\Message\ResponseInterface $response, string $context): array
    {
        $raw  = (string) $response->getBody();
        $data = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("[TitanZero] Invalid JSON from OpenAI ({$context}): {$raw}");
        }

        if (isset($data['error'])) {
            $msg = $data['error']['message'] ?? json_encode($data['error']);
            throw new \RuntimeException("[TitanZero] OpenAI error ({$context}): {$msg}");
        }

        return $data;
    }
}
