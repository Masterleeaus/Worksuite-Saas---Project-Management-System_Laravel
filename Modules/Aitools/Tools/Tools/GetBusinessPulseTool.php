<?php

namespace Modules\Aitools\Tools\Tools;

use Modules\Aitools\Tools\Contracts\AiToolInterface;
use Modules\Aitools\Tools\DTO\AitoolsContext;
use Modules\Aitools\Services\Insights\PulseService;

class GetBusinessPulseTool implements AiToolInterface
{
    public static function name(): string
    {
        return 'get_business_pulse';
    }

    public static function description(): string
    {
        return 'Summarize recent business signals and key aggregates for the last N hours (best-effort).';
    }

    public static function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'hours' => [
                    'type' => 'integer',
                    'minimum' => 1,
                    'maximum' => 168,
                    'default' => 24,
                    'description' => 'Window size in hours (default 24).',
                ],
            ],
            'required' => [],
        ];
    }

    public function execute(AitoolsContext $ctx, array $args = []): array
    {
        $hours = (int)($args['hours'] ?? 24);
        $hours = max(1, min(168, $hours));

        $service = app(PulseService::class);
        return $service->getPulse($ctx, $hours);
    }
}
