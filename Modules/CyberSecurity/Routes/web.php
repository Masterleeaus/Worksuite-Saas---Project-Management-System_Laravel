<?php

use Illuminate\Support\Facades\Route;
use Modules\CyberSecurity\Http\Controllers\BlacklistEmailController;
use Modules\CyberSecurity\Http\Controllers\BlacklistIpController;
use Modules\CyberSecurity\Http\Controllers\BreachResponseController;
use Modules\CyberSecurity\Http\Controllers\ComplianceDashboardController;
use Modules\CyberSecurity\Http\Controllers\CyberSecuritySettingController;
use Modules\CyberSecurity\Http\Controllers\DataPrivacyController;
use Modules\CyberSecurity\Http\Controllers\LoginExpiryController;
use Modules\CyberSecurity\Http\Controllers\SecurityScanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => 'auth', 'prefix' => 'account/settings'], function () {

    Route::group(['prefix' => 'cybersecurity', 'as' => 'cybersecurity.'], function () {
        Route::resource('blacklist-ip', BlacklistIpController::class);
        Route::resource('blacklist-email', BlacklistEmailController::class);
        Route::resource('login-expiry', LoginExpiryController::class);
    });
    Route::resource('cybersecurity', CyberSecuritySettingController::class);

});

Route::group(['middleware' => 'auth', 'prefix' => 'account/cybersecurity', 'as' => 'cybersecurity.'], function () {

    // Compliance Dashboard (GDPR + Privacy Act AU checklist)
    Route::get('compliance', [ComplianceDashboardController::class, 'index'])
        ->name('compliance.index');
    Route::post('compliance/update', [ComplianceDashboardController::class, 'update'])
        ->name('compliance.update');

    // Security Scan
    Route::get('security-scan', [SecurityScanController::class, 'index'])
        ->name('security-scan.index');
    Route::post('security-scan/run', [SecurityScanController::class, 'run'])
        ->name('security-scan.run');

    // Data Privacy (Right of Access, Deletion, etc.)
    Route::get('data-privacy', [DataPrivacyController::class, 'index'])
        ->name('data-privacy.index');
    Route::get('data-privacy/create', [DataPrivacyController::class, 'create'])
        ->name('data-privacy.create');
    Route::post('data-privacy', [DataPrivacyController::class, 'store'])
        ->name('data-privacy.store');
    Route::get('data-privacy/{id}', [DataPrivacyController::class, 'show'])
        ->name('data-privacy.show');
    Route::post('data-privacy/{id}/status', [DataPrivacyController::class, 'updateStatus'])
        ->name('data-privacy.update-status');
    Route::delete('data-privacy/{id}', [DataPrivacyController::class, 'destroy'])
        ->name('data-privacy.destroy');

    // Breach Response Workflow
    Route::get('breach-response', [BreachResponseController::class, 'index'])
        ->name('breach-response.index');
    Route::get('breach-response/create', [BreachResponseController::class, 'create'])
        ->name('breach-response.create');
    Route::post('breach-response', [BreachResponseController::class, 'store'])
        ->name('breach-response.store');
    Route::get('breach-response/{id}', [BreachResponseController::class, 'show'])
        ->name('breach-response.show');
    Route::put('breach-response/{id}', [BreachResponseController::class, 'update'])
        ->name('breach-response.update');
    Route::delete('breach-response/{id}', [BreachResponseController::class, 'destroy'])
        ->name('breach-response.destroy');

});
