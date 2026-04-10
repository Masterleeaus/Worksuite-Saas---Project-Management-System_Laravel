<?php

namespace Modules\FSMServiceAgreement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMLocation;
use Modules\FSMCore\Models\FSMTemplate;
use Modules\FSMServiceAgreement\Models\FSMAgreementLine;
use Modules\FSMServiceAgreement\Models\FSMServiceAgreement;

class AgreementController extends Controller
{
    // ── List ─────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $q = FSMServiceAgreement::query()->with('client');

        if ($request->filled('state')) {
            $q->where('state', $request->string('state')->toString());
        }

        if ($request->filled('q')) {
            $term = trim((string) $request->get('q'));
            $q->where('name', 'like', "%{$term}%");
        }

        $agreements = $q->orderByDesc('id')->paginate(50)->withQueryString();
        $filter     = $request->only(['state', 'q']);

        // Agreements expiring within 30 days (active only)
        $expiringSoon = FSMServiceAgreement::where('state', FSMServiceAgreement::STATE_ACTIVE)
            ->whereNotNull('end_date')
            ->whereDate('end_date', '>=', now()->toDateString())
            ->whereDate('end_date', '<=', now()->addDays(30)->toDateString())
            ->count();

        return view('fsmserviceagreement::agreements.index', compact('agreements', 'filter', 'expiringSoon'));
    }

    // ── Create ────────────────────────────────────────────────────────────────

    public function create()
    {
        $locations = FSMLocation::where('active', true)->orderBy('name')->get();
        $templates = FSMTemplate::where('active', true)->orderBy('name')->get();

        return view('fsmserviceagreement::agreements.create', compact('locations', 'templates'));
    }

    public function store(Request $request)
    {
        $data = $this->validateAgreement($request);

        $last   = FSMServiceAgreement::max('id') ?? 0;
        $data['name'] = 'SRV-' . date('Y') . '-' . str_pad((int) $last + 1, 3, '0', STR_PAD_LEFT);

        $agreement = FSMServiceAgreement::create($data);

        $this->syncRelations($agreement, $request);
        $this->syncLines($agreement, $request);

        return redirect()->route('fsmserviceagreement.agreements.show', $agreement->id)
            ->with('success', 'Service agreement created successfully.');
    }

    // ── Show ──────────────────────────────────────────────────────────────────

    public function show(int $id)
    {
        $agreement = FSMServiceAgreement::with([
            'client', 'locations', 'templates', 'lines.location', 'orders.location', 'orders.stage',
        ])->findOrFail($id);

        return view('fsmserviceagreement::agreements.show', compact('agreement'));
    }

    // ── Edit ──────────────────────────────────────────────────────────────────

    public function edit(int $id)
    {
        $agreement = FSMServiceAgreement::with(['locations', 'templates', 'lines'])->findOrFail($id);
        $locations = FSMLocation::where('active', true)->orderBy('name')->get();
        $templates = FSMTemplate::where('active', true)->orderBy('name')->get();

        return view('fsmserviceagreement::agreements.edit', compact('agreement', 'locations', 'templates'));
    }

    public function update(Request $request, int $id)
    {
        $agreement = FSMServiceAgreement::findOrFail($id);
        $data      = $this->validateAgreement($request, $id);

        $agreement->update($data);
        $this->syncRelations($agreement, $request);
        $this->syncLines($agreement, $request);

        return redirect()->route('fsmserviceagreement.agreements.show', $agreement->id)
            ->with('success', 'Service agreement updated successfully.');
    }

    // ── Destroy ───────────────────────────────────────────────────────────────

    public function destroy(int $id)
    {
        $agreement = FSMServiceAgreement::findOrFail($id);
        $agreement->delete();

        return redirect()->route('fsmserviceagreement.agreements.index')
            ->with('success', 'Agreement deleted.');
    }

    // ── State Transitions ─────────────────────────────────────────────────────

    public function activate(int $id)
    {
        $agreement = FSMServiceAgreement::with(['locations', 'templates'])->findOrFail($id);

        if (! $agreement->isDraft()) {
            return redirect()->route('fsmserviceagreement.agreements.show', $id)
                ->with('error', 'Only draft agreements can be activated.');
        }

        $orders = $agreement->activate();

        return redirect()->route('fsmserviceagreement.agreements.show', $id)
            ->with('success', "Agreement activated. {$orders->count()} job order(s) generated.");
    }

    public function cancel(int $id)
    {
        $agreement = FSMServiceAgreement::findOrFail($id);

        if ($agreement->isCancelled()) {
            return redirect()->route('fsmserviceagreement.agreements.show', $id)
                ->with('error', 'Agreement is already cancelled.');
        }

        $agreement->cancel();

        return redirect()->route('fsmserviceagreement.agreements.show', $id)
            ->with('success', 'Agreement cancelled.');
    }

    // ── Private Helpers ───────────────────────────────────────────────────────

    private function validateAgreement(Request $request, ?int $excludeId = null): array
    {
        return $request->validate([
            'partner_id'      => 'nullable|integer',
            'start_date'      => 'required|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
            'state'           => 'nullable|in:draft,active,expired,cancelled',
            'recurrence_rule' => 'nullable|string|max:2048',
            'notes'           => 'nullable|string|max:65535',
            'value'           => 'nullable|numeric|min:0',
            'location_ids'    => 'nullable|array',
            'location_ids.*'  => 'integer|exists:fsm_locations,id',
            'template_ids'    => 'nullable|array',
            'template_ids.*'  => 'integer|exists:fsm_templates,id',
        ]);
    }

    private function syncRelations(FSMServiceAgreement $agreement, Request $request): void
    {
        $agreement->locations()->sync($request->input('location_ids', []));
        $agreement->templates()->sync($request->input('template_ids', []));
    }

    private function syncLines(FSMServiceAgreement $agreement, Request $request): void
    {
        // Delete existing lines and recreate from submitted data
        $agreement->lines()->delete();

        $descriptions = $request->input('line_service_description', []);
        $locationIds  = $request->input('line_location_id', []);
        $frequencies  = $request->input('line_frequency', []);
        $unitPrices   = $request->input('line_unit_price', []);

        foreach ($descriptions as $i => $desc) {
            if (trim((string) $desc) === '') {
                continue;
            }
            FSMAgreementLine::create([
                'agreement_id'        => $agreement->id,
                'location_id'         => $locationIds[$i] ?? null,
                'service_description' => $desc,
                'frequency'           => $frequencies[$i] ?? null,
                'unit_price'          => (float) ($unitPrices[$i] ?? 0),
                'sort_order'          => $i,
            ]);
        }
    }
}
