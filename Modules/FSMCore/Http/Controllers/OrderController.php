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

        return view('fsmcore::orders.create', compact('stages', 'locations', 'teams', 'templates', 'tags', 'equipment'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'location_id'          => 'nullable|integer|exists:fsm_locations,id',
            'person_id'            => 'nullable|integer',
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

        // Auto-generate order reference
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

        return redirect()->route('fsmcore.orders.show', $order->id)
            ->with('success', 'Order created successfully.');
    }

    public function show(int $id)
    {
        $order = FSMOrder::with(['location', 'person', 'team', 'stage', 'template', 'equipment', 'tags'])->findOrFail($id);
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

        return view('fsmcore::orders.edit', compact('order', 'stages', 'locations', 'teams', 'templates', 'tags', 'equipment'));
    }

    public function update(Request $request, int $id)
    {
        $order = FSMOrder::findOrFail($id);

        $data = $request->validate([
            'location_id'          => 'nullable|integer|exists:fsm_locations,id',
            'person_id'            => 'nullable|integer',
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

        $order->update($data);
        $order->equipment()->sync($data['equipment_ids'] ?? []);
        $order->tags()->sync($data['tag_ids'] ?? []);

        return redirect()->route('fsmcore.orders.show', $order->id)
            ->with('success', 'Order updated successfully.');
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
}
