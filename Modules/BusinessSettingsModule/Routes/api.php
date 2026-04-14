<?php

use Illuminate\Support\Facades\Route;
use Modules\BusinessSettingsModule\Http\Controllers\Api\IntegrationController;

Route::group([
    'prefix' => 'business-settings-module',
    'as' => 'business-settings-module.',
    'middleware' => ['auth:api'],
], function () {
    Route::get('checklist', [IntegrationController::class, 'checklist'])
        ->name('checklist');
    Route::post('onboarding/save', [IntegrationController::class, 'saveOnboarding'])
        ->name('onboarding.save');
    Route::get('cms/snapshot', [IntegrationController::class, 'cmsSnapshot'])
        ->name('cms.snapshot');
});

