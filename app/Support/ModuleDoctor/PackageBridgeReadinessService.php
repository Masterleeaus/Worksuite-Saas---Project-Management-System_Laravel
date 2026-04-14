<?php

namespace App\Support\ModuleDoctor;

class PackageBridgeReadinessService
{
    public function inspect(string|array $module): array
    {
        $moduleName = is_array($module)
            ? (string) ($module['name'] ?? $module['module_name'] ?? $module['module'] ?? '')
            : (string) $module;

        $bridge = app(PackageEntitlementAuditService::class)->inspect($moduleName);
        $company = app(CompanyModuleSettingAuditService::class)->inspect($moduleName);
        $registry = app(ModuleRegistryAuditService::class)->inspect($moduleName);

        $bridgeReady = ($bridge['ready'] ?? (($bridge['status'] ?? 'warn') === 'pass'));
        $companyReady = ($company['ready'] ?? (($company['status'] ?? 'warn') === 'pass'));
        $registryReady = ($registry['ready'] ?? (($registry['status'] ?? 'warn') === 'pass'));

        return [
            'module_name' => $moduleName,
            'bridge' => $bridge,
            'company' => $company,
            'registry' => $registry,
            'ready' => $moduleName !== '' && $bridgeReady && $companyReady && $registryReady,
        ];
    }
}
