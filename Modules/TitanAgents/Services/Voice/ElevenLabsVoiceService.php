<?php

namespace Modules\TitanAgents\Services\Voice;

use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * ElevenLabs Conversational AI service for Voice Chatbot platform.
 *
 * ElevenLabs is a hosted voice-synthesis and conversational-AI platform.
 * This service wraps the ElevenLabs REST API. It is intentionally kept
 * in TitanAgents (not routed through TitanZero) because ElevenLabs is a
 * speech/audio platform, not a text-generation AI provider — the
 * INTENT_LOCK restriction covers OpenAI/Anthropic-style text-generation.
 */
class ElevenLabsVoiceService
{
    public const DEFAULT_ELEVENLABS_VOICE_ID    = 'EXAVITQu4vr4xnSDxMaL';
    public const DEFAULT_ELEVENLABS_MODEL        = 'eleven_turbo_v2';
    public const DEFAULT_ELEVENLABS_MODEL_FOR_ENGLISH = 'eleven_turbo_v2_5';

    private const BASE_URL = 'https://api.elevenlabs.io/v1';

    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('titanagents.elevenlabs_api_key', '');
    }

    // ──────────────────────────────────────────────────────────────────
    // Agent management
    // ──────────────────────────────────────────────────────────────────

    public function createAgent(array $conversation_config, string $name): JsonResponse
    {
        try {
            $response = $this->http()->post(self::BASE_URL . '/convai/agents/create', [
                'name'                => $name,
                'conversation_config' => $conversation_config,
            ]);

            return $this->parseResponse($response, 'agent created');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function updateAgent(string $agent_id, array $conversation_config, ?string $name = null): JsonResponse
    {
        try {
            $payload = ['conversation_config' => $conversation_config];
            if ($name !== null) {
                $payload['name'] = $name;
            }

            $response = $this->http()->patch(self::BASE_URL . '/convai/agents/' . $agent_id, $payload);

            return $this->parseResponse($response, 'agent updated');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function deleteAgent(string $agent_id): JsonResponse
    {
        try {
            $response = $this->http()->delete(self::BASE_URL . '/convai/agents/' . $agent_id);

            return $this->parseResponse($response, 'agent deleted');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    // ──────────────────────────────────────────────────────────────────
    // Voices
    // ──────────────────────────────────────────────────────────────────

    public function getListOfVoices(int $page_size = 100): JsonResponse
    {
        try {
            $response = $this->http()->get(self::BASE_URL . '/voices', [
                'page_size' => $page_size,
            ]);

            return $this->parseResponse($response, 'voices fetched');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    // ──────────────────────────────────────────────────────────────────
    // Knowledge base
    // ──────────────────────────────────────────────────────────────────

    public function createKnowledgebaseDocFromText(string $text, ?string $name = null): JsonResponse
    {
        try {
            $response = $this->http()->post(self::BASE_URL . '/convai/knowledge-base/text', [
                'text' => $text,
                'name' => $name,
            ]);

            return $this->parseResponse($response, 'knowledge base doc created from text');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function createKnowledgebaseDocFromUrl(string $url, ?string $name = null): JsonResponse
    {
        try {
            $response = $this->http()->post(self::BASE_URL . '/convai/knowledge-base/url', [
                'url'  => $url,
                'name' => $name,
            ]);

            return $this->parseResponse($response, 'knowledge base doc created from URL');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function createKnowledgebaseDocFromFile(UploadedFile $file, ?string $name = null): JsonResponse
    {
        try {
            $response = $this->http()->attach(
                'file',
                fopen($file->getRealPath(), 'r'),
                $file->getClientOriginalName()
            )->post(self::BASE_URL . '/convai/knowledge-base/file', array_filter(['name' => $name]));

            return $this->parseResponse($response, 'knowledge base doc created from file');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function deleteKnowledgebaseDocument(string $doc_id): JsonResponse
    {
        try {
            $response = $this->http()->delete(self::BASE_URL . '/convai/knowledge-base/' . $doc_id);

            return $this->parseResponse($response, 'knowledge base doc deleted');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    // ──────────────────────────────────────────────────────────────────
    // Conversation history
    // ──────────────────────────────────────────────────────────────────

    public function getConversationDetail(string $conversation_id): JsonResponse
    {
        try {
            $response = $this->http()->get(self::BASE_URL . '/convai/conversations/' . $conversation_id);

            return $this->parseResponse($response, 'conversation fetched');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    // ──────────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────────

    protected function http()
    {
        return Http::withHeaders([
            'xi-api-key'   => $this->apiKey,
            'Content-Type' => 'application/json',
        ]);
    }

    protected function parseResponse(Response $response, string $successMessage = 'success'): JsonResponse
    {
        $body = $response->json();

        if ($response->successful()) {
            return response()->json([
                'status'  => 'success',
                'message' => $successMessage,
                'resData' => (object) $body,
            ]);
        }

        Log::warning('[ElevenLabsVoiceService] API error', [
            'status' => $response->status(),
            'body'   => $body,
        ]);

        return response()->json([
            'status'  => 'error',
            'message' => $body['detail'] ?? $body['message'] ?? 'ElevenLabs API error',
        ], $response->status());
    }

    protected function errorResponse(string $message): JsonResponse
    {
        Log::error('[ElevenLabsVoiceService] ' . $message);

        return response()->json([
            'status'  => 'error',
            'message' => $message,
        ], 500);
    }
}
