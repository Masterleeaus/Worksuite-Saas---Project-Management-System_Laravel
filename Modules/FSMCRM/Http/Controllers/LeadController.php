<?php

namespace Modules\FSMCRM\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadStatus;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMLocation;
use Modules\FSMCore\Models\FSMTemplate;

class LeadController extends Controller
{
    private const FSM_STAGE_ORDER = ['new', 'qualified', 'won', 'lost'];

    private const FSM_STAGE_COLORS = [
        'new' => 'secondary',
        'qualified' => 'info',
        'won' => 'success',
        'lost' => 'danger',
    ];

    public function index(Request $request)
    {
        $q = Lead::query()
            ->whereNotNull('fsm_location_id')
            ->with(['fsmLocation', 'fsmServiceType', 'leadStatus'])
            ->withCount('fsmOrders as orders_count');

        if ($request->filled('stage')) {
            $statusId = $this->findStageStatusId($request->string('stage')->toString());

            if ($statusId) {
                $q->where('status_id', $statusId);
            }
        }

        if ($request->filled('q')) {
            $term = trim((string) $request->get('q'));
            $q->where(function ($sub) use ($term) {
                $sub->where('company_name', 'like', "%{$term}%")
                    ->orWhere('client_name', 'like', "%{$term}%")
                    ->orWhere('client_email', 'like', "%{$term}%");
            });
        }

        $leads = $q->orderByDesc('id')->paginate(50)->withQueryString();
        $stages = $this->stageLabels();
        $stageColors = self::FSM_STAGE_COLORS;
        $filter = $request->only(['stage', 'q']);

        return view('fsmcrm::leads.index', compact('leads', 'stages', 'stageColors', 'filter'));
    }

    public function create()
    {
        $stages = $this->stageLabels();
        $locations = FSMLocation::where('active', true)->orderBy('name')->get();
        $templates = FSMTemplate::where('active', true)->orderBy('name')->get();

        return view('fsmcrm::leads.create', compact('stages', 'locations', 'templates'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_name'        => 'required|string|max:255',
            'client_name'         => 'required|string|max:255',
            'client_email'        => 'nullable|email|max:128',
            'mobile'              => 'nullable|string|max:64',
            'note'                => 'nullable|string|max:65535',
            'stage'               => 'required|string|in:new,qualified,won,lost',
            'expected_revenue'    => 'nullable|numeric|min:0',
            'fsm_location_id'     => 'nullable|integer|exists:fsm_locations,id',
            'fsm_service_type_id' => 'nullable|integer|exists:fsm_templates,id',
            'site_count'          => 'nullable|integer|min:1',
            'estimated_hours'     => 'nullable|numeric|min:0',
            'create_recurring'    => 'nullable|boolean',
        ]);

        $lead = new Lead();
        $lead->company_name = $data['company_name'];
        $lead->client_name = $data['client_name'];
        $lead->client_email = $data['client_email'] ?? null;
        $lead->mobile = $data['mobile'] ?? null;
        $lead->note = $data['note'] ?? null;
        $lead->status_id = $this->resolveStageStatusId($data['stage']);
        $lead->value = $data['expected_revenue'] ?? 0;
        $lead->fsm_location_id = $data['fsm_location_id'] ?? null;
        $lead->fsm_service_type_id = $data['fsm_service_type_id'] ?? null;
        $lead->site_count = $data['site_count'] ?? null;
        $lead->estimated_hours = $data['estimated_hours'] ?? null;
        $lead->create_recurring = $request->boolean('create_recurring');
        $lead->column_priority = ((int) Lead::max('column_priority')) + 1;
        $lead->save();

        return redirect()->route('fsmcrm.leads.index')
            ->with('success', 'Lead created.');
    }

    public function show(int $id)
    {
        $lead = Lead::whereNotNull('fsm_location_id')
            ->with(['fsmLocation', 'fsmServiceType', 'leadStatus', 'fsmOrders.stage', 'fsmOrders.location'])
            ->findOrFail($id);
        $orders = $lead->fsmOrders()->with(['stage', 'location'])->get();
        $stage = strtolower((string) $lead->leadStatus?->type);
        $stageColors = self::FSM_STAGE_COLORS;
        $stageLabel = $this->stageLabels()[$stage] ?? ($lead->leadStatus?->type ?? '—');

        return view('fsmcrm::leads.show', compact('lead', 'orders', 'stage', 'stageColors', 'stageLabel'));
    }

    public function edit(int $id)
    {
        $lead = Lead::whereNotNull('fsm_location_id')->with('leadStatus')->findOrFail($id);
        $stages = $this->stageLabels();
        $locations = FSMLocation::where('active', true)->orderBy('name')->get();
        $templates = FSMTemplate::where('active', true)->orderBy('name')->get();

        return view('fsmcrm::leads.edit', compact('lead', 'stages', 'locations', 'templates'));
    }

    public function update(Request $request, int $id)
    {
        $lead = Lead::whereNotNull('fsm_location_id')->findOrFail($id);
        $data = $request->validate([
            'company_name'        => 'required|string|max:255',
            'client_name'         => 'required|string|max:255',
            'client_email'        => 'nullable|email|max:128',
            'mobile'              => 'nullable|string|max:64',
            'note'                => 'nullable|string|max:65535',
            'stage'               => 'required|string|in:new,qualified,won,lost',
            'expected_revenue'    => 'nullable|numeric|min:0',
            'fsm_location_id'     => 'nullable|integer|exists:fsm_locations,id',
            'fsm_service_type_id' => 'nullable|integer|exists:fsm_templates,id',
            'site_count'          => 'nullable|integer|min:1',
            'estimated_hours'     => 'nullable|numeric|min:0',
            'create_recurring'    => 'nullable|boolean',
        ]);

        $lead->company_name = $data['company_name'];
        $lead->client_name = $data['client_name'];
        $lead->client_email = $data['client_email'] ?? null;
        $lead->mobile = $data['mobile'] ?? null;
        $lead->note = $data['note'] ?? null;
        $lead->status_id = $this->resolveStageStatusId($data['stage']);
        $lead->value = $data['expected_revenue'] ?? 0;
        $lead->fsm_location_id = $data['fsm_location_id'] ?? null;
        $lead->fsm_service_type_id = $data['fsm_service_type_id'] ?? null;
        $lead->site_count = $data['site_count'] ?? null;
        $lead->estimated_hours = $data['estimated_hours'] ?? null;
        $lead->create_recurring = $request->boolean('create_recurring');
        $lead->save();

        return redirect()->route('fsmcrm.leads.show', $lead->id)
            ->with('success', 'Lead updated.');
    }

    public function destroy(int $id)
    {
        Lead::whereNotNull('fsm_location_id')->findOrFail($id)->delete();

        return redirect()->route('fsmcrm.leads.index')
            ->with('success', 'Lead deleted.');
    }

    private function stageLabels(): array
    {
        return collect(self::FSM_STAGE_ORDER)
            ->mapWithKeys(fn(string $stage) => [$stage => ucfirst($stage)])
            ->all();
    }

    private function resolveStageStatusId(string $stage): int
    {
        $normalizedStage = strtolower(trim($stage));
        $status = LeadStatus::firstOrCreate(
            ['type' => $normalizedStage],
            ['priority' => 0, 'default' => 0, 'label_color' => '#' . ltrim($this->stageColorHex($normalizedStage), '#')]
        );

        return (int) $status->id;
    }

    private function stageColorHex(string $stage): string
    {
        return match ($stage) {
            'qualified' => '0dcaf0',
            'won' => '198754',
            'lost' => 'dc3545',
            default => '6c757d',
        };
    }

    private function findStageStatusId(string $stage): ?int
    {
        $normalizedStage = strtolower(trim($stage));
        $status = LeadStatus::where('type', $normalizedStage)->first();

        return $status ? (int) $status->id : null;
    }
}
