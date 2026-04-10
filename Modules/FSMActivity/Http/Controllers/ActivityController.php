<?php

namespace Modules\FSMActivity\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMActivity\Models\FSMActivity;
use Modules\FSMActivity\Models\FSMActivityType;
use Modules\FSMCore\Models\FSMOrder;

class ActivityController extends Controller
{
    public function index(int $orderId)
    {
        $order      = FSMOrder::findOrFail($orderId);
        $activities = FSMActivity::with(['activityType', 'assignedUser'])
            ->where('fsm_order_id', $orderId)
            ->orderBy('due_date')
            ->get();

        return view('fsmactivity::activities.index', compact('order', 'activities'));
    }

    public function create(int $orderId)
    {
        $order = FSMOrder::findOrFail($orderId);
        $types = FSMActivityType::where('active', true)->orderBy('name')->get();
        $users = \App\Models\User::orderBy('name')->get(['id', 'name']);

        return view('fsmactivity::activities.create', compact('order', 'types', 'users'));
    }

    public function store(Request $request, int $orderId)
    {
        FSMOrder::findOrFail($orderId);

        $data = $request->validate([
            'activity_type_id' => 'nullable|integer|exists:fsm_activity_types,id',
            'summary'          => 'nullable|string|max:255',
            'note'             => 'nullable|string',
            'due_date'         => 'nullable|date',
            'assigned_to'      => 'nullable|integer|exists:users,id',
            'state'            => 'nullable|string|in:open,done,cancelled,overdue',
        ]);

        $data['fsm_order_id'] = $orderId;
        $data['state']        = $data['state'] ?? 'open';

        FSMActivity::create($data);

        return redirect()->route('fsmactivity.activities.index', $orderId)
            ->with('success', 'Activity logged.');
    }

    public function edit(int $orderId, int $id)
    {
        $order    = FSMOrder::findOrFail($orderId);
        $activity = FSMActivity::where('fsm_order_id', $orderId)->findOrFail($id);
        $types    = FSMActivityType::where('active', true)->orderBy('name')->get();
        $users    = \App\Models\User::orderBy('name')->get(['id', 'name']);

        return view('fsmactivity::activities.edit', compact('order', 'activity', 'types', 'users'));
    }

    public function update(Request $request, int $orderId, int $id)
    {
        FSMOrder::findOrFail($orderId);
        $activity = FSMActivity::where('fsm_order_id', $orderId)->findOrFail($id);

        $data = $request->validate([
            'activity_type_id' => 'nullable|integer|exists:fsm_activity_types,id',
            'summary'          => 'nullable|string|max:255',
            'note'             => 'nullable|string',
            'due_date'         => 'nullable|date',
            'assigned_to'      => 'nullable|integer|exists:users,id',
            'state'            => 'nullable|string|in:open,done,cancelled,overdue',
        ]);

        $activity->update($data);

        return redirect()->route('fsmactivity.activities.index', $orderId)
            ->with('success', 'Activity updated.');
    }

    public function destroy(int $orderId, int $id)
    {
        FSMOrder::findOrFail($orderId);
        FSMActivity::where('fsm_order_id', $orderId)->findOrFail($id)->delete();

        return redirect()->route('fsmactivity.activities.index', $orderId)
            ->with('success', 'Activity deleted.');
    }

    public function markDone(int $orderId, int $id)
    {
        FSMOrder::findOrFail($orderId);
        $activity = FSMActivity::where('fsm_order_id', $orderId)->findOrFail($id);

        $activity->update([
            'state'   => 'done',
            'done_at' => now(),
            'done_by' => auth()->id(),
        ]);

        return redirect()->route('fsmactivity.activities.index', $orderId)
            ->with('success', 'Activity marked as done.');
    }

    public function markCancelled(int $orderId, int $id)
    {
        FSMOrder::findOrFail($orderId);
        $activity = FSMActivity::where('fsm_order_id', $orderId)->findOrFail($id);

        $activity->update(['state' => 'cancelled']);

        return redirect()->route('fsmactivity.activities.index', $orderId)
            ->with('success', 'Activity cancelled.');
    }
}
