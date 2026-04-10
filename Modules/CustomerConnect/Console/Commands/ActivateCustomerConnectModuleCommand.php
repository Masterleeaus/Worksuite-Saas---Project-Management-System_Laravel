<?php

namespace Modules\CustomerConnect\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\Module;
use Modules\CustomerConnect\Entities\CustomerConnectSetting;

class ActivateCustomerConnectModuleCommand extends Command
{
    protected $signature = 'customerconnect:activate';
    protected $description = 'Backfill CustomerConnect module settings for all companies';

    public function handle(): int
    {
        // Ensure module row exists (Packages visibility)
        Module::firstOrCreate(
            ['module_name' => CustomerConnectSetting::MODULE_NAME],
            ['is_superadmin' => 0]
        );

        $companies = Company::query()->get();
        foreach ($companies as $company) {
            CustomerConnectSetting::addModuleSetting($company);
        }

        $this->info('CustomerConnect activated for all companies.');
        return self::SUCCESS;
    }
}
