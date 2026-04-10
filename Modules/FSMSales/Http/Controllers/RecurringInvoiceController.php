<?php

namespace Modules\FSMSales\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMSales\Models\FSMRecurringInvoice;
use Modules\FSMSales\Services\InvoiceGenerationService;

class RecurringInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $q = FSMRecurringInvoice::with(['client'])->latest();

        if ($request->filled('status')) {
            $q->where('status', $request->get('status'));
        }

        if ($request->filled('schedule')) {
            $q->where('billing_schedule', $request->get('schedule'));
        }

        $recurring  = $q->paginate(50)->withQueryString();
        $statuses   = config('fsmsales.invoice_statuses', []);
        $schedules  = config('fsmsales.billing_schedules', []);
        $filter     = $request->only(['status', 'schedule']);

        return view('fsmsales::recurring.index', compact('recurring', 'statuses', 'schedules', 'filter'));
    }

    public function show(int $id)
    {
        $entry = FSMRecurringInvoice::with(['client', 'invoice.lines', 'agreement'])->findOrFail($id);

        return view('fsmsales::recurring.show', compact('entry'));
    }

    /**
     * Convert a draft recurring entry into a real invoice.
     */
    public function convertToInvoice(int $id, InvoiceGenerationService $service)
    {
        $entry = FSMRecurringInvoice::findOrFail($id);

        if ($entry->status !== FSMRecurringInvoice::STATUS_DRAFT) {
            return back()->with('error', 'Only draft recurring invoices can be converted.');
        }

        $invoice = $service->convertRecurringToInvoice($entry);

        return redirect()->route('fsmsales.invoices.show', $invoice->id)
            ->with('success', "Invoice {$invoice->number} created from recurring entry.");
    }

    /**
     * Mark a recurring invoice as paid.
     */
    public function markPaid(int $id)
    {
        $entry = FSMRecurringInvoice::findOrFail($id);
        $entry->update(['status' => FSMRecurringInvoice::STATUS_PAID]);

        return back()->with('success', 'Recurring invoice marked as paid.');
    }

    public function destroy(int $id)
    {
        FSMRecurringInvoice::findOrFail($id)->delete();

        return redirect()->route('fsmsales.recurring.index')
            ->with('success', 'Recurring invoice entry deleted.');
    }
}
