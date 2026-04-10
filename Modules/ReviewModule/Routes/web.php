<?php

use Illuminate\Support\Facades\Route;
use Modules\ReviewModule\Http\Controllers\Web\Admin\ReviewController;

Route::middleware(['web', 'auth'])->prefix('account')->group(function () {
    Route::prefix('reviews')->as('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::get('{id}', [ReviewController::class, 'show'])->name('show');
        Route::post('{id}/approve', [ReviewController::class, 'approve'])->name('approve');
        Route::post('{id}/reject', [ReviewController::class, 'reject'])->name('reject');
        Route::post('{id}/publish', [ReviewController::class, 'publish'])->name('publish');
        Route::post('{id}/respond', [ReviewController::class, 'respond'])->name('respond');
        Route::get('analytics/summary', [ReviewController::class, 'analytics'])->name('analytics');
    });

    // Public tokenised review submission link
    Route::get('review/submit/{token}', [ReviewController::class, 'publicForm'])->name('reviews.public_form')->withoutMiddleware(['auth']);
    Route::post('review/submit/{token}', [ReviewController::class, 'publicStore'])->name('reviews.public_store')->withoutMiddleware(['auth']);
});
