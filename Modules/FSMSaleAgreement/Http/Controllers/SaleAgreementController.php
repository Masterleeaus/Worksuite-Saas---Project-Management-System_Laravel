<?php

namespace Modules\FSMSaleAgreement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMSaleAgreement\Services\SaleAgreementPropagationService;
use Modules\FSMSales\Models\FSMSalesInvoice;

class SaleAgreementController extends Controller
{
    public function __construct(private SaleAgreementPropagationService $service) {}

    public function index(Request $request)
    {
        $invoices = FSMSalesInvoice::whereNotNull('agreement_id')
            ->with(['orders', 'lines'])
            ->orderByDesc('invoice_date')
            ->paginate(20)
            ->withQueryString();

        return view('fsmsaleagreement::invoices.index', compact('invoices'));
    }

    public function propagate(int $id)
    {
        $invoice = FSMSalesInvoice::findOrFail($id);
        $count = $this->service->propagate($invoice);
        return back()->with('success', "Propagated agreement to {$count} order(s).");
    }
}
