<?php

use Illuminate\Support\Facades\Route;
use Modules\CustomerConnect\Http\Controllers\WebhookController;
use Modules\CustomerConnect\Http\Controllers\ProviderCallbackController;

/*
|--------------------------------------------------------------------------
| CustomerConnect Webhook Routes
|--------------------------------------------------------------------------
| These are stateless (api middleware). Do NOT add auth/account middleware.
| Tenant resolution happens inside the controller via channel identities.
| Signature verification is handled by the customerconnect.twilio.sig and
| customerconnect.vonage.sig middleware aliases (registered in service provider).
*/

// ── Inbound messages (customers replying) ────────────────────────────────────

Route::post('/twilio', [WebhookController::class, 'twilio'])
    ->middleware(['customerconnect.twilio.sig'])
    ->name('customerconnect.webhook.twilio');

Route::post('/vonage', [WebhookController::class, 'vonage'])
    ->middleware(['customerconnect.vonage.sig'])
    ->name('customerconnect.webhook.vonage');

Route::post('/telegram', [WebhookController::class, 'telegram'])
    ->name('customerconnect.webhook.telegram');

// ── Delivery status callbacks (provider receipts) ────────────────────────────

Route::post('/twilio/status', [ProviderCallbackController::class, 'twilio'])
    ->middleware(['customerconnect.twilio.sig'])
    ->name('customerconnect.webhook.twilio.status');

Route::post('/vonage/status', [ProviderCallbackController::class, 'vonage'])
    ->middleware(['customerconnect.vonage.sig'])
    ->name('customerconnect.webhook.vonage.status');
