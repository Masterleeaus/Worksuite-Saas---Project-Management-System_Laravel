<?php

namespace Modules\FSMTimesheet\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMTimesheet\Models\FSMTimesheetLine;

class TimesheetReportController extends Controller
{
    public function index(Request $request)
    {
        $query = FSMTimesheetLine::with(['order.location', 'user'])
            ->orderBy('date', 'desc');

        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->get('user_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->get('date_to'));
        }

        if ($request->filled('fsm_order_id')) {
            $query->where('fsm_order_id', (int) $request->get('fsm_order_id'));
        }

        $lines      = $query->paginate(100)->withQueryString();
        $totalHours = FSMTimesheetLine::query()
            ->when($request->filled('user_id'), fn ($q) => $q->where('user_id', (int) $request->get('user_id')))
            ->when($request->filled('date_from'), fn ($q) => $q->whereDate('date', '>=', $request->get('date_from')))
            ->when($request->filled('date_to'), fn ($q) => $q->whereDate('date', '<=', $request->get('date_to')))
            ->when($request->filled('fsm_order_id'), fn ($q) => $q->where('fsm_order_id', (int) $request->get('fsm_order_id')))
            ->sum('unit_amount');

        $users  = \App\Models\User::orderBy('name')->get();
        $filter = $request->only(['user_id', 'date_from', 'date_to', 'fsm_order_id']);

        return view('fsmtimesheet::report.index', compact('lines', 'totalHours', 'users', 'filter'));
    }

    public function exportCsv(Request $request)
    {
        $query = FSMTimesheetLine::with(['order', 'user'])
            ->orderBy('date')
            ->orderBy('fsm_order_id');

        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->get('user_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->get('date_to'));
        }

        if ($request->filled('fsm_order_id')) {
            $query->where('fsm_order_id', (int) $request->get('fsm_order_id'));
        }

        $lines = $query->get();

        $filename = 'timesheet_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($lines) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Date', 'Order', 'Worker', 'Description', 'Start', 'End', 'Hours']);

            foreach ($lines as $line) {
                fputcsv($handle, [
                    $line->date?->format('Y-m-d') ?? '',
                    $line->order?->name ?? '',
                    $line->user?->name ?? '',
                    $line->name ?? '',
                    $line->start_time ?? '',
                    $line->end_time ?? '',
                    number_format((float) $line->unit_amount, 2),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
