<?php

namespace Modules\Communication\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Communication\app\Models\CommunicationTemplate;

class TemplateController extends Controller
{
    /**
     * List all templates for the current company (plus global templates).
     */
    public function index(Request $request): View
    {
        $companyId = user()->company_id ?? null;

        $query = CommunicationTemplate::when($companyId, fn ($q) => $q->forCompany($companyId))
            ->orderBy('name');

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($search = $request->input('q')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $templates = $query->paginate(25)->withQueryString();

        return view('communication::templates.index', [
            'templates' => $templates,
            'types'     => CommunicationTemplate::typeLabels(),
            'filter'    => $request->only(['type', 'q']),
        ]);
    }

    /**
     * Show the create template form.
     */
    public function create(): View
    {
        return view('communication::templates.create', [
            'types' => CommunicationTemplate::typeLabels(),
        ]);
    }

    /**
     * Store a new template.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'type'    => 'required|in:email,sms,chat,push,all',
            'subject' => 'nullable|string|max:512',
            'body'    => 'required|string',
        ]);

        // Parse {variable} placeholders from the body
        preg_match_all('/\{(\w+)\}/', $validated['body'] . ($validated['subject'] ?? ''), $matches);
        $variables = array_values(array_unique($matches[1]));

        CommunicationTemplate::create(array_merge($validated, [
            'company_id' => user()->company_id ?? null,
            'variables'  => $variables,
            'status'     => 'active',
        ]));

        return redirect()->route('communications.templates.index')
            ->with('success', 'Template created successfully.');
    }

    /**
     * Show the edit form.
     */
    public function edit(int $id): View
    {
        $template = $this->findForCompany($id);

        return view('communication::templates.edit', [
            'template' => $template,
            'types'    => CommunicationTemplate::typeLabels(),
        ]);
    }

    /**
     * Update an existing template.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $template = $this->findForCompany($id);

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'type'    => 'required|in:email,sms,chat,push,all',
            'subject' => 'nullable|string|max:512',
            'body'    => 'required|string',
        ]);

        preg_match_all('/\{(\w+)\}/', $validated['body'] . ($validated['subject'] ?? ''), $matches);
        $variables = array_values(array_unique($matches[1]));

        $template->update(array_merge($validated, ['variables' => $variables]));

        return redirect()->route('communications.templates.index')
            ->with('success', 'Template updated successfully.');
    }

    /**
     * Delete a template.
     */
    public function destroy(int $id): RedirectResponse
    {
        $template = $this->findForCompany($id);
        $template->delete();

        return redirect()->route('communications.templates.index')
            ->with('success', 'Template deleted.');
    }

    /** Find a template scoped to the current company. */
    private function findForCompany(int $id): CommunicationTemplate
    {
        $companyId = user()->company_id ?? null;

        return CommunicationTemplate::when(
            $companyId,
            fn ($q) => $q->forCompany($companyId)
        )->findOrFail($id);
    }
}
