<?php

namespace Modules\Aitools\Tools;

use Modules\Aitools\Services\AiClientFactory;

/**
 * Tool: extract_json
 * Params:
 *  - text (string, required)
 *  - schema_hint (string, optional) e.g. "{name:string, phone:string, address:string}"
 */
class ExtractJsonTool
{
    public function __construct(private AiClientFactory $clients) {}

    public function handle(array $params): array
    {
        $text = trim((string)($params['text'] ?? ''));
        if ($text === '') {
            return ['ok' => false, 'error' => 'Missing text'];
        }

        $hint = trim((string)($params['schema_hint'] ?? ''));

        $client = $this->clients->makeDefaultChatClient(optional(company())->id);
        if (!$client) {
            return ['ok' => false, 'error' => 'No chat provider/model configured'];
        }

        $sys = 'Extract structured data from the user text. Output JSON only. Do not include markdown.';
        $user = "Text:\n{$text}\n\n";
        if ($hint !== '') {
            $user .= "Schema hint (informal): {$hint}\n";
        } else {
            $user .= "Schema hint: infer sensible keys.\n";
        }
        $user .= "\nReturn a single JSON object.";

        $resp = $client->chat([
            ['role' => 'system', 'content' => $sys],
            ['role' => 'user', 'content' => $user],
        ], ['temperature' => 0.0]);

        if (!($resp['ok'] ?? false)) {
            return ['ok' => false, 'error' => $resp['reason'] ?? 'Chat failed'];
        }

        $raw = trim((string)($resp['content'] ?? ''));

        // Try strict JSON first
        $json = json_decode($raw, true);
        if (is_array($json)) {
            return ['ok' => true, 'data' => $json];
        }

        // Attempt to salvage JSON substring
        $start = strpos($raw, '{');
        $end = strrpos($raw, '}');
        if ($start !== false && $end !== false && $end > $start) {
            $slice = substr($raw, $start, $end - $start + 1);
            $json2 = json_decode($slice, true);
            if (is_array($json2)) {
                return ['ok' => true, 'data' => $json2, 'raw' => $raw];
            }
        }

        return ['ok' => false, 'error' => 'Model did not return valid JSON', 'raw' => $raw];
    }
}
