<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMCore\Http\Controllers\DashboardController;
use Modules\FSMCore\Http\Controllers\OrderController;
use Modules\FSMCore\Http\Controllers\LocationController;
use Modules\FSMCore\Http\Controllers\TeamController;
use Modules\FSMCore\Http\Controllers\StageController;
use Modules\FSMCore\Http\Controllers\TerritoryController;
use Modules\FSMCore\Http\Controllers\EquipmentController;
use Modules\FSMCore\Http\Controllers\TemplateController;

Route::middleware(['web', 'auth'])
    ->prefix('account/fsm')
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('fsmcore.dashboard');

        // FSM Orders
        Route::get('/orders',                [OrderController::class, 'index'])->name('fsmcore.orders.index');
        Route::get('/orders/kanban',         [OrderController::class, 'kanban'])->name('fsmcore.orders.kanban');
        Route::get('/orders/create',         [OrderController::class, 'create'])->name('fsmcore.orders.create');
        Route::post('/orders',               [OrderController::class, 'store'])->name('fsmcore.orders.store');
        Route::get('/orders/{id}',           [OrderController::class, 'show'])->name('fsmcore.orders.show');
        Route::get('/orders/{id}/edit',      [OrderController::class, 'edit'])->name('fsmcore.orders.edit');
        Route::post('/orders/{id}',          [OrderController::class, 'update'])->name('fsmcore.orders.update');
        Route::post('/orders/{id}/delete',   [OrderController::class, 'destroy'])->name('fsmcore.orders.destroy');
        Route::post('/orders/{id}/stage',    [OrderController::class, 'updateStage'])->name('fsmcore.orders.stage');

        // FSM Locations
        Route::get('/locations',             [LocationController::class, 'index'])->name('fsmcore.locations.index');
        Route::get('/locations/create',      [LocationController::class, 'create'])->name('fsmcore.locations.create');
        Route::post('/locations',            [LocationController::class, 'store'])->name('fsmcore.locations.store');
        Route::get('/locations/{id}',        [LocationController::class, 'show'])->name('fsmcore.locations.show');
        Route::get('/locations/{id}/edit',   [LocationController::class, 'edit'])->name('fsmcore.locations.edit');
        Route::post('/locations/{id}',       [LocationController::class, 'update'])->name('fsmcore.locations.update');
        Route::post('/locations/{id}/delete',[LocationController::class, 'destroy'])->name('fsmcore.locations.destroy');

        // FSM Teams
        Route::get('/teams',                 [TeamController::class, 'index'])->name('fsmcore.teams.index');
        Route::get('/teams/create',          [TeamController::class, 'create'])->name('fsmcore.teams.create');
        Route::post('/teams',                [TeamController::class, 'store'])->name('fsmcore.teams.store');
        Route::get('/teams/{id}',            [TeamController::class, 'show'])->name('fsmcore.teams.show');
        Route::get('/teams/{id}/edit',       [TeamController::class, 'edit'])->name('fsmcore.teams.edit');
        Route::post('/teams/{id}',           [TeamController::class, 'update'])->name('fsmcore.teams.update');
        Route::post('/teams/{id}/delete',    [TeamController::class, 'destroy'])->name('fsmcore.teams.destroy');

        // FSM Stages
        Route::get('/stages',                [StageController::class, 'index'])->name('fsmcore.stages.index');
        Route::get('/stages/create',         [StageController::class, 'create'])->name('fsmcore.stages.create');
        Route::post('/stages',               [StageController::class, 'store'])->name('fsmcore.stages.store');
        Route::get('/stages/{id}/edit',      [StageController::class, 'edit'])->name('fsmcore.stages.edit');
        Route::post('/stages/{id}',          [StageController::class, 'update'])->name('fsmcore.stages.update');
        Route::post('/stages/{id}/delete',   [StageController::class, 'destroy'])->name('fsmcore.stages.destroy');

        // FSM Territories
        Route::get('/territories',           [TerritoryController::class, 'index'])->name('fsmcore.territories.index');
        Route::get('/territories/create',    [TerritoryController::class, 'create'])->name('fsmcore.territories.create');
        Route::post('/territories',          [TerritoryController::class, 'store'])->name('fsmcore.territories.store');
        Route::get('/territories/{id}/edit', [TerritoryController::class, 'edit'])->name('fsmcore.territories.edit');
        Route::post('/territories/{id}',     [TerritoryController::class, 'update'])->name('fsmcore.territories.update');
        Route::post('/territories/{id}/delete',[TerritoryController::class, 'destroy'])->name('fsmcore.territories.destroy');

        // FSM Equipment
        Route::get('/equipment',             [EquipmentController::class, 'index'])->name('fsmcore.equipment.index');
        Route::get('/equipment/create',      [EquipmentController::class, 'create'])->name('fsmcore.equipment.create');
        Route::post('/equipment',            [EquipmentController::class, 'store'])->name('fsmcore.equipment.store');
        Route::get('/equipment/{id}/edit',   [EquipmentController::class, 'edit'])->name('fsmcore.equipment.edit');
        Route::post('/equipment/{id}',       [EquipmentController::class, 'update'])->name('fsmcore.equipment.update');
        Route::post('/equipment/{id}/delete',[EquipmentController::class, 'destroy'])->name('fsmcore.equipment.destroy');

        // FSM Templates
        Route::get('/templates',             [TemplateController::class, 'index'])->name('fsmcore.templates.index');
        Route::get('/templates/create',      [TemplateController::class, 'create'])->name('fsmcore.templates.create');
        Route::post('/templates',            [TemplateController::class, 'store'])->name('fsmcore.templates.store');
        Route::get('/templates/{id}/edit',   [TemplateController::class, 'edit'])->name('fsmcore.templates.edit');
        Route::post('/templates/{id}',       [TemplateController::class, 'update'])->name('fsmcore.templates.update');
        Route::post('/templates/{id}/delete',[TemplateController::class, 'destroy'])->name('fsmcore.templates.destroy');

    });
