<?php

use Illuminate\Support\Facades\Route;
use Modules\Testimonials\app\Http\Controllers\AdminTestimonialsController;
use Modules\Testimonials\app\Http\Controllers\PublicTestimonialsController;
use Modules\Testimonials\app\Http\Controllers\TestimonialsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ------------------------------------------------------------------
// Public routes (no auth required)
// ------------------------------------------------------------------
Route::middleware('web')->prefix('testimonials')->name('testimonials.')->group(function () {
    Route::get('/', [PublicTestimonialsController::class, 'index'])->name('public.index');
    Route::get('/widget/{widget}', [PublicTestimonialsController::class, 'widget'])->name('widget.embed');
});

// ------------------------------------------------------------------
// Admin routes (authenticated + admin.auth + permission middleware)
// ------------------------------------------------------------------
Route::middleware(['web', 'auth'])
    ->prefix('account')
    ->group(function () {
        Route::prefix('admin/content')->group(function () {
            // Main testimonials management page
            Route::get('/testimonials', [AdminTestimonialsController::class, 'index'])
                ->name('admin.testimonials')
                ->middleware(['admin.auth', 'permission']);

            // Widget management pages
            Route::get('/testimonial-widgets', [AdminTestimonialsController::class, 'widgets'])
                ->name('admin.testimonials.widgets')
                ->middleware(['admin.auth', 'permission']);
            Route::post('/testimonial-widgets', [AdminTestimonialsController::class, 'storeWidget'])
                ->name('admin.testimonials.widgets.store')
                ->middleware(['admin.auth', 'permission']);
            Route::delete('/testimonial-widgets/{id}', [AdminTestimonialsController::class, 'destroyWidget'])
                ->name('admin.testimonials.widgets.destroy')
                ->middleware(['admin.auth', 'permission']);

            // Import actions (POST, admin only)
            Route::post('/testimonials/import-reviews', [TestimonialsController::class, 'importFromReviews'])
                ->name('admin.testimonials.import_reviews')
                ->middleware(['admin.auth', 'permission']);
            Route::post('/testimonials/import-feedback', [TestimonialsController::class, 'importFromFeedback'])
                ->name('admin.testimonials.import_feedback')
                ->middleware(['admin.auth', 'permission']);
        });
    });

