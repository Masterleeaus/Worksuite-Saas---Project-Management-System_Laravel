<?php

namespace Modules\FSMSize\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\FSMSize\Entities\FsmSize;

class FsmSizeController extends Controller
{
    public function index(Request $request)
    {
        $sizes = $this->query()
            ->where('active', true)
            ->orderBy('name')
            ->get();

        if ($request->expectsJson()) {
            return response()->json($sizes);
        }

        return view('fsmsize::sizes.index', compact('sizes'));
    }

    public function create()
    {
        $parents = $this->query()->where('active', true)->orderBy('name')->get();

        return view('fsmsize::sizes.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:191',
            'unit_of_measure' => 'nullable|string|max:50',
            'is_order_size'   => 'boolean',
            'parent_id'       => 'nullable|integer|exists:fsm_sizes,id',
        ]);

        $validated['company_id'] = $this->companyId();
        $size = FsmSize::create($validated);

        if ($request->expectsJson()) {
            return response()->json($size, 201);
        }

        return redirect()->route('fsmsize.index')
            ->with('success', 'Size created successfully.');
    }

    public function edit(int $id)
    {
        $size = $this->query()->findOrFail($id);
        $parents = $this->query()->where('id', '<>', $id)->where('active', true)->orderBy('name')->get();

        return view('fsmsize::sizes.edit', compact('size', 'parents'));
    }

    public function update(Request $request, int $id)
    {
        $size = $this->query()->findOrFail($id);
        $size->update($request->only($size->getFillable()));

        if ($request->expectsJson()) {
            return response()->json($size->fresh());
        }

        return redirect()->route('fsmsize.index')
            ->with('success', 'Size updated successfully.');
    }

    public function destroy(Request $request, int $id)
    {
        $this->query()->findOrFail($id)->delete();

        if ($request->expectsJson()) {
            return response()->json(['deleted' => true]);
        }

        return redirect()->route('fsmsize.index')
            ->with('success', 'Size deleted.');
    }

    private function query()
    {
        return FsmSize::query()->where('company_id', $this->companyId());
    }

    private function companyId(): int
    {
        $user = auth()->user();
        abort_if(!$user || !$user->company_id, 403);

        return (int) $user->company_id;
    }
}
