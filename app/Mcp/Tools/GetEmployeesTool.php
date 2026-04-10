<?php

namespace App\Mcp\Tools;

use App\Mcp\Contracts\McpTool;
use Illuminate\Support\Facades\DB;

class GetEmployeesTool implements McpTool
{
    public function name(): string
    {
        return 'get_employees';
    }

    public function description(): string
    {
        return 'Retrieve a list of employees/cleaners with their star rating and availability info.';
    }

    public function inputSchema(): array
    {
        return [
            'type'       => 'object',
            'properties' => [
                'search' => [
                    'type'        => 'string',
                    'description' => 'Search by employee name or email',
                ],
                'is_subcontractor' => [
                    'type'        => 'boolean',
                    'description' => 'Filter subcontractors only',
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
            ->join('employee_details', 'users.id', '=', 'employee_details.user_id')
            ->where('users.role', 'employee')
            ->where('users.status', 'active')
            ->select([
                'users.id', 'users.name', 'users.email', 'users.mobile',
                'employee_details.star_rating', 'employee_details.is_subcontractor',
                'employee_details.abn', 'employee_details.police_check_expiry',
            ]);

        if (!empty($arguments['search'])) {
            $s = $arguments['search'];
            $query->where(function ($q) use ($s) {
                $q->where('users.name', 'like', "%{$s}%")
                  ->orWhere('users.email', 'like', "%{$s}%");
            });
        }

        if (isset($arguments['is_subcontractor'])) {
            $query->where('employee_details.is_subcontractor', (bool) $arguments['is_subcontractor']);
        }

        $limit     = min((int) ($arguments['limit'] ?? 20), 100);
        $employees = $query->orderBy('users.name')->limit($limit)->get();

        return [
            [
                'type' => 'text',
                'text' => json_encode([
                    'count'     => $employees->count(),
                    'employees' => $employees->toArray(),
                ], JSON_PRETTY_PRINT),
            ],
        ];
    }
}
