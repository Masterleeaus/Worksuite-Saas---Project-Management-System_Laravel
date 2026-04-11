<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * ChemicalUsageReportController
 *
 * Aggregates consumable/chemical usage per booking from the FSMStock module
 * (fsm_stock_moves + fsm_stock_items tables).  When FSMStock is not installed
 * the page renders with an empty state instead of erroring.
 *
 * No new tables created.
 */
class ChemicalUsageReportController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Chemical Usage Report';
    }

    /**
     * Display the chemical usage report.
     */
    public function index(Request $request)
    {
        abort_403(user()->permission('view_reports') !== 'all');

        $startDate = $this->resolveDate($request->startDate, now($this->company->timezone)->startOfMonth()->toDateString());
        $endDate   = $this->resolveDate($request->endDate, now($this->company->timezone)->toDateString());

        $this->fromDate     = $startDate;
        $this->toDate       = $endDate;
        $this->fsmInstalled = $this->fsmStockInstalled();
        $this->rows         = $this->fsmInstalled ? $this->buildUsageRows($startDate, $endDate) : collect();

        return view('report::chemical-usage.index', $this->data);
    }

    /**
     * Export chemical usage as CSV.
     */
    public function export(Request $request)
    {
        abort_403(user()->permission('export_reports') !== 'all');

        $startDate = $this->resolveDate($request->startDate, now($this->company->timezone)->startOfMonth()->toDateString());
        $endDate   = $this->resolveDate($request->endDate, now($this->company->timezone)->toDateString());

        $rows = $this->fsmStockInstalled() ? $this->buildUsageRows($startDate, $endDate) : collect();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="chemical_usage_' . $startDate . '_' . $endDate . '.csv"',
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Product', 'Category', 'Total Qty Used', 'Unit', 'Bookings Count']);
            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->product_name,
                    $row->category_name ?? 'Uncategorised',
                    $row->total_qty,
                    $row->unit ?? '',
                    $row->booking_count,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    private function fsmStockInstalled(): bool
    {
        return Schema::hasTable('fsm_stock_moves') && Schema::hasTable('fsm_stock_items');
    }

    /**
     * Build product-level usage aggregation across bookings in date range.
     * Direction='out' means consumed; direction='in' means restocked.
     */
    private function buildUsageRows(string $startDate, string $endDate)
    {
        return DB::table('fsm_stock_moves as m')
            ->leftJoin('fsm_stock_items as i',  'i.id', '=', 'm.product_id')
            ->leftJoin('fsm_stock_categories as c', 'c.id', '=', 'i.category_id')
            ->where('m.direction', 'out')
            ->whereBetween(DB::raw('DATE(m.moved_at)'), [$startDate, $endDate])
            ->select([
                DB::raw('COALESCE(i.name, "Unknown") as product_name'),
                DB::raw('COALESCE(c.name, NULL) as category_name'),
                DB::raw('SUM(m.qty) as total_qty'),
                DB::raw('i.unit as unit'),
                DB::raw('COUNT(DISTINCT m.order_id) as booking_count'),
            ])
            ->groupBy('m.product_id', 'i.name', 'c.name', 'i.unit')
            ->orderByDesc('total_qty')
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
