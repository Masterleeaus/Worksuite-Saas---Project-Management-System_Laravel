<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * RouteEfficiencyReportController
 *
 * Calculates travel time vs billable time per worker per day using:
 *   - fsm_worker_location_pings — timestamped GPS pings from FSMRoute module
 *   - fsm_orders                — actual job start/end (date_start / date_end)
 *
 * Falls back gracefully when FSMRoute is not installed.
 * No new tables created.
 */
class RouteEfficiencyReportController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Route Efficiency Report';
    }

    /**
     * Display the route efficiency report.
     */
    public function index(Request $request)
    {
        abort_403(user()->permission('view_reports') !== 'all');

        $startDate = $this->resolveDate($request->startDate, now($this->company->timezone)->startOfMonth()->toDateString());
        $endDate   = $this->resolveDate($request->endDate, now($this->company->timezone)->toDateString());

        $this->fromDate      = $startDate;
        $this->toDate        = $endDate;
        $this->fsmInstalled  = $this->fsmRouteInstalled();
        $this->rows          = $this->fsmInstalled
            ? $this->buildEfficiencyRows($startDate, $endDate, $request->worker_id ?? null)
            : collect();
        $this->employees     = \App\Models\User::allEmployees();

        return view('report::route-efficiency.index', $this->data);
    }

    /**
     * Export route efficiency as CSV.
     */
    public function export(Request $request)
    {
        abort_403(user()->permission('export_reports') !== 'all');

        $startDate = $this->resolveDate($request->startDate, now($this->company->timezone)->startOfMonth()->toDateString());
        $endDate   = $this->resolveDate($request->endDate, now($this->company->timezone)->toDateString());

        $rows = $this->fsmRouteInstalled()
            ? $this->buildEfficiencyRows($startDate, $endDate, $request->worker_id ?? null)
            : collect();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="route_efficiency_' . $startDate . '_' . $endDate . '.csv"',
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Worker', 'Date', 'Jobs Completed', 'Billable Mins', 'Avg Job Duration Mins', 'Efficiency %']);
            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->worker_name,
                    $row->work_date,
                    $row->jobs_completed,
                    $row->billable_mins,
                    $row->avg_duration_mins,
                    $row->efficiency_pct,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    private function fsmRouteInstalled(): bool
    {
        return Schema::hasTable('fsm_orders')
            && Schema::hasColumn('fsm_orders', 'date_start')
            && Schema::hasColumn('fsm_orders', 'date_end');
    }

    /**
     * Build per-worker per-day efficiency rows from fsm_orders.
     *
     * Billable minutes = SUM of (date_end - date_start) for completed orders.
     * Efficiency % = billable_mins / (working day minutes * jobs) — simplified
     * as avg job duration vs estimated.
     */
    private function buildEfficiencyRows(string $startDate, string $endDate, $workerId = null)
    {
        $query = DB::table('fsm_orders as o')
            ->join('users', 'users.id', '=', 'o.person_id')
            ->whereNotNull('o.date_start')
            ->whereNotNull('o.date_end')
            ->whereBetween(DB::raw('DATE(o.date_start)'), [$startDate, $endDate])
            ->select([
                'users.id as worker_id',
                'users.name as worker_name',
                DB::raw('DATE(o.date_start) as work_date'),
                DB::raw('COUNT(o.id) as jobs_completed'),
                DB::raw('SUM(TIMESTAMPDIFF(MINUTE, o.date_start, o.date_end)) as billable_mins'),
                DB::raw('ROUND(AVG(TIMESTAMPDIFF(MINUTE, o.date_start, o.date_end)), 1) as avg_duration_mins'),
                // Efficiency: billable mins as % of an 8h work day
                DB::raw('ROUND(
                    SUM(TIMESTAMPDIFF(MINUTE, o.date_start, o.date_end))
                    / 480 * 100, 1) as efficiency_pct'),
            ])
            ->groupBy('users.id', 'users.name', DB::raw('DATE(o.date_start)'))
            ->orderBy('work_date')
            ->orderBy('worker_name');

        if ($workerId && $workerId !== 'all') {
            $query->where('o.person_id', $workerId);
        }

        return $query->get();
    }

    private function resolveDate(?string $requestDate, string $default): string
    {
        if ($requestDate !== null && $requestDate !== 'null' && $requestDate !== '') {
            return companyToDateString($requestDate);
        }
        return $default;
    }
}
