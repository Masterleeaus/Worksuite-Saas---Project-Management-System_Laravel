<?php

namespace Modules\Communication\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Communication\app\Models\CommunicationAutomation;
use Modules\Communication\app\Models\CommunicationTemplate;

class AutomationController extends Controller
{
    /**
     * List all automation rules for the current company.
     */
    public function index(): View
    {
        $companyId = user()->company_id ?? null;

        $automations = CommunicationAutomation::with('template')
            ->when($companyId, fn ($q) => $q->forCompany($companyId))
            ->orderBy('name')
            ->paginate(25);

        return view('communication::automations.index', [
            'automations'   => $automations,
            'triggerEvents' => CommunicationAutomation::triggerEventLabels(),
        ]);
    }

    /**
     * Show the create automation form.
     */
    public function create(): View
    {
        $companyId = user()->company_id ?? null;

        $templates = CommunicationTemplate::when($companyId, fn ($q) => $q->forCompany($companyId))
            ->active()
            ->orderBy('name')
            ->get();

        return view('communication::automations.create', [
            'templates'      => $templates,
            'triggerEvents'  => CommunicationAutomation::triggerEventLabels(),
            'recipientTypes' => CommunicationAutomation::recipientTypeLabels(),
        ]);
    }

    /**
     * Store a new automation rule.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'trigger_event'  => 'required|string|max:100',
            'template_id'    => 'nullable|integer|exists:communication_templates,id',
            'delay_minutes'  => 'nullable|integer|min:0',
            'channel'        => 'nullable|in:email,sms,chat,push',
            'recipient_type' => 'required|in:customer,cleaner,admin,custom_email',
        ]);

        CommunicationAutomation::create(array_merge($validated, [
            'company_id'    => user()->company_id ?? null,
            'delay_minutes' => $validated['delay_minutes'] ?? 0,
            'status'        => 'active',
        ]));

        return redirect()->route('communications.automations.index')
            ->with('success', 'Automation rule created successfully.');
    }

    /**
     * Show the edit form.
     */
    public function edit(int $id): View
    {
        $automation = $this->findForCompany($id);
        $companyId  = user()->company_id ?? null;

        $templates = CommunicationTemplate::when($companyId, fn ($q) => $q->forCompany($companyId))
            ->active()
            ->orderBy('name')
            ->get();

        return view('communication::automations.edit', [
            'automation'     => $automation,
            'templates'      => $templates,
            'triggerEvents'  => CommunicationAutomation::triggerEventLabels(),
            'recipientTypes' => CommunicationAutomation::recipientTypeLabels(),
        ]);
    }

    /**
     * Update an existing automation rule.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $automation = $this->findForCompany($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'trigger_event'  => 'required|string|max:100',
            'template_id'    => 'nullable|integer|exists:communication_templates,id',
            'delay_minutes'  => 'nullable|integer|min:0',
            'channel'        => 'nullable|in:email,sms,chat,push',
            'recipient_type' => 'required|in:customer,cleaner,admin,custom_email',
            'status'         => 'nullable|in:active,inactive,paused',
        ]);

        $automation->update(array_merge($validated, [
            'delay_minutes' => $validated['delay_minutes'] ?? 0,
        ]));

        return redirect()->route('communications.automations.index')
            ->with('success', 'Automation rule updated.');
    }

    /**
     * Delete an automation rule.
     */
    public function destroy(int $id): RedirectResponse
    {
        $automation = $this->findForCompany($id);
        $automation->delete();

        return redirect()->route('communications.automations.index')
            ->with('success', 'Automation rule deleted.');
    }

    /** Find an automation scoped to the current company. */
    private function findForCompany(int $id): CommunicationAutomation
    {
        $companyId = user()->company_id ?? null;

        return CommunicationAutomation::when(
            $companyId,
            fn ($q) => $q->forCompany($companyId)
        )->findOrFail($id);
    }
}
