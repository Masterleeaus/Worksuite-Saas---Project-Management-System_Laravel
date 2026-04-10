<?php

namespace Modules\Aitools\Tools\Tools;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Aitools\Tools\Contracts\AiToolInterface;
use Modules\Aitools\Tools\DTO\AitoolsContext;

class GetTodaySummaryTool implements AiToolInterface
{
    public static function name(): string { return 'get_today_summary'; }

    public static function description(): string
    {
        return 'Summarize today\'s operational workload: jobs/projects, tasks and unpaid invoices (best-effort based on available tables).';
    }

    public static function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'date' => ['type' => 'string', 'description' => 'ISO date (YYYY-MM-DD). Defaults to today.'],
            ],
            'required' => [],
        ];
    }

    public function execute(AitoolsContext $ctx, array $args): array
    {
        $date = $args['date'] ?? null;
        $day = $date ? Carbon::parse($date, $ctx->timezone) : Carbon::now($ctx->timezone);
        $start = $day->copy()->startOfDay()->toDateTimeString();
        $end = $day->copy()->endOfDay()->toDateTimeString();

        $summary = [
            'date' => $day->toDateString(),
            'jobs' => null,
            'tasks' => null,
            'unpaid_invoices' => null,
            'notes' => [],
        ];

        // Jobs / Projects
        $jobTable = null;
        foreach (['jobs', 'projects', 'work_orders'] as $t) {
            if (Schema::hasTable($t)) { $jobTable = $t; break; }
        }
        if ($jobTable) {
            $q = DB::table($jobTable);
            if (Schema::hasColumn($jobTable, 'company_id')) $q->where('company_id', $ctx->companyId);
            if (Schema::hasColumn($jobTable, 'start_date')) $q->whereBetween('start_date', [$start, $end]);
            elseif (Schema::hasColumn($jobTable, 'created_at')) $q->whereBetween('created_at', [$start, $end]);
            $summary['jobs'] = [
                'table' => $jobTable,
                'count' => (int) $q->count(),
            ];
        } else {
            $summary['notes'][] = 'No jobs/projects table found (checked: jobs, projects, work_orders).';
        }

        // Tasks
        if (Schema::hasTable('tasks')) {
            $q = DB::table('tasks');
            if (Schema::hasColumn('tasks', 'company_id')) $q->where('company_id', $ctx->companyId);
            if (Schema::hasColumn('tasks', 'due_date')) $q->whereBetween('due_date', [$start, $end]);
            elseif (Schema::hasColumn('tasks', 'created_at')) $q->whereBetween('created_at', [$start, $end]);
            $summary['tasks'] = [
                'count' => (int) $q->count(),
            ];
        } else {
            $summary['notes'][] = 'No tasks table found.';
        }

        // Unpaid invoices
        $invoiceTable = null;
        foreach (['invoices', 'invoice'] as $t) {
            if (Schema::hasTable($t)) { $invoiceTable = $t; break; }
        }
        if ($invoiceTable) {
            $q = DB::table($invoiceTable);
            if (Schema::hasColumn($invoiceTable, 'company_id')) $q->where('company_id', $ctx->companyId);
            if (Schema::hasColumn($invoiceTable, 'status')) $q->whereIn('status', ['unpaid', 'pending', 'due']);
            $summary['unpaid_invoices'] = [
                'table' => $invoiceTable,
                'count' => (int) $q->count(),
            ];
        } else {
            $summary['notes'][] = 'No invoices table found (checked: invoices, invoice).';
        }

        return ['success' => true, 'data' => $summary];
    }
}
