<?php

namespace Modules\FSMSales\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\FSMSales\Models\FSMSalesInvoice;

class DashboardController extends Controller
{
    public function index()
    {
        $thisMonth = now()->startOfMonth();

        $invoiced = FSMSalesInvoice::where('invoice_date', '>=', $thisMonth)
            ->whereNotIn('status', [FSMSalesInvoice::STATUS_VOID])
            ->sum('total');

        $collected = FSMSalesInvoice::where('invoice_date', '>=', $thisMonth)
            ->where('status', FSMSalesInvoice::STATUS_PAID)
            ->sum('amount_paid');

        $outstanding = FSMSalesInvoice::whereNotIn('status', [FSMSalesInvoice::STATUS_PAID, FSMSalesInvoice::STATUS_VOID])
            ->whereNotNull('due_date')
            ->sum(DB::raw('total - amount_paid'));

        $overdueCount = FSMSalesInvoice::where('status', FSMSalesInvoice::STATUS_OVERDUE)->count();

        return view('fsmsales::dashboard.widget', compact(
            'invoiced', 'collected', 'outstanding', 'overdueCount'
        ));
    }
}
