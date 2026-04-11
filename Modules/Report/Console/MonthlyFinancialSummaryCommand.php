<?php

namespace Modules\Report\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * MonthlyFinancialSummaryCommand
 *
 * Emails a monthly financial summary (invoice reconciliation) to admins.
 * Schedule: first day of each month.
 *
 * Usage: php artisan report:monthly-financial-summary
 */
class MonthlyFinancialSummaryCommand extends Command
{
    protected $signature = 'report:monthly-financial-summary';
    protected $description = 'Email monthly financial summary with invoice reconciliation to admins';

    public function handle(): int
    {
        $lastMonth  = Carbon::now()->subMonth();
        $startDate  = $lastMonth->copy()->startOfMonth()->toDateString();
        $endDate    = $lastMonth->copy()->endOfMonth()->toDateString();
        $monthLabel = $lastMonth->format('F Y');

        if (!DB::getSchemaBuilder()->hasTable('payments') || !DB::getSchemaBuilder()->hasTable('invoices')) {
            $this->warn('payments / invoices tables not found — skipping monthly financial summary.');
            return self::SUCCESS;
        }

        $revenue = DB::table('payments')
            ->where('status', 'complete')
            ->whereBetween(DB::raw('DATE(paid_on)'), [$startDate, $endDate])
            ->sum('paid_amount');

        $invoiceCount = DB::table('invoices')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->count();

        $unpaidInvoices = DB::table('invoices')
            ->where('status', 'unpaid')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->count();

        $admins = DB::table('users')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', 'admin')
            ->whereNotNull('users.email')
            ->pluck('users.email')
            ->unique();

        foreach ($admins as $email) {
            try {
                Mail::raw(
                    "Monthly Financial Summary — {$monthLabel}\n\n"
                    . "Total Revenue Collected : " . number_format($revenue, 2) . "\n"
                    . "Invoices Raised         : {$invoiceCount}\n"
                    . "Unpaid Invoices         : {$unpaidInvoices}\n",
                    function ($msg) use ($email, $monthLabel) {
                        $msg->to($email)
                            ->subject("Monthly Financial Summary: {$monthLabel}");
                    }
                );
            } catch (\Throwable $e) {
                $this->error("Failed to send to {$email}: {$e->getMessage()}");
            }
        }

        $this->info("Monthly financial summary ({$monthLabel}) sent to {$admins->count()} admin(s).");
        return self::SUCCESS;
    }
}
