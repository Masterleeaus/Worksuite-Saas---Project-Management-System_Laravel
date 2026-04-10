<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMSkill\Http\Controllers\SkillTypeController;
use Modules\FSMSkill\Http\Controllers\SkillController;
use Modules\FSMSkill\Http\Controllers\SkillLevelController;
use Modules\FSMSkill\Http\Controllers\EmployeeSkillController;
use Modules\FSMSkill\Http\Controllers\OrderSkillController;
use Modules\FSMSkill\Http\Controllers\TemplateSkillController;
use Modules\FSMSkill\Http\Controllers\ExpiryDashboardController;
use Modules\FSMSkill\Http\Controllers\DispatchController;

Route::middleware(['web', 'auth'])
    ->prefix('account/fsm/skills')
    ->group(function () {

        // ── Skill Types ──────────────────────────────────────────────────────
        Route::get('/types',                  [SkillTypeController::class, 'index'])->name('fsmskill.skill-types.index');
        Route::get('/types/create',           [SkillTypeController::class, 'create'])->name('fsmskill.skill-types.create');
        Route::post('/types',                 [SkillTypeController::class, 'store'])->name('fsmskill.skill-types.store');
        Route::get('/types/{id}/edit',        [SkillTypeController::class, 'edit'])->name('fsmskill.skill-types.edit');
        Route::post('/types/{id}',            [SkillTypeController::class, 'update'])->name('fsmskill.skill-types.update');
        Route::post('/types/{id}/delete',     [SkillTypeController::class, 'destroy'])->name('fsmskill.skill-types.destroy');

        // ── Skills ───────────────────────────────────────────────────────────
        Route::get('/',                        [SkillController::class, 'index'])->name('fsmskill.skills.index');
        Route::get('/create',                  [SkillController::class, 'create'])->name('fsmskill.skills.create');
        Route::post('/',                       [SkillController::class, 'store'])->name('fsmskill.skills.store');
        Route::get('/{id}/edit',               [SkillController::class, 'edit'])->name('fsmskill.skills.edit');
        Route::post('/{id}',                   [SkillController::class, 'update'])->name('fsmskill.skills.update');
        Route::post('/{id}/delete',            [SkillController::class, 'destroy'])->name('fsmskill.skills.destroy');

        // ── Skill Levels (nested under skill) ────────────────────────────────
        Route::get('/{skillId}/levels',                    [SkillLevelController::class, 'index'])->name('fsmskill.skill-levels.index');
        Route::get('/{skillId}/levels/create',             [SkillLevelController::class, 'create'])->name('fsmskill.skill-levels.create');
        Route::post('/{skillId}/levels',                   [SkillLevelController::class, 'store'])->name('fsmskill.skill-levels.store');
        Route::get('/{skillId}/levels/{levelId}/edit',     [SkillLevelController::class, 'edit'])->name('fsmskill.skill-levels.edit');
        Route::post('/{skillId}/levels/{levelId}',         [SkillLevelController::class, 'update'])->name('fsmskill.skill-levels.update');
        Route::post('/{skillId}/levels/{levelId}/delete',  [SkillLevelController::class, 'destroy'])->name('fsmskill.skill-levels.destroy');

        // ── AJAX: levels for a skill ─────────────────────────────────────────
        Route::get('/ajax/levels/{skillId}',  [EmployeeSkillController::class, 'levels'])->name('fsmskill.ajax.levels');

    });

// ── Worker / Employee Skills (prefixed separately for clarity) ───────────────
Route::middleware(['web', 'auth'])
    ->prefix('account/fsm/workers')
    ->group(function () {

        Route::get('/{userId}/skills',               [EmployeeSkillController::class, 'index'])->name('fsmskill.employee-skills.index');
        Route::get('/{userId}/skills/create',        [EmployeeSkillController::class, 'create'])->name('fsmskill.employee-skills.create');
        Route::post('/{userId}/skills',              [EmployeeSkillController::class, 'store'])->name('fsmskill.employee-skills.store');
        Route::get('/{userId}/skills/{id}/edit',     [EmployeeSkillController::class, 'edit'])->name('fsmskill.employee-skills.edit');
        Route::post('/{userId}/skills/{id}',         [EmployeeSkillController::class, 'update'])->name('fsmskill.employee-skills.update');
        Route::post('/{userId}/skills/{id}/delete',  [EmployeeSkillController::class, 'destroy'])->name('fsmskill.employee-skills.destroy');

    });

// ── FSM Order skill requirements ─────────────────────────────────────────────
Route::middleware(['web', 'auth'])
    ->prefix('account/fsm/orders')
    ->group(function () {

        Route::get('/{orderId}/skill-requirements',           [OrderSkillController::class, 'index'])->name('fsmskill.order-skills.index');
        Route::post('/{orderId}/skill-requirements',          [OrderSkillController::class, 'store'])->name('fsmskill.order-skills.store');
        Route::post('/{orderId}/skill-requirements/{id}/delete', [OrderSkillController::class, 'destroy'])->name('fsmskill.order-skills.destroy');
        Route::post('/{orderId}/validate-worker',             [OrderSkillController::class, 'validateWorker'])->name('fsmskill.order-skills.validate-worker');

    });

// ── FSM Template skill requirements ──────────────────────────────────────────
Route::middleware(['web', 'auth'])
    ->prefix('account/fsm/templates')
    ->group(function () {

        Route::get('/{templateId}/skill-requirements',              [TemplateSkillController::class, 'index'])->name('fsmskill.template-skills.index');
        Route::post('/{templateId}/skill-requirements',             [TemplateSkillController::class, 'store'])->name('fsmskill.template-skills.store');
        Route::post('/{templateId}/skill-requirements/{id}/delete', [TemplateSkillController::class, 'destroy'])->name('fsmskill.template-skills.destroy');

    });

// ── Expiry Dashboard & Dispatch ───────────────────────────────────────────────
Route::middleware(['web', 'auth'])
    ->prefix('account/fsm')
    ->group(function () {

        Route::get('/certification-expiry', [ExpiryDashboardController::class, 'index'])->name('fsmskill.expiry-dashboard');
        Route::get('/dispatch',             [DispatchController::class, 'index'])->name('fsmskill.dispatch');

    });
