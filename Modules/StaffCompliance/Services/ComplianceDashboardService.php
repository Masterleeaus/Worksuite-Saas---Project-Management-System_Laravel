<?php

namespace Modules\StaffCompliance\Services;

use App\Models\User;
use Modules\StaffCompliance\Entities\ComplianceDocumentType;
use Modules\StaffCompliance\Entities\WorkerComplianceDocument;

class ComplianceDashboardService
{
    /**
     * Return per-worker compliance status for all employees.
     *
     * Each item contains:
     *   worker   – User model
     *   expired  – collection of verified docs that have passed their expiry
     *   expiring – collection of verified docs expiring within 30 days
     *   missing  – mandatory doc types not yet verified for this worker
     *   status   – 'red' | 'orange' | 'yellow' | 'green'
     */
    public function getComplianceStatus(): array
    {
        $mandatoryTypes = ComplianceDocumentType::where('is_mandatory', true)->get();

        return User::scopeOnlyEmployee(User::query())
            ->with('employeeDetail')
            ->get()
            ->map(function (User $worker) use ($mandatoryTypes) {
                $docs = WorkerComplianceDocument::where('user_id', $worker->id)->get();

                $expired = $docs->where('status', 'verified')
                    ->filter(fn ($d) => $d->expiry_date && $d->expiry_date->isPast());

                $expiring = $docs->where('status', 'verified')
                    ->filter(fn ($d) => $d->expiry_date
                        && $d->expiry_date->between(now(), now()->addDays(30)));

                $missing = $mandatoryTypes->filter(
                    fn ($t) => !$docs->where('document_type_id', $t->id)
                                     ->where('status', 'verified')
                                     ->count()
                );

                return [
                    'worker'   => $worker,
                    'expired'  => $expired,
                    'expiring' => $expiring,
                    'missing'  => $missing,
                    'status'   => $this->overallStatus($expired, $expiring, $missing),
                ];
            })
            ->toArray();
    }

    /**
     * Determine traffic-light status from the three risk collections.
     */
    private function overallStatus($expired, $expiring, $missing): string
    {
        if ($expired->isNotEmpty()) {
            return 'red';
        }

        if ($missing->isNotEmpty()) {
            return 'yellow';
        }

        if ($expiring->isNotEmpty()) {
            return 'orange';
        }

        return 'green';
    }

    /**
     * Aggregate counts for the dashboard summary cards.
     */
    public function getSummaryCounts(): array
    {
        $statuses = $this->getComplianceStatus();

        return [
            'red'    => collect($statuses)->where('status', 'red')->count(),
            'orange' => collect($statuses)->where('status', 'orange')->count(),
            'yellow' => collect($statuses)->where('status', 'yellow')->count(),
            'green'  => collect($statuses)->where('status', 'green')->count(),
            'total'  => count($statuses),
        ];
    }
}
