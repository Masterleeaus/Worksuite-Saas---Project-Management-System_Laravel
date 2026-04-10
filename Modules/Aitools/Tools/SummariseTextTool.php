<?php

namespace Modules\Aitools\Tools;

use Modules\Aitools\Services\AiClientFactory;

/**
 * Tool: summarise_text
 * Params:
 *  - text (string, required)
 *  - max_words (int, optional)
 */
class SummariseTextTool
{
    public function __construct(private AiClientFactory $clients) {}

    public function handle(array $params): array
    {
        $text = trim((string)($params['text'] ?? ''));
        if ($text === '') {
            return ['ok' => false, 'error' => 'Missing text'];
        }

        $maxWords = (int)($params['max_words'] ?? 140);
        $maxWords = max(30, min(400, $maxWords));

        $client = $this->clients->makeDefaultChatClient(optional(company())->id);
        if (!$client) {
            return ['ok' => false, 'error' => 'No chat provider/model configured'];
        }

        $messages = [
            ['role' => 'system', 'content' => 'You are a concise assistant. Summarise the user text clearly.'],
            ['role' => 'user', 'content' => "Summarise this text in <= {$maxWords} words. Use plain language.\n\nTEXT:\n".$text],
        ];

        $resp = $client->chat($messages, [
            'temperature' => 0.2,
        ]);

        if (!($resp['ok'] ?? false)) {
            return ['ok' => false, 'error' => $resp['reason'] ?? 'Chat failed'];
        }

        return ['ok' => true, 'summary' => (string)($resp['content'] ?? '')];
    }
}
