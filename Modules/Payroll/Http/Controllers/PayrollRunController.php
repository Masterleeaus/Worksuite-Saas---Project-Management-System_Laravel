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
     */
    public function store(Request $request)
    {
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
     */
    public function approve($id)
    {
        $run = PayrollRun::where('company_id', company()->id)->findOrFail($id);
        abort_403($run->isApproved());
        abort_403(!in_array('admin', user_roles()));

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
     */
    public function exportCsv($id)
    {
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
     */
    public function exportPdf($id)
    {
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
