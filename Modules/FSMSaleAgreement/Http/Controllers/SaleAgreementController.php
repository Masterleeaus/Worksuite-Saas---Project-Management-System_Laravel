<?php

namespace Modules\FSMSaleAgreement\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMSaleAgreement\Services\SaleAgreementPropagationService;

class SaleAgreementController extends Controller
{
    public function __construct(private SaleAgreementPropagationService $service) {}

    public function index(Request $request)
    {
        $invoices = Invoice::whereNotNull('fsm_order_id')
            ->with(['fsmOrders', 'items'])
            ->orderByDesc('issue_date')
            ->paginate(20)
            ->withQueryString();

        return view('fsmsaleagreement::invoices.index', compact('invoices'));
    }

    public function propagate(int $id)
    {
        $invoice = Invoice::findOrFail($id);
        $count = $this->service->propagate($invoice);
        return back()->with('success', "Propagated agreement to {$count} order(s).");
    }
}
