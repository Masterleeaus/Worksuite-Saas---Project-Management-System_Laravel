<?php

namespace Modules\Inspection\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\QualityControl\Services\ExecutionRecordService;
use Modules\QualityControl\Support\InspectionPermissions;
use Modules\QualityControl\Support\ModuleAccess;

/**
 * @deprecated Compatibility bridge. Canonical inspection-run ownership lives in QualityControl.
 */
class InspectionController extends AccountBaseController
{
    public function __construct(private readonly ExecutionRecordService $records)
    {
        parent::__construct();
        $this->pageTitle = 'Inspections';
        $this->middleware(function ($request, $next) {
            abort_403(!ModuleAccess::userHasModule($this->user));

            return $next($request);
        });
    }

    public function index()
    {
        abort_403(!ModuleAccess::can(InspectionPermissions::VIEW, ['all'], $this->user));

        return redirect()->route('qc-records.index');
    }

    public function create()
    {
        abort_403(!ModuleAccess::can(InspectionPermissions::CREATE, ['all'], $this->user));

        return redirect()->route('qc-records.create');
    }

    public function store(Request $request)
    {
        abort_403(!ModuleAccess::can(InspectionPermissions::CREATE, ['all'], $this->user));

        $validated = $request->validate([
            'booking_id' => 'nullable',
            'inspector_id' => 'nullable|integer|min:1',
            'template_id' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'inspected_at' => 'nullable|date',
            'items' => 'nullable|array',
            'items.*.area' => 'required|string|max:191',
            'items.*.passed' => 'required|boolean',
            'items.*.notes' => 'nullable|string',
        ]);

        $payload = [
            'booking_id' => isset($validated['booking_id']) ? (string) $validated['booking_id'] : null,
            'cleaner_id' => $validated['inspector_id'] ?? null,
            'template_id' => $validated['template_id'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'inspected_at' => $validated['inspected_at'] ?? null,
            'items' => array_map(function ($item) {
                return [
                    'item_label' => $item['area'],
                    'score' => !empty($item['passed']) ? 100 : 0,
                    'weight' => 0,
                    'notes' => $item['notes'] ?? null,
                ];
            }, $validated['items'] ?? []),
        ];

        $record = $this->records->create($payload, $this->user);

        return redirect()
            ->route('inspections.show', $record->id)
            ->with('success', __('inspection::messages.inspection_created'));
    }

    public function show($id)
    {
        abort_403(!ModuleAccess::can(InspectionPermissions::VIEW, ['all'], $this->user));

        $record = $this->records->findByLegacyInspectionIdOrId((int) $id, $this->user->company_id ?? null);
        abort_if(!$record, 404);

        return redirect()->route('qc-records.show', $record->id);
    }

    public function edit($id)
    {
        abort_403(!ModuleAccess::can(InspectionPermissions::UPDATE, ['all'], $this->user));

        $record = $this->records->findByLegacyInspectionIdOrId((int) $id, $this->user->company_id ?? null);
        abort_if(!$record, 404);

        return redirect()->route('qc-records.edit', $record->id);
    }

    public function update(Request $request, $id)
    {
        abort_403(!ModuleAccess::can(InspectionPermissions::UPDATE, ['all'], $this->user));

        $record = $this->records->findByLegacyInspectionIdOrId((int) $id, $this->user->company_id ?? null);
        abort_if(!$record, 404);

        $validated = $request->validate([
            'booking_id' => 'nullable',
            'inspector_id' => 'nullable|integer|min:1',
            'template_id' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'inspected_at' => 'nullable|date',
            'items' => 'nullable|array',
            'items.*.area' => 'required|string|max:191',
            'items.*.passed' => 'required|boolean',
            'items.*.notes' => 'nullable|string',
        ]);

        $payload = [
            'booking_id' => isset($validated['booking_id']) ? (string) $validated['booking_id'] : null,
            'cleaner_id' => $validated['inspector_id'] ?? null,
            'template_id' => $validated['template_id'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'inspected_at' => $validated['inspected_at'] ?? null,
            'items' => array_map(function ($item) {
                return [
                    'item_label' => $item['area'],
                    'score' => !empty($item['passed']) ? 100 : 0,
                    'weight' => 0,
                    'notes' => $item['notes'] ?? null,
                ];
            }, $validated['items'] ?? []),
        ];

        $updated = $this->records->update((int) $record->id, $payload, $this->user->company_id ?? null);

        return redirect()
            ->route('inspections.show', $updated->id)
            ->with('success', __('inspection::messages.inspection_updated'));
    }

    public function destroy($id)
    {
        abort_403(!ModuleAccess::can(InspectionPermissions::DELETE, ['all'], $this->user));

        $record = $this->records->findByLegacyInspectionIdOrId((int) $id, $this->user->company_id ?? null);
        abort_if(!$record, 404);
        $this->records->delete((int) $record->id, $this->user->company_id ?? null);

        return Reply::success(__('inspection::messages.inspection_deleted'));
    }

    public function approve(Request $request, $id)
    {
        abort_403(!ModuleAccess::can(InspectionPermissions::UPDATE, ['all'], $this->user));

        $record = $this->records->findByLegacyInspectionIdOrId((int) $id, $this->user->company_id ?? null);
        abort_if(!$record, 404);
        $this->records->markApproved((int) $record->id, $this->user->company_id ?? null);

        return Reply::success(__('inspection::messages.inspection_approved'));
    }

    public function requestReclean(Request $request, $id)
    {
        abort_403(!ModuleAccess::can(InspectionPermissions::TRIGGER_RECLEAN, ['all'], $this->user));

        $record = $this->records->findByLegacyInspectionIdOrId((int) $id, $this->user->company_id ?? null);
        abort_if(!$record, 404);
        $this->records->markNeedsReclean((int) $record->id, $this->user->company_id ?? null);

        return Reply::success(__('inspection::messages.reclean_requested'));
    }
}

