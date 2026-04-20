<?php

namespace Modules\FSMSales\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItems;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMSales\Services\InvoiceGenerationService;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $q = Invoice::with(['client'])->latest('issue_date');

        if ($request->filled('status')) {
            $status = (string) $request->get('status');
            if ($status === 'overdue') {
                $q->whereNotIn('status', ['paid', 'canceled'])
                    ->whereDate('due_date', '<', now()->toDateString());
            } else {
                $q->where('status', $this->mapStatusToNative($status));
            }
        }

        if ($request->filled('client_id')) {
            $q->where('client_id', (int) $request->get('client_id'));
        }

        if ($request->filled('q')) {
            $term = trim((string) $request->get('q'));
            $q->where(function ($sub) use ($term) {
                $sub->where('invoice_number', 'like', "%{$term}%")
                    ->orWhere('note', 'like', "%{$term}%");
            });
        }

        $invoices = $q->paginate(50)->withQueryString();
        $statuses = config('fsmsales.invoice_statuses', []);
        $filter   = $request->only(['status', 'client_id', 'q']);

        return view('fsmsales::invoices.index', compact('invoices', 'statuses', 'filter'));
    }

    public function show(int $id)
    {
        $invoice = Invoice::with(['items', 'fsmOrders', 'client'])->findOrFail($id);

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

        $invoice = Invoice::create([
            'company_id'   => auth()->user()->company_id ?? null,
            'invoice_number' => Invoice::lastInvoiceNumber(auth()->user()->company_id ?? null) + 1,
            'client_id'    => $data['client_id'] ?? null,
            'issue_date' => $data['invoice_date'],
            'due_date'     => $data['due_date'] ?? null,
            'status'       => 'draft',
            'note'        => $data['notes'] ?? null,
            'currency_id' => company()?->currency_id ?? null,
            'default_currency_id' => company()?->currency_id ?? null,
            'sub_total' => 0,
            'total' => 0,
            'due_amount' => 0,
            'discount' => 0,
            'discount_type' => 'percent',
            'recurring' => 'no',
        ]);

        if (!empty($data['order_ids'])) {
            $invoice->fsmOrders()->attach($data['order_ids']);
            $invoice->update(['fsm_order_id' => $data['order_ids'][0] ?? null]);

            // Mark orders as invoiced
            FSMOrder::whereIn('id', $data['order_ids'])->update(['is_invoiced' => true]);
        }

        return redirect()->route('fsmsales.invoices.show', $invoice->id)
            ->with('success', "Invoice {$invoice->number} created.");
    }

    public function edit(int $id)
    {
        $invoice = Invoice::with(['items', 'fsmOrders'])->findOrFail($id);
        $clients = \App\Models\User::orderBy('name')->get();

        return view('fsmsales::invoices.edit', compact('invoice', 'clients'));
    }

    public function update(Request $request, int $id)
    {
        $invoice = Invoice::findOrFail($id);

        $data = $request->validate([
            'client_id'    => 'nullable|integer|exists:users,id',
            'invoice_date' => 'required|date',
            'due_date'     => 'nullable|date|after_or_equal:invoice_date',
            'status'       => 'required|string|in:draft,sent,paid,overdue,void',
            'notes'        => 'nullable|string|max:2000',
            'amount_paid'  => 'nullable|numeric|min:0',
        ]);

        $nativeStatus = $this->mapStatusToNative((string) $data['status']);
        $amountPaid = isset($data['amount_paid']) ? (float) $data['amount_paid'] : $invoice->amount_paid;

        $invoice->update([
            'client_id' => $data['client_id'] ?? null,
            'issue_date' => $data['invoice_date'],
            'due_date' => $data['due_date'] ?? null,
            'status' => $nativeStatus,
            'note' => $data['notes'] ?? null,
            'due_amount' => max(0, round((float) $invoice->total - $amountPaid, 2)),
        ]);

        return redirect()->route('fsmsales.invoices.show', $invoice->id)
            ->with('success', 'Invoice updated.');
    }

    public function destroy(int $id)
    {
        $invoice = Invoice::findOrFail($id);
        $number  = $invoice->invoice_number;
        $invoice->delete();

        return redirect()->route('fsmsales.invoices.index')
            ->with('success', "Invoice {$number} deleted.");
    }

    // ── Line management ──────────────────────────────────────────────────────

    public function addLine(Request $request, int $id)
    {
        $invoice = Invoice::findOrFail($id);

        $data = $request->validate([
            'line_type'   => 'required|string|in:service,timesheet,stock,equipment,other',
            'description' => 'nullable|string|max:255',
            'qty'         => 'required|numeric|min:0',
            'unit_price'  => 'required|numeric|min:0',
            'tax_rate'    => 'nullable|numeric|min:0|max:1',
        ]);

        InvoiceItems::create([
            'company_id'           => $invoice->company_id,
            'invoice_id' => $invoice->id,
            'type' => 'item',
            'item_name' => ucfirst($data['line_type']),
            'item_summary' => $data['description'] ?? null,
            'quantity' => $data['qty'],
            'unit_price'           => $data['unit_price'],
            'amount' => round((float) $data['qty'] * (float) $data['unit_price'], 2),
            'fsm_order_id' => $invoice->fsm_order_id,
        ]);

        $this->recalculateInvoiceTotals($invoice);

        return redirect()->route('fsmsales.invoices.edit', $invoice->id)
            ->with('success', 'Line added.');
    }

    public function deleteLine(int $invoiceId, int $lineId)
    {
        $line = InvoiceItems::where('invoice_id', $invoiceId)->findOrFail($lineId);
        $line->delete();
        $this->recalculateInvoiceTotals(Invoice::findOrFail($invoiceId));

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

    private function mapStatusToNative(string $status): string
    {
        return match ($status) {
            'paid' => 'paid',
            'void' => 'canceled',
            'draft' => 'draft',
            'sent', 'overdue' => 'unpaid',
            default => throw new \InvalidArgumentException("Unsupported invoice status: {$status}"),
        };
    }

    private function recalculateInvoiceTotals(Invoice $invoice): void
    {
        $subTotal = (float) $invoice->items()->sum('amount');
        $amountPaid = (float) $invoice->amount_paid;
        $invoice->update([
            'sub_total' => $subTotal,
            'total' => $subTotal,
            'due_amount' => max(0, round($subTotal - $amountPaid, 2)),
        ]);
    }
}
