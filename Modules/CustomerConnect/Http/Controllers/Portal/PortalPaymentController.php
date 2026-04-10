<?php

namespace Modules\CustomerConnect\Http\Controllers\Portal;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

/**
 * PortalPaymentController — customer views payment history and initiates online payments.
 */
class PortalPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List payment history for the authenticated client.
     */
    public function index()
    {
        $user = Auth::user();
        $payments = collect();

        // Look for payments in the Payments table (core Worksuite)
        if (Schema::hasTable('payments')) {
            try {
                $payments = \DB::table('payments')
                    ->where('client_id', $user->id)
                    ->orderByDesc('paid_on')
                    ->limit(50)
                    ->get();
            } catch (\Throwable) {
            }
        }

        // Outstanding invoices that can be paid online
        $outstandingInvoices = collect();
        if (class_exists(\App\Models\Invoice::class) && Schema::hasTable('invoices')) {
            try {
                $outstandingInvoices = \App\Models\Invoice::query()
                    ->where('client_id', $user->id)
                    ->whereIn('status', ['unpaid', 'partial'])
                    ->orderBy('due_date')
                    ->get();
            } catch (\Throwable) {
            }
        }

        return view('customerconnect::portal.payments.index', compact('payments', 'outstandingInvoices'));
    }

    /**
     * Redirect to the PaymentModule online payment flow for a specific invoice.
     */
    public function payInvoice(int $invoiceId)
    {
        $user = Auth::user();

        if (!class_exists(\App\Models\Invoice::class) || !Schema::hasTable('invoices')) {
            return redirect()->route('customerconnect.portal.payments.index')
                ->with('error', 'Online payment is not available at this time.');
        }

        try {
            $invoice = \App\Models\Invoice::query()
                ->where('client_id', $user->id)
                ->findOrFail($invoiceId);
        } catch (\Throwable) {
            abort(403, 'You do not have permission to pay this invoice.');
        }

        // Delegate to PaymentModule if available
        if (class_exists(\Modules\PaymentModule\Http\Controllers\ClientPaymentController::class)) {
            try {
                return redirect()->route('paymentmodule.client.invoice.pay', ['invoiceId' => $invoiceId]);
            } catch (\Throwable) {
            }
        }

        return redirect()->route('customerconnect.portal.payments.index')
            ->with('info', 'Online payment via portal is coming soon. Please contact us to arrange payment.');
    }
}
