<?php



use Illuminate\Support\Facades\Route;
use Modules\Sms\Http\Controllers\SmsSettingsController;
use Modules\Sms\Http\Controllers\Gateway\Web\Admin\SmsGatewayConfigController;

/*
|--------------------------------------------------------------------------
| Web Routes — Sms Module (merged Sms + SMSModule)
|--------------------------------------------------------------------------
|
| Two route groups:
|   1. Worksuite account routes  → SmsSettingsController (tenant settings,
|      notification toggles, test messages)
|   2. Worksuite/SaaS admin routes → SmsGatewayConfigController (provider
|      config: Twilio, Nexmo, Releans, MSG91, 2Factor, AlphaNet)
|
*/

// -----------------------------------------------------------------------
// Group 1: Worksuite tenant-facing SMS settings
// -----------------------------------------------------------------------
Route::group(['middleware' => 'auth', 'prefix' => 'account'], function () {
    Route::group(['prefix' => 'settings'], function () {
        Route::get('sms-setting/test-message', [SmsSettingsController::class, 'testMessage'])
            ->name('sms-setting.test_message');
        Route::post('sms-setting/send-test-message', [SmsSettingsController::class, 'sendTestMessage'])
            ->name('sms-setting.send_test_message');
        Route::resource('sms-setting', SmsSettingsController::class);
    });
});

// -----------------------------------------------------------------------
// Group 2: SaaS admin gateway configuration (merged from SMSModule)
// -----------------------------------------------------------------------
Route::group([
    'prefix'     => 'admin',
    'as'         => 'admin.',
    'middleware' => ['admin', 'actch:admin_panel'],
], function () {
    Route::group(['prefix' => 'configuration', 'as' => 'configuration.'], function () {
        Route::get('sms-get', [SmsGatewayConfigController::class, 'smsConfigGet'])
            ->name('sms-get');
        Route::put('sms-set', [SmsGatewayConfigController::class, 'smsConfigSet'])
            ->name('sms-set');
        Route::post('update-gateway-status/{gateway}/{status}', [SmsGatewayConfigController::class, 'updateGatewayStatus'])
            ->name('update-gateway-status');
    });
});
