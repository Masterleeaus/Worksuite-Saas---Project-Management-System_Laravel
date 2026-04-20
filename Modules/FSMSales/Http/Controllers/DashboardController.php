<?php

namespace Modules\FSMSales\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $thisMonth = now()->startOfMonth();

        $invoiced = Invoice::where('issue_date', '>=', $thisMonth)
            ->whereNotIn('status', ['canceled'])
            ->sum('total');

        $collected = Invoice::where('issue_date', '>=', $thisMonth)
            ->where('status', 'paid')
            ->sum(DB::raw('total - due_amount'));

        $outstanding = Invoice::whereNotIn('status', ['paid', 'canceled'])
            ->whereNotNull('due_date')
            ->sum('due_amount');

        $overdueCount = Invoice::whereNotIn('status', ['paid', 'canceled'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->count();

        return view('fsmsales::dashboard.widget', compact(
            'invoiced', 'collected', 'outstanding', 'overdueCount'
        ));
    }
}
