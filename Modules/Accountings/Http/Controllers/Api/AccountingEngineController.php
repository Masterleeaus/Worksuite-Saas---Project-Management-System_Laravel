<?php

namespace Modules\Accountings\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Accountings\Actions\AdjustInvoiceAction;
use Modules\Accountings\Actions\CloseAccountingPeriodAction;
use Modules\Accountings\Actions\PostInvoiceToLedgerAction;
use Modules\Accountings\Actions\PostVisitCostAction;
use Modules\Accountings\Actions\RecordPaymentAction;
use Modules\Accountings\Actions\WriteoffInvoiceAction;
use Modules\Accountings\Entities\FinancialTransaction;
use Modules\Accountings\Policies\AccountingEnginePolicy;
use Modules\Accountings\Services\ContractProfitabilityService;
use Modules\Accountings\Services\SiteProfitabilityService;
use Modules\Accountings\Support\CompanyContext;

class AccountingEngineController extends Controller
{
    private function policy(): AccountingEnginePolicy
    {
        return app(AccountingEnginePolicy::class);
    }

    public function postVisitCost(Request $request, string $visitRef, PostVisitCostAction $postVisitCostAction): JsonResponse
    {
        abort_unless($this->policy()->postTransactions($request->user()), 403);
        $companyId = CompanyContext::resolveCompanyId();

        $payload = $request->validate([
            'job_ref' => ['nullable', 'string', 'max:191'],
            'site_ref' => ['nullable', 'string', 'max:191'],
            'contract_ref' => ['nullable', 'string', 'max:191'],
            'service_agreement_ref' => ['nullable', 'string', 'max:191'],
            'labour_cost' => ['nullable', 'numeric'],
            'travel_cost' => ['nullable', 'numeric'],
            'equipment_cost' => ['nullable', 'numeric'],
            'consumables_cost' => ['nullable', 'numeric'],
            'overhead_cost' => ['nullable', 'numeric'],
            'revenue' => ['nullable', 'numeric'],
        ]);

        $payload['company_id'] = $companyId;
        $payload['visit_ref'] = $visitRef;
        $payload['user_id'] = auth()->id();

        $visitCost = $postVisitCostAction->execute($payload);

        return response()->json(['data' => $visitCost], 201);
    }

    public function postInvoiceToLedger(Request $request, int $invoiceId, PostInvoiceToLedgerAction $postInvoiceToLedgerAction): JsonResponse
    {
        abort_unless($this->policy()->postTransactions($request->user()), 403);
        $companyId = CompanyContext::resolveCompanyId();

        $invoice = Invoice::query()->where('company_id', $companyId)->findOrFail($invoiceId);
        $postInvoiceToLedgerAction->execute($invoice, $companyId);

        return response()->json(['status' => 'ok']);
    }

    public function adjustInvoice(Request $request, int $invoiceId, AdjustInvoiceAction $adjustInvoiceAction): JsonResponse
    {
        abort_unless($this->policy()->adjustInvoices($request->user()), 403);
        $companyId = CompanyContext::resolveCompanyId();

        $data = $request->validate(['amount' => ['required', 'numeric'], 'reason' => ['required', 'string', 'max:255']]);

        $invoice = Invoice::query()->where('company_id', $companyId)->findOrFail($invoiceId);
        $invoice = $adjustInvoiceAction->execute($invoice, $companyId, (float) $data['amount'], $data['reason']);

        return response()->json(['data' => $invoice]);
    }

    public function writeoffInvoice(Request $request, int $invoiceId, WriteoffInvoiceAction $writeoffInvoiceAction): JsonResponse
    {
        abort_unless($this->policy()->approveWriteoffs($request->user()), 403);
        $companyId = CompanyContext::resolveCompanyId();

        $data = $request->validate(['amount' => ['required', 'numeric', 'min:0.01'], 'reason' => ['required', 'string', 'max:255']]);

        $invoice = Invoice::query()->where('company_id', $companyId)->findOrFail($invoiceId);
        $invoice = $writeoffInvoiceAction->execute($invoice, $companyId, (float) $data['amount'], $data['reason']);

        return response()->json(['data' => $invoice]);
    }

    public function recordPayment(Request $request, int $paymentId, RecordPaymentAction $recordPaymentAction): JsonResponse
    {
        abort_unless($this->policy()->postTransactions($request->user()), 403);
        $companyId = CompanyContext::resolveCompanyId();

        $payment = Payment::query()->where('company_id', $companyId)->findOrFail($paymentId);
        $recordPaymentAction->execute($payment, $companyId);

        return response()->json(['status' => 'ok']);
    }

    public function closePeriod(Request $request, CloseAccountingPeriodAction $closeAccountingPeriodAction): JsonResponse
    {
        abort_unless($this->policy()->closePeriods($request->user()), 403);
        $companyId = CompanyContext::resolveCompanyId();

        $data = $request->validate([
            'period_key' => ['required', 'string', 'max:40'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after_or_equal:period_start'],
        ]);

        $period = $closeAccountingPeriodAction->execute($companyId, $data['period_key'], $data['period_start'], $data['period_end']);

        return response()->json(['data' => $period]);
    }

    public function siteProfitability(Request $request, string $siteRef, SiteProfitabilityService $siteProfitabilityService): JsonResponse
    {
        abort_unless($this->policy()->viewProfitabilityReports($request->user()), 403);
        $companyId = CompanyContext::resolveCompanyId();

        return response()->json(['data' => $siteProfitabilityService->recalculate($companyId, $siteRef)]);
    }

    public function contractProfitability(Request $request, string $contractRef, ContractProfitabilityService $contractProfitabilityService): JsonResponse
    {
        abort_unless($this->policy()->viewProfitabilityReports($request->user()), 403);
        $companyId = CompanyContext::resolveCompanyId();

        return response()->json(['data' => $contractProfitabilityService->recalculate($companyId, $contractRef)]);
    }

    public function transactions(Request $request): JsonResponse
    {
        abort_unless($this->policy()->viewAccounts($request->user()), 403);
        $companyId = CompanyContext::resolveCompanyId();

        return response()->json(FinancialTransaction::query()->where('company_id', $companyId)->latest('id')->paginate(50));
    }
}
