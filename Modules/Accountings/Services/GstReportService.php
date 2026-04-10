<?php

namespace Modules\Accountings\Services;

use Illuminate\Support\Facades\DB;

class GstReportService
{
    /**
     * GST summary with accrual vs cash basis.
     *
     * - accrual: bills by bill_date, invoices by issue_date/created_at
     * - cash: bills by payment date (acc_bill_payments), invoices by paid_at/payment_date when available
     */
    public function summary(?string $from, ?string $to, string $basis = 'accrual'): array
    {
        $basis = in_array($basis, ['accrual', 'cash']) ? $basis : 'accrual';

        $user = auth()->user();
        $companyId = $user->company_id ?? null;
        $userId = $user->id ?? null;

        $schema = DB::getSchemaBuilder();

        // ---------- GST PAID (INPUTS) ----------
        $gstPaidBills = 0.0;

        if ($basis === 'cash' && $schema->hasTable('acc_bill_payments')) {
            // allocate bill tax proportionally to payments: (bill.tax_total / bill.total) * payment.amount
            $pay = DB::table('acc_bill_payments as p')
                ->join('acc_bills as b', 'b.id', '=', 'p.bill_id')
                ->when($companyId, fn($q) => $q->where('p.company_id', $companyId))
                ->when($userId, fn($q) => $q->where('p.user_id', $userId))
                ->whereNotNull('p.paid_at');

            if ($from) $pay->whereDate('p.paid_at', '>=', $from);
            if ($to) $pay->whereDate('p.paid_at', '<=', $to);

            $rows = $pay->select('p.amount', 'b.tax_total', 'b.total')->get();
            $calc = 0.0;
            foreach ($rows as $r) {
                $total = (float)($r->total ?? 0);
                $tax = (float)($r->tax_total ?? 0);
                if ($total <= 0 || $tax <= 0) continue;
                $calc += ((float)$r->amount) * ($tax / $total);
            }
            $gstPaidBills = $calc;
        } else {
            // accrual basis: use bill lines by bill_date or created_at
            $billLines = DB::table('acc_bill_lines as l')
                ->join('acc_bills as b', 'b.id', '=', 'l.bill_id')
                ->when($companyId, fn($q) => $q->where('l.company_id', $companyId))
                ->when($userId, fn($q) => $q->where('l.user_id', $userId));

            $dateCol = $schema->hasColumn('acc_bills', 'bill_date') ? 'b.bill_date' : 'l.created_at';
            if ($from) $billLines->whereDate($dateCol, '>=', $from);
            if ($to) $billLines->whereDate($dateCol, '<=', $to);

            $gstPaidBills = (float) ($billLines->sum('l.line_tax') ?? 0);
        }

        $expenses = DB::table('acc_expenses')
            ->when($companyId, fn($q) => $q->where('company_id', $companyId))
            ->when($userId, fn($q) => $q->where('user_id', $userId));

        if ($from) $expenses->whereDate('expense_date', '>=', $from);
        if ($to) $expenses->whereDate('expense_date', '<=', $to);

        $gstPaidExpenses = (float) ($expenses->sum('tax_amount') ?? 0);
        $gstPaidTotal = round($gstPaidBills + $gstPaidExpenses, 2);

        // ---------- GST COLLECTED (OUTPUTS) ----------
        $gstCollectedTotal = 0.0;
        if ($schema->hasTable('invoices')) {
            $inv = DB::table('invoices')
                ->when($companyId, fn($q) => $schema->hasColumn('invoices','company_id') ? $q->where('company_id', $companyId) : $q)
                ->when($userId, fn($q) => $schema->hasColumn('invoices','user_id') ? $q->where('user_id', $userId) : $q);

            // date col for basis
            $dateCol = null;
            if ($basis === 'cash') {
                foreach (['paid_at','payment_date','paid_date','updated_at'] as $c) {
                    if ($schema->hasColumn('invoices', $c)) { $dateCol = $c; break; }
                }
            } else {
                foreach (['issue_date','invoice_date','created_at'] as $c) {
                    if ($schema->hasColumn('invoices', $c)) { $dateCol = $c; break; }
                }
            }
            if ($from && $dateCol) $inv->whereDate($dateCol, '>=', $from);
            if ($to && $dateCol) $inv->whereDate($dateCol, '<=', $to);

            if ($basis === 'cash') {
                // best-effort: require paid marker
                foreach (['paid_at','paid_date'] as $c) {
                    if ($schema->hasColumn('invoices', $c)) { $inv->whereNotNull($c); break; }
                }
                foreach (['status','payment_status'] as $c) {
                    if ($schema->hasColumn('invoices', $c)) { $inv->whereIn($c, ['paid','Paid','PAID']); break; }
                }
            }

            foreach (['tax', 'tax_total', 'total_tax', 'gst'] as $col) {
                if ($schema->hasColumn('invoices', $col)) {
                    $gstCollectedTotal = (float) ($inv->sum($col) ?? 0);
                    break;
                }
            }

            if ($gstCollectedTotal == 0.0) {
                if ($schema->hasColumn('invoices', 'total') && $schema->hasColumn('invoices', 'sub_total')) {
                    $rows = $inv->select('total', 'sub_total')->get();
                    $calc = 0.0;
                    foreach ($rows as $r) {
                        $calc += max(0, ((float)$r->total - (float)$r->sub_total));
                    }
                    $gstCollectedTotal = $calc;
                }
            }
        }

        $gstCollectedTotal = round((float)$gstCollectedTotal, 2);
        $gstPaidBills = round((float)$gstPaidBills, 2);
        $gstPaidExpenses = round((float)$gstPaidExpenses, 2);
        $net = round($gstCollectedTotal - $gstPaidTotal, 2);

        return [
            'from' => $from,
            'to' => $to,
            'basis' => $basis,
            'gst_collected' => $gstCollectedTotal,
            'gst_paid' => $gstPaidTotal,
            'gst_paid_bills' => $gstPaidBills,
            'gst_paid_expenses' => $gstPaidExpenses,
            'net_gst' => $net,
        ];
    }
}
