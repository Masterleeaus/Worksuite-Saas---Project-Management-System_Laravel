<?php

namespace Modules\Payroll\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Payroll\Entities\CleanerRateConfig;
use Modules\Payroll\Entities\PayrollRun;
use Modules\Payroll\Entities\PayrollRunLineItem;
use Modules\Payroll\Entities\PayrollRunOverride;
use Modules\Payroll\Entities\PayrollSetting;
use Modules\Payroll\Services\VariableRateEngine;

class PayrollRunController extends AccountBaseController
{
    protected VariableRateEngine $engine;

    public function __construct(VariableRateEngine $engine)
    {
        parent::__construct();
        $this->engine = $engine;
        $this->pageTitle = __('payroll::app.menu.payrollRuns');
        $this->middleware(function ($request, $next) {
            abort_403(!in_array(PayrollSetting::MODULE_NAME, $this->user->modules));
            return $next($request);
        });
    }

    public function index()
    {
        abort_403(!in_array(user()->permission('view_payroll'), ['all', 'added', 'owned', 'both']));

        $this->runs = PayrollRun::where('company_id', company()->id)
            ->orderByDesc('period_start')
            ->paginate(20);

        return view('payroll::payroll-run.index', $this->data);
    }

    public function create()
    {
        $this->states = VariableRateEngine::AU_STATES;
        $html = view('payroll::payroll-run.ajax.create', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'html' => $html]);
    }

    /**
     * Create a new draft payroll run.
     * Requires run_payroll permission.
     */
    public function store(Request $request)
    {
        abort_403(!in_array(user()->permission('run_payroll'), ['all']));

        $request->validate([
            'period_start' => 'required|date',
            'period_end'   => 'required|date|after_or_equal:period_start',
            'state'        => 'nullable|in:' . implode(',', VariableRateEngine::AU_STATES),
        ]);

        $run = PayrollRun::create([
            'company_id'   => company()->id,
            'period_start' => $request->period_start,
            'period_end'   => $request->period_end,
            'state'        => $request->state,
            'status'       => 'draft',
            'created_by'   => user()->id,
            'notes'        => $request->notes,
        ]);

        return Reply::successWithData(
            __('messages.recordSaved'),
            ['redirectUrl' => route('payroll-runs.preview', $run->id)]
        );
    }

    /**
     * Preview a payroll run – calculate line items from job card data.
     * For demonstration, this accepts job data via POST (job_cards[] array).
     * In production, hook this to the actual JobCard model/query.
     */
    public function preview($id, Request $request)
    {
        $run = PayrollRun::where('company_id', company()->id)->findOrFail($id);
        abort_403($run->isApproved());

        // Accept manually submitted job_cards for preview/recalculation
        if ($request->isMethod('post') && $request->has('job_cards')) {
            $this->recalculate($run, $request->input('job_cards', []));
        }

        $run->load(['lineItems.user', 'lineItems.overrides.overriddenBy']);

        $this->run = $run;

        // Group line items by user
        $this->byEmployee = $run->lineItems->groupBy('user_id')->map(function ($items) {
            return [
                'user'      => $items->first()->user,
                'items'     => $items,
                'total_pay' => $items->sum('total_pay'),
            ];
        });

        $this->states = VariableRateEngine::AU_STATES;
        $this->employees = User::join('employee_details', 'employee_details.user_id', 'users.id')
            ->select('users.id', 'users.name')
            ->get();

        return view('payroll::payroll-run.preview', $this->data);
    }

    /**
     * Override a single line item pay amount.
     */
    public function overrideLineItem(Request $request, $runId, $lineItemId)
    {
        $request->validate([
            'new_total_pay' => 'required|numeric|min:0',
            'reason'        => 'required|string|min:5|max:1000',
        ]);

        $run = PayrollRun::where('company_id', company()->id)->findOrFail($runId);
        abort_403($run->isApproved());

        $item = PayrollRunLineItem::where('payroll_run_id', $runId)->findOrFail($lineItemId);

        PayrollRunOverride::create([
            'line_item_id'       => $item->id,
            'overridden_by'      => user()->id,
            'original_total_pay' => $item->total_pay,
            'new_total_pay'      => $request->new_total_pay,
            'reason'             => $request->reason,
        ]);

        $item->update([
            'total_pay'     => $request->new_total_pay,
            'is_overridden' => true,
        ]);

        return Reply::success(__('payroll::app.overrideApplied'));
    }

    /**
     * Approve the payroll run.
     * Requires approve_payroll permission and admin role.
     */
    public function approve($id)
    {
        abort_403(!in_array(user()->permission('approve_payroll'), ['all']));
        abort_403(!in_array('admin', user_roles()));

        $run = PayrollRun::where('company_id', company()->id)->findOrFail($id);
        abort_403($run->isApproved());

        $run->update([
            'status'      => 'approved',
            'approved_by' => user()->id,
            'approved_at' => now(),
        ]);

        return Reply::successWithData(__('payroll::app.payrollRunApproved'), [
            'redirectUrl' => route('payroll-runs.show', $id),
        ]);
    }

    public function show($id)
    {
        $run = PayrollRun::where('company_id', company()->id)
            ->with(['lineItems.user', 'lineItems.overrides.overriddenBy', 'creator', 'approver'])
            ->findOrFail($id);

        $this->run = $run;
        $this->byEmployee = $run->lineItems->groupBy('user_id')->map(function ($items) {
            return [
                'user'      => $items->first()->user,
                'items'     => $items,
                'total_pay' => $items->sum('total_pay'),
            ];
        });

        return view('payroll::payroll-run.show', $this->data);
    }

    /**
     * Export payroll run to CSV.
     * Requires export_payroll permission.
     */
    public function exportCsv($id)
    {
        abort_403(!in_array(user()->permission('export_payroll'), ['all']));

        $run = PayrollRun::where('company_id', company()->id)
            ->with(['lineItems.user', 'lineItems.overrides'])
            ->findOrFail($id);

        abort_403(!$run->isApproved());

        $filename = 'payroll_run_' . $run->id . '_' . $run->period_start . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($run) {
            $handle = fopen('php://output', 'w');
            // Header row compatible with MYOB/Xero/QuickBooks
            fputcsv($handle, [
                'Employee ID', 'Employee Name', 'Job Date', 'Job Start', 'Job End',
                'Hours Worked', 'Rate Type', 'Rate Applied', 'Gross Pay',
                'Rooms Cleaned', 'Commission', 'Total Pay', 'Is Public Holiday', 'Overridden', 'Notes',
            ]);

            foreach ($run->lineItems as $item) {
                fputcsv($handle, [
                    $item->user_id,
                    optional($item->user)->name,
                    $item->job_date,
                    $item->job_start,
                    $item->job_end,
                    $item->hours_worked,
                    $item->rate_type,
                    $item->rate_applied,
                    $item->gross_pay,
                    $item->rooms_cleaned,
                    $item->commission_amount,
                    $item->total_pay,
                    $item->is_public_holiday ? 'Yes' : 'No',
                    $item->is_overridden ? 'Yes' : 'No',
                    $item->notes,
                ]);
            }

            // Summary rows per employee
            fputcsv($handle, []);
            fputcsv($handle, ['--- SUMMARY ---']);
            fputcsv($handle, ['Employee ID', 'Employee Name', 'Total Hours', 'Total Pay']);

            $grouped = $run->lineItems->groupBy('user_id');
            foreach ($grouped as $userId => $items) {
                fputcsv($handle, [
                    $userId,
                    optional($items->first()->user)->name,
                    round($items->sum('hours_worked'), 2),
                    round($items->sum('total_pay'), 2),
                ]);
            }

            fclose($handle);
        };

        $run->update(['status' => 'exported']);

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export payroll run to PDF.
     * Requires export_payroll permission.
     */
    public function exportPdf($id)
    {
        abort_403(!in_array(user()->permission('export_payroll'), ['all']));
        $run = PayrollRun::where('company_id', company()->id)
            ->with(['lineItems.user', 'lineItems.overrides.overriddenBy', 'creator', 'approver'])
            ->findOrFail($id);

        abort_403(!$run->isApproved());

        $this->run = $run;
        $this->byEmployee = $run->lineItems->groupBy('user_id')->map(function ($items) {
            return [
                'user'        => $items->first()->user,
                'items'       => $items,
                'total_hours' => round($items->sum('hours_worked'), 2),
                'total_pay'   => round($items->sum('total_pay'), 2),
            ];
        });

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('payroll::payroll-run.pdf', $this->data);

        $run->update(['status' => 'exported']);

        return $pdf->download('payroll_run_' . $run->id . '_' . $run->period_start . '.pdf');
    }

    public function destroy($id)
    {
        $run = PayrollRun::where('company_id', company()->id)->findOrFail($id);
        abort_403($run->isApproved());
        $run->delete();
        return Reply::success(__('messages.deleteSuccess'));
    }

    /**
     * Export ABA bank file for bulk payment of this payroll run.
     * Requires export_payroll permission and an approved run.
     */
    public function exportAba($id)
    {
        abort_403(!in_array(user()->permission('export_payroll'), ['all']));

        $run = PayrollRun::where('company_id', company()->id)
            ->with('payslips.employee.employeeDetail')
            ->findOrFail($id);

        abort_403(!$run->isApproved());

        $abaService = app(\Modules\Payroll\Services\AbaFileService::class);
        $payrollSetting = \Modules\Payroll\Entities\PayrollSetting::where('company_id', company()->id)->firstOrFail();

        // Build header from company/payroll settings
        $companyBsb    = $payrollSetting->bank_bsb     ?? '000-000';
        $companyAccount = $payrollSetting->bank_account ?? '000000000';
        $companyName   = company()->company_name ?? 'Company';

        $header = [
            'bsb'             => $companyBsb,
            'account_number'  => $companyAccount,
            'bank_name'       => $payrollSetting->bank_code ?? 'NAB',
            'user_name'       => mb_substr($companyName, 0, 26),
            'user_number'     => $payrollSetting->apca_user_number ?? '000000',
            'description'     => 'PAYROLL',
            'processing_date' => now()->format('dmY'),
        ];

        $records = [];
        foreach ($run->payslips as $payslip) {
            $emp = optional($payslip->employee);
            $detail = optional($emp->employeeDetail);

            $bsb     = $detail->bank_bsb ?? null;
            $account = $detail->bank_account_number ?? null;

            if (empty($bsb) || empty($account)) {
                continue; // skip employees without bank details
            }

            $records[] = [
                'bsb'            => $bsb,
                'account_number' => $account,
                'account_name'   => mb_substr($detail->bank_account_name ?? $emp->name ?? '', 0, 32),
                'amount_cents'   => (int) round((float) $payslip->net_pay * 100),
                'lodgement_ref'  => mb_substr('PAY ' . $run->period_end, 0, 18),
                'trace_bsb'      => $companyBsb,
                'trace_account'  => $companyAccount,
                'remitter_name'  => mb_substr($companyName, 0, 16),
                'tax_amount'     => (int) round((float) $payslip->tax_withheld * 100),
            ];
        }

        if (empty($records)) {
            return Reply::error(__('payroll::app.noPayslipsForAba'));
        }

        $abaContent = $abaService->generate($header, $records);
        $filename   = 'payroll_run_' . $run->id . '_' . $run->period_end . '.aba';

        return response($abaContent, 200, [
            'Content-Type'        => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Calculate / recalculate line items from job card data.
     * $jobCards is an array of:
     * [
     *   'user_id'       => int,
     *   'job_start'     => 'Y-m-d H:i:s',
     *   'job_end'       => 'Y-m-d H:i:s',
     *   'rooms_cleaned' => int,
     *   'contract_ref'  => string|null,
     *   'source_ref'    => string|null,
     * ]
     */
    protected function recalculate(PayrollRun $run, array $jobCards): void
    {
        // Delete existing draft line items
        $run->lineItems()->delete();

        foreach ($jobCards as $card) {
            $jobStart = Carbon::parse($card['job_start']);
            $jobEnd   = Carbon::parse($card['job_end']);

            if ($jobEnd <= $jobStart) {
                continue;
            }

            $result = $this->engine->calculate(
                (int) ($card['user_id'] ?? 0),
                $jobStart,
                $jobEnd,
                (int) ($card['rooms_cleaned'] ?? 0),
                $card['contract_ref'] ?? null,
                $run->state
            );

            PayrollRunLineItem::create([
                'payroll_run_id'    => $run->id,
                'user_id'           => $card['user_id'],
                'job_date'          => $jobStart->toDateString(),
                'job_start'         => $jobStart,
                'job_end'           => $jobEnd,
                'hours_worked'      => $result['total_hours'],
                'rate_type'         => $result['rate_type'],
                'rate_applied'      => $result['rate_applied'],
                'gross_pay'         => $result['gross_pay'],
                'rooms_cleaned'     => $card['rooms_cleaned'] ?? 0,
                'commission_amount' => $result['commission'],
                'total_pay'         => $result['total_pay'],
                'is_public_holiday' => $result['is_public_holiday'],
                'source_ref'        => $card['source_ref'] ?? null,
            ]);
        }

        $run->update(['status' => 'preview']);
    }
}
