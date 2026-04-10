<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanReach\Http\Webhooks\TwilioSmsWebhookController;
use Modules\TitanReach\Http\Webhooks\TwilioVoiceWebhookController;
use Modules\TitanReach\Http\Webhooks\TwilioWhatsappWebhookController;
use Modules\TitanReach\Http\Webhooks\TelegramWebhookController;

// Webhook routes (no CSRF, no auth – external services post here)
Route::prefix('titanreach/webhooks')->group(function () {
    Route::post('/sms/inbound', [TwilioSmsWebhookController::class, 'inbound'])
        ->name('titanreach.webhooks.sms.inbound');

    Route::post('/voice/inbound', [TwilioVoiceWebhookController::class, 'inbound'])
        ->name('titanreach.webhooks.voice.inbound');

    Route::post('/voice/status', [TwilioVoiceWebhookController::class, 'status'])
        ->name('titanreach.webhooks.voice.status');

    Route::match(['get', 'post'], '/voice/twiml', [TwilioVoiceWebhookController::class, 'twiml'])
        ->name('titanreach.webhooks.voice.twiml');

    Route::post('/whatsapp/inbound', [TwilioWhatsappWebhookController::class, 'inbound'])
        ->name('titanreach.webhooks.whatsapp.inbound');

    Route::post('/telegram/inbound', [TelegramWebhookController::class, 'inbound'])
        ->name('titanreach.webhooks.telegram.inbound');
});
