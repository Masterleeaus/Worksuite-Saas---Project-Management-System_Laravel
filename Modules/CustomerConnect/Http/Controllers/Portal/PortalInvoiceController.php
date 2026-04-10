<?php

namespace Modules\CustomerConnect\Http\Controllers\Portal;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

/**
 * PortalInvoiceController — customer views and downloads their own invoices.
 */
class PortalInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List invoices for the authenticated client.
     */
    public function index()
    {
        $user = Auth::user();
        $invoices = collect();

        if (class_exists(\App\Models\Invoice::class) && Schema::hasTable('invoices')) {
            try {
                $invoices = \App\Models\Invoice::query()
                    ->where('client_id', $user->id)
                    ->orderByDesc('issue_date')
                    ->paginate(15);
            } catch (\Throwable) {
            }
        }

        return view('customerconnect::portal.invoices.index', compact('invoices'));
    }

    /**
     * Download an invoice as PDF.
     * Delegates to EInvoice module if available; otherwise generates a simple PDF.
     */
    public function download(int $id)
    {
        $user = Auth::user();

        if (!class_exists(\App\Models\Invoice::class) || !Schema::hasTable('invoices')) {
            abort(404, 'Invoices are not available.');
        }

        try {
            $invoice = \App\Models\Invoice::query()
                ->where('client_id', $user->id)
                ->findOrFail($id);
        } catch (\Throwable) {
            abort(403, 'You do not have permission to view this invoice.');
        }

        // Delegate to EInvoice PDF generation if available
        if (class_exists(\Modules\EInvoice\Http\Controllers\InvoicePdfController::class)) {
            try {
                $controller = app(\Modules\EInvoice\Http\Controllers\InvoicePdfController::class);
                return $controller->download($id);
            } catch (\Throwable) {
            }
        }

        // Fallback: simple Blade-based PDF via dompdf
        if (app()->bound('dompdf.wrapper')) {
            $pdf = app('dompdf.wrapper');
            $html = view('customerconnect::portal.invoices.pdf', compact('invoice'))->render();
            $pdf->loadHTML($html);
            return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
        }

        // Last resort: show the invoice as HTML
        return view('customerconnect::portal.invoices.show', compact('invoice'));
    }
}
