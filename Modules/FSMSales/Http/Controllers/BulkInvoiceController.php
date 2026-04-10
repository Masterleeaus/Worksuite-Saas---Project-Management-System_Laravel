<?php

namespace Modules\FSMSales\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMSales\Services\InvoiceGenerationService;

class BulkInvoiceController extends Controller
{
    public function create()
    {
        return view('fsmsales::invoices.bulk');
    }

    public function store(Request $request, InvoiceGenerationService $service)
    {
        $data = $request->validate([
            'from' => 'required|date',
            'to'   => 'required|date|after_or_equal:from',
        ]);

        $companyId = auth()->user()->company_id ?? null;
        $invoices  = $service->bulkCreateForPeriod($data['from'], $data['to'], $companyId);

        $count = $invoices->count();

        if ($count === 0) {
            return back()->with('info', 'No unbilled completed orders found in that period.');
        }

        return redirect()->route('fsmsales.invoices.index')
            ->with('success', "Created {$count} draft invoice(s) for the selected period.");
    }
}
