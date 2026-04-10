<?php

namespace Modules\FSMSales\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMSales\Models\FSMSalesInvoice;
use Modules\FSMSales\Models\FSMSalesInvoiceLine;
use Modules\FSMSales\Services\InvoiceGenerationService;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $q = FSMSalesInvoice::with(['client'])->latest();

        if ($request->filled('status')) {
            $q->where('status', $request->get('status'));
        }

        if ($request->filled('client_id')) {
            $q->where('client_id', (int) $request->get('client_id'));
        }

        if ($request->filled('q')) {
            $term = trim((string) $request->get('q'));
            $q->where(function ($sub) use ($term) {
                $sub->where('number', 'like', "%{$term}%")
                    ->orWhere('notes', 'like', "%{$term}%");
            });
        }

        $invoices = $q->paginate(50)->withQueryString();
        $statuses = config('fsmsales.invoice_statuses', []);
        $filter   = $request->only(['status', 'client_id', 'q']);

        return view('fsmsales::invoices.index', compact('invoices', 'statuses', 'filter'));
    }

    public function show(int $id)
    {
        $invoice = FSMSalesInvoice::with(['lines.order', 'orders', 'client'])->findOrFail($id);

        return view('fsmsales::invoices.show', compact('invoice'));
    }

    public function create(Request $request)
    {
        // Pre-populate from an order if given
        $order = $request->filled('order_id')
            ? FSMOrder::findOrFail((int) $request->get('order_id'))
            : null;

        $clients = \App\Models\User::orderBy('name')->get();

        return view('fsmsales::invoices.create', compact('order', 'clients'));
    }

    public function store(Request $request, InvoiceGenerationService $service)
    {
        $data = $request->validate([
            'client_id'    => 'nullable|integer|exists:users,id',
            'agreement_id' => 'nullable|integer',
            'invoice_date' => 'required|date',
            'due_date'     => 'nullable|date|after_or_equal:invoice_date',
            'notes'        => 'nullable|string|max:2000',
            'order_ids'    => 'nullable|array',
            'order_ids.*'  => 'integer|exists:fsm_orders,id',
        ]);

        $invoice = FSMSalesInvoice::create([
            'company_id'   => auth()->user()->company_id ?? null,
            'number'       => FSMSalesInvoice::nextNumber(),
            'client_id'    => $data['client_id'] ?? null,
            'agreement_id' => $data['agreement_id'] ?? null,
            'invoice_date' => $data['invoice_date'],
            'due_date'     => $data['due_date'] ?? null,
            'status'       => FSMSalesInvoice::STATUS_DRAFT,
            'notes'        => $data['notes'] ?? null,
        ]);

        if (!empty($data['order_ids'])) {
            $invoice->orders()->attach($data['order_ids']);

            // Mark orders as invoiced
            FSMOrder::whereIn('id', $data['order_ids'])->update(['is_invoiced' => true]);
        }

        return redirect()->route('fsmsales.invoices.show', $invoice->id)
            ->with('success', "Invoice {$invoice->number} created.");
    }

    public function edit(int $id)
    {
        $invoice = FSMSalesInvoice::with(['lines', 'orders'])->findOrFail($id);
        $clients = \App\Models\User::orderBy('name')->get();

        return view('fsmsales::invoices.edit', compact('invoice', 'clients'));
    }

    public function update(Request $request, int $id)
    {
        $invoice = FSMSalesInvoice::findOrFail($id);

        $data = $request->validate([
            'client_id'    => 'nullable|integer|exists:users,id',
            'invoice_date' => 'required|date',
            'due_date'     => 'nullable|date|after_or_equal:invoice_date',
            'status'       => 'required|string|in:draft,sent,paid,overdue,void',
            'notes'        => 'nullable|string|max:2000',
            'amount_paid'  => 'nullable|numeric|min:0',
        ]);

        $invoice->update($data);

        return redirect()->route('fsmsales.invoices.show', $invoice->id)
            ->with('success', 'Invoice updated.');
    }

    public function destroy(int $id)
    {
        $invoice = FSMSalesInvoice::findOrFail($id);
        $number  = $invoice->number;
        $invoice->delete();

        return redirect()->route('fsmsales.invoices.index')
            ->with('success', "Invoice {$number} deleted.");
    }

    // ── Line management ──────────────────────────────────────────────────────

    public function addLine(Request $request, int $id)
    {
        $invoice = FSMSalesInvoice::findOrFail($id);

        $data = $request->validate([
            'line_type'   => 'required|string|in:service,timesheet,stock,equipment,other',
            'description' => 'nullable|string|max:255',
            'qty'         => 'required|numeric|min:0',
            'unit_price'  => 'required|numeric|min:0',
            'tax_rate'    => 'nullable|numeric|min:0|max:1',
        ]);

        FSMSalesInvoiceLine::create([
            'company_id'           => $invoice->company_id,
            'fsm_sales_invoice_id' => $invoice->id,
            'line_type'            => $data['line_type'],
            'description'          => $data['description'] ?? null,
            'qty'                  => $data['qty'],
            'unit_price'           => $data['unit_price'],
            'tax_rate'             => $data['tax_rate'] ?? 0,
        ]);

        return redirect()->route('fsmsales.invoices.edit', $invoice->id)
            ->with('success', 'Line added.');
    }

    public function deleteLine(int $invoiceId, int $lineId)
    {
        $line = FSMSalesInvoiceLine::where('fsm_sales_invoice_id', $invoiceId)->findOrFail($lineId);
        $line->delete();

        return redirect()->route('fsmsales.invoices.edit', $invoiceId)
            ->with('success', 'Line removed.');
    }

    // ── Quick-create from FSM Order ──────────────────────────────────────────

    public function createFromOrder(int $orderId, InvoiceGenerationService $service)
    {
        $order   = FSMOrder::findOrFail($orderId);
        $invoice = $service->createFromOrderCompletion($order);

        return redirect()->route('fsmsales.invoices.show', $invoice->id)
            ->with('success', "Draft invoice {$invoice->number} created from order {$order->name}.");
    }
}
