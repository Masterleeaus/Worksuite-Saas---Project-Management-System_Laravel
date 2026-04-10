<?php

namespace Modules\FSMActivity\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMActivity\Models\FSMActivity;
use Modules\FSMActivity\Models\FSMActivityType;

class GlobalActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = FSMActivity::with(['activityType', 'assignedUser', 'order'])
            ->orderBy('due_date', 'asc');

        if ($request->filled('type_id')) {
            $query->where('activity_type_id', $request->type_id);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        if ($request->filled('due_date_from')) {
            $query->whereDate('due_date', '>=', $request->due_date_from);
        }

        if ($request->filled('due_date_to')) {
            $query->whereDate('due_date', '<=', $request->due_date_to);
        }

        $activities = $query->paginate(50)->withQueryString();
        $types      = FSMActivityType::orderBy('name')->get(['id', 'name']);
        $users      = \App\Models\User::orderBy('name')->get(['id', 'name']);

        return view('fsmactivity::global.index', compact('activities', 'types', 'users'));
    }
}
