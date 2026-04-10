<?php

namespace Modules\FSMCore\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMTemplate;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = FSMTemplate::orderBy('name')->paginate(50);
        return view('fsmcore::templates.index', compact('templates'));
    }

    public function create()
    {
        return view('fsmcore::templates.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                       => 'required|string|max:256',
            'description'                => 'nullable|string|max:65535',
            'checklist'                  => 'nullable|string',
            'estimated_duration_minutes' => 'nullable|integer|min:0',
            'active'                     => 'nullable|boolean',
        ]);

        // Parse checklist lines to JSON array
        if (!empty($data['checklist'])) {
            $lines = array_filter(array_map('trim', explode("\n", $data['checklist'])));
            $data['checklist'] = json_encode(array_values($lines));
        }

        FSMTemplate::create($data);

        return redirect()->route('fsmcore.templates.index')
            ->with('success', 'Template created.');
    }

    public function edit(int $id)
    {
        $template = FSMTemplate::findOrFail($id);
        return view('fsmcore::templates.edit', compact('template'));
    }

    public function update(Request $request, int $id)
    {
        $template = FSMTemplate::findOrFail($id);

        $data = $request->validate([
            'name'                       => 'required|string|max:256',
            'description'                => 'nullable|string|max:65535',
            'checklist'                  => 'nullable|string',
            'estimated_duration_minutes' => 'nullable|integer|min:0',
            'active'                     => 'nullable|boolean',
        ]);

        if (!empty($data['checklist'])) {
            $lines = array_filter(array_map('trim', explode("\n", $data['checklist'])));
            $data['checklist'] = json_encode(array_values($lines));
        }

        $template->update($data);

        return redirect()->route('fsmcore.templates.index')
            ->with('success', 'Template updated.');
    }

    public function destroy(int $id)
    {
        $template = FSMTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('fsmcore.templates.index')
            ->with('success', 'Template deleted.');
    }
}
