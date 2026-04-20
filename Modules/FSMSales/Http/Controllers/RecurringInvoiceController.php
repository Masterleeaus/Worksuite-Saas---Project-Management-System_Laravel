<?php

namespace Modules\FSMSales\Http\Controllers;

use App\Models\RecurringInvoice;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMSales\Services\InvoiceGenerationService;

class RecurringInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $q = RecurringInvoice::with(['client', 'recurrings'])->latest();

        if ($request->filled('status')) {
            $q->where('status', $request->get('status'));
        }

        if ($request->filled('schedule')) {
            $q->where('rotation', $request->get('schedule'));
        }

        $recurring  = $q->paginate(50)->withQueryString();
        $statuses   = ['active' => 'Active', 'inactive' => 'Inactive'];
        $schedules  = [
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'annually' => 'Annual',
            'weekly' => 'Weekly',
            'bi-weekly' => 'Bi-Weekly',
            'daily' => 'Daily',
            'half-yearly' => 'Half Yearly',
        ];
        $filter     = $request->only(['status', 'schedule']);

        return view('fsmsales::recurring.index', compact('recurring', 'statuses', 'schedules', 'filter'));
    }

    public function show(int $id)
    {
        $entry = RecurringInvoice::with(['client', 'recurrings.items'])->findOrFail($id);

        return view('fsmsales::recurring.show', compact('entry'));
    }

    /**
     * Convert a draft recurring entry into a real invoice.
     */
    public function convertToInvoice(int $id, InvoiceGenerationService $service)
    {
        $entry = RecurringInvoice::findOrFail($id);

        if ($entry->status !== 'active') {
            return back()->with('error', 'Only active recurring invoices can be converted.');
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
        $entry = RecurringInvoice::findOrFail($id);
        $entry->update(['status' => 'inactive']);

        return back()->with('success', 'Recurring invoice marked as paid.');
    }

    public function destroy(int $id)
    {
        RecurringInvoice::findOrFail($id)->delete();

        return redirect()->route('fsmsales.recurring.index')
            ->with('success', 'Recurring invoice entry deleted.');
    }
}
