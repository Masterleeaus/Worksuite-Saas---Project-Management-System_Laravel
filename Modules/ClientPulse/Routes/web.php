<?php

use Illuminate\Support\Facades\Route;
use Modules\ClientPulse\Http\Controllers\CleaningHistoryController;
use Modules\ClientPulse\Http\Controllers\JobRatingController;
use Modules\ClientPulse\Http\Controllers\ExtrasRequestController;
use Modules\ClientPulse\Http\Controllers\Admin\RatingAdminController;
use Modules\ClientPulse\Http\Controllers\Admin\ExtrasAdminController;

// ─────────────────────────────────────────────────────────────────────────────
// Client Portal Routes
// ─────────────────────────────────────────────────────────────────────────────
// All new portal views are behind the existing core auth middleware.
// No separate login system — hooks into the core Worksuite client session.
// ─────────────────────────────────────────────────────────────────────────────

Route::middleware(['web', 'auth'])
    ->prefix('portal')
    ->name('clientpulse.portal.')
    ->group(function () {

        // ── 1. Cleaning History Timeline ─────────────────────────────────────
        Route::get('/cleaning-history', [CleaningHistoryController::class, 'index'])
            ->name('history.index');

        // ── 2. Post-Job Feedback – 5-Star Rating ─────────────────────────────
        Route::get('/rate-job/{jobId}', [JobRatingController::class, 'show'])
            ->name('rating.show');

        Route::post('/rate-job/{jobId}', [JobRatingController::class, 'store'])
            ->name('rating.store');

        Route::get('/rate-job/{jobId}/thanks', [JobRatingController::class, 'thanks'])
            ->name('rating.thanks');

        // ── 3. Extras Request ────────────────────────────────────────────────
        Route::get('/request-extra', [ExtrasRequestController::class, 'create'])
            ->name('extras.create');

        Route::post('/request-extra', [ExtrasRequestController::class, 'store'])
            ->name('extras.store');

        Route::get('/request-extra/thanks', [ExtrasRequestController::class, 'thanks'])
            ->name('extras.thanks');
    });

// ─────────────────────────────────────────────────────────────────────────────
// Admin Routes
// ─────────────────────────────────────────────────────────────────────────────

Route::middleware(['web', 'auth'])
    ->prefix('clientpulse/admin')
    ->name('clientpulse.admin.')
    ->group(function () {

        // ── Ratings Dashboard ─────────────────────────────────────────────────
        Route::get('/ratings', [RatingAdminController::class, 'index'])
            ->name('ratings.index');

        Route::get('/ratings/{rating}', [RatingAdminController::class, 'show'])
            ->name('ratings.show');

        // ── Extras Items Management ───────────────────────────────────────────
        Route::get('/extras-items', [ExtrasAdminController::class, 'index'])
            ->name('extras.index');

        Route::post('/extras-items', [ExtrasAdminController::class, 'store'])
            ->name('extras.store');

        Route::patch('/extras-items/{item}', [ExtrasAdminController::class, 'update'])
            ->name('extras.update');

        Route::delete('/extras-items/{item}', [ExtrasAdminController::class, 'destroy'])
            ->name('extras.destroy');

        // ── Extras Requests Management ────────────────────────────────────────
        Route::get('/extras-requests', [ExtrasAdminController::class, 'requests'])
            ->name('extras.requests');

        Route::patch('/extras-requests/{request}/acknowledge', [ExtrasAdminController::class, 'acknowledge'])
            ->name('extras.acknowledge');
    });
