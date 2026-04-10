<?php

namespace Modules\FSMSales\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;

class UnbilledOrdersController extends Controller
{
    public function index(Request $request)
    {
        $q = FSMOrder::query()
            ->with(['location', 'person', 'team', 'stage'])
            ->where('is_invoiced', false)
            ->whereNotNull('date_end'); // only completed orders

        if ($request->filled('from')) {
            $q->where('date_end', '>=', $request->get('from'));
        }

        if ($request->filled('to')) {
            $q->where('date_end', '<=', $request->get('to') . ' 23:59:59');
        }

        if ($request->filled('client_id')) {
            // Filter by location partner if FSMLocation has partner_id
            $clientId = (int) $request->get('client_id');
            $q->whereHas('location', function ($lq) use ($clientId) {
                $lq->where('partner_id', $clientId);
            });
        }

        if ($request->filled('team_id')) {
            $q->where('team_id', (int) $request->get('team_id'));
        }

        $orders  = $q->orderByDesc('date_end')->paginate(50)->withQueryString();
        $teams   = \Modules\FSMCore\Models\FSMTeam::where('active', true)->get();
        $filter  = $request->only(['from', 'to', 'client_id', 'team_id']);

        return view('fsmsales::unbilled.index', compact('orders', 'teams', 'filter'));
    }
}
