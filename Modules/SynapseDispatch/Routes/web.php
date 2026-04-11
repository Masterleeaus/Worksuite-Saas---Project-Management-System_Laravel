<?php

use Illuminate\Support\Facades\Route;
use Modules\SynapseDispatch\Http\Controllers\DispatchJobController;
use Modules\SynapseDispatch\Http\Controllers\DispatchWorkerController;
use Modules\SynapseDispatch\Http\Controllers\DispatchTeamController;
use Modules\SynapseDispatch\Http\Controllers\DispatchLocationController;
use Modules\SynapseDispatch\Http\Controllers\PlannerController;
use Modules\SynapseDispatch\Http\Controllers\MyJobsController;

Route::middleware(['web', 'auth'])
    ->prefix('account/synapse-dispatch')
    ->name('synapsedispatch.')
    ->group(function () {

        // Teams
        Route::resource('teams', DispatchTeamController::class)->names('teams');

        // Workers
        Route::get('workers/fc-resources', [DispatchWorkerController::class, 'fcResources'])->name('workers.fc_resources');
        Route::resource('workers', DispatchWorkerController::class)->names('workers');

        // Locations
        Route::resource('locations', DispatchLocationController::class)->names('locations');

        // Jobs — custom actions before resource to avoid route conflict with {job}
        Route::get('jobs/fc-events',               [DispatchJobController::class, 'fcEvents'])->name('jobs.fc_events');
        Route::patch('jobs/{job}/reschedule',       [DispatchJobController::class, 'reschedule'])->name('jobs.reschedule');
        Route::patch('jobs/{job}/assign',           [DispatchJobController::class, 'manualAssign'])->name('jobs.manual_assign');
        Route::post('jobs/{job}/dispatch',          [DispatchJobController::class, 'triggerAutoDispatch'])->name('jobs.trigger_dispatch');
        Route::resource('jobs', DispatchJobController::class)->names('jobs');

        // Planner
        Route::get('planner',               [PlannerController::class, 'index'])->name('planner.index');
        Route::get('planner/gantt',         [PlannerController::class, 'gantt'])->name('planner.gantt');
        Route::get('planner/suggest/{job}', [PlannerController::class, 'suggest'])->name('planner.suggest');

        // My Jobs — for field workers logged in as Worksuite users
        Route::get('my-jobs', [MyJobsController::class, 'index'])->name('my_jobs.index');
    });
