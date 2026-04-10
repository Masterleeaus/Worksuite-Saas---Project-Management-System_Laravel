<?php
/**
 * PASS G NOTE:
 * Merge these routes into your existing Modules/CustomerConnect/Routes/web.php
 * (do not replace blindly if you've customized earlier passes).
 *
 * Webhooks (no auth/account middleware)
 *  - Twilio status callback: /webhook/customerconnect/twilio/status
 *  - Vonage status callback: /webhook/customerconnect/vonage/status
 *
 * Account routes
 *  - Health dashboard: /account/customer-connect/health
 */

use Illuminate\Support\Facades\Route;

Route::post('/webhook/customerconnect/twilio/status', [\Modules\CustomerConnect\Http\Controllers\ProviderCallbackController::class, 'twilio'])
    ->name('customerconnect.webhook.twilio.status');

Route::post('/webhook/customerconnect/vonage/status', [\Modules\CustomerConnect\Http\Controllers\ProviderCallbackController::class, 'vonage'])
    ->name('customerconnect.webhook.vonage.status');

// Inside your account-prefixed group:
Route::get('/account/customer-connect/health', [\Modules\CustomerConnect\Http\Controllers\HealthController::class, 'index'])
    ->name('customerconnect.health.index');
