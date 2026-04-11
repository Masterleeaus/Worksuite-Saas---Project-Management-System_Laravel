<?php

namespace Modules\ClientPulse\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\ClientPulse\Models\ExtrasItem;
use Modules\ClientPulse\Models\ExtrasRequest;

class ExtrasAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Extras Items (admin configures the menu of available extras)
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * List all extras items.
     */
    public function index()
    {
        $items = ExtrasItem::orderBy('sort_order')->orderBy('name')->get();

        return view('clientpulse::admin.extras.index', compact('items'));
    }

    /**
     * Create a new extras item.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
            'active'      => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        ExtrasItem::create([
            'company_id'  => Auth::user()->company_id ?? null,
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'active'      => $data['active'] ?? true,
            'sort_order'  => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('clientpulse.admin.extras.index')
            ->with('success', 'Extras item created.');
    }

    /**
     * Update an existing extras item (name, description, active, sort_order).
     */
    public function update(Request $request, int $item)
    {
        $item = ExtrasItem::findOrFail($item);

        $data = $request->validate([
            'name'        => 'sometimes|required|string|max:150',
            'description' => 'nullable|string|max:500',
            'active'      => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $item->update($data);

        return redirect()->route('clientpulse.admin.extras.index')
            ->with('success', 'Extras item updated.');
    }

    /**
     * Delete an extras item.
     */
    public function destroy(int $item)
    {
        ExtrasItem::findOrFail($item)->delete();

        return redirect()->route('clientpulse.admin.extras.index')
            ->with('success', 'Extras item deleted.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Extras Requests (client submissions)
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * List all pending/recent extras requests.
     */
    public function requests(Request $request)
    {
        $q = ExtrasRequest::with(['client', 'order'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $q->where('status', $request->get('status'));
        }

        $requests = $q->paginate(30)->withQueryString();

        return view('clientpulse::admin.extras.requests', compact('requests'));
    }

    /**
     * Acknowledge an extras request (mark as seen by admin).
     */
    public function acknowledge(Request $request, int $extrasRequest)
    {
        $extrasRequest = ExtrasRequest::findOrFail($extrasRequest);

        $extrasRequest->update([
            'status'          => ExtrasRequest::STATUS_ACKNOWLEDGED,
            'acknowledged_at' => now(),
            'acknowledged_by' => Auth::id(),
        ]);

        return redirect()->route('clientpulse.admin.extras.requests')
            ->with('success', 'Extras request acknowledged.');
    }
}
