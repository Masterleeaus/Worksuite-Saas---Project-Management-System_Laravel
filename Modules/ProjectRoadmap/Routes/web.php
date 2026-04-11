<?php

use Illuminate\Support\Facades\Route;
use Modules\ProjectRoadmap\Http\Controllers\RoadmapController;
use Modules\ProjectRoadmap\Http\Controllers\FeatureVoteController;
use Modules\ProjectRoadmap\Http\Controllers\RoadmapPublicController;
use Modules\ProjectRoadmap\Http\Controllers\Admin\RoadmapAdminController;

// ── Authenticated user routes ─────────────────────────────────────────────
Route::middleware(['web', 'auth'])->prefix('account')->group(function () {

    // User-facing Kanban roadmap
    Route::get('roadmap', [RoadmapController::class, 'index'])->name('roadmap.index');

    // Feature voting (toggle)
    Route::post('roadmap/{itemId}/vote', [FeatureVoteController::class, 'vote'])->name('roadmap.vote');

    // Admin roadmap item CRUD
    Route::prefix('roadmap/admin')->as('roadmap.admin.')->group(function () {
        Route::get('/',               [RoadmapAdminController::class, 'index'])->name('index');
        Route::get('create',          [RoadmapAdminController::class, 'create'])->name('create');
        Route::post('/',              [RoadmapAdminController::class, 'store'])->name('store');
        Route::get('{id}/edit',       [RoadmapAdminController::class, 'edit'])->name('edit');
        Route::put('{id}',            [RoadmapAdminController::class, 'update'])->name('update');
        Route::delete('{id}',         [RoadmapAdminController::class, 'destroy'])->name('destroy');

        // Milestones
        Route::get('milestones',         [RoadmapAdminController::class, 'milestones'])->name('milestones');
        Route::post('milestones',        [RoadmapAdminController::class, 'storeMilestone'])->name('milestones.store');
        Route::put('milestones/{id}',    [RoadmapAdminController::class, 'updateMilestone'])->name('milestones.update');
        Route::delete('milestones/{id}', [RoadmapAdminController::class, 'destroyMilestone'])->name('milestones.destroy');
    });

});

// ── Public roadmap (no authentication) ───────────────────────────────────
Route::middleware(['web'])->group(function () {
    Route::get('roadmap/public', [RoadmapPublicController::class, 'index'])->name('roadmap.public');
});
