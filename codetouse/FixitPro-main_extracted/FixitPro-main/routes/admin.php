<?php

use App\Http\Controllers\Admin\Auth\Controllers\LoginController;
use App\Http\Controllers\Admin\Admin_user\Controllers\AdminUserController;
use App\Http\Controllers\Admin\Acl\Controllers\RoleController;
use App\Http\Controllers\Admin\Acl\Controllers\ModuleController;
use App\Http\Controllers\Admin\Acl\Controllers\ModuleCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Tenant\Controllers\TenantController;
use App\Http\Controllers\Admin\Plan\Controllers\PlanController;
use App\Http\Controllers\Admin\Coupon\Controllers\CouponController;
use App\Http\Controllers\Admin\Settings\Controllers\SettingsController;
use App\Http\Controllers\Admin\AuditLog\Controllers\AuditLogController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login']);
    });
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::middleware(['XSS', 'Admin'])->group(function () {



        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Admin User Management (ACL)
        Route::get('acl/users', [AdminUserController::class, 'index'])->name('acl.users');
        Route::get('acl/users/edit/{id}', [AdminUserController::class, 'edit'])->name('acl.users.edit');
        Route::post('acl/users/edit/{id}', [AdminUserController::class, 'update'])->name('acl.users.update');
        Route::get('acl/users/add', [AdminUserController::class, 'create'])->name('acl.users.add');
        Route::post('acl/users/add', [AdminUserController::class, 'store'])->name('acl.users.store');
        Route::get('acl/users/delete/{id}', [AdminUserController::class, 'destroy'])->name('acl.users.delete');

        // Roles Management
        Route::get('acl/roles', [RoleController::class, 'index'])->name('acl.roles');
        Route::get('acl/roles/add', [RoleController::class, 'create'])->name('acl.roles.add');
        Route::post('acl/roles/add', [RoleController::class, 'store'])->name('acl.roles.store');
        Route::get('acl/roles/edit/{id}', [RoleController::class, 'edit'])->name('acl.roles.edit');
        Route::post('acl/roles/edit/{id}', [RoleController::class, 'update'])->name('acl.roles.update');
        Route::get('acl/roles/delete/{id}', [RoleController::class, 'destroy'])->name('acl.roles.delete');
        
        // Modules Management
        Route::get('acl/modules', [ModuleController::class, 'index'])->name('acl.modules');
        Route::get('acl/modules/add', [ModuleController::class, 'create'])->name('acl.modules.add');
        Route::post('acl/modules/add', [ModuleController::class, 'store'])->name('acl.modules.store');
        Route::get('acl/modules/edit/{id}', [ModuleController::class, 'edit'])->name('acl.modules.edit');
        Route::post('acl/modules/edit/{id}', [ModuleController::class, 'update'])->name('acl.modules.update');
        Route::get('acl/modules/delete/{id}', [ModuleController::class, 'destroy'])->name('acl.modules.delete');

        // Categories Management
        Route::get('acl/categories', [ModuleCategoryController::class, 'index'])->name('acl.categories');
        Route::get('acl/categories/add', [ModuleCategoryController::class, 'create'])->name('acl.categories.add');
        Route::post('acl/categories/add', [ModuleCategoryController::class, 'store'])->name('acl.categories.store');
        Route::get('acl/categories/edit/{id}', [ModuleCategoryController::class, 'edit'])->name('acl.categories.edit');
        Route::post('acl/categories/edit/{id}', [ModuleCategoryController::class, 'update'])->name('acl.categories.update');
        Route::get('acl/categories/delete/{id}', [ModuleCategoryController::class, 'destroy'])->name('acl.categories.delete');

        // Tenant (Business) Management
        Route::get('tenants', [TenantController::class, 'index'])->name('tenants');
        Route::get('tenants/view/{id}', [TenantController::class, 'show'])->name('tenants.view');
        Route::get('tenants/edit/{id}', [TenantController::class, 'edit'])->name('tenants.edit');
        Route::post('tenants/edit/{id}', [TenantController::class, 'update'])->name('tenants.update');
        Route::get('tenants/delete/{id}', [TenantController::class, 'destroy'])->name('tenants.delete');
        Route::post('tenants/toggle-status/{id}', [TenantController::class, 'toggleStatus'])->name('tenants.toggle-status');
        Route::post('tenants/impersonate/{id}', [TenantController::class, 'impersonate'])->name('tenants.impersonate');
        Route::post('tenants/stop-impersonation', [TenantController::class, 'stopImpersonation'])->name('tenants.stop-impersonation');

        // Subscription Plans Management
        Route::get('plans', [PlanController::class, 'index'])->name('plans');
        Route::get('plans/add', [PlanController::class, 'create'])->name('plans.add');
        Route::post('plans/add', [PlanController::class, 'store'])->name('plans.store');
        Route::get('plans/edit/{id}', [PlanController::class, 'edit'])->name('plans.edit');
        Route::post('plans/edit/{id}', [PlanController::class, 'update'])->name('plans.update');
        Route::get('plans/delete/{id}', [PlanController::class, 'destroy'])->name('plans.delete');
        Route::post('plans/toggle-status/{id}', [PlanController::class, 'toggleStatus'])->name('plans.toggle-status');
        // Coupon Management
        Route::get('coupons', [CouponController::class, 'index'])->name('coupons');
        Route::get('coupons/add', [CouponController::class, 'create'])->name('coupons.add');
        Route::post('coupons/add', [CouponController::class, 'store'])->name('coupons.store');
        Route::get('coupons/edit/{id}', [CouponController::class, 'edit'])->name('coupons.edit');
        Route::post('coupons/edit/{id}', [CouponController::class, 'update'])->name('coupons.update');
        Route::get('coupons/delete/{id}', [CouponController::class, 'destroy'])->name('coupons.delete');
        Route::post('coupons/toggle-status/{id}', [CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
        Route::get('coupons/generate-code', [CouponController::class, 'generateCode'])->name('coupons.generate-code');

        // Settings Management
        Route::get('settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

        // Audit Logs
        Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs');
        Route::get('audit-logs/view/{id}', [AuditLogController::class, 'show'])->name('audit-logs.view');
    });
});
