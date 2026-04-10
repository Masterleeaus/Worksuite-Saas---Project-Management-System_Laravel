<?php

namespace Modules\Aitools\Services\Insights;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Aitools\Tools\DTO\AitoolsContext;

class PulseService
{
    /**
     * Build a compact, tool-friendly pulse payload for the last N hours.
     * Best-effort: if ai_tools_signals isn't present, fall back to basic aggregates.
     */
    public function getPulse(AitoolsContext $ctx, int $hours = 24): array
    {
        $since = Carbon::now()->subHours($hours);

        $payload = [
            'window_hours' => $hours,
            'since' => $since->toIso8601String(),
            'signals' => [],
            'aggregates' => [],
        ];

        if (Schema::hasTable('ai_tools_signals')) {
            $q = DB::table('ai_tools_signals')
                ->where('occurred_at', '>=', $since);

            if (!empty($ctx->company_id)) {
                $q->where(function ($w) use ($ctx) {
                    $w->whereNull('company_id')->orWhere('company_id', $ctx->company_id);
                });
            }

            $payload['signals'] = $q->orderByDesc('occurred_at')->limit(50)->get()->map(function ($row) {
                return [
                    'type' => $row->type,
                    'severity' => $row->severity,
                    'occurred_at' => $row->occurred_at,
                    'payload' => $row->payload ? json_decode($row->payload, true) : null,
                ];
            })->toArray();
        }

        // Optional: best-effort aggregates from common tables (if present)
        $payload['aggregates'] = $this->bestEffortAggregates($ctx);

        return $payload;
    }

    private function bestEffortAggregates(AitoolsContext $ctx): array
    {
        $agg = [];

        // Jobs
        if (Schema::hasTable('jobs')) {
            $q = DB::table('jobs');
            if (!empty($ctx->company_id) && Schema::hasColumn('jobs', 'company_id')) {
                $q->where('company_id', $ctx->company_id);
            }
            $agg['jobs_total'] = (int) $q->count();
        }

        // Invoices (common table names vary; keep it safe)
        foreach (['invoices', 'invoice'] as $tbl) {
            if (Schema::hasTable($tbl)) {
                $q = DB::table($tbl);
                if (!empty($ctx->company_id) && Schema::hasColumn($tbl, 'company_id')) {
                    $q->where('company_id', $ctx->company_id);
                }
                $agg['invoices_total'] = (int) $q->count();
                break;
            }
        }

        // Tasks
        if (Schema::hasTable('tasks')) {
            $q = DB::table('tasks');
            if (!empty($ctx->company_id) && Schema::hasColumn('tasks', 'company_id')) {
                $q->where('company_id', $ctx->company_id);
            }
            $agg['tasks_total'] = (int) $q->count();
        }

        return $agg;
    }
}
