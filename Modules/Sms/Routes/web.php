<?php



use Illuminate\Support\Facades\Route;
use Modules\Sms\Http\Controllers\SmsSettingsController;
use Modules\Sms\Http\Controllers\Gateway\Web\Admin\SmsGatewayConfigController;
use Modules\Sms\Http\Controllers\Cleaning\CleaningTriggersController;
use Modules\Sms\Http\Controllers\Cleaning\SmsNotificationLogController;
use Modules\Sms\Http\Controllers\Cleaning\SmsOptOutController;

/*
|--------------------------------------------------------------------------
| Web Routes — Sms Module (merged Sms + SMSModule)
|--------------------------------------------------------------------------
|
| Three route groups:
|   1. Worksuite account routes  → SmsSettingsController (tenant settings,
|      notification toggles, test messages)
|   2. Worksuite/SaaS admin routes → SmsGatewayConfigController (provider
|      config: Twilio, Nexmo, Releans, MSG91, 2Factor, AlphaNet)
|   3. Cleaning triggers, notification log, opt-out management
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

    // ------------------------------------------------------------------
    // Group 3: Cleaning-specific triggers, log, and opt-out management
    // ------------------------------------------------------------------
    Route::group(['as' => 'account.'], function () {
        // Cleaning triggers (template editor + enable/disable)
        Route::get('sms-cleaning-triggers', [CleaningTriggersController::class, 'index'])
            ->name('sms-cleaning-triggers.index');
        Route::put('sms-cleaning-triggers', [CleaningTriggersController::class, 'update'])
            ->name('sms-cleaning-triggers.update');

        // Notification delivery log
        Route::get('sms-notification-log', [SmsNotificationLogController::class, 'index'])
            ->name('sms-notification-log.index');

        // Opt-out management
        Route::get('sms-opt-outs', [SmsOptOutController::class, 'index'])
            ->name('sms-opt-outs.index');
        Route::post('sms-opt-outs', [SmsOptOutController::class, 'store'])
            ->name('sms-opt-outs.store');
        Route::delete('sms-opt-outs/{id}', [SmsOptOutController::class, 'destroy'])
            ->name('sms-opt-outs.destroy');

        // Per-client channel preference (AJAX)
        Route::post('sms-channel-preference', [SmsOptOutController::class, 'updateChannelPreference'])
            ->name('sms-channel-preference.update');
    });
});

// Twilio inbound STOP/START webhook (no auth — Twilio calls this directly)
Route::post('sms/twilio/webhook', [SmsOptOutController::class, 'twilioWebhook'])
    ->name('sms.twilio.webhook')
    ->withoutMiddleware(['web']);

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

