<?php

use Illuminate\Support\Facades\Route;
use Modules\CustomerFeedback\Http\Controllers\FeedbackTicketController;
use Modules\CustomerFeedback\Http\Controllers\FeedbackReplyController;
use Modules\CustomerFeedback\Http\Controllers\NpsSurveyController;

Route::prefix('feedback')->name('customer-feedback.api.')->middleware('auth:api')->group(function () {
    // Tickets API
    Route::apiResource('tickets', FeedbackTicketController::class)->names('tickets');
    Route::post('tickets/{ticket}/replies', [FeedbackReplyController::class, 'store'])->name('replies.store');
    Route::get('tickets/{ticket}/replies', [FeedbackReplyController::class, 'index'])->name('replies.index');

    // Surveys API
    Route::post('surveys/nps', [NpsSurveyController::class, 'store'])->name('surveys.nps.store');
    Route::get('surveys/nps/{survey}', [NpsSurveyController::class, 'show'])->name('surveys.nps.show');
    Route::post('surveys/nps/{survey}/respond', [NpsSurveyController::class, 'submitResponse'])->name('surveys.nps.respond');
});

// Public survey submission endpoint (no auth required for clients)
Route::post('feedback/surveys/{survey}/respond', [NpsSurveyController::class, 'submitResponse'])->name('customer-feedback.api.surveys.respond-public');
