<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\TaskReportController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * BookingReportController
 *
 * Extends the core TaskReportController and adds FSM-specific filters:
 *   - zone / territory filter
 *   - service_type filter
 *   - cleaner (assigned worker) filter
 *   - completion rate, cancellation rate, reclean rate KPIs
 *
 * No new database tables are created — all data is sourced from the
 * existing `tasks` table (filtered to task_type='booking') and the
 * `fsm_locations` / `fsm_territories` tables.
 */
class BookingReportController extends TaskReportController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'FSM Booking Performance Report';
    }

    /**
     * Display the FSM booking performance report index.
     */
    public function index(Request $request)
    {
        abort_403(user()->permission('view_reports') !== 'all');

        if (!request()->ajax()) {
            $this->setupViewData($request);
        }

        return view('report::booking-performance.index', $this->data);
    }

    /**
     * Return chart data for the booking performance report.
     */
    public function chartData(Request $request): JsonResponse
    {
        abort_403(user()->permission('view_reports') !== 'all');

        $startDate = $this->resolveDate($request->startDate, now($this->company->timezone)->startOfMonth()->toDateString());
        $endDate   = $this->resolveDate($request->endDate, now($this->company->timezone)->toDateString());

        $query = $this->buildBookingQuery($request, $startDate, $endDate);

        $total      = (clone $query)->count();
        $completed  = (clone $query)->where('booking_status', 'completed')->count();
        $cancelled  = (clone $query)->where('booking_status', 'cancelled')->count();
        $reclean    = (clone $query)->where('booking_status', 'reclean')->count();

        $completionRate   = $total > 0 ? round(($completed  / $total) * 100, 1) : 0;
        $cancellationRate = $total > 0 ? round(($cancelled  / $total) * 100, 1) : 0;
        $recleanRate      = $total > 0 ? round(($reclean    / $total) * 100, 1) : 0;

        // Daily trend
        $trend = (clone $query)
            ->select(
                DB::raw('DATE(due_date) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN booking_status='completed' THEN 1 ELSE 0 END) as completed"),
                DB::raw("SUM(CASE WHEN booking_status='cancelled' THEN 1 ELSE 0 END) as cancelled")
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'total'            => $total,
            'completed'        => $completed,
            'cancelled'        => $cancelled,
            'reclean'          => $reclean,
            'completionRate'   => $completionRate,
            'cancellationRate' => $cancellationRate,
            'recleanRate'      => $recleanRate,
            'trend'            => $trend,
        ]);
    }

    /**
     * Export bookings as CSV (streamed — no synchronous large response).
     */
    public function export(Request $request)
    {
        abort_403(user()->permission('export_reports') !== 'all');

        $startDate = $this->resolveDate($request->startDate, now($this->company->timezone)->startOfMonth()->toDateString());
        $endDate   = $this->resolveDate($request->endDate, now($this->company->timezone)->toDateString());

        $rows = $this->buildBookingQuery($request, $startDate, $endDate)
            ->select([
                'tasks.id',
                'tasks.heading',
                'tasks.due_date',
                'tasks.booking_status',
                'tasks.service_type',
                'tasks.service_address',
                'tasks.estimated_duration_hours',
                'tasks.actual_duration_hours',
                DB::raw('CONCAT(users.name) as cleaner_name'),
            ])
            ->leftJoin('task_users', function ($j) {
                $j->on('task_users.task_id', '=', 'tasks.id')->where('task_users.is_owner', 0);
            })
            ->leftJoin('users', 'users.id', '=', 'task_users.user_id')
            ->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="booking_performance_' . $startDate . '_' . $endDate . '.csv"',
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Heading', 'Due Date', 'Status', 'Service Type', 'Address', 'Est. Hrs', 'Actual Hrs', 'Cleaner']);
            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->id,
                    $row->heading,
                    $row->due_date,
                    $row->booking_status,
                    $row->service_type,
                    $row->service_address,
                    $row->estimated_duration_hours,
                    $row->actual_duration_hours,
                    $row->cleaner_name,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    /**
     * Build the base booking query with optional FSM filters applied.
     *
     * @return \Illuminate\Database\Query\Builder|\App\Models\Task
     */
    private function buildBookingQuery(Request $request, string $startDate, string $endDate)
    {
        $query = \App\Models\Task::where('task_type', 'booking')
            ->whereBetween(DB::raw('DATE(due_date)'), [$startDate, $endDate]);

        if ($request->filled('service_type') && $request->service_type !== 'all') {
            $query->where('service_type', $request->service_type);
        }

        if ($request->filled('booking_status') && $request->booking_status !== 'all') {
            $query->where('booking_status', $request->booking_status);
        }

        // Cleaner filter: tasks are assigned via task_users pivot
        if ($request->filled('cleaner_id') && $request->cleaner_id !== 'all') {
            $query->whereHas('users', fn ($q) => $q->where('users.id', $request->cleaner_id));
        }

        // Zone filter: tasks reference a location via service_address; territories live in fsm_locations
        if ($request->filled('territory_id') && $request->territory_id !== 'all') {
            if (\Illuminate\Support\Facades\Schema::hasTable('fsm_locations')) {
                $locationCities = \Illuminate\Support\Facades\DB::table('fsm_locations')
                    ->where('territory_id', $request->territory_id)
                    ->pluck('city')
                    ->filter()
                    ->map(fn ($city) => addcslashes((string) $city, '%_\\'))
                    ->values();
                if ($locationCities->isNotEmpty()) {
                    $query->where(function ($q) use ($locationCities) {
                        foreach ($locationCities as $city) {
                            // The value is passed as a PDO bound parameter by Laravel's query builder,
                            // preventing SQL injection. addcslashes escapes LIKE pattern wildcards.
                            $q->orWhere('service_address', 'like', '%' . $city . '%');
                        }
                    });
                }
            }
        }

        return $query;
    }

    private function setupViewData(Request $request): void
    {
        $this->serviceTypes = \Modules\BookingModule\Models\CleaningBooking::SERVICE_TYPES ?? [];
        $this->employees    = \App\Models\User::allEmployees();
        $this->territories  = \Illuminate\Support\Facades\Schema::hasTable('fsm_territories')
            ? \Modules\FSMCore\Models\FSMTerritory::where('active', true)->get()
            : collect();
        $this->statuses     = array_keys(\Modules\BookingModule\Models\CleaningBooking::VALID_TRANSITIONS ?? []);
        $this->fromDate     = now($this->company->timezone)->startOfMonth();
        $this->toDate       = now($this->company->timezone);
    }

    private function resolveDate(?string $requestDate, string $default): string
    {
        if ($requestDate !== null && $requestDate !== 'null' && $requestDate !== '') {
            return companyToDateString($requestDate);
        }
        return $default;
    }
}
