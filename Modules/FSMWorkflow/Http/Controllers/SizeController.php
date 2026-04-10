<?php

namespace Modules\FSMWorkflow\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMWorkflow\Models\FSMSize;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = FSMSize::orderBy('sequence')->get();
        return view('fsmworkflow::sizes.index', compact('sizes'));
    }

    public function create()
    {
        return view('fsmworkflow::sizes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'        => 'required|string|max:8',
            'name'        => 'required|string|max:128',
            'description' => 'nullable|string|max:65535',
            'sequence'    => 'nullable|integer|min:0',
            'active'      => 'nullable|boolean',
        ]);

        $data['active'] = $request->boolean('active', true);

        FSMSize::create($data);

        return redirect()->route('fsmworkflow.sizes.index')
            ->with('success', 'Size tier created.');
    }

    public function edit(int $id)
    {
        $size = FSMSize::findOrFail($id);
        return view('fsmworkflow::sizes.edit', compact('size'));
    }

    public function update(Request $request, int $id)
    {
        $size = FSMSize::findOrFail($id);

        $data = $request->validate([
            'code'        => 'required|string|max:8',
            'name'        => 'required|string|max:128',
            'description' => 'nullable|string|max:65535',
            'sequence'    => 'nullable|integer|min:0',
            'active'      => 'nullable|boolean',
        ]);

        $data['active'] = $request->boolean('active', true);

        $size->update($data);

        return redirect()->route('fsmworkflow.sizes.index')
            ->with('success', 'Size tier updated.');
    }

    public function destroy(int $id)
    {
        FSMSize::findOrFail($id)->delete();

        return redirect()->route('fsmworkflow.sizes.index')
            ->with('success', 'Size tier deleted.');
    }
}
