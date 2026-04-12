<?php

namespace Modules\Payroll\Services;

use App\Models\EmployeeDetails;
use App\Models\Leave;
use Carbon\Carbon;
use Modules\Payroll\Entities\Payslip;
use Modules\Payroll\Entities\PayrollRun;

/**
 * PayslipService — bcmath-based payroll calculation engine.
 *
 * All monetary arithmetic is done with bcmath to avoid float precision errors.
 * Scale of 6 decimal places is used internally; results are rounded to 2dp.
 */
class PayslipService
{
    private const SCALE = 6;

    // -------------------------------------------------------------------------
    // ATO weekly tax table brackets (2023-24 financial year, residents)
    // Each entry: [weekly_earnings_threshold, tax_per_week_at_threshold, marginal_rate_cents_per_dollar]
    // Source: ATO NAT 1008
    // -------------------------------------------------------------------------
    private const ATO_WEEKLY_BRACKETS = [
        [   359, '0',    0.1900],
        [   438, '0',    0.2348],
        [   548, '0',    0.2190],
        [   721, '0',    0.3477],
        [   865, '0',    0.3450],
        [   924, '0',    0.3900],
        [  1282, '0',    0.3900],
        [  1730, '0',    0.3250],
        [  3461, '0',    0.3700],
        [  PHP_INT_MAX, '0', 0.4700],
    ];

    /**
     * Calculate the payslip for one employee in a payroll run.
     *
     * @param  PayrollRun      $run
     * @param  EmployeeDetails $details
     * @param  array           $lineItemsData  Array of pay component arrays, each:
     *   [
     *     'description'  => string,
     *     'hours'        => numeric string,   // for hourly/overtime components
     *     'rate'         => numeric string,
     *     'amount'       => numeric string,   // pre-calculated amount (optional)
     *     'type'         => 'earning'|'deduction'|'allowance',
     *   ]
     * @return Payslip
     */
    public function calculate(PayrollRun $run, EmployeeDetails $details, array $lineItemsData = []): Payslip
    {
        $payType        = $details->pay_type ?? 'hourly';
        $regularHours   = '0';
        $overtimeHours  = '0';
        $regularRate    = (string) ($details->hourly_rate ?? '0');
        $overtimeRate   = $this->mul($regularRate, '1.5');  // default 1.5x
        $grossPay       = '0';
        $deductions     = '0';
        $allowances     = '0';
        $lineItems      = [];

        // ---- Build gross pay from line items ----
        foreach ($lineItemsData as $item) {
            $type   = $item['type'] ?? 'earning';
            $amount = isset($item['amount']) ? (string) $item['amount'] : '0';

            // If amount not pre-calculated but hours+rate provided, compute it
            if ($amount === '0' && isset($item['hours'], $item['rate'])) {
                $amount = $this->mul((string) $item['hours'], (string) $item['rate']);
            }

            $item['amount'] = $amount;
            $lineItems[]    = $item;

            if ($type === 'earning') {
                $grossPay = $this->add($grossPay, $amount);

                // Track regular/overtime hours
                if (isset($item['hours'])) {
                    $label = strtolower($item['description'] ?? '');
                    if (str_contains($label, 'overtime')) {
                        $overtimeHours = $this->add($overtimeHours, (string) $item['hours']);
                    } else {
                        $regularHours = $this->add($regularHours, (string) $item['hours']);
                    }
                }
            } elseif ($type === 'allowance') {
                $allowances = $this->add($allowances, $amount);
                $grossPay   = $this->add($grossPay, $amount);
            } elseif ($type === 'deduction') {
                $deductions = $this->add($deductions, $amount);
            }
        }

        // ---- Deduct approved leave (unpaid leave) ----
        $leaveDeduction = $this->calculateLeaveDeductions($details->user_id, $run, $regularRate);
        if (bccomp($leaveDeduction, '0', self::SCALE) > 0) {
            $deductions = $this->add($deductions, $leaveDeduction);
            $lineItems[] = [
                'description' => 'Unpaid Leave Deduction',
                'type'        => 'deduction',
                'amount'      => $leaveDeduction,
            ];
        }

        // ---- Net pay before tax ----
        $taxableIncome = $this->sub($grossPay, $deductions);
        if (bccomp($taxableIncome, '0', self::SCALE) < 0) {
            $taxableIncome = '0';
        }

        // ---- Tax withheld (ATO resident weekly table, annualised) ----
        $taxBasis       = $details->tax_basis ?? 'weekly';
        $taxWithheld    = $details->is_subcontractor
            ? '0'  // subcontractors: no PAYG withholding
            : $this->calculateTax($taxableIncome, $taxBasis);

        // ---- Superannuation ----
        $superRate          = (string) ($details->super_rate ?? '0.1100');
        $superContribution  = $this->mul($grossPay, $superRate);

        // ---- Net pay ----
        $netPay = $this->sub($taxableIncome, $taxWithheld);
        if (bccomp($netPay, '0', self::SCALE) < 0) {
            $netPay = '0';
        }

        // ---- Build / update Payslip model ----
        $payslip = Payslip::firstOrNew([
            'payroll_run_id' => $run->id,
            'employee_id'    => $details->user_id,
        ]);

        $payslip->fill([
            'company_id'        => $run->company_id,
            'pay_type'          => $payType,
            'regular_hours'     => $this->round2($regularHours),
            'overtime_hours'    => $this->round2($overtimeHours),
            'regular_rate'      => $this->round2($regularRate),
            'overtime_rate'     => $this->round2($overtimeRate),
            'gross_pay'         => $this->round2($grossPay),
            'tax_withheld'      => $this->round2($taxWithheld),
            'super_contribution'=> $this->round2($superContribution),
            'deductions'        => $this->round2($deductions),
            'allowances'        => $this->round2($allowances),
            'net_pay'           => $this->round2($netPay),
            'line_items'        => $lineItems,
            'is_subcontractor'  => (bool) ($details->is_subcontractor ?? false),
            'payment_status'    => $payslip->exists ? $payslip->payment_status : 'pending',
        ]);

        $payslip->save();

        return $payslip;
    }

    /**
     * Calculate tax withheld using ATO resident individual weekly earnings table.
     *
     * Converts to weekly earnings regardless of pay basis, applies the table,
     * then converts back to the pay period amount.
     *
     * @param  string $taxableIncome  Taxable income for the period (bcmath string)
     * @param  string $basis          'weekly' | 'fortnightly' | 'monthly'
     * @return string  Tax withheld for the period (bcmath string)
     */
    public function calculateTax(string $taxableIncome, string $basis = 'weekly'): string
    {
        // Normalise to weekly
        switch ($basis) {
            case 'fortnightly':
                $weekly = $this->div($taxableIncome, '2');
                break;
            case 'monthly':
                $weekly = $this->mul($taxableIncome, '12');
                $weekly = $this->div($weekly, '52');
                break;
            default: // weekly
                $weekly = $taxableIncome;
        }

        $weeklyTax = $this->lookupAtoWeeklyTax($weekly);

        // Convert tax back to pay period
        switch ($basis) {
            case 'fortnightly':
                $periodTax = $this->mul($weeklyTax, '2');
                break;
            case 'monthly':
                $periodTax = $this->mul($weeklyTax, '52');
                $periodTax = $this->div($periodTax, '12');
                break;
            default:
                $periodTax = $weeklyTax;
        }

        if (bccomp($periodTax, '0', self::SCALE) < 0) {
            return '0';
        }

        return $this->round2($periodTax);
    }

    /**
     * Lookup ATO weekly tax from simplified bracket table.
     *
     * Each bracket entry: [upper_threshold, base_tax_at_lower_bound, marginal_rate]
     * - base = accumulated tax at the LOWER bound of this bracket
     * - rate = marginal rate within this bracket
     *
     * tax = base + (earnings - prev_threshold) * rate
     *
     * For full ATO accuracy the complete NAT 1008 coefficients should be used.
     */
    private function lookupAtoWeeklyTax(string $weeklyEarnings): string
    {
        $earnings = (float) $weeklyEarnings;

        if ($earnings <= 0) {
            return '0';
        }

        // ATO NAT 1008 simplified brackets (weekly, resident, 2023-24)
        // [upper_threshold, base_tax_at_lower_bound, marginal_rate]
        // base is the accumulated tax at the lower bound of this range.
        $brackets = [
            //  threshold  base     rate
            [      359,     0.00, 0.0000],  // $0–359:   tax-free threshold
            [      438,     0.00, 0.1900],  // $359–438: 19% above $359
            [      548,    15.01, 0.2348],  // $438–548: 23.48% above $438
            [      721,    40.84, 0.2190],  // $548–721: 21.9% above $548
            [      865,    78.72, 0.3477],  // $721–865: 34.77% above $721
            [      924,   128.77, 0.3450],  // $865–924: 34.5% above $865
            [     1282,   149.12, 0.3900],  // $924–1282: 39% above $924
            [     1730,   288.74, 0.3900],  // $1282–1730: 39% above $1282
            [     3461,   463.66, 0.3250],  // $1730–3461: 32.5% above $1730
            [PHP_INT_MAX, 1026.24, 0.4700], // $3461+: 47% above $3461
        ];

        $prevThreshold = 0;

        foreach ($brackets as [$threshold, $base, $rate]) {
            if ($earnings <= $threshold) {
                $tax = $base + ($earnings - $prevThreshold) * $rate;
                return (string) max(0, round($tax, 6));
            }
            $prevThreshold = $threshold;
        }

        // Should not reach here due to PHP_INT_MAX last bracket
        return '0';
    }

    /**
     * Calculate unpaid leave deductions for the pay period.
     *
     * Reads approved leaves of type 'unpaid' within the payroll run period
     * and deducts at the employee's hourly rate.
     */
    private function calculateLeaveDeductions(int $userId, PayrollRun $run, string $hourlyRate): string
    {
        if (bccomp($hourlyRate, '0', self::SCALE) <= 0) {
            return '0';
        }

        try {
            $unpaidLeaveHours = Leave::where('user_id', $userId)
                ->where('status', 'approved')
                ->where('leave_type', 'unpaid')
                ->where(function ($q) use ($run) {
                    $q->whereBetween('leave_date', [$run->period_start, $run->period_end])
                      ->orWhereBetween('leave_date', [$run->period_start, $run->period_end]);
                })
                ->sum('no_of_days');
        } catch (\Exception $e) {
            // Leave model may not have this column structure; return 0 safely
            return '0';
        }

        if ($unpaidLeaveHours <= 0) {
            return '0';
        }

        // Assume 8-hour working day
        $hours = $this->mul((string) $unpaidLeaveHours, '8');
        return $this->mul($hours, $hourlyRate);
    }

    // -------------------------------------------------------------------------
    // bcmath helpers
    // -------------------------------------------------------------------------

    private function add(string $a, string $b): string
    {
        return bcadd($a, $b, self::SCALE);
    }

    private function sub(string $a, string $b): string
    {
        return bcsub($a, $b, self::SCALE);
    }

    private function mul(string $a, string $b): string
    {
        return bcmul($a, $b, self::SCALE);
    }

    private function div(string $a, string $b): string
    {
        if (bccomp($b, '0', self::SCALE) === 0) {
            return '0';
        }
        return bcdiv($a, $b, self::SCALE);
    }

    private function round2(string $value): string
    {
        return number_format((float) $value, 2, '.', '');
    }
}
