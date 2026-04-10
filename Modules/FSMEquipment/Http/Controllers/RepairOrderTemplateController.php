<?php

namespace Modules\FSMEquipment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMEquipment\Models\RepairOrderTemplate;

class RepairOrderTemplateController extends Controller
{
    public function index(Request $request)
    {
        $q = RepairOrderTemplate::query();

        if ($request->filled('q')) {
            $term = trim((string) $request->get('q'));
            $q->where('name', 'like', "%{$term}%");
        }

        $templates = $q->orderBy('name')->paginate(50)->withQueryString();
        $filter    = $request->only(['q']);

        return view('fsmequipment::repair_templates.index', compact('templates', 'filter'));
    }

    public function create()
    {
        return view('fsmequipment::repair_templates.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'               => 'required|string|max:256',
            'equipment_category' => 'nullable|string|max:128',
            'description'        => 'nullable|string',
            'standard_parts'     => 'nullable|string',
            'estimated_hours'    => 'nullable|numeric|min:0',
        ]);

        RepairOrderTemplate::create($data);

        return redirect()->route('fsmequipment.repair-templates.index')
            ->with('success', 'Repair template created.');
    }

    public function edit(int $id)
    {
        $template = RepairOrderTemplate::findOrFail($id);
        return view('fsmequipment::repair_templates.edit', compact('template'));
    }

    public function update(Request $request, int $id)
    {
        $template = RepairOrderTemplate::findOrFail($id);

        $data = $request->validate([
            'name'               => 'required|string|max:256',
            'equipment_category' => 'nullable|string|max:128',
            'description'        => 'nullable|string',
            'standard_parts'     => 'nullable|string',
            'estimated_hours'    => 'nullable|numeric|min:0',
        ]);

        $template->update($data);

        return redirect()->route('fsmequipment.repair-templates.index')
            ->with('success', 'Repair template updated.');
    }

    public function destroy(int $id)
    {
        $template = RepairOrderTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('fsmequipment.repair-templates.index')
            ->with('success', 'Repair template deleted.');
    }
}
