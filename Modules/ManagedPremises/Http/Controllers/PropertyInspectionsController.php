<?php
namespace Modules\ManagedPremises\Http\Controllers;

/**
 * @deprecated Inspection CRUD ownership has moved to QualityControl.
 *             This controller is a compatibility bridge only. All mutating actions
 *             redirect to QualityControl routes. Do not add new write logic here.
 *             Canonical controller: Modules\QualityControl\Http\Controllers\QcRecordController
 */

use Modules\ManagedPremises\Http\Controllers\Concerns\EnsuresManagedPremisesPermissions;
use Illuminate\Routing\Controller;
use Modules\ManagedPremises\Entities\Property;
use Modules\ManagedPremises\Entities\PropertyInspection;
use Modules\ManagedPremises\Http\Requests\StorePropertyInspectionRequest;
use Modules\ManagedPremises\Http\Requests\UpdatePropertyInspectionRequest;
use Modules\QualityControl\Entities\QcRecord;
use Modules\QualityControl\Services\ExecutionRecordService;

class PropertyInspectionsController extends Controller
{
    
    use EnsuresManagedPremisesPermissions;
public function index(Property $property)
    {
        $this->ensureCanViewManagedPremises();
        $this->authorize('view', $property);
        return redirect()
            ->route('qc-records.index', ['property_id' => $property->id])
            ->with('warning', __('Managed Premises inspections are deprecated. You were redirected to Quality Control.'));
    }

    public function create(Property $property)
    {
        $this->ensureCanViewManagedPremises();
        $this->authorize('update', $property);
        return redirect()
            ->route('qc-records.create', ['property_id' => $property->id])
            ->with('warning', __('Managed Premises inspections are now created in Quality Control.'));
    }

    public function store(StorePropertyInspectionRequest $request, Property $property)
    {
        $this->ensureCanViewManagedPremises();
        $this->authorize('update', $property);
        return redirect()
            ->route('qc-records.create', ['property_id' => $property->id])
            ->with('warning', __('Managed Premises inspections are now created in Quality Control.'));
    }

    public function edit(Property $property, PropertyInspection $inspection)
    {
        $this->ensureCanViewManagedPremises();
        $this->authorize('update', $property);
        abort_unless($inspection->property_id === $property->id, 404);

        $record = $this->resolveLegacyQcRecord($inspection->id);

        if ($record) {
            return redirect()
                ->route('qc-records.edit', $record->id)
                ->with('warning', __('This inspection is managed by Quality Control.'));
        }

        return redirect()
            ->route('qc-records.index', ['property_id' => $property->id])
            ->with('warning', __('This inspection is managed by Quality Control.'));
    }

    public function update(UpdatePropertyInspectionRequest $request, Property $property, PropertyInspection $inspection)
    {
        $this->ensureCanViewManagedPremises();
        $this->authorize('update', $property);
        abort_unless($inspection->property_id === $property->id, 404);

        $record = $this->resolveLegacyQcRecord($inspection->id);

        if ($record) {
            return redirect()
                ->route('qc-records.edit', $record->id)
                ->with('warning', __('This inspection is managed by Quality Control.'));
        }

        return redirect()
            ->route('qc-records.index', ['property_id' => $property->id])
            ->with('warning', __('This inspection is managed by Quality Control.'));
    }

    public function destroy(Property $property, PropertyInspection $inspection)
    {
        $this->ensureCanViewManagedPremises();
        $this->authorize('update', $property);
        abort_unless($inspection->property_id === $property->id, 404);

        $record = $this->resolveLegacyQcRecord($inspection->id);

        if ($record) {
            return redirect()
                ->route('qc-records.show', $record->id)
                ->with('warning', __('Delete this inspection from Quality Control.'));
        }

        return redirect()
            ->route('qc-records.index', ['property_id' => $property->id])
            ->with('warning', __('Delete this inspection from Quality Control.'));
    }

    private function resolveLegacyQcRecord(int $legacyInspectionId): ?QcRecord
    {
        if (!class_exists(ExecutionRecordService::class)) {
            return null;
        }

        try {
            $companyId = function_exists('company_id') ? company_id() : null;

            return app(ExecutionRecordService::class)->findByLegacyInspectionIdOrId($legacyInspectionId, $companyId);
        } catch (\Throwable) {
            return null;
        }
    }
}
