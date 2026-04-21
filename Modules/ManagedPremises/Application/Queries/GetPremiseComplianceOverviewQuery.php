<?php

namespace Modules\ManagedPremises\Application\Queries;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\ManagedPremises\Entities\Property;

class GetPremiseComplianceOverviewQuery
{
    public function handle(Property $property): array
    {
        $companyId = function_exists('company_id') ? company_id() : null;

        $openHazards = Schema::hasTable('pm_property_hazards') ? DB::table('pm_property_hazards')
            ->where('property_id', $property->id)
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->count() : 0;

        $overdueApprovals = Schema::hasTable('pm_property_approvals') ? DB::table('pm_property_approvals')
            ->where('property_id', $property->id)
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->where('status', 'pending')
            ->whereNotNull('requested_at')
            ->where('requested_at', '<', now()->subDays(7))
            ->count() : 0;

        $documentsCount = Schema::hasTable('pm_property_documents') ? DB::table('pm_property_documents')
            ->where('property_id', $property->id)
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->count() : 0;

        $qcFailing = Schema::hasTable('qc_records') ? DB::table('qc_records')
            ->where('property_id', $property->id)
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->whereIn('status', ['fail', 'reclean_required'])
            ->count() : 0;

        return [
            'open_hazards' => $openHazards,
            'overdue_approvals' => $overdueApprovals,
            'documents_count' => $documentsCount,
            'qc_failing' => $qcFailing,
        ];
    }
}
