<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * CleanerPerformanceReportController
 *
 * Builds per-cleaner scorecards from existing data:
 *   - Bookings completed / cancelled (from tasks table)
 *   - Star rating (from reviews table via ReviewModule, if installed)
 *   - Complaints received (from Complaint module, if installed)
 *   - Punctuality % (cleaner_arrived_at vs due_date on tasks)
 *
 * No new tables created — all data sourced from existing tables.
 * N+1 queries avoided: all aggregation done in a single GROUP-BY query.
 */
class CleanerPerformanceReportController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Cleaner Performance Scorecard';
    }

    /**
     * List all cleaners with aggregate KPI columns.
     */
    public function index(Request $request)
    {
        abort_403(user()->permission('view_hr_reports') !== 'all');

        if (!request()->ajax()) {
            $this->employees = \App\Models\User::allEmployees();
            $this->fromDate  = now($this->company->timezone)->startOfMonth();
            $this->toDate    = now($this->company->timezone);
        }

        return view('report::cleaner-scorecard.index', $this->data);
    }

    /**
     * Show the scorecard for a single cleaner.
     * Employees may only view their own scorecard.
     */
    public function show(Request $request, int $cleanerId)
    {
        $permission = user()->permission('view_hr_reports');

        // Employees can only see their own scorecard.
        if ($permission !== 'all' && user()->id !== $cleanerId) {
            abort(403, 'You may only view your own scorecard.');
        }

        $cleaner = \App\Models\User::findOrFail($cleanerId);

        $startDate = $this->resolveDate($request->startDate, now($this->company->timezone)->startOfMonth()->toDateString());
        $endDate   = $this->resolveDate($request->endDate, now($this->company->timezone)->toDateString());

        $scorecard = $this->buildScorecard($cleanerId, $startDate, $endDate);

        $this->cleaner   = $cleaner;
        $this->scorecard = $scorecard;
        $this->fromDate  = $startDate;
        $this->toDate    = $endDate;

        return view('report::cleaner-scorecard.show', $this->data);
    }

    /**
     * Export all cleaner scorecards as CSV.
     */
    public function export(Request $request)
    {
        abort_403(user()->permission('export_reports') !== 'all');

        $startDate = $this->resolveDate($request->startDate, now($this->company->timezone)->startOfMonth()->toDateString());
        $endDate   = $this->resolveDate($request->endDate, now($this->company->timezone)->toDateString());

        $rows = $this->buildAllScorecards($startDate, $endDate);

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="cleaner_scorecard_' . $startDate . '_' . $endDate . '.csv"',
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Cleaner', 'Jobs Completed', 'Jobs Cancelled', 'Recleans', 'Avg Rating', 'Punctuality %', 'Complaints']);
            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->cleaner_name,
                    $row->completed,
                    $row->cancelled,
                    $row->recleans,
                    $row->avg_rating ?? 'N/A',
                    $row->punctuality_pct,
                    $row->complaints,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    /**
     * Build the scorecard for a single cleaner.
     */
    private function buildScorecard(int $cleanerId, string $startDate, string $endDate): array
    {
        $rows = $this->buildAllScorecards($startDate, $endDate, $cleanerId);
        return $rows->first() ? (array) $rows->first() : $this->emptyScorecard();
    }

    /**
     * Build aggregate scorecards for all cleaners (or filtered to one).
     *
     * Aggregation is done in a single query per data source to avoid N+1.
     */
    private function buildAllScorecards(string $startDate, string $endDate, ?int $cleanerId = null)
    {
        // ── Booking stats from tasks ───────────────────────────────────────
        $bookingsQuery = DB::table('tasks')
            ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->join('users', 'users.id', '=', 'task_users.user_id')
            ->where('tasks.task_type', 'booking')
            ->where('task_users.is_owner', 0)
            ->whereBetween(DB::raw('DATE(tasks.due_date)'), [$startDate, $endDate])
            ->select([
                'users.id as cleaner_id',
                'users.name as cleaner_name',
                DB::raw("SUM(CASE WHEN tasks.booking_status='completed'  THEN 1 ELSE 0 END) as completed"),
                DB::raw("SUM(CASE WHEN tasks.booking_status='cancelled'  THEN 1 ELSE 0 END) as cancelled"),
                DB::raw("SUM(CASE WHEN tasks.booking_status='reclean'    THEN 1 ELSE 0 END) as recleans"),
                DB::raw('COUNT(*) as total_jobs'),
                // Punctuality: arrived on time = cleaner_arrived_at <= due_date
                DB::raw("ROUND(
                    SUM(CASE WHEN tasks.cleaner_arrived_at IS NOT NULL
                              AND tasks.cleaner_arrived_at <= tasks.due_date THEN 1 ELSE 0 END)
                    / NULLIF(SUM(CASE WHEN tasks.cleaner_arrived_at IS NOT NULL THEN 1 ELSE 0 END), 0)
                    * 100, 1) as punctuality_pct"),
            ])
            ->groupBy('users.id', 'users.name');

        if ($cleanerId !== null) {
            $bookingsQuery->where('users.id', $cleanerId);
        }

        $bookingStats = $bookingsQuery->get()->keyBy('cleaner_id');

        // ── Average rating from reviews table (ReviewModule, optional) ─────
        $ratingStats = collect();
        if (Schema::hasTable('reviews') && Schema::hasColumn('reviews', 'reviewer_id')) {
            $ratingsQuery = DB::table('reviews')
                ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                ->whereNotNull('rating')
                ->select([
                    'reviewer_id as cleaner_id',
                    DB::raw('ROUND(AVG(rating), 2) as avg_rating'),
                ])
                ->groupBy('reviewer_id');

            if ($cleanerId !== null) {
                $ratingsQuery->where('reviewer_id', $cleanerId);
            }

            $ratingStats = $ratingsQuery->get()->keyBy('cleaner_id');
        }

        // ── Complaints (Complaint module, optional) ────────────────────────
        $complaintStats = collect();
        if (Schema::hasTable('complaints') && Schema::hasColumn('complaints', 'user_id')) {
            $complaintsQuery = DB::table('complaints')
                ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                ->select([
                    'user_id as cleaner_id',
                    DB::raw('COUNT(*) as complaints'),
                ])
                ->groupBy('user_id');

            if ($cleanerId !== null) {
                $complaintsQuery->where('user_id', $cleanerId);
            }

            $complaintStats = $complaintsQuery->get()->keyBy('cleaner_id');
        }

        // ── Merge ──────────────────────────────────────────────────────────
        return $bookingStats->map(function ($row) use ($ratingStats, $complaintStats) {
            $row->avg_rating  = optional($ratingStats->get($row->cleaner_id))->avg_rating;
            $row->complaints  = optional($complaintStats->get($row->cleaner_id))->complaints ?? 0;
            return $row;
        })->values();
    }

    private function emptyScorecard(): array
    {
        return [
            'cleaner_id'      => null,
            'cleaner_name'    => '',
            'completed'       => 0,
            'cancelled'       => 0,
            'recleans'        => 0,
            'total_jobs'      => 0,
            'punctuality_pct' => null,
            'avg_rating'      => null,
            'complaints'      => 0,
        ];
    }

    private function resolveDate(?string $requestDate, string $default): string
    {
        if ($requestDate !== null && $requestDate !== 'null' && $requestDate !== '') {
            return companyToDateString($requestDate);
        }
        return $default;
    }
}
