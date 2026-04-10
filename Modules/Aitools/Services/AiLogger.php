<?php

namespace Modules\Aitools\Services;

use Illuminate\Support\Facades\Schema;
use Modules\Aitools\Entities\AiUsageLog;
use Modules\Aitools\Entities\AiRequestLog;

class AiLogger
{
    public static function hash(string $operation, ?string $prompt = null): string
    {
        return hash('sha256', $operation . '|' . ($prompt ?? '') . '|' . date('Y-m-d'));
    }

    public static function logUsage(array $data): void
    {
        if (!Schema::hasTable('ai_usage_logs')) {
            return;
        }

        try {
            AiUsageLog::create($data);
        } catch (\Throwable $e) {
            logger()->warning('Aitools: failed to write ai_usage_logs', ['error' => $e->getMessage()]);
        }
    }

    public static function logRequest(array $data): void
    {
        if (!Schema::hasTable('ai_request_logs')) {
            return;
        }

        try {
            AiRequestLog::create($data);
        } catch (\Throwable $e) {
            logger()->warning('Aitools: failed to write ai_request_logs', ['error' => $e->getMessage()]);
        }
    }
}
