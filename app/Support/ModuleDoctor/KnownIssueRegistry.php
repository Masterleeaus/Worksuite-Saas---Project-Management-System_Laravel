<?php

namespace App\Support\ModuleDoctor;

class KnownIssueRegistry
{
    public function detect(array $context, array $signals = []): array
    {
        $issues = [];
        if (!empty($signals['sidebar']['unsupported_patterns'])) {
            $issues[] = [
                'key' => 'foreign_permission_api',
                'severity' => 'warn',
                'title' => 'Sidebar uses foreign permission API',
                'detail' => 'Detected references like isAbleTo(), @permission, or Spatie-style middleware inside the sidebar/menu layer.',
            ];
        }
        if (!empty($signals['routes']['middleware_issues'])) {
            $issues[] = [
                'key' => 'route_permission_middleware',
                'severity' => 'warn',
                'title' => 'Route file references unsupported permission middleware',
                'detail' => 'The package still references permission middleware that often fails on Worksuite-only installs.',
            ];
        }
        if (empty($signals['sidebar']['files'])) {
            $issues[] = [
                'key' => 'no_sidebar',
                'severity' => 'warn',
                'title' => 'No user sidebar integration detected',
                'detail' => 'The module may install but remain invisible in the user menu.',
            ];
        }
        if (empty($signals['permissions']['permission_keys']) && empty($signals['permissions']['seeders'])) {
            $issues[] = [
                'key' => 'no_permission_chain',
                'severity' => 'warn',
                'title' => 'No permission chain detected',
                'detail' => 'The module has no clear way to grant sidebar visibility to tenant users.',
            ];
        }

        return $issues;
    }
}
