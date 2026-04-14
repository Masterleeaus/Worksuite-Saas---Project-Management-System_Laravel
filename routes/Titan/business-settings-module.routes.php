<?php

use Illuminate\Support\Facades\Route;
use Modules\BusinessSettingsModule\Http\Controllers\Api\IntegrationController;

if (!class_exists(IntegrationController::class)) {
    return;
}

Route::middleware(['web', 'auth'])
    ->prefix('titan/business-settings-module')
    ->name('titan.business-settings-module.')
    ->group(function () {
        Route::get('checklist', [IntegrationController::class, 'checklist'])->name('checklist');
        Route::get('cms/snapshot', [IntegrationController::class, 'cmsSnapshot'])->name('cms.snapshot');
    });
