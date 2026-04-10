<?php

namespace App\Mcp\Tools;

use App\Mcp\Contracts\McpTool;
use Illuminate\Support\Facades\File;

class GetModuleStatusTool implements McpTool
{
    public function name(): string
    {
        return 'get_module_status';
    }

    public function description(): string
    {
        return 'List all installed Worksuite modules with their enabled/disabled status.';
    }

    public function inputSchema(): array
    {
        return [
            'type'       => 'object',
            'properties' => [
                'filter' => [
                    'type'        => 'string',
                    'enum'        => ['all', 'enabled', 'disabled'],
                    'default'     => 'all',
                    'description' => 'Show all modules, only enabled, or only disabled.',
                ],
            ],
        ];
    }

    public function handle(array $arguments): array
    {
        $statusFile = base_path('config/modules_statuses.json');
        $statuses   = File::exists($statusFile)
            ? json_decode(File::get($statusFile), true)
            : [];

        $filter  = $arguments['filter'] ?? 'all';
        $modules = [];

        foreach ($statuses as $name => $enabled) {
            if ($filter === 'enabled' && !$enabled) {
                continue;
            }
            if ($filter === 'disabled' && $enabled) {
                continue;
            }

            $modules[] = [
                'name'    => $name,
                'enabled' => (bool) $enabled,
                'path'    => "Modules/{$name}",
            ];
        }

        return [[
            'type' => 'text',
            'text' => json_encode([
                'total'   => count($modules),
                'filter'  => $filter,
                'modules' => $modules,
            ], JSON_PRETTY_PRINT),
        ]];
    }
}
