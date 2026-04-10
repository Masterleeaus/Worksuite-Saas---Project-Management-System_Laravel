<?php

namespace Modules\FSMTimesheet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMTimesheet\Models\FSMTimesheetLine;

class TimesheetController extends Controller
{
    public function index(int $orderId)
    {
        $order = FSMOrder::findOrFail($orderId);
        $lines = FSMTimesheetLine::with('user')
            ->where('fsm_order_id', $orderId)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $plannedHours   = $this->plannedHours($order);
        $effectiveHours = (float) $lines->sum('unit_amount');
        $remainingHours = max(0, $plannedHours - $effectiveHours);

        return view('fsmtimesheet::timesheets.index', compact(
            'order', 'lines', 'plannedHours', 'effectiveHours', 'remainingHours'
        ));
    }

    public function create(int $orderId)
    {
        $order = FSMOrder::findOrFail($orderId);
        $users = \App\Models\User::orderBy('name')->get();

        return view('fsmtimesheet::timesheets.create', compact('order', 'users'));
    }

    public function store(Request $request, int $orderId)
    {
        $order = FSMOrder::findOrFail($orderId);

        $data = $request->validate([
            'user_id'     => 'nullable|integer|exists:users,id',
            'date'        => 'required|date',
            'name'        => 'nullable|string|max:255',
            'unit_amount' => 'nullable|numeric|min:0|max:24',
            'start_time'  => 'nullable|date_format:H:i',
            'end_time'    => 'nullable|date_format:H:i|after:start_time',
        ]);

        $data['fsm_order_id'] = $order->id;
        $data['company_id']   = $order->company_id;

        FSMTimesheetLine::create($data);

        return redirect()->route('fsmtimesheet.timesheets.index', $orderId)
            ->with('success', 'Timesheet line added.');
    }

    public function edit(int $orderId, int $id)
    {
        $order = FSMOrder::findOrFail($orderId);
        $line  = FSMTimesheetLine::where('fsm_order_id', $orderId)->findOrFail($id);
        $users = \App\Models\User::orderBy('name')->get();

        return view('fsmtimesheet::timesheets.edit', compact('order', 'line', 'users'));
    }

    public function update(Request $request, int $orderId, int $id)
    {
        FSMOrder::findOrFail($orderId);
        $line = FSMTimesheetLine::where('fsm_order_id', $orderId)->findOrFail($id);

        $data = $request->validate([
            'user_id'     => 'nullable|integer|exists:users,id',
            'date'        => 'required|date',
            'name'        => 'nullable|string|max:255',
            'unit_amount' => 'nullable|numeric|min:0|max:24',
            'start_time'  => 'nullable|date_format:H:i',
            'end_time'    => 'nullable|date_format:H:i|after:start_time',
        ]);

        $line->update($data);

        return redirect()->route('fsmtimesheet.timesheets.index', $orderId)
            ->with('success', 'Timesheet line updated.');
    }

    public function destroy(int $orderId, int $id)
    {
        FSMOrder::findOrFail($orderId);
        $line = FSMTimesheetLine::where('fsm_order_id', $orderId)->findOrFail($id);
        $line->delete();

        return redirect()->route('fsmtimesheet.timesheets.index', $orderId)
            ->with('success', 'Timesheet line deleted.');
    }

    /**
     * Calculate planned hours from the order's scheduled start/end datetimes.
     */
    private function plannedHours(FSMOrder $order): float
    {
        if (!$order->scheduled_date_start || !$order->scheduled_date_end) {
            return 0.0;
        }

        $diff = $order->scheduled_date_start->diffInMinutes($order->scheduled_date_end);

        return round($diff / 60, 2);
    }
}
