<?php

namespace Modules\ServiceManagement\Listeners;

use App\Models\ModuleSetting;

class CompanyCreatedListener
{
    public function handle($event): void
    {
        $company = $event->company ?? null;

        if (!$company) {
            return;
        }

        $isAllowed = $this->isModuleInCompanyPackage($company);
        $status = $isAllowed ? 'active' : 'deactive';

        foreach (['admin', 'employee', 'client'] as $type) {
            ModuleSetting::withoutGlobalScopes()->updateOrCreate(
                [
                    'company_id' => $company->id,
                    'module_name' => 'servicemanagement',
                    'type' => $type,
                ],
                [
                    'status' => $status,
                    'is_allowed' => $isAllowed ? 1 : 0,
                ]
            );
        }
    }

    private function isModuleInCompanyPackage($company): bool
    {
        $moduleList = data_get($company, 'package.module_in_package');

        if (blank($moduleList) && method_exists($company, 'loadMissing')) {
            $company->loadMissing('package');
            $moduleList = data_get($company, 'package.module_in_package');
        }

        $decoded = json_decode((string) $moduleList, true);
        if (!is_array($decoded)) {
            return true;
        }

        $modules = collect($decoded)
            ->filter(fn ($value) => is_string($value) && $value !== '')
            ->map(fn (string $value) => strtolower($value))
            ->values();

        return $modules->contains('servicemanagement');
    }
}
