<?php

namespace Modules\Aitools\Tools\Tools;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Aitools\Tools\Contracts\AiToolInterface;
use Modules\Aitools\Tools\DTO\AitoolsContext;

class SearchJobsTool implements AiToolInterface
{
    public static function name(): string { return 'search_jobs'; }

    public static function description(): string
    {
        return 'Search jobs/projects/work orders by keyword and optional date range (best-effort).';
    }

    public static function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'query' => ['type' => 'string', 'description' => 'Keyword search.'],
                'date_from' => ['type' => 'string', 'description' => 'YYYY-MM-DD'],
                'date_to' => ['type' => 'string', 'description' => 'YYYY-MM-DD'],
                'limit' => ['type' => 'integer', 'description' => 'Max results (default 10).'],
            ],
            'required' => ['query'],
        ];
    }

    public function execute(AitoolsContext $ctx, array $args): array
    {
        $qstr = trim((string)($args['query'] ?? ''));
        $limit = (int)($args['limit'] ?? 10);
        $limit = max(1, min(50, $limit));

        $table = null;
        foreach (['jobs', 'projects', 'work_orders'] as $t) {
            if (Schema::hasTable($t)) { $table = $t; break; }
        }
        if (!$table) {
            return ['success' => false, 'error' => 'No jobs/projects/work_orders table found.'];
        }

        $q = DB::table($table);
        if (Schema::hasColumn($table, 'company_id')) $q->where('company_id', $ctx->companyId);

        // keyword
        $q->where(function($sub) use ($table, $qstr) {
            foreach (['name','title','heading','description','notes'] as $col) {
                if (Schema::hasColumn($table, $col)) $sub->orWhere($col, 'like', "%{$qstr}%");
            }
        });

        // date range
        $df = $args['date_from'] ?? null;
        $dt = $args['date_to'] ?? null;
        $dateCol = Schema::hasColumn($table,'start_date') ? 'start_date' : (Schema::hasColumn($table,'created_at') ? 'created_at' : null);
        if ($dateCol && ($df || $dt)) {
            $from = $df ? Carbon::parse($df, $ctx->timezone)->startOfDay() : Carbon::now($ctx->timezone)->subYears(10);
            $to = $dt ? Carbon::parse($dt, $ctx->timezone)->endOfDay() : Carbon::now($ctx->timezone)->addDays(1);
            $q->whereBetween($dateCol, [$from->toDateTimeString(), $to->toDateTimeString()]);
        }

        $select = [];
        foreach (['id','name','title','heading','start_date','created_at','status'] as $col) {
            if (Schema::hasColumn($table, $col)) $select[] = $col;
        }
        if (!$select) $select = ['*'];

        $rows = $q->limit($limit)->get($select);

        return ['success' => true, 'data' => ['table' => $table, 'results' => $rows]];
    }
}
