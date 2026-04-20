<?php
namespace Modules\ManagedPremises\Http\Controllers;


use Modules\ManagedPremises\Http\Controllers\Concerns\EnsuresManagedPremisesPermissions;
use App\Http\Controllers\AccountBaseController;
use Modules\ManagedPremises\Entities\Property;
use Modules\ManagedPremises\Application\Queries\GetPremiseComplianceOverviewQuery;
use Modules\ManagedPremises\Application\Queries\GetPremiseServiceReadinessQuery;
use Modules\ManagedPremises\Application\Queries\GetPremiseTimelineQuery;

class PropertyOverviewController extends AccountBaseController
{
    
    use EnsuresManagedPremisesPermissions;
    public function show(
        Property $property,
        GetPremiseComplianceOverviewQuery $complianceQuery,
        GetPremiseServiceReadinessQuery $readinessQuery,
        GetPremiseTimelineQuery $timelineQuery
    )
    {
        $this->ensureCanViewManagedPremises();
        $this->authorize('view', $property);
        $compliance = $complianceQuery->handle($property);
        $readiness = $readinessQuery->handle($property);
        $timeline = $timelineQuery->handle($property, 15);

        return view('managedpremises::properties.overview', compact('property', 'compliance', 'readiness', 'timeline'));
    }
}
