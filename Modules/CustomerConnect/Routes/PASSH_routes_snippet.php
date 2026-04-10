<?php
/**
 * PASS H ROUTE SNIPPET
 *
 * 1) Paste WEBHOOK routes OUTSIDE your account group:
 *
 * Route::post('webhook/customerconnect/twilio', ...)->middleware(['customerconnect.twilio.sig']);
 *
 * 2) Paste HEALTH route INSIDE account group:
 * Route::get('customer-connect/health', ...)->name('customerconnect.health');
 */

use Illuminate\Support\Facades\Route;

// Webhook signature middleware aliases (register in your HTTP Kernel or within module RouteServiceProvider if you use it)
Route::middleware(['web'])->group(function () {
    Route::post('webhook/customerconnect/twilio', [\Modules\CustomerConnect\Http\Controllers\Webhooks\TwilioWebhookController::class, 'inbound'])
        ->middleware(['customerconnect.twilio.sig'])
        ->name('customerconnect.webhook.twilio');

    Route::post('webhook/customerconnect/twilio/status', [\Modules\CustomerConnect\Http\Controllers\Webhooks\TwilioStatusWebhookController::class, 'handle'])
        ->middleware(['customerconnect.twilio.sig'])
        ->name('customerconnect.webhook.twilio.status');

    Route::post('webhook/customerconnect/vonage', [\Modules\CustomerConnect\Http\Controllers\Webhooks\VonageWebhookController::class, 'inbound'])
        ->middleware(['customerconnect.vonage.sig'])
        ->name('customerconnect.webhook.vonage');

    Route::post('webhook/customerconnect/vonage/status', [\Modules\CustomerConnect\Http\Controllers\Webhooks\VonageStatusWebhookController::class, 'handle'])
        ->middleware(['customerconnect.vonage.sig'])
        ->name('customerconnect.webhook.vonage.status');
});

// Inside account group add:
// Route::get('customer-connect/health', [\Modules\CustomerConnect\Http\Controllers\HealthController::class, 'index'])->name('customerconnect.health');
