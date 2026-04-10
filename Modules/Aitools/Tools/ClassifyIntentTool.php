<?php

namespace Modules\Aitools\Tools;

use Modules\Aitools\Services\AiClientFactory;

/**
 * Tool: classify_intent
 * Params:
 *  - text (string, required)
 *  - labels (array|string, optional) e.g. ["sales","support","billing"]
 */
class ClassifyIntentTool
{
    public function __construct(private AiClientFactory $clients) {}

    public function handle(array $params): array
    {
        $text = trim((string)($params['text'] ?? ''));
        if ($text === '') {
            return ['ok' => false, 'error' => 'Missing text'];
        }

        $labels = $params['labels'] ?? ['general', 'sales', 'support', 'billing', 'ops'];
        if (is_string($labels)) {
            $labels = array_values(array_filter(array_map('trim', explode(',', $labels))));
        }
        if (!is_array($labels) || count($labels) < 2) {
            $labels = ['general', 'sales', 'support', 'billing', 'ops'];
        }

        $client = $this->clients->makeDefaultChatClient(optional(company())->id);
        if (!$client) {
            return ['ok' => false, 'error' => 'No chat provider/model configured'];
        }

        $labelsJson = json_encode(array_values($labels));

        $messages = [
            ['role' => 'system', 'content' => 'Classify the user text into exactly one label from the allowed list. Respond with JSON only.'],
            ['role' => 'user', 'content' => "Allowed labels (JSON array): {$labelsJson}\n\nText:\n{$text}\n\nReturn JSON: {\"label\":\"...\",\"confidence\":0-1,\"reason\":\"short\"}"],
        ];

        $resp = $client->chat($messages, ['temperature' => 0.0]);
        if (!($resp['ok'] ?? false)) {
            return ['ok' => false, 'error' => $resp['reason'] ?? 'Chat failed'];
        }

        $raw = trim((string)($resp['content'] ?? ''));
        $json = json_decode($raw, true);
        if (!is_array($json)) {
            // Best-effort fallback
            return ['ok' => true, 'label' => 'general', 'confidence' => 0.2, 'reason' => 'Non-JSON model output', 'raw' => $raw];
        }

        return [
            'ok' => true,
            'label' => (string)($json['label'] ?? 'general'),
            'confidence' => (float)($json['confidence'] ?? 0.5),
            'reason' => (string)($json['reason'] ?? ''),
        ];
    }
}
