<?php

use App\Http\Controllers\CustomModuleController;
use App\Http\Controllers\ModuleInstallerDashboardController;
use App\Http\Controllers\ModuleSettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Worksuite Custom Module Installer Routes
|--------------------------------------------------------------------------
| Option A: dedicated installer-only route surface.
| Keep this file and do not load the broad host route bundles from
| routes/web-settings.php or routes/SuperAdmin/web.php for this package.
*/

Route::middleware(['auth'])->prefix('account/settings')->group(function () {
    Route::resource('module-settings', ModuleSettingController::class);

    Route::post('custom-modules/verify-purchase', [CustomModuleController::class, 'verifyingModulePurchase'])
        ->name('custom-modules.verify_purchase');

    Route::post('custom-modules/upload-temp', [CustomModuleController::class, 'uploadTemp'])
        ->name('custom-modules.upload-temp');

    Route::post('custom-modules/analyze', [CustomModuleController::class, 'analyze'])
        ->name('custom-modules.analyze');

    Route::post('custom-modules/safe-fix-preview', [CustomModuleController::class, 'safeFix'])
        ->name('custom-modules.safe-fix');

    Route::post('custom-modules/apply-safe-fixes', [CustomModuleController::class, 'applySafeFixes'])
        ->name('custom-modules.apply_safe_fixes');

    Route::post('custom-modules/export-json', [CustomModuleController::class, 'exportJson'])
        ->name('custom-modules.export-json');

    Route::post('custom-modules/analyze-download', [CustomModuleController::class, 'downloadAnalysis'])
        ->name('custom-modules.analyze_download');

    Route::post('custom-modules/{install}/rollback', [CustomModuleController::class, 'rollback'])
        ->name('custom-modules.rollback')
        ->middleware('superadmin');

    Route::resource('custom-modules', CustomModuleController::class);
});

Route::middleware(['auth', 'superadmin'])->prefix('admin/modules')->group(function () {
    Route::get('/dashboard', [ModuleInstallerDashboardController::class, 'index'])->name('modules.dashboard');
    Route::get('/history', [ModuleInstallerDashboardController::class, 'history'])->name('modules.history');
    Route::get('/logs', [ModuleInstallerDashboardController::class, 'logs'])->name('modules.logs');
    Route::get('/performance', [ModuleInstallerDashboardController::class, 'performance'])->name('modules.performance');
    Route::post('/logs/export', [ModuleInstallerDashboardController::class, 'exportLogs'])->name('modules.logs.export');
    Route::get('/{install}', [ModuleInstallerDashboardController::class, 'show'])->name('modules.show');
});

// Alias routes required by the Module Registry dashboard validation
Route::middleware(['auth', 'superadmin'])->prefix('account/settings')->group(function () {
    Route::get('/custom-modules/dashboard', [ModuleInstallerDashboardController::class, 'index'])
        ->name('custom-modules.dashboard');
    Route::get('/custom-modules/install', [CustomModuleController::class, 'create'])
        ->name('custom-modules.install');
    Route::get('/custom-modules/analysis', [CustomModuleController::class, 'index'])
        ->name('custom-modules.analysis');
    Route::get('/custom-modules/registry', [ModuleInstallerDashboardController::class, 'index'])
        ->name('custom-modules.registry');
    Route::get('/custom-modules/repairs', [CustomModuleController::class, 'index'])
        ->name('custom-modules.repairs');
});
