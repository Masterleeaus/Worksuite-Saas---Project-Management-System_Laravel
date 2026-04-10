<?php

namespace Modules\Aitools\Tools;

use Modules\Aitools\Services\AiClientFactory;

/**
 * Tool: rewrite_with_tone
 * Params:
 *  - text (string, required)
 *  - tone (string, optional) e.g. "friendly", "formal", "short", "salesy"
 *  - audience (string, optional)
 */
class RewriteWithToneTool
{
    public function __construct(private AiClientFactory $clients) {}

    public function handle(array $params): array
    {
        $text = trim((string)($params['text'] ?? ''));
        if ($text === '') {
            return ['ok' => false, 'error' => 'Missing text'];
        }

        $tone = trim((string)($params['tone'] ?? 'friendly and clear'));
        $audience = trim((string)($params['audience'] ?? 'a customer'));

        $client = $this->clients->makeDefaultChatClient(optional(company())->id);
        if (!$client) {
            return ['ok' => false, 'error' => 'No chat provider/model configured'];
        }

        $messages = [
            ['role' => 'system', 'content' => 'You rewrite text while preserving meaning and key details.'],
            ['role' => 'user', 'content' => "Rewrite for {$audience}. Tone: {$tone}. Keep it accurate and practical.\n\nTEXT:\n{$text}"],
        ];

        $resp = $client->chat($messages, ['temperature' => 0.4]);
        if (!($resp['ok'] ?? false)) {
            return ['ok' => false, 'error' => $resp['reason'] ?? 'Chat failed'];
        }

        return ['ok' => true, 'text' => (string)($resp['content'] ?? '')];
    }
}
