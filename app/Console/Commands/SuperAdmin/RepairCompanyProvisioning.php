<?php

namespace App\Console\Commands\SuperAdmin;

use App\Models\Company;
use App\Models\Currency;
use App\Models\EmployeeDetails;
use App\Models\Role;
use App\Models\SuperAdmin\GlobalCurrency;
use App\Models\User;
use App\Scopes\ActiveScope;
use App\Scopes\CompanyScope;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RepairCompanyProvisioning extends Command
{

    protected $signature = 'superadmin:repair-company-provisioning
        {--company_id= : Repair only one company ID}
        {--apply : Apply changes (default is dry-run report only)}';

    protected $description = 'Repairs company currency/admin role provisioning inconsistencies in a safe idempotent way.';

    private int $anomalyCount = 0;
    private int $fixCount = 0;

    public function handle(): int
    {
        $apply = (bool)$this->option('apply');
        $companyId = $this->option('company_id');

        $companies = Company::withoutGlobalScopes([CompanyScope::class, ActiveScope::class]);

        if (!is_null($companyId)) {
            $companies->where('id', (int)$companyId);
        }

        $companies = $companies->get();

        if ($companies->isEmpty()) {
            $this->warn('No companies found for repair.');

            return self::SUCCESS;
        }

        $mode = $apply ? 'APPLY' : 'DRY-RUN';
        $this->info("Starting company provisioning repair ({$mode}) for {$companies->count()} company(s).");

        foreach ($companies as $company) {
            $this->repairCompany($company, $apply);
        }

        $this->line('');
        $this->info("Completed. Anomalies detected: {$this->anomalyCount}. Fixes applied: {$this->fixCount}.");

        if (!$apply) {
            $this->comment('Dry-run mode made no data changes. Re-run with --apply to persist fixes.');
        }

        return self::SUCCESS;
    }

    private function repairCompany(Company $company, bool $apply): void
    {
        $this->line('');
        $this->line("Company #{$company->id}: {$company->company_name}");

        DB::beginTransaction();

        try {
            $adminRole = $this->ensureRole(
                $company->id,
                'admin',
                'App Administrator',
                'Admin is allowed to manage everything of the app.',
                $apply
            );

            $employeeRole = $this->ensureRole(
                $company->id,
                'employee',
                'Employee',
                'Employee can see tasks and projects assigned to him.',
                $apply
            );

            $this->ensureRole(
                $company->id,
                'client',
                'Client',
                'Client can see own tasks and projects.',
                $apply
            );

            $this->repairCurrency($company, $apply);
            $this->repairAdmins($company, $adminRole, $employeeRole, $apply);

            if ($apply) {
                DB::commit();
            }
            else {
                DB::rollBack();
            }
        } catch (\Throwable $exception) {
            DB::rollBack();
            $this->error("Failed to repair company {$company->id}: {$exception->getMessage()}");
        }
    }

    private function ensureRole(int $companyId, string $roleName, string $displayName, string $description, bool $apply): ?Role
    {
        $role = Role::withoutGlobalScope(CompanyScope::class)
            ->where('company_id', $companyId)
            ->where('name', $roleName)
            ->first();

        if ($role) {
            return $role;
        }

        $this->anomalyCount++;
        $this->warn(" - Missing '{$roleName}' role for company {$companyId}");

        if (!$apply) {
            return null;
        }

        $role = new Role();
        $role->name = $roleName;
        $role->company_id = $companyId;
        $role->display_name = $displayName;
        $role->description = $description;
        $role->saveQuietly();

        $this->fixCount++;
        $this->info("   Created '{$roleName}' role.");

        return $role;
    }

    private function repairCurrency(Company $company, bool $apply): void
    {
        $currency = null;

        if ($company->currency_id) {
            $currency = Currency::withoutGlobalScope(CompanyScope::class)
                ->where('id', $company->currency_id)
                ->where('company_id', $company->id)
                ->first();
        }

        if ($currency) {
            return;
        }

        $this->anomalyCount++;
        $this->warn(" - Invalid or missing currency mapping for company {$company->id}");

        $globalCurrencyCode = optional(global_setting()->currency)->currency_code;

        $fallback = Currency::withoutGlobalScope(CompanyScope::class)
            ->where('company_id', $company->id)
            ->when($globalCurrencyCode, function ($query) use ($globalCurrencyCode) {
                return $query->where('currency_code', $globalCurrencyCode);
            })
            ->first();

        if (!$fallback) {
            $globalCurrency = $globalCurrencyCode ? GlobalCurrency::where('currency_code', $globalCurrencyCode)->first() : null;
            $globalCurrency = $globalCurrency ?: GlobalCurrency::first();

            if ($globalCurrency && $apply) {
                $fallback = new Currency();
                $fallback->currency_name = $globalCurrency->currency_name;
                $fallback->currency_symbol = $globalCurrency->currency_symbol;
                $fallback->currency_code = $globalCurrency->currency_code;
                $fallback->exchange_rate = $globalCurrency->exchange_rate;
                $fallback->currency_position = $globalCurrency->currency_position;
                $fallback->no_of_decimal = $globalCurrency->no_of_decimal;
                $fallback->thousand_separator = $globalCurrency->thousand_separator;
                $fallback->decimal_separator = $globalCurrency->decimal_separator;
                $fallback->is_cryptocurrency = $globalCurrency->is_cryptocurrency;
                $fallback->usd_price = $globalCurrency->usd_price;
                $fallback->company_id = $company->id;
                $fallback->saveQuietly();

                $this->fixCount++;
                $this->info("   Created fallback currency {$fallback->currency_code}.");
            }
        }

        if (!$fallback) {
            $this->warn('   No fallback currency available to assign.');

            return;
        }

        if ($apply) {
            $company->currency_id = $fallback->id;
            $company->saveQuietly();
            $this->fixCount++;
            $this->info("   Updated company currency_id to {$fallback->id} ({$fallback->currency_code}).");
        }
    }

    private function repairAdmins(Company $company, ?Role $adminRole, ?Role $employeeRole, bool $apply): void
    {
        $adminUsers = User::withoutGlobalScopes([CompanyScope::class, ActiveScope::class])
            ->where('users.company_id', $company->id)
            ->whereHas('roles', function ($query) use ($company) {
                $query->withoutGlobalScope(CompanyScope::class)
                    ->where('roles.name', 'admin')
                    ->where('roles.company_id', $company->id);
            })
            ->get();

        foreach ($adminUsers as $adminUser) {
            if ((int)$adminUser->is_superadmin === 1) {
                $this->anomalyCount++;
                $this->warn(" - Tenant admin user {$adminUser->id} has is_superadmin=1");

                if ($apply) {
                    $adminUser->is_superadmin = 0;
                    $adminUser->saveQuietly();
                    $this->fixCount++;
                    $this->info("   Normalized is_superadmin for user {$adminUser->id}.");
                }
            }

            if ($employeeRole && $apply) {
                $adminUser->roles()->syncWithoutDetaching([$employeeRole->id]);
            }

            if ($apply) {
                $this->ensureEmployeeDetails($adminUser, $company->id);
            }
        }

        if ($adminUsers->isNotEmpty()) {
            return;
        }

        $this->anomalyCount++;
        $this->warn(" - No tenant admin mapped for company {$company->id}");

        if (!$apply || !$adminRole) {
            return;
        }

        $candidate = User::withoutGlobalScopes([CompanyScope::class, ActiveScope::class])
            ->where('company_id', $company->id)
            ->orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")
            ->orderBy('id')
            ->first();

        if (!$candidate) {
            $this->warn('   No users available to map as tenant admin.');

            return;
        }

        $candidate->is_superadmin = 0;
        $candidate->saveQuietly();
        $candidate->roles()->syncWithoutDetaching([$adminRole->id]);

        if ($employeeRole) {
            $candidate->roles()->syncWithoutDetaching([$employeeRole->id]);
        }

        $this->ensureEmployeeDetails($candidate, $company->id);

        $this->fixCount++;
        $this->info("   Assigned user {$candidate->id} as company admin.");
    }

    private function ensureEmployeeDetails(User $user, int $companyId): void
    {
        $employeeDetails = EmployeeDetails::firstOrNew(['user_id' => $user->id]);
        $employeeDetails->company_id = $companyId;
        $employeeDetails->employee_id = $employeeDetails->employee_id ?: ('EMP-' . $user->id);
        $employeeDetails->save();
    }

}
