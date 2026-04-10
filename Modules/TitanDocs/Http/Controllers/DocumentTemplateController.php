<?php

namespace Modules\TitanDocs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\TitanDocs\Entities\DocumentTemplate;

class DocumentTemplateController extends Controller
{
    /**
     * Display the template library.
     */
    public function index()
    {
        if (!Auth::user()->isAbleTo('manage_templates') && !Auth::user()->isAbleTo('view_documents')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $companyId = getActiveWorkSpace();
        $templates = DocumentTemplate::availableFor($companyId)
            ->orderBy('template_type')
            ->orderBy('name')
            ->get();

        return view('titandocs::document_templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new template.
     */
    public function create()
    {
        if (!Auth::user()->isAbleTo('manage_templates')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        return view('titandocs::document_templates.create');
    }

    /**
     * Store a newly created template.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isAbleTo('manage_templates')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'template_type' => 'required|string|in:client,employee,contract,letter',
            'document_type' => 'required|string|max:100',
            'html_content'  => 'required|string',
            'required_fields' => 'nullable|array',
        ]);

        $companyId = getActiveWorkSpace();

        DocumentTemplate::create([
            'name'            => $validated['name'],
            'template_type'   => $validated['template_type'],
            'document_type'   => $validated['document_type'],
            'html_content'    => $validated['html_content'],
            'required_fields' => $validated['required_fields'] ?? [],
            'is_active'       => true,
            'is_global'       => false,
            'company_id'      => $companyId,
            'created_by'      => Auth::id(),
            'is_approved'     => false,
        ]);

        return redirect()->route('titandocs.templates.index')
            ->with('success', __('Template created successfully.'));
    }

    /**
     * Show the form for editing the template.
     */
    public function edit(int $id)
    {
        if (!Auth::user()->isAbleTo('manage_templates')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $companyId = getActiveWorkSpace();
        $template = DocumentTemplate::where('id', $id)
            ->where(function ($q) use ($companyId) {
                $q->where('company_id', $companyId)->orWhere('is_global', true);
            })
            ->firstOrFail();

        return view('titandocs::document_templates.edit', compact('template'));
    }

    /**
     * Update the template.
     */
    public function update(Request $request, int $id)
    {
        if (!Auth::user()->isAbleTo('manage_templates')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'template_type'   => 'required|string|in:client,employee,contract,letter',
            'document_type'   => 'required|string|max:100',
            'html_content'    => 'required|string',
            'required_fields' => 'nullable|array',
        ]);

        $companyId = getActiveWorkSpace();
        $template = DocumentTemplate::where('id', $id)
            ->where('company_id', $companyId) // only own templates can be edited
            ->firstOrFail();

        $template->update([
            'name'            => $validated['name'],
            'template_type'   => $validated['template_type'],
            'document_type'   => $validated['document_type'],
            'html_content'    => $validated['html_content'],
            'required_fields' => $validated['required_fields'] ?? [],
            'is_approved'     => false, // require re-approval after edit
        ]);

        return redirect()->route('titandocs.templates.index')
            ->with('success', __('Template updated successfully.'));
    }

    /**
     * Delete (soft delete) a template.
     */
    public function destroy(int $id)
    {
        if (!Auth::user()->isAbleTo('manage_templates')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $companyId = getActiveWorkSpace();
        $template = DocumentTemplate::where('id', $id)
            ->where('company_id', $companyId)
            ->firstOrFail();

        $template->delete();

        return redirect()->route('titandocs.templates.index')
            ->with('success', __('Template deleted successfully.'));
    }

    /**
     * Approve a template (super-admin only).
     */
    public function approve(int $id)
    {
        // Only super-admin or manage_templates permission can approve
        if (!Auth::user()->isAbleTo('manage_templates')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $template = DocumentTemplate::findOrFail($id);
        $template->update([
            'is_approved' => true,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', __('Template approved.'));
    }
}
