<?php

namespace App\Mcp\Tools;

use App\Mcp\Contracts\McpTool;
use Illuminate\Support\Facades\DB;

class GetClientsTool implements McpTool
{
    public function name(): string
    {
        return 'get_clients';
    }

    public function description(): string
    {
        return 'Retrieve a list of clients with optional search and filters.';
    }

    public function inputSchema(): array
    {
        return [
            'type'       => 'object',
            'properties' => [
                'search' => [
                    'type'        => 'string',
                    'description' => 'Search by client name, email, or phone',
                ],
                'client_tag' => [
                    'type'        => 'string',
                    'description' => 'Filter by tag: residential, commercial, strata, airbnb, vip',
                ],
                'limit' => [
                    'type'    => 'integer',
                    'default' => 20,
                ],
            ],
        ];
    }

    public function handle(array $arguments): array
    {
        $query = DB::table('users')
            ->join('client_details', 'users.id', '=', 'client_details.user_id')
            ->where('users.role', 'client')
            ->select([
                'users.id', 'users.name', 'users.email', 'users.mobile',
                'client_details.address', 'client_details.client_tag',
            ]);

        if (!empty($arguments['search'])) {
            $s = $arguments['search'];
            $query->where(function ($q) use ($s) {
                $q->where('users.name', 'like', "%{$s}%")
                  ->orWhere('users.email', 'like', "%{$s}%")
                  ->orWhere('users.mobile', 'like', "%{$s}%");
            });
        }

        if (!empty($arguments['client_tag'])) {
            $query->where('client_details.client_tag', $arguments['client_tag']);
        }

        $limit   = min((int) ($arguments['limit'] ?? 20), 100);
        $clients = $query->orderBy('users.name')->limit($limit)->get();

        return [
            [
                'type' => 'text',
                'text' => json_encode([
                    'count'   => $clients->count(),
                    'clients' => $clients->toArray(),
                ], JSON_PRETTY_PRINT),
            ],
        ];
    }
}
