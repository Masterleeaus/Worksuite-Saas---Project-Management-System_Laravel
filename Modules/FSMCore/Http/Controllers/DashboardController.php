<?php

namespace Modules\FSMCore\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMStage;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $todayOrders = FSMOrder::query()
            ->whereDate('scheduled_date_start', $today)
            ->count();

        $overdueOrders = FSMOrder::query()
            ->whereDate('scheduled_date_end', '<', $today)
            ->whereNull('date_end')
            ->count();

        $stages = FSMStage::query()
            ->orderBy('sequence')
            ->withCount('orders')
            ->get();

        return view('fsmcore::dashboard.index', compact('todayOrders', 'overdueOrders', 'stages'));
    }
}
