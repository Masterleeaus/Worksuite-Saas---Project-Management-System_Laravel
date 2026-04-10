<?php

namespace Modules\FSMCore\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMStage;
use Modules\FSMCore\Models\FSMLocation;
use Modules\FSMCore\Models\FSMTeam;
use Modules\FSMCore\Models\FSMTemplate;
use Modules\FSMCore\Models\FSMTag;
use Modules\FSMCore\Models\FSMEquipment;

class OrderController extends Controller
{
    private function getVehicles(): \Illuminate\Support\Collection
    {
        if (class_exists(\Modules\FSMVehicle\Models\FSMVehicle::class)
            && \Illuminate\Support\Facades\Schema::hasTable('fsm_vehicles')
        ) {
            return \Modules\FSMVehicle\Models\FSMVehicle::where('active', true)->orderBy('name')->get();
        }
        return collect();
    }

    private function vehicleValidationRule(): string
    {
        if (class_exists(\Modules\FSMVehicle\Models\FSMVehicle::class)
            && \Illuminate\Support\Facades\Schema::hasTable('fsm_vehicles')
        ) {
            return 'nullable|integer|exists:fsm_vehicles,id';
        }
        return 'nullable|integer';
    }

    private function getSizes(): \Illuminate\Support\Collection
    {
        if (class_exists(\Modules\FSMWorkflow\Models\FSMSize::class)
            && \Illuminate\Support\Facades\Schema::hasTable('fsm_sizes')
        ) {
            return \Modules\FSMWorkflow\Models\FSMSize::active()->orderBy('sequence')->get();
        }
        return collect();
    }
    public function index(Request $request)
    {
        $q = FSMOrder::query()->with(['location', 'person', 'team', 'stage']);

        if ($request->filled('stage_id')) {
            $q->where('stage_id', (int) $request->get('stage_id'));
        }

        if ($request->filled('team_id')) {
            $q->where('team_id', (int) $request->get('team_id'));
        }

        if ($request->filled('priority')) {
            $q->where('priority', $request->string('priority')->toString());
        }

        if ($request->filled('q')) {
            $term = trim((string) $request->get('q'));
            $q->where(function ($sub) use ($term) {
                $sub->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        }

        $orders = $q->orderByDesc('id')->paginate(50)->withQueryString();
        $stages = FSMStage::orderBy('sequence')->get();
        $teams = FSMTeam::where('active', true)->get();
        $filter = $request->only(['stage_id', 'team_id', 'priority', 'q']);

        return view('fsmcore::orders.index', compact('orders', 'stages', 'teams', 'filter'));
    }

    public function kanban()
    {
        $stages = FSMStage::orderBy('sequence')
            ->with(['orders' => function ($q) {
                $q->with(['location', 'person', 'team'])->orderBy('id');
            }])
            ->get();

        return view('fsmcore::orders.kanban', compact('stages'));
    }

    public function create()
    {
        $stages = FSMStage::orderBy('sequence')->get();
        $locations = FSMLocation::where('active', true)->orderBy('name')->get();
        $teams = FSMTeam::where('active', true)->orderBy('name')->get();
        $templates = FSMTemplate::where('active', true)->orderBy('name')->get();
        $tags = FSMTag::orderBy('name')->get();
        $equipment = FSMEquipment::where('active', true)->orderBy('name')->get();
        $vehicles = $this->getVehicles();
        $sizes = $this->getSizes();

        return view('fsmcore::orders.create', compact('stages', 'locations', 'teams', 'templates', 'tags', 'equipment', 'vehicles', 'sizes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'location_id'          => 'nullable|integer|exists:fsm_locations,id',
            'person_id'            => 'nullable|integer',
            'vehicle_id'           => $this->vehicleValidationRule(),
            'team_id'              => 'nullable|integer|exists:fsm_teams,id',
            'stage_id'             => 'nullable|integer|exists:fsm_stages,id',
            'template_id'          => 'nullable|integer|exists:fsm_templates,id',
            'priority'             => 'nullable|in:0,1',
            'color'                => 'nullable|integer',
            'scheduled_date_start' => 'nullable|date',
            'scheduled_date_end'   => 'nullable|date',
            'description'          => 'nullable|string|max:65535',
            'equipment_ids'        => 'nullable|array',
            'equipment_ids.*'      => 'integer|exists:fsm_equipment,id',
            'tag_ids'              => 'nullable|array',
            'tag_ids.*'            => 'integer|exists:fsm_tags,id',
        ]);

        // FSMSales billing fields (optional, only when module is installed)
        if (class_exists(\Modules\FSMSales\Models\FSMSalesInvoice::class)
            && \Illuminate\Support\Facades\Schema::hasColumn('fsm_orders', 'billing_policy')
        ) {
            $billingData = $request->validate([
                'billing_policy' => 'nullable|string|in:manual,on_completion,on_timesheet',
                'billing_amount' => 'nullable|numeric|min:0',
                'hourly_rate'    => 'nullable|numeric|min:0',
            ]);
            $data = array_merge($data, array_filter($billingData, fn($v) => $v !== null));
        }

        // FSMWorkflow size fields (optional, only when module is installed)
        if (class_exists(\Modules\FSMWorkflow\Models\FSMSize::class)
            && \Illuminate\Support\Facades\Schema::hasColumn('fsm_orders', 'size_id')
        ) {
            $sizeData = $request->validate([
                'size_id'       => 'nullable|integer|exists:fsm_sizes,id',
                'estimated_sqm' => 'nullable|integer|min:0',
                'room_count'    => 'nullable|integer|min:0',
            ]);
            $data = array_merge($data, array_filter($sizeData, fn($v) => $v !== null));
        }
        $last = FSMOrder::max('id') ?? 0;
        $prefix = config('fsmcore.order_reference_prefix', 'ORD');
        $data['name'] = $prefix . '-' . str_pad((int) $last + 1, 5, '0', STR_PAD_LEFT);

        $order = FSMOrder::create($data);

        if (!empty($data['equipment_ids'])) {
            $order->equipment()->sync($data['equipment_ids']);
        }

        if (!empty($data['tag_ids'])) {
            $order->tags()->sync($data['tag_ids']);
        }

        $redirect = redirect()->route('fsmcore.orders.show', $order->id)
            ->with('success', 'Order created successfully.');

        // Availability warning (FSMAvailability module optional integration)
        $availWarning = $this->getAvailabilityWarning($data['person_id'] ?? null, $order);
        if ($availWarning) {
            $redirect = $redirect->with('availability_warning', $availWarning);
        }

        // Skill match warning (FSMSkill module optional integration)
        $skillWarning = $this->getSkillMatchWarning($data['person_id'] ?? null, $order->id);
        if ($skillWarning) {
            $redirect = $redirect->with('skill_warning', $skillWarning);
        }

        return $redirect;
    }

    public function show(int $id)
    {
        $relations = ['location', 'person', 'team', 'stage', 'template', 'equipment', 'tags'];

        // Conditionally eager-load source lead if FSMCRM is installed
        if (class_exists(\Modules\FSMCRM\Models\FSMLead::class)
            && \Illuminate\Support\Facades\Schema::hasTable('fsm_leads')
        ) {
            $relations[] = 'lead';
        }

        $order = FSMOrder::with($relations)->findOrFail($id);
        return view('fsmcore::orders.show', compact('order'));
    }

    public function edit(int $id)
    {
        $order = FSMOrder::with(['equipment', 'tags'])->findOrFail($id);
        $stages = FSMStage::orderBy('sequence')->get();
        $locations = FSMLocation::where('active', true)->orderBy('name')->get();
        $teams = FSMTeam::where('active', true)->orderBy('name')->get();
        $templates = FSMTemplate::where('active', true)->orderBy('name')->get();
        $tags = FSMTag::orderBy('name')->get();
        $equipment = FSMEquipment::where('active', true)->orderBy('name')->get();
        $vehicles = $this->getVehicles();
        $sizes = $this->getSizes();

        return view('fsmcore::orders.edit', compact('order', 'stages', 'locations', 'teams', 'templates', 'tags', 'equipment', 'vehicles', 'sizes'));
    }

    public function update(Request $request, int $id)
    {
        $order = FSMOrder::findOrFail($id);

        $data = $request->validate([
            'location_id'          => 'nullable|integer|exists:fsm_locations,id',
            'person_id'            => 'nullable|integer',
            'vehicle_id'           => $this->vehicleValidationRule(),
            'team_id'              => 'nullable|integer|exists:fsm_teams,id',
            'stage_id'             => 'nullable|integer|exists:fsm_stages,id',
            'template_id'          => 'nullable|integer|exists:fsm_templates,id',
            'priority'             => 'nullable|in:0,1',
            'color'                => 'nullable|integer',
            'scheduled_date_start' => 'nullable|date',
            'scheduled_date_end'   => 'nullable|date',
            'date_start'           => 'nullable|date',
            'date_end'             => 'nullable|date',
            'description'          => 'nullable|string|max:65535',
            'equipment_ids'        => 'nullable|array',
            'equipment_ids.*'      => 'integer|exists:fsm_equipment,id',
            'tag_ids'              => 'nullable|array',
            'tag_ids.*'            => 'integer|exists:fsm_tags,id',
        ]);

        // FSMSales billing fields (optional, only when module is installed)
        if (class_exists(\Modules\FSMSales\Models\FSMSalesInvoice::class)
            && \Illuminate\Support\Facades\Schema::hasColumn('fsm_orders', 'billing_policy')
        ) {
            $billingData = $request->validate([
                'billing_policy' => 'nullable|string|in:manual,on_completion,on_timesheet',
                'billing_amount' => 'nullable|numeric|min:0',
                'hourly_rate'    => 'nullable|numeric|min:0',
            ]);
            $data = array_merge($data, array_filter($billingData, fn($v) => $v !== null));
        }

        // FSMWorkflow size fields (optional, only when module is installed)
        if (class_exists(\Modules\FSMWorkflow\Models\FSMSize::class)
            && \Illuminate\Support\Facades\Schema::hasColumn('fsm_orders', 'size_id')
        ) {
            $sizeData = $request->validate([
                'size_id'       => 'nullable|integer|exists:fsm_sizes,id',
                'estimated_sqm' => 'nullable|integer|min:0',
                'room_count'    => 'nullable|integer|min:0',
            ]);
            $data = array_merge($data, array_filter($sizeData, fn($v) => $v !== null));
        }
        $order->equipment()->sync($data['equipment_ids'] ?? []);
        $order->tags()->sync($data['tag_ids'] ?? []);

        $redirect = redirect()->route('fsmcore.orders.show', $order->id)
            ->with('success', 'Order updated successfully.');

        // Availability warning (FSMAvailability module optional integration)
        $availWarning = $this->getAvailabilityWarning($data['person_id'] ?? null, $order);
        if ($availWarning) {
            $redirect = $redirect->with('availability_warning', $availWarning);
        }

        // Skill match warning (FSMSkill module optional integration)
        $skillWarning = $this->getSkillMatchWarning($data['person_id'] ?? null, $order->id);
        if ($skillWarning) {
            $redirect = $redirect->with('skill_warning', $skillWarning);
        }

        return $redirect;
    }

    public function destroy(int $id)
    {
        $order = FSMOrder::findOrFail($id);
        $order->delete();

        return redirect()->route('fsmcore.orders.index')
            ->with('success', 'Order deleted.');
    }

    public function updateStage(Request $request, int $id)
    {
        $order = FSMOrder::findOrFail($id);
        $order->stage_id = (int) $request->get('stage_id');
        $order->save();

        return response()->json(['ok' => true]);
    }

    /**
     * Check whether the assigned worker is available during the order's scheduled window.
     * Returns a warning message string when FSMAvailability is installed and there are issues,
     * or null when the module is not present / no issues found.
     */
    private function getAvailabilityWarning(?int $userId, FSMOrder $order): ?string
    {
        if ($userId === null) {
            return null;
        }

        if (!class_exists(\Modules\FSMAvailability\Services\AvailabilityService::class)) {
            return null;
        }

        if (!\Illuminate\Support\Facades\Schema::hasTable('fsm_availability_exceptions')) {
            return null;
        }

        return app(\Modules\FSMAvailability\Services\AvailabilityService::class)
            ->getOrderWarning(
                $userId,
                $order->scheduled_date_start ? \Carbon\Carbon::parse($order->scheduled_date_start) : null,
                $order->scheduled_date_end   ? \Carbon\Carbon::parse($order->scheduled_date_end)   : null
            );
    }

    /**
     * Check whether the assigned worker meets all skill requirements for the order.
     * Returns a warning message string when FSMSkill is installed and there are issues,
     * or null when the module is not present / no issues found.
     */
    private function getSkillMatchWarning(?int $userId, int $orderId): ?string
    {
        if ($userId === null) {
            return null;
        }

        if (!class_exists(\Modules\FSMSkill\Services\SkillMatchService::class)) {
            return null;
        }

        if (!\Illuminate\Support\Facades\Schema::hasTable('fsm_order_skill_requirements')) {
            return null;
        }

        $result = app(\Modules\FSMSkill\Services\SkillMatchService::class)
            ->checkOrderMatch($userId, $orderId);

        if (!$result['match']) {
            return 'Skill mismatch: ' . implode('; ', $result['issues']);
        }

        if (!empty($result['warnings'])) {
            return 'Skill notice: ' . implode('; ', $result['warnings']);
        }

        return null;
    }
}
