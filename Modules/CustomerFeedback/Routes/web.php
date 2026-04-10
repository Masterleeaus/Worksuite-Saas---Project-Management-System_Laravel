<?php

use Illuminate\Support\Facades\Route;
use Modules\CustomerFeedback\Http\Controllers\FeedbackTicketController;
use Modules\CustomerFeedback\Http\Controllers\FeedbackReplyController;
use Modules\CustomerFeedback\Http\Controllers\NpsSurveyController;
use Modules\CustomerFeedback\Http\Controllers\AnalyticsController;
use Modules\CustomerFeedback\Http\Controllers\FeedbackInsightsController;

Route::middleware(['auth'])->prefix('customer-feedback')->group(function () {
    // Main feedback tickets
    Route::resource('tickets', FeedbackTicketController::class);
    Route::post('tickets/bulk', [FeedbackTicketController::class, 'bulk'])->name('tickets.bulk');
    Route::post('tickets/export', [FeedbackTicketController::class, 'export'])->name('tickets.export');

    // Replies
    Route::post('tickets/{ticket}/replies', [FeedbackReplyController::class, 'store'])->name('replies.store');
    Route::get('tickets/{ticket}/replies', [FeedbackReplyController::class, 'index'])->name('replies.index');
    Route::delete('replies/{reply}', [FeedbackReplyController::class, 'destroy'])->name('replies.destroy');
    Route::post('tickets/{ticket}/replies/resolve', [FeedbackReplyController::class, 'resolve'])->name('replies.resolve');
    Route::get('reply-templates/{template}', [FeedbackReplyController::class, 'template'])->name('reply-template.show');

    // NPS Surveys
    Route::prefix('surveys/nps')->group(function () {
        Route::get('/', [NpsSurveyController::class, 'index'])->name('nps.index');
        Route::get('create', [NpsSurveyController::class, 'create'])->name('nps.create');
        Route::post('/', [NpsSurveyController::class, 'store'])->name('nps.store');
        Route::get('{survey}', [NpsSurveyController::class, 'show'])->name('nps.show');
        Route::delete('{survey}', [NpsSurveyController::class, 'destroy'])->name('nps.destroy');
    });

    // Analytics Dashboard
    Route::prefix('analytics')->group(function () {
        Route::get('dashboard', [AnalyticsController::class, 'dashboard'])->name('analytics.dashboard');
        Route::get('nps', [AnalyticsController::class, 'nps'])->name('analytics.nps');
        Route::get('csat', [AnalyticsController::class, 'csat'])->name('analytics.csat');
    });

    // AI Insights
    Route::prefix('insights')->group(function () {
        Route::get('dashboard', [FeedbackInsightsController::class, 'dashboard'])->name('insights.dashboard');
        Route::get('tickets/{ticket}', [FeedbackInsightsController::class, 'getTicketInsights'])->name('insights.ticket');
        Route::post('tickets/{ticket}/analyze', [FeedbackInsightsController::class, 'analyzeTicket'])->name('insights.analyze');
        Route::get('tickets/{ticket}/sentiment', [FeedbackInsightsController::class, 'getSentiment'])->name('insights.sentiment');
        Route::get('tickets/{ticket}/category', [FeedbackInsightsController::class, 'getSuggestedCategory'])->name('insights.category');
        Route::get('tickets/{ticket}/priority', [FeedbackInsightsController::class, 'getSuggestedPriority'])->name('insights.priority');
        Route::get('tickets/{ticket}/response', [FeedbackInsightsController::class, 'getSuggestedResponse'])->name('insights.response');
    });
});

// Legacy Complaint routes for backward compatibility
Route::middleware(['auth'])->prefix('complaint')->group(function () {
    Route::get('/', [FeedbackTicketController::class, 'index'])->where('type', 'complaint')->name('complaint.index');
    Route::get('create', [FeedbackTicketController::class, 'create'])->name('complaint.create');
    Route::post('/', [FeedbackTicketController::class, 'store'])->name('complaint.store');
    Route::get('{ticket}', [FeedbackTicketController::class, 'show'])->name('complaint.show');
    Route::get('{ticket}/edit', [FeedbackTicketController::class, 'edit'])->name('complaint.edit');
    Route::put('{ticket}', [FeedbackTicketController::class, 'update'])->name('complaint.update');
    Route::delete('{ticket}', [FeedbackTicketController::class, 'destroy'])->name('complaint.destroy');
});
