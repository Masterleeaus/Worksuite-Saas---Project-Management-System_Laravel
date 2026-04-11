<?php

use Illuminate\Support\Facades\Route;
use Modules\Testimonials\app\Http\Controllers\TestimonialsController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

// Public JSON endpoint (no auth — for embeddable widget JS)
Route::get('/testimonials/public', [TestimonialsController::class, 'publicList'])
    ->name('api.testimonials.public');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::group(['prefix' => 'admin', 'middleware' => 'api'], function () {
        // Existing CRUD
        Route::post('/testimonial-list',            [TestimonialsController::class, 'index']);
        Route::post('/save-testimonial',            [TestimonialsController::class, 'store']);
        Route::post('/delete-testimonial',          [TestimonialsController::class, 'destroy']);
        Route::post('/change-status-testimonial',   [TestimonialsController::class, 'statusChange']);

        // Publish / Unpublish / Featured
        Route::post('/publish-testimonial',         [TestimonialsController::class, 'publish']);
        Route::post('/unpublish-testimonial',       [TestimonialsController::class, 'unpublish']);
        Route::post('/toggle-featured-testimonial', [TestimonialsController::class, 'toggleFeatured']);

        // Import
        Route::post('/import-testimonials-reviews',  [TestimonialsController::class, 'importFromReviews']);
        Route::post('/import-testimonials-feedback', [TestimonialsController::class, 'importFromFeedback']);
    });
});

