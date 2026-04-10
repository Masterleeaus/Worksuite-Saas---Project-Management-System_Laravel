<?php

namespace App\Mcp\Tools;

use App\Mcp\Contracts\McpTool;
use Illuminate\Support\Facades\DB;

class GetInvoicesTool implements McpTool
{
    public function name(): string
    {
        return 'get_invoices';
    }

    public function description(): string
    {
        return 'Retrieve invoices with optional filters for status, client, and date range. Returns totals and due amounts.';
    }

    public function inputSchema(): array
    {
        return [
            'type'       => 'object',
            'properties' => [
                'status' => [
                    'type'        => 'string',
                    'description' => 'Filter by status: unpaid, paid, partial, cancelled, draft',
                ],
                'client_id' => [
                    'type'    => 'integer',
                    'description' => 'Filter by client ID',
                ],
                'date_from' => [
                    'type'   => 'string',
                    'format' => 'date',
                ],
                'date_to' => [
                    'type'   => 'string',
                    'format' => 'date',
                ],
                'overdue_only' => [
                    'type'        => 'boolean',
                    'description' => 'Return only overdue invoices',
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
        $query = DB::table('invoices')
            ->select([
                'id', 'invoice_number', 'status', 'total', 'due_amount',
                'issue_date', 'due_date', 'client_id',
            ]);

        if (!empty($arguments['status'])) {
            $query->where('status', $arguments['status']);
        }

        if (!empty($arguments['client_id'])) {
            $query->where('client_id', $arguments['client_id']);
        }

        if (!empty($arguments['date_from'])) {
            $query->whereDate('issue_date', '>=', $arguments['date_from']);
        }

        if (!empty($arguments['date_to'])) {
            $query->whereDate('issue_date', '<=', $arguments['date_to']);
        }

        if (!empty($arguments['overdue_only'])) {
            $query->where('status', '!=', 'paid')
                  ->whereDate('due_date', '<', now()->toDateString())
                  ->where('due_amount', '>', 0);
        }

        $limit    = min((int) ($arguments['limit'] ?? 20), 100);
        $invoices = $query->orderByDesc('issue_date')->limit($limit)->get();

        $totalDue = $invoices->sum('due_amount');

        return [
            [
                'type' => 'text',
                'text' => json_encode([
                    'count'     => $invoices->count(),
                    'total_due' => round($totalDue, 2),
                    'invoices'  => $invoices->toArray(),
                ], JSON_PRETTY_PRINT),
            ],
        ];
    }
}
