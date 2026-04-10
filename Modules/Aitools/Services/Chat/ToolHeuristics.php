<?php

namespace Modules\Aitools\Services\Chat;

/**
 * Pass 2: pragmatic tool routing.
 *
 * This is intentionally simple for MVP. In later passes we can
 * upgrade to model-driven tool selection.
 */
class ToolHeuristics
{
    /**
     * Parse explicit tool calls:
     *   /tool tool_name {"key":"value"}
     *
     * @return array{name:string,args:array}|null
     */
    public function parseExplicitToolCall(string $text): ?array
    {
        $text = trim($text);
        if (!str_starts_with($text, '/tool')) {
            return null;
        }

        // /tool <name> <json>
        $parts = preg_split('/\s+/', $text, 3);
        if (!$parts || count($parts) < 2) {
            return null;
        }

        $name = trim((string)($parts[1] ?? ''));
        if ($name === '') {
            return null;
        }

        $args = [];
        $json = $parts[2] ?? '';
        $json = trim((string)$json);
        if ($json !== '') {
            try {
                $decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
                if (is_array($decoded)) {
                    $args = $decoded;
                }
            } catch (\Throwable $e) {
                // ignore parse errors; run with empty args
            }
        }

        return ['name' => $name, 'args' => $args];
    }

    /**
     * Very small heuristic routing for MVP.
     * @return array{name:string,args:array}|null
     */
    public function pickToolForMessage(string $text): ?array
    {
        $t = mb_strtolower($text);

        if (str_contains($t, 'unpaid') || str_contains($t, 'overdue invoice') || str_contains($t, 'outstanding invoice')) {
            return ['name' => 'get_unpaid_invoices', 'args' => []];
        }

        if (str_contains($t, 'today') && (str_contains($t, 'summary') || str_contains($t, 'what') || str_contains($t, 'jobs') || str_contains($t, 'tasks'))) {
            return ['name' => 'get_today_summary', 'args' => []];
        }

        if (str_contains($t, 'client') && (str_contains($t, 'find') || str_contains($t, 'search') || str_contains($t, 'lookup'))) {
            // try to extract the query after 'client'
            $q = trim(preg_replace('/.*client(s)?\s*/i', '', $text));
            if ($q !== '') {
                return ['name' => 'search_clients', 'args' => ['query' => $q]];
            }
        }

        if (str_contains($t, 'job') && (str_contains($t, 'find') || str_contains($t, 'search') || str_contains($t, 'lookup'))) {
            $q = trim(preg_replace('/.*job(s)?\s*/i', '', $text));
            if ($q !== '') {
                return ['name' => 'search_jobs', 'args' => ['query' => $q]];
            }
        }

        // Create task: "create task ..." or "remind me to ..."
        if (str_starts_with($t, 'create task') || str_starts_with($t, 'add task') || str_starts_with($t, 'remind me')) {
            $title = trim(preg_replace('/^(create|add)\s+task\s*/i', '', $text));
            if (str_starts_with($t, 'remind me')) {
                $title = trim(preg_replace('/^remind\s+me\s*/i', '', $text));
            }
            if ($title !== '') {
                return ['name' => 'create_task', 'args' => ['title' => $title, 'dry_run' => true]];
            }
        }

        return null;
    }
}
