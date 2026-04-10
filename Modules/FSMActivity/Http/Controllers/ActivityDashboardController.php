<?php

namespace Modules\FSMActivity\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\FSMActivity\Models\FSMActivity;

class ActivityDashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $dueToday = FSMActivity::with(['order', 'activityType', 'assignedUser'])
            ->where('state', 'open')
            ->whereDate('due_date', $today)
            ->orderBy('due_date')
            ->get();

        $dueTodayCount = $dueToday->count();

        $overdueCount = FSMActivity::whereIn('state', ['open', 'overdue'])
            ->whereDate('due_date', '<', $today)
            ->count();

        return view('fsmactivity::dashboard.index', compact('dueToday', 'dueTodayCount', 'overdueCount'));
    }
}
