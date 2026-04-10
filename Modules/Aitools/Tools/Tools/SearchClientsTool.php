<?php

namespace Modules\Aitools\Tools\Tools;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Aitools\Tools\Contracts\AiToolInterface;
use Modules\Aitools\Tools\DTO\AitoolsContext;

class SearchClientsTool implements AiToolInterface
{
    public static function name(): string { return 'search_clients'; }

    public static function description(): string
    {
        return 'Search customers/clients by name/email/phone (best-effort; uses users table when available).';
    }

    public static function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'query' => ['type' => 'string', 'description' => 'Search string (name/email/phone).'],
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
        foreach (['clients', 'customers', 'users'] as $t) {
            if (Schema::hasTable($t)) { $table = $t; break; }
        }
        if (!$table) {
            return ['success' => false, 'error' => 'No clients/customers/users table found.'];
        }

        $q = DB::table($table);
        if (Schema::hasColumn($table, 'company_id')) $q->where('company_id', $ctx->companyId);

        $q->where(function($sub) use ($table, $qstr) {
            foreach (['name', 'full_name'] as $col) {
                if (Schema::hasColumn($table, $col)) $sub->orWhere($col, 'like', "%{$qstr}%");
            }
            foreach (['email'] as $col) {
                if (Schema::hasColumn($table, $col)) $sub->orWhere($col, 'like', "%{$qstr}%");
            }
            foreach (['phone', 'mobile', 'contact_number'] as $col) {
                if (Schema::hasColumn($table, $col)) $sub->orWhere($col, 'like', "%{$qstr}%");
            }
        });

        $select = [];
        foreach (['id','name','full_name','email','phone','mobile'] as $col) {
            if (Schema::hasColumn($table, $col)) $select[] = $col;
        }
        if (!$select) $select = ['*'];

        $rows = $q->limit($limit)->get($select);

        return ['success' => true, 'data' => ['table' => $table, 'results' => $rows]];
    }
}
