<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMTerritory\Http\Controllers\RegionController;
use Modules\FSMTerritory\Http\Controllers\DistrictController;
use Modules\FSMTerritory\Http\Controllers\BranchController;
use Modules\FSMTerritory\Http\Controllers\TerritoryController;

Route::middleware(['web', 'auth'])
    ->prefix('account/fsm-territory')
    ->group(function () {

        // Regions
        Route::get('/regions',              [RegionController::class, 'index'])->name('fsmterritory.regions.index');
        Route::get('/regions/create',       [RegionController::class, 'create'])->name('fsmterritory.regions.create');
        Route::post('/regions',             [RegionController::class, 'store'])->name('fsmterritory.regions.store');
        Route::get('/regions/{id}/edit',    [RegionController::class, 'edit'])->name('fsmterritory.regions.edit');
        Route::post('/regions/{id}',        [RegionController::class, 'update'])->name('fsmterritory.regions.update');
        Route::post('/regions/{id}/delete', [RegionController::class, 'destroy'])->name('fsmterritory.regions.destroy');

        // Districts
        Route::get('/districts',              [DistrictController::class, 'index'])->name('fsmterritory.districts.index');
        Route::get('/districts/create',       [DistrictController::class, 'create'])->name('fsmterritory.districts.create');
        Route::post('/districts',             [DistrictController::class, 'store'])->name('fsmterritory.districts.store');
        Route::get('/districts/{id}/edit',    [DistrictController::class, 'edit'])->name('fsmterritory.districts.edit');
        Route::post('/districts/{id}',        [DistrictController::class, 'update'])->name('fsmterritory.districts.update');
        Route::post('/districts/{id}/delete', [DistrictController::class, 'destroy'])->name('fsmterritory.districts.destroy');

        // Branches
        Route::get('/branches',              [BranchController::class, 'index'])->name('fsmterritory.branches.index');
        Route::get('/branches/create',       [BranchController::class, 'create'])->name('fsmterritory.branches.create');
        Route::post('/branches',             [BranchController::class, 'store'])->name('fsmterritory.branches.store');
        Route::get('/branches/{id}/edit',    [BranchController::class, 'edit'])->name('fsmterritory.branches.edit');
        Route::post('/branches/{id}',        [BranchController::class, 'update'])->name('fsmterritory.branches.update');
        Route::post('/branches/{id}/delete', [BranchController::class, 'destroy'])->name('fsmterritory.branches.destroy');

        // Territories
        Route::get('/territories',              [TerritoryController::class, 'index'])->name('fsmterritory.territories.index');
        Route::get('/territories/create',       [TerritoryController::class, 'create'])->name('fsmterritory.territories.create');
        Route::post('/territories',             [TerritoryController::class, 'store'])->name('fsmterritory.territories.store');
        Route::get('/territories/{id}/edit',    [TerritoryController::class, 'edit'])->name('fsmterritory.territories.edit');
        Route::post('/territories/{id}',        [TerritoryController::class, 'update'])->name('fsmterritory.territories.update');
        Route::post('/territories/{id}/delete', [TerritoryController::class, 'destroy'])->name('fsmterritory.territories.destroy');

    });
