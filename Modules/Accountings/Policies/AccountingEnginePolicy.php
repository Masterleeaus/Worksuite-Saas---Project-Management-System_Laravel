<?php

namespace Modules\Accountings\Policies;

use App\Models\User;

class AccountingEnginePolicy
{
    private function hasPermission(User $user, string $permission): bool
    {
        if (method_exists($user, 'hasRole') && ($user->hasRole('superadmin') || $user->hasRole('admin'))) {
            return true;
        }

        return method_exists($user, 'permission') && !in_array($user->permission($permission), [false, null, 'none'], true);
    }

    public function viewAccounts(User $user): bool { return $this->hasPermission($user, 'view_accounts'); }
    public function postTransactions(User $user): bool { return $this->hasPermission($user, 'post_transactions'); }
    public function adjustInvoices(User $user): bool { return $this->hasPermission($user, 'adjust_invoices'); }
    public function approveWriteoffs(User $user): bool { return $this->hasPermission($user, 'approve_writeoffs'); }
    public function closePeriods(User $user): bool { return $this->hasPermission($user, 'close_periods'); }
    public function viewProfitabilityReports(User $user): bool { return $this->hasPermission($user, 'view_profitability_reports'); }
    public function exportFinancialReports(User $user): bool { return $this->hasPermission($user, 'export_financial_reports'); }
}
