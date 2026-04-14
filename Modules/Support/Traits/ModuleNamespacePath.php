<?php

namespace Modules\Support\Traits;

trait ModuleNamespacePath
{
    protected function moduleNamespace(string $module, string $path = ''): string
    {
        $baseNamespace = trim(config('modules.namespace', 'Modules'), '\\');
        $normalizedPath = $this->normalizeNamespacePath($path);

        return $normalizedPath === ''
            ? $baseNamespace.'\\'.$module
            : $baseNamespace.'\\'.$module.'\\'.$normalizedPath;
    }

    protected function normalizeNamespacePath(string $path): string
    {
        return trim(str_replace('/', '\\', $path), '\\');
    }
}
