<?php

namespace App\Support\ModuleDoctor;

class ModuleDoctorManifest
{
    public function build(): array
    {
        return [
            'workspace' => 'worksuite',
            'features' => [
                'visibility_report',
                'tenant_simulation',
                'known_issue_rules',
                'auto_repair_queue',
                'protected_paths',
            ],
        ];
    }
}
