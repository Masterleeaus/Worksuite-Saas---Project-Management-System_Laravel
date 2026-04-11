<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\FinanceReportController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * RevenueByZoneReportController
 *
 * Extends the core FinanceReportController and adds FSM zone-level revenue
 * breakdown (suburb / territory) sourced from existing invoices/payments and
 * fsm_locations / fsm_territories tables.
 *
 * No new tables created.
 */
class RevenueByZoneReportController extends FinanceReportController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Revenue by Zone Report';
    }

    /**
     * Display the zone revenue index page.
     */
    public function index(Request $request)
    {
        abort_403(user()->permission('view_financial_reports') !== 'all');

        if (!request()->ajax()) {
            $this->territories = Schema::hasTable('fsm_territories')
                ? \Modules\FSMCore\Models\FSMTerritory::where('active', true)->get()
                : collect();
            $this->fromDate = now($this->company->timezone)->startOfMonth();
            $this->toDate   = now($this->company->timezone);
        }

        return view('report::zone-revenue.index', $this->data);
    }

    /**
     * Return JSON chart data broken down by zone/territory.
     */
    public function chartData(Request $request): JsonResponse
    {
        abort_403(user()->permission('view_financial_reports') !== 'all');

        $startDate = $this->resolveDate($request->startDate, now($this->company->timezone)->startOfMonth()->toDateString());
        $endDate   = $this->resolveDate($request->endDate, now($this->company->timezone)->toDateString());

        $rows = $this->buildZoneRevenueQuery($startDate, $endDate, $request->territory_id ?? null);

        return response()->json([
            'zones'  => $rows->pluck('zone_name'),
            'totals' => $rows->pluck('total_revenue'),
            'rows'   => $rows,
        ]);
    }

    /**
     * Export zone revenue as CSV.
     */
    public function export(Request $request)
    {
        abort_403(user()->permission('export_reports') !== 'all');

        $startDate = $this->resolveDate($request->startDate, now($this->company->timezone)->startOfMonth()->toDateString());
        $endDate   = $this->resolveDate($request->endDate, now($this->company->timezone)->toDateString());

        $rows = $this->buildZoneRevenueQuery($startDate, $endDate);

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="zone_revenue_' . $startDate . '_' . $endDate . '.csv"',
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Zone / Suburb', 'Jobs Count', 'Total Revenue', 'Avg Revenue per Job', 'Cost per Job']);
            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->zone_name,
                    $row->job_count,
                    $row->total_revenue,
                    $row->avg_revenue,
                    $row->cost_per_job,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    /**
     * Build zone revenue aggregate from payments joined to FSM orders/locations.
     *
     * Falls back to suburb-level grouping from fsm_locations.city when the
     * fsm_territories table is available; otherwise groups by location city
     * directly from the tasks.service_address field.
     */
    private function buildZoneRevenueQuery(string $startDate, string $endDate, $territoryId = null)
    {
        // Strategy 1 — FSM locations + territories table available
        if (Schema::hasTable('fsm_orders') && Schema::hasTable('fsm_locations')) {
            // Join invoices directly on project_id to avoid per-row correlated subqueries.
            $query = DB::table('fsm_orders')
                ->join('fsm_locations', 'fsm_locations.id', '=', 'fsm_orders.location_id')
                ->leftJoin('invoices', 'invoices.project_id', '=', 'fsm_orders.id')
                ->leftJoin('payments', function ($j) {
                    $j->on('payments.invoice_id', '=', 'invoices.id')
                      ->where('payments.status', 'complete');
                })
                ->whereBetween(DB::raw('DATE(payments.paid_on)'), [$startDate, $endDate])
                ->select([
                    DB::raw('COALESCE(fsm_locations.city, "Unknown") as zone_name'),
                    DB::raw('COUNT(DISTINCT fsm_orders.id) as job_count'),
                    DB::raw('SUM(payments.paid_amount) as total_revenue'),
                    DB::raw('ROUND(AVG(payments.paid_amount), 2) as avg_revenue'),
                    DB::raw('ROUND(SUM(payments.paid_amount) / NULLIF(COUNT(DISTINCT fsm_orders.id), 0), 2) as cost_per_job'),
                ])
                ->groupBy('fsm_locations.city')
                ->orderByDesc('total_revenue');

            if ($territoryId && $territoryId !== 'all') {
                $query->where('fsm_locations.territory_id', $territoryId);
            }

            return $query->get();
        }

        // Strategy 2 — Fallback: group payments by project
        return DB::table('payments')
            ->join('projects', 'projects.id', '=', 'payments.project_id')
            ->where('payments.status', 'complete')
            ->whereBetween(DB::raw('DATE(payments.paid_on)'), [$startDate, $endDate])
            ->select([
                DB::raw('COALESCE(projects.project_name, "Unassigned") as zone_name'),
                DB::raw('COUNT(*) as job_count'),
                DB::raw('SUM(payments.paid_amount) as total_revenue'),
                DB::raw('ROUND(AVG(payments.paid_amount), 2) as avg_revenue'),
                DB::raw('ROUND(SUM(payments.paid_amount) / NULLIF(COUNT(*), 0), 2) as cost_per_job'),
            ])
            ->groupBy('projects.project_name')
            ->orderByDesc('total_revenue')
            ->get();
    }

    private function resolveDate(?string $requestDate, string $default): string
    {
        if ($requestDate !== null && $requestDate !== 'null' && $requestDate !== '') {
            return companyToDateString($requestDate);
        }
        return $default;
    }
}
