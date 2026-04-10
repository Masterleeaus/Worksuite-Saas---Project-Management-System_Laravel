<?php

namespace Modules\TitanReach\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanReach\Models\ReachSegment;

class SegmentController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()?->company_id ?? null;
        $segments  = ReachSegment::when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->orderBy('name')
            ->paginate(20);

        return view('titanreach::segments.index', compact('segments'));
    }

    public function create()
    {
        return view('titanreach::segments.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'filters'     => 'nullable|array',
        ]);

        $data['company_id'] = auth()->user()?->company_id;
        ReachSegment::create($data);

        return redirect()->route('titanreach.segments.index')->with('success', 'Segment created.');
    }

    public function edit(int $id)
    {
        $segment = ReachSegment::findOrFail($id);
        return view('titanreach::segments.create', compact('segment'));
    }

    public function update(Request $request, int $id)
    {
        $segment = ReachSegment::findOrFail($id);

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'filters'     => 'nullable|array',
        ]);

        $segment->update($data);

        return redirect()->route('titanreach.segments.index')->with('success', 'Segment updated.');
    }

    public function destroy(int $id)
    {
        ReachSegment::findOrFail($id)->delete();

        return redirect()->route('titanreach.segments.index')->with('success', 'Segment deleted.');
    }
}
