<?php

namespace App\Traits;

trait UploadSizeHelperTrait
{
    protected function initUploadLimits(): void
    {
        // Backward-compatible no-op for modules expecting legacy upload limit bootstrap.
    }

    public function validateUploadedFile($request, array $fields, string $type = 'image')
    {
        return true;
    }
}
