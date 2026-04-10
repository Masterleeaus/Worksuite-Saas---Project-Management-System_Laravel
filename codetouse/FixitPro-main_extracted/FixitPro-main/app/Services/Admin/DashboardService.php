<?php

namespace App\Services\Admin;

use App\Models\Tenant;
use App\Models\Subscription;
use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardService
{
    /**
     * Get Total Monthly Recurring Revenue from active subscriptions
     */
    public function getTotalMRR(): float
    {
        return Subscription::active()->sum('monthly_amount');
    }

    /**
     * Get count of active tenants
     */
    public function getActiveTenants(): int
    {
        return Tenant::where('status', 'active')->count();
    }

    /**
     * Get count of tenants on trial
     */
    public function getTrialTenants(): int
    {
        return Tenant::where('status', 'trial')
            ->where('trial_ends_at', '>', now())
            ->count();
    }

    /**
     * Get total tenant count
     */
    public function getTotalTenants(): int
    {
        return Tenant::count();
    }

    /**
     * Get job volume for today
     */
    public function getTodayJobVolume(): int
    {
        return Job::today()->count();
    }

    /**
     * Get total jobs across all tenants
     */
    public function getTotalJobs(): int
    {
        return Job::count();
    }

    /**
     * Get completed jobs today
     */
    public function getCompletedJobsToday(): int
    {
        return Job::today()->completed()->count();
    }

    /**
     * Get pending jobs across platform
     */
    public function getPendingJobs(): int
    {
        return Job::pending()->count();
    }

    /**
     * Get signup trend for last N days
     */
    public function getSignupTrend(int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days);
        
        $signups = Tenant::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Fill in missing days with 0
        $trend = [];
        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $trend[] = [
                'date' => Carbon::parse($date)->format('M d'),
                'count' => $signups[$date] ?? 0
            ];
        }

        return $trend;
    }

    /**
     * Get MRR trend for last N days
     */
    public function getMRRTrend(int $days = 30): array
    {
        // For now, return simulated trend based on subscription creation dates
        $startDate = Carbon::now()->subDays($days);
        
        $trend = [];
        $runningMRR = 0;
        
        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Get subscriptions active by this date
            $dayMRR = Subscription::where('status', 'active')
                ->where('created_at', '<=', $date)
                ->sum('monthly_amount');
            
            $trend[] = [
                'date' => $date->format('M d'),
                'amount' => (float) $dayMRR
            ];
        }

        return $trend;
    }

    /**
     * Get growth rate compared to last period
     */
    public function getGrowthRate(): float
    {
        $currentPeriod = Tenant::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $previousPeriod = Tenant::where('created_at', '>=', Carbon::now()->subDays(60))
            ->where('created_at', '<', Carbon::now()->subDays(30))
            ->count();

        if ($previousPeriod === 0) {
            return $currentPeriod > 0 ? 100 : 0;
        }

        return round((($currentPeriod - $previousPeriod) / $previousPeriod) * 100, 1);
    }

    /**
     * Get recent tenant signups
     */
    public function getRecentSignups(int $limit = 5): Collection
    {
        return Tenant::with('activeSubscription')
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getTotalRevenue(): float
    {
        // For now, based on active subscriptions * monthly_amount
        // In real app, sum(payments)
        return \App\Models\Subscription::where('status', 'active')->sum('monthly_amount');
    }

    public function getActiveJobsCount(): int
    {
        return \App\Models\Job::whereIn('status', ['in_progress', 'scheduled'])->count();
    }

    public function getChurnRate(): float
    {
        $total = \App\Models\Subscription::count();
        if ($total == 0) return 0;
        
        $churned = \App\Models\Subscription::where('status', 'cancelled')->count();
        
        return round(($churned / $total) * 100, 1);
    }

    /**
     * Get platform statistics summary
     */
    public function getPlatformStats(): array
    {
        return [
            'mrr' => $this->getTotalMRR(),
            'total_revenue' => $this->getTotalRevenue(),
            'active_tenants' => $this->getActiveTenants(),
            'trial_tenants' => $this->getTrialTenants(),
            'total_tenants' => $this->getTotalTenants(),
            'today_jobs' => $this->getTodayJobVolume(),
            'active_jobs' => $this->getActiveJobsCount(),
            'total_jobs' => $this->getTotalJobs(),
            'pending_jobs' => $this->getPendingJobs(),
            'growth_rate' => $this->getGrowthRate(),
            'churn_rate' => $this->getChurnRate(),
        ];
    }
}
