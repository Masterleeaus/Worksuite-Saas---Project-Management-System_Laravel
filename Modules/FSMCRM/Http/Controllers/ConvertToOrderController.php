<?php

namespace Modules\FSMCRM\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;
use Modules\FSMCRM\Models\FSMLead;
use Modules\FSMCore\Models\FSMLocation;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMStage;
use Modules\FSMCore\Models\FSMTemplate;

class ConvertToOrderController extends Controller
{
    /**
     * Show pre-filled FSM Order creation form for a Won lead.
     */
    public function create(int $leadId)
    {
        $lead = FSMLead::with(['fsmLocation', 'serviceType'])->findOrFail($leadId);

        if (! $lead->isWon()) {
            return redirect()->route('fsmcrm.leads.show', $leadId)
                ->with('error', 'Only Won leads can be converted to FSM Orders.');
        }

        $stages    = FSMStage::orderBy('sequence')->get();
        $locations = FSMLocation::where('active', true)->orderBy('name')->get();
        $templates = FSMTemplate::where('active', true)->orderBy('name')->get();

        // Check if FSMServiceAgreement is available for recurring path
        $canCreateAgreement = class_exists(\Modules\FSMServiceAgreement\Models\FSMServiceAgreement::class)
            && Schema::hasTable('fsm_service_agreements');

        return view('fsmcrm::convert.create', compact(
            'lead', 'stages', 'locations', 'templates', 'canCreateAgreement'
        ));
    }

    /**
     * Create an FSM Order from the Won lead.
     */
    public function store(Request $request, int $leadId)
    {
        $lead = FSMLead::findOrFail($leadId);

        if (! $lead->isWon()) {
            return redirect()->route('fsmcrm.leads.show', $leadId)
                ->with('error', 'Only Won leads can be converted to FSM Orders.');
        }

        $data = $request->validate([
            'location_id'          => 'nullable|integer|exists:fsm_locations,id',
            'stage_id'             => 'nullable|integer|exists:fsm_stages,id',
            'template_id'          => 'nullable|integer|exists:fsm_templates,id',
            'scheduled_date_start' => 'nullable|date',
            'scheduled_date_end'   => 'nullable|date|after_or_equal:scheduled_date_start',
            'description'          => 'nullable|string|max:65535',
            'priority'             => 'nullable|string|in:0,1',
            'create_agreement'     => 'nullable|boolean',
        ]);

        // Auto-resolve location from lead if not overridden
        if (empty($data['location_id']) && $lead->fsm_location_id) {
            $data['location_id'] = $lead->fsm_location_id;
        }

        // Auto-resolve template from lead's service type if not overridden
        if (empty($data['template_id']) && $lead->service_type_id) {
            $data['template_id'] = $lead->service_type_id;
        }

        // Default description from lead notes
        if (empty($data['description']) && $lead->notes) {
            $data['description'] = $lead->notes;
        }

        // Generate order reference
        $data['name']    = $this->nextOrderReference();
        $data['lead_id'] = $lead->id;

        $order = FSMOrder::create($data);

        // If "create recurring agreement" requested and FSMServiceAgreement is available
        if ($request->boolean('create_agreement')
            && class_exists(\Modules\FSMServiceAgreement\Models\FSMServiceAgreement::class)
            && Schema::hasTable('fsm_service_agreements')
        ) {
            return redirect()->route('fsmserviceagreement.agreements.create', [
                'lead_id'     => $lead->id,
                'location_id' => $order->location_id,
                'template_id' => $order->template_id,
                'name'        => $lead->name,
            ])->with('info', 'FSM Order created. Now create the recurring service agreement.');
        }

        return redirect()->route('fsmcore.orders.show', $order->id)
            ->with('success', "FSM Order {$order->name} created from lead '{$lead->name}'.");
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function nextOrderReference(): string
    {
        $last = FSMOrder::orderByDesc('id')->value('name');

        if ($last && preg_match('/ORD-(\d+)$/', $last, $m)) {
            $next = (int) $m[1] + 1;
        } else {
            $next = FSMOrder::count() + 1;
        }

        return 'ORD-' . str_pad((string) $next, 5, '0', STR_PAD_LEFT);
    }
}
