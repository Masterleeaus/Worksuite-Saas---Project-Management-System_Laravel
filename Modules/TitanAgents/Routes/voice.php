<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanAgents\Http\Controllers\Voice\VoiceAvatarController;
use Modules\TitanAgents\Http\Controllers\Voice\VoiceChatbotController;
use Modules\TitanAgents\Http\Controllers\Voice\VoiceChatbotEmbedController;
use Modules\TitanAgents\Http\Controllers\Voice\VoiceChatbotHistoryController;
use Modules\TitanAgents\Http\Controllers\Voice\VoiceChatbotTrainController;

// Public frame view (no auth — served in iframe on client websites)
Route::middleware('web')->group(function () {
    Route::get('voice-chatbot/{uuid}/frame', [VoiceChatbotController::class, 'frame'])
        ->name('titanagents.voice.frame');
    Route::post('voice-chatbot/check-balance', [VoiceChatbotController::class, 'checkVoiceBalance'])
        ->name('titanagents.voice.check-balance');
});

// Dashboard (authenticated) routes
Route::middleware(['web', 'auth'])
    ->prefix('account/voice-chatbots')
    ->name('titanagents.voice.')
    ->group(function () {

        // CRUD
        Route::get('/', [VoiceChatbotController::class, 'index'])->name('index');
        Route::post('/store', [VoiceChatbotController::class, 'store'])->name('store');
        Route::put('/update', [VoiceChatbotController::class, 'update'])->name('update');
        Route::delete('/delete', [VoiceChatbotController::class, 'delete'])->name('delete');

        // Avatar upload
        Route::post('/avatar/upload', VoiceAvatarController::class)->name('avatar.upload');

        // Training
        Route::prefix('train')->name('train.')->group(function () {
            Route::get('/data', [VoiceChatbotTrainController::class, 'trainData'])->name('data');
            Route::delete('/delete', [VoiceChatbotTrainController::class, 'delete'])->name('delete');
            Route::post('/generate', [VoiceChatbotTrainController::class, 'generateEmbedding'])->name('generate');
            Route::post('/file', [VoiceChatbotTrainController::class, 'trainFile'])->name('file');
            Route::post('/text', [VoiceChatbotTrainController::class, 'trainText'])->name('text');
            Route::post('/url', [VoiceChatbotTrainController::class, 'trainUrl'])->name('url');
        });

        // Conversation history
        Route::prefix('conversation')->name('conversation.')->group(function () {
            Route::get('/with-paginate', [VoiceChatbotHistoryController::class, 'loadConversationWithPaginate'])
                ->name('with.paginate');
        });
    });

// Public embed API (no auth — called from client websites)
Route::prefix('api/v2/voice-chatbot')->name('titanagents.voice.api.')->group(function () {
    Route::get('/{uuid}', [VoiceChatbotEmbedController::class, 'index'])->name('index');
    Route::post('/{uuid}/store-conversation', [VoiceChatbotHistoryController::class, 'storeConversation'])
        ->name('store-conversation');
});
