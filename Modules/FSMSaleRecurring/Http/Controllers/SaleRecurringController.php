<?php

namespace Modules\FSMSaleRecurring\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMRecurring\Models\FSMRecurring;
use Modules\FSMSales\Models\FSMSalesInvoice;
use Modules\FSMSaleRecurring\Services\SaleRecurringService;

class SaleRecurringController extends Controller
{
    public function __construct(private SaleRecurringService $service) {}

    /** List recurring schedules with their invoice links */
    public function index(Request $request)
    {
        $recurrings = FSMRecurring::with(['location', 'recurringTemplate'])
            ->when($request->q, fn($q, $v) => $q->where('name', 'like', "%$v%"))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();
        $filter = $request->only(['q']);
        return view('fsmsalerecurring::recurrings.index', compact('recurrings', 'filter'));
    }

    /** Show link form for a recurring to an invoice line */
    public function linkForm(int $id)
    {
        $recurring = FSMRecurring::findOrFail($id);
        $invoices = FSMSalesInvoice::with('lines')->orderByDesc('invoice_date')->get();
        return view('fsmsalerecurring::recurrings.link', compact('recurring', 'invoices'));
    }

    /** Store the link */
    public function link(Request $request, int $id)
    {
        $recurring = FSMRecurring::findOrFail($id);
        $data = $request->validate(['invoice_line_id' => 'required|integer|exists:fsm_sales_invoice_lines,id']);
        $line = \Modules\FSMSales\Models\FSMSalesInvoiceLine::findOrFail($data['invoice_line_id']);
        $this->service->linkToInvoiceLine($recurring, $line);
        return redirect()->route('fsmsalerecurring.index')->with('success', 'Recurring linked to invoice line.');
    }

    /** Remove link */
    public function unlink(int $id)
    {
        $recurring = FSMRecurring::findOrFail($id);
        $this->service->unlink($recurring);
        return redirect()->route('fsmsalerecurring.index')->with('success', 'Recurring unlinked.');
    }
}
