<?php

namespace Modules\Aitools\Tools;

use Modules\Aitools\Services\KbSearchService;

/**
 * Tool: kb_search
 * Params:
 *  - query (string, required)
 *  - limit (int, optional, default 5)
 */
class KbSearchTool
{
    public function __construct(private KbSearchService $kb) {}

    public function handle(array $params): array
    {
        $query = trim((string)($params['query'] ?? ''));
        $limit = (int)($params['limit'] ?? 5);
        $limit = max(1, min(20, $limit));

        $res = $this->kb->search($query, $limit, [
            'company_id' => optional(company())->id,
        ]);

        if (!($res['ok'] ?? false)) {
            return ['ok' => false, 'error' => $res['reason'] ?? 'KB search failed'];
        }

        return [
            'ok' => true,
            'results' => $res['results'] ?? [],
        ];
    }
}
