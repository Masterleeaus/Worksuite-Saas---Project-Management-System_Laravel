<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMWorkflow\Http\Controllers\StageActionController;
use Modules\FSMWorkflow\Http\Controllers\SizeController;
use Modules\FSMWorkflow\Http\Controllers\KanbanConfigController;

Route::middleware(['web', 'auth'])->prefix('account/fsm/workflow')->group(function () {

    // ── Stage Actions ─────────────────────────────────────────────────────────
    // Actions per stage (nested under /stages/{stageId}/actions)
    Route::prefix('stages/{stageId}/actions')->group(function () {
        Route::get('/',              [StageActionController::class, 'index'])->name('fsmworkflow.stage_actions.index');
        Route::get('/create',        [StageActionController::class, 'create'])->name('fsmworkflow.stage_actions.create');
        Route::post('/',             [StageActionController::class, 'store'])->name('fsmworkflow.stage_actions.store');
        Route::get('/{id}/edit',     [StageActionController::class, 'edit'])->name('fsmworkflow.stage_actions.edit');
        Route::post('/{id}',         [StageActionController::class, 'update'])->name('fsmworkflow.stage_actions.update');
        Route::post('/{id}/delete',  [StageActionController::class, 'destroy'])->name('fsmworkflow.stage_actions.destroy');
        Route::post('/{id}/test',    [StageActionController::class, 'testFire'])->name('fsmworkflow.stage_actions.test');
    });

    // ── Job Sizes ─────────────────────────────────────────────────────────────
    Route::prefix('sizes')->group(function () {
        Route::get('/',           [SizeController::class, 'index'])->name('fsmworkflow.sizes.index');
        Route::get('/create',     [SizeController::class, 'create'])->name('fsmworkflow.sizes.create');
        Route::post('/',          [SizeController::class, 'store'])->name('fsmworkflow.sizes.store');
        Route::get('/{id}/edit',  [SizeController::class, 'edit'])->name('fsmworkflow.sizes.edit');
        Route::post('/{id}',      [SizeController::class, 'update'])->name('fsmworkflow.sizes.update');
        Route::post('/{id}/delete',[SizeController::class, 'destroy'])->name('fsmworkflow.sizes.destroy');
    });

    // ── Kanban Card Configuration ─────────────────────────────────────────────
    Route::prefix('kanban-config')->group(function () {
        Route::get('/',                   [KanbanConfigController::class, 'index'])->name('fsmworkflow.kanban_config.index');
        Route::get('/edit',               [KanbanConfigController::class, 'edit'])->name('fsmworkflow.kanban_config.edit.global');
        Route::post('/update',            [KanbanConfigController::class, 'update'])->name('fsmworkflow.kanban_config.update.global');
        Route::get('/{teamId}/edit',      [KanbanConfigController::class, 'edit'])->name('fsmworkflow.kanban_config.edit');
        Route::post('/{teamId}/update',   [KanbanConfigController::class, 'update'])->name('fsmworkflow.kanban_config.update');
        Route::post('/{id}/delete',       [KanbanConfigController::class, 'destroy'])->name('fsmworkflow.kanban_config.destroy');
    });
});
