<?php

namespace Modules\Aitools\Tools\Tools;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Aitools\Tools\Contracts\AiToolInterface;
use Modules\Aitools\Tools\DTO\AitoolsContext;

class GetUnpaidInvoicesTool implements AiToolInterface
{
    public static function name(): string { return 'get_unpaid_invoices'; }

    public static function description(): string
    {
        return 'List unpaid/pending invoices (best-effort).';
    }

    public static function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'limit' => ['type' => 'integer', 'description' => 'Max results (default 10).'],
            ],
            'required' => [],
        ];
    }

    public function execute(AitoolsContext $ctx, array $args): array
    {
        $limit = (int)($args['limit'] ?? 10);
        $limit = max(1, min(50, $limit));

        $table = null;
        foreach (['invoices','invoice'] as $t) {
            if (Schema::hasTable($t)) { $table = $t; break; }
        }
        if (!$table) {
            return ['success' => false, 'error' => 'No invoices table found.'];
        }

        $q = DB::table($table);
        if (Schema::hasColumn($table, 'company_id')) $q->where('company_id', $ctx->companyId);
        if (Schema::hasColumn($table, 'status')) $q->whereIn('status', ['unpaid','pending','due']);
        $select = [];
        foreach (['id','invoice_number','number','total','amount','status','due_date','created_at','client_id'] as $col) {
            if (Schema::hasColumn($table, $col)) $select[] = $col;
        }
        if (!$select) $select = ['*'];

        $rows = $q->orderByDesc(Schema::hasColumn($table,'created_at')?'created_at':'id')->limit($limit)->get($select);

        return ['success' => true, 'data' => ['table' => $table, 'results' => $rows]];
    }
}
