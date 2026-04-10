<?php

use Illuminate\Support\Facades\Route;
use Modules\CustomerFeedback\Http\Controllers\FeedbackTicketController;
use Modules\CustomerFeedback\Http\Controllers\FeedbackReplyController;
use Modules\CustomerFeedback\Http\Controllers\NpsSurveyController;
use Modules\CustomerFeedback\Http\Controllers\PublicSurveyController;
use Modules\CustomerFeedback\Http\Controllers\AnalyticsController;
use Modules\CustomerFeedback\Http\Controllers\FeedbackInsightsController;

// ─── Public survey link (no authentication required) ─────────────────────────
Route::prefix('survey')->name('survey.')->group(function () {
    Route::get('{token}',  [PublicSurveyController::class, 'show'])->name('show');
    Route::post('{token}', [PublicSurveyController::class, 'submit'])->name('submit');
});

// ─── Authenticated routes ─────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('customer-feedback')->group(function () {

    // Main feedback tickets
    Route::resource('tickets', FeedbackTicketController::class);
    Route::post('tickets/bulk',   [FeedbackTicketController::class, 'bulk'])->name('tickets.bulk');
    Route::post('tickets/export', [FeedbackTicketController::class, 'export'])->name('tickets.export');

    // Replies
    Route::post('tickets/{ticket}/replies',         [FeedbackReplyController::class, 'store'])->name('replies.store');
    Route::get('tickets/{ticket}/replies',          [FeedbackReplyController::class, 'index'])->name('replies.index');
    Route::delete('replies/{reply}',                [FeedbackReplyController::class, 'destroy'])->name('replies.destroy');
    Route::post('tickets/{ticket}/replies/resolve', [FeedbackReplyController::class, 'resolve'])->name('replies.resolve');
    Route::get('reply-templates/{template}',        [FeedbackReplyController::class, 'template'])->name('reply-template.show');

    // NPS Surveys (admin management)
    Route::prefix('surveys/nps')->name('nps.')->group(function () {
        Route::get('/',                          [NpsSurveyController::class, 'index'])->name('index');
        Route::delete('{survey}',                [NpsSurveyController::class, 'destroy'])->name('destroy');
        Route::post('{survey}/toggle-public',    [NpsSurveyController::class, 'togglePublic'])->name('toggle-public');
    });

    // Analytics Dashboard
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('dashboard', [AnalyticsController::class, 'dashboard'])->name('dashboard');
        Route::get('nps',       [AnalyticsController::class, 'nps'])->name('nps');
        Route::get('csat',      [AnalyticsController::class, 'csat'])->name('csat');
    });

    // AI Insights
    Route::prefix('insights')->name('insights.')->group(function () {
        Route::get('dashboard',                            [FeedbackInsightsController::class, 'dashboard'])->name('dashboard');
        Route::get('tickets/{ticket}',                     [FeedbackInsightsController::class, 'getTicketInsights'])->name('ticket');
        Route::post('tickets/{ticket}/analyze',            [FeedbackInsightsController::class, 'analyzeTicket'])->name('analyze');
        Route::get('tickets/{ticket}/sentiment',           [FeedbackInsightsController::class, 'getSentiment'])->name('sentiment');
        Route::get('tickets/{ticket}/category',            [FeedbackInsightsController::class, 'getSuggestedCategory'])->name('category');
        Route::get('tickets/{ticket}/priority',            [FeedbackInsightsController::class, 'getSuggestedPriority'])->name('priority');
        Route::get('tickets/{ticket}/response',            [FeedbackInsightsController::class, 'getSuggestedResponse'])->name('response');
    });
});

// ─── Legacy Complaint routes (backward compatibility) ─────────────────────────
Route::middleware(['auth'])->prefix('complaint')->group(function () {
    Route::get('/',          [FeedbackTicketController::class, 'index'])->name('complaint.index');
    Route::get('create',     [FeedbackTicketController::class, 'create'])->name('complaint.create');
    Route::post('/',         [FeedbackTicketController::class, 'store'])->name('complaint.store');
    Route::get('{ticket}',   [FeedbackTicketController::class, 'show'])->name('complaint.show');
    Route::get('{ticket}/edit', [FeedbackTicketController::class, 'edit'])->name('complaint.edit');
    Route::put('{ticket}',   [FeedbackTicketController::class, 'update'])->name('complaint.update');
    Route::delete('{ticket}', [FeedbackTicketController::class, 'destroy'])->name('complaint.destroy');
});
