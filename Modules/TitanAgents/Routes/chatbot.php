<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanAgents\Http\Controllers\Api\ChatbotApiController;
use Modules\TitanAgents\Http\Controllers\Chatbot\AvatarController;
use Modules\TitanAgents\Http\Controllers\Chatbot\ChatbotAnalyticsController;
use Modules\TitanAgents\Http\Controllers\Chatbot\ChatbotCannedResponseController;
use Modules\TitanAgents\Http\Controllers\Chatbot\ChatbotController;
use Modules\TitanAgents\Http\Controllers\Chatbot\ChatbotCustomerController;
use Modules\TitanAgents\Http\Controllers\Chatbot\ChatbotKnowledgeBaseArticleController;
use Modules\TitanAgents\Http\Controllers\Chatbot\ChatbotMultiChannelController;
use Modules\TitanAgents\Http\Controllers\Chatbot\ChatbotTrainController;

// Web routes for chatbot management
Route::middleware(['web', 'auth'])->prefix('account/chatbots')->name('titanagents.chatbot.')->group(function () {
    Route::get('/', [ChatbotController::class, 'index'])->name('index');
    Route::get('/create', [ChatbotController::class, 'create'])->name('create');
    Route::post('/', [ChatbotController::class, 'store'])->name('store');
    Route::get('/{chatbot}', [ChatbotController::class, 'show'])->name('show');
    Route::get('/{chatbot}/edit', [ChatbotController::class, 'edit'])->name('edit');
    Route::put('/{chatbot}', [ChatbotController::class, 'update'])->name('update');
    Route::delete('/{chatbot}', [ChatbotController::class, 'destroy'])->name('destroy');

    // Avatar
    Route::post('/{chatbot}/avatar', [AvatarController::class, 'store'])->name('avatar.store');
    Route::delete('/{chatbot}/avatar', [AvatarController::class, 'destroy'])->name('avatar.destroy');

    // Channels
    Route::get('/{chatbot}/channels', [ChatbotMultiChannelController::class, 'index'])->name('channels.index');
    Route::post('/{chatbot}/channels', [ChatbotMultiChannelController::class, 'store'])->name('channels.store');
    Route::delete('/{chatbot}/channels/{channel}', [ChatbotMultiChannelController::class, 'destroy'])->name('channels.destroy');

    // Knowledge Base
    Route::get('/{chatbot}/kb', [ChatbotKnowledgeBaseArticleController::class, 'index'])->name('kb.index');
    Route::post('/{chatbot}/kb', [ChatbotKnowledgeBaseArticleController::class, 'store'])->name('kb.store');
    Route::put('/{chatbot}/kb/{article}', [ChatbotKnowledgeBaseArticleController::class, 'update'])->name('kb.update');
    Route::delete('/{chatbot}/kb/{article}', [ChatbotKnowledgeBaseArticleController::class, 'destroy'])->name('kb.destroy');

    // Canned Responses
    Route::get('/{chatbot}/canned', [ChatbotCannedResponseController::class, 'index'])->name('canned.index');
    Route::post('/{chatbot}/canned', [ChatbotCannedResponseController::class, 'store'])->name('canned.store');
    Route::put('/{chatbot}/canned/{cannedResponse}', [ChatbotCannedResponseController::class, 'update'])->name('canned.update');
    Route::delete('/{chatbot}/canned/{cannedResponse}', [ChatbotCannedResponseController::class, 'destroy'])->name('canned.destroy');

    // Training
    Route::get('/{chatbot}/train', [ChatbotTrainController::class, 'index'])->name('train.index');
    Route::post('/{chatbot}/train/all', [ChatbotTrainController::class, 'trainAll'])->name('train.all');
    Route::post('/{chatbot}/train/retrain', [ChatbotTrainController::class, 'retrainAll'])->name('train.retrain');

    // Analytics
    Route::get('/{chatbot}/analytics', [ChatbotAnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/{chatbot}/analytics/export', [ChatbotAnalyticsController::class, 'export'])->name('analytics.export');

    // Customers
    Route::get('/{chatbot}/customers', [ChatbotCustomerController::class, 'index'])->name('customers.index');
    Route::get('/{chatbot}/customers/{customer}', [ChatbotCustomerController::class, 'show'])->name('customers.show');
    Route::delete('/{chatbot}/customers/{customer}', [ChatbotCustomerController::class, 'destroy'])->name('customers.destroy');
});

// Public API routes (no auth required — embed widget)
Route::prefix('api/chatbot')->name('chatbot.api.')->group(function () {
    Route::get('/widget/{chatbotId}', [ChatbotApiController::class, 'widget'])->name('widget');
    Route::post('/widget/{chatbotId}/start', [ChatbotApiController::class, 'startConversation'])->name('start');
    Route::post('/message', [ChatbotApiController::class, 'sendMessage'])->name('message');
    Route::get('/widget/{chatbotId}/canned', [ChatbotApiController::class, 'getCannedResponses'])->name('canned');
});
