<?php

namespace Modules\FSMCore\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMStage;

class StageController extends Controller
{
    public function index()
    {
        $stages = FSMStage::orderBy('sequence')->withCount('orders')->get();
        return view('fsmcore::stages.index', compact('stages'));
    }

    public function create()
    {
        return view('fsmcore::stages.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'               => 'required|string|max:128',
            'description'        => 'nullable|string|max:65535',
            'sequence'           => 'nullable|integer|min:0',
            'is_completion_stage'=> 'nullable|boolean',
            'color'              => 'nullable|string|max:32',
        ]);

        FSMStage::create($data);

        return redirect()->route('fsmcore.stages.index')
            ->with('success', 'Stage created.');
    }

    public function edit(int $id)
    {
        $stage = FSMStage::findOrFail($id);
        return view('fsmcore::stages.edit', compact('stage'));
    }

    public function update(Request $request, int $id)
    {
        $stage = FSMStage::findOrFail($id);

        $data = $request->validate([
            'name'               => 'required|string|max:128',
            'description'        => 'nullable|string|max:65535',
            'sequence'           => 'nullable|integer|min:0',
            'is_completion_stage'=> 'nullable|boolean',
            'color'              => 'nullable|string|max:32',
        ]);

        $stage->update($data);

        return redirect()->route('fsmcore.stages.index')
            ->with('success', 'Stage updated.');
    }

    public function destroy(int $id)
    {
        $stage = FSMStage::findOrFail($id);
        $stage->delete();

        return redirect()->route('fsmcore.stages.index')
            ->with('success', 'Stage deleted.');
    }
}
