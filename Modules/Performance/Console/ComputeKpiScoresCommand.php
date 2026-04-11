<?php

namespace Modules\Performance\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Company;

class ComputeKpiScoresCommand extends Command
{
    protected $signature = 'performance:compute-kpi-scores
                            {--company= : Limit to a specific company ID}';

    protected $description = 'Daily batch: recalculate KPI performance scores for all employees from live data.';

    public function handle(): int
    {
        if (!Schema::hasTable('employee_details') || !Schema::hasColumn('employee_details', 'performance_score')) {
            $this->warn('employee_details KPI columns not yet migrated. Skipping.');
            return Command::SUCCESS;
        }

        $companyId = $this->option('company');

        $query = Company::active()->select('id');

        if ($companyId) {
            $query->where('id', $companyId);
        }

        $query->chunk(50, function ($companies) {
            foreach ($companies as $company) {
                $this->processCompany($company->id);
            }
        });

        $this->info('KPI scores computed successfully.');

        return Command::SUCCESS;
    }

    private function processCompany(int $companyId): void
    {
        $employees = DB::table('employee_details')
            ->where('company_id', $companyId)
            ->get();

        $hasJobsTable = Schema::hasTable('fsm_orders');
        $hasRatingsTable = Schema::hasTable('project_ratings') && Schema::hasColumn('project_ratings', 'quality_rating');

        foreach ($employees as $emp) {
            $this->updateEmployeeKpi($emp, $companyId, $hasJobsTable, $hasRatingsTable);
        }
    }

    private function updateEmployeeKpi(object $emp, int $companyId, bool $hasJobsTable, bool $hasRatingsTable): void
    {
        $weekStart = now()->startOfWeek();
        $weekEnd   = now()->endOfWeek();

        $actualJobsWeek = 0;
        $completionRate = 0.00;
        $punctualityRate = 0.00;

        if ($hasJobsTable) {
            $totalJobs = DB::table('fsm_orders')
                ->where('company_id', $companyId)
                ->where('assigned_to', $emp->user_id)
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->count();

            $completedJobs = DB::table('fsm_orders')
                ->where('company_id', $companyId)
                ->where('assigned_to', $emp->user_id)
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->where('status', 'completed')
                ->count();

            $onTimeJobs = 0;

            if (Schema::hasColumn('fsm_orders', 'completed_at') && Schema::hasColumn('fsm_orders', 'scheduled_end')) {
                $onTimeJobs = DB::table('fsm_orders')
                    ->where('company_id', $companyId)
                    ->where('assigned_to', $emp->user_id)
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->where('status', 'completed')
                    ->whereColumn('completed_at', '<=', 'scheduled_end')
                    ->count();
            }

            $actualJobsWeek  = $totalJobs;
            // Division-by-zero protection
            $completionRate  = $totalJobs > 0 ? round(($completedJobs / $totalJobs) * 100, 2) : 0.00;
            $punctualityRate = $completedJobs > 0 ? round(($onTimeJobs / $completedJobs) * 100, 2) : 0.00;
        }

        $qualityScore = 0.00;

        if ($hasRatingsTable) {
            $avg = DB::table('project_ratings')
                ->where('user_id', $emp->user_id)
                ->whereNotNull('quality_rating')
                ->avg('quality_rating');

            $qualityScore = $avg !== null ? round((float) $avg, 2) : 0.00;
        }

        $complaintsCount = 0;

        if (Schema::hasTable('complaints') && Schema::hasColumn('complaints', 'employee_id')) {
            $complaintsCount = DB::table('complaints')
                ->where('employee_id', $emp->user_id)
                ->where('company_id', $companyId)
                ->count();
        }

        // Overall performance score (weighted composite 0–100)
        $target = max((int) $emp->kpi_target_jobs_per_week, 1);
        $jobsScore = min(($actualJobsWeek / $target) * 100, 100);

        // Normalise quality (1-5) to 0-100
        $qualityNorm = $qualityScore > 0 ? (($qualityScore - 1) / 4) * 100 : 0;

        // Penalty per complaint (capped at 25 points deduction)
        $complaintPenalty = min($complaintsCount * 5, 25);

        $performanceScore = round(
            ($jobsScore * 0.30)
            + ($completionRate * 0.25)
            + ($punctualityRate * 0.20)
            + ($qualityNorm * 0.25)
            - $complaintPenalty,
            2
        );

        // Clamp 0-100
        $performanceScore = max(0, min(100, $performanceScore));

        DB::table('employee_details')
            ->where('id', $emp->id)
            ->update([
                'performance_score'     => $performanceScore,
                'kpi_actual_jobs_week'  => $actualJobsWeek,
                'kpi_completion_rate'   => $completionRate,
                'kpi_punctuality_rate'  => $punctualityRate,
                'kpi_quality_score'     => $qualityScore,
                'kpi_complaints_count'  => $complaintsCount,
            ]);

        $this->maybeFireLowPerformerAlert($emp->user_id, $companyId, $performanceScore);
    }

    private function maybeFireLowPerformerAlert(int $userId, int $companyId, float $score): void
    {
        $threshold = (float) config('performance.low_performer_threshold', 40);

        if ($score < $threshold) {
            try {
                $user = \App\Models\User::find($userId);

                if ($user) {
                    $user->notify(new \Modules\Performance\Notifications\LowPerformerAlertNotification($score, $companyId));
                }
            } catch (\Throwable $e) {
                // Silent — don't break the batch
                logger()->warning('LowPerformerAlert failed for user ' . $userId . ': ' . $e->getMessage());
            }
        }
    }
}
