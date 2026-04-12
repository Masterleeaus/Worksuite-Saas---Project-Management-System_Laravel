<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanTheme\Http\Controllers\ThemeController;
use Modules\TitanTheme\Http\Controllers\LiveCustomizerController;
use Modules\TitanTheme\Http\Controllers\MegaMenuController;
use Modules\TitanTheme\Http\Controllers\MegaMenuItemController;
use Modules\TitanTheme\Http\Controllers\MenuController;
use Modules\TitanTheme\Http\Controllers\NavigationController;
use Modules\TitanTheme\Http\Controllers\WhiteLabelController;

/*
|--------------------------------------------------------------------------
| Web Routes – TitanTheme
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth', 'prefix' => 'account', 'as' => 'titantheme.'], function () {

    // ── Live Customizer ────────────────────────────────────────────────────
    Route::prefix('theme/customizer')->name('customizer.')->group(function () {
        Route::get('/',         [LiveCustomizerController::class, 'index'])->name('index');
        Route::post('/preview', [LiveCustomizerController::class, 'preview'])->name('preview');
        Route::post('/save',    [LiveCustomizerController::class, 'save'])->name('save');
    });

    // CSS variables endpoint (included in <head> via blade directive)
    Route::get('theme/css-variables', [ThemeController::class, 'cssVariables'])
        ->name('css-variables')
        ->withoutMiddleware('auth');

    // ── Theme Presets ──────────────────────────────────────────────────────
    Route::prefix('theme/presets')->name('presets.')->group(function () {
        Route::get('/',               [ThemeController::class, 'index'])->name('index');
        Route::get('/create',         [ThemeController::class, 'create'])->name('create');
        Route::post('/',              [ThemeController::class, 'store'])->name('store');
        Route::get('/{id}/edit',      [ThemeController::class, 'edit'])->name('edit');
        Route::put('/{id}',           [ThemeController::class, 'update'])->name('update');
        Route::delete('/{id}',        [ThemeController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/activate', [ThemeController::class, 'activate'])->name('activate');
        Route::post('/deactivate',    [ThemeController::class, 'deactivate'])->name('deactivate');
    });

    // ── Mega Menu ──────────────────────────────────────────────────────────
    Route::prefix('theme/mega-menu')->name('mega-menu.')->group(function () {
        Route::get('/',          [MegaMenuController::class, 'index'])->name('index');
        Route::get('/create',    [MegaMenuController::class, 'create'])->name('create');
        Route::post('/',         [MegaMenuController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [MegaMenuController::class, 'edit'])->name('edit');
        Route::put('/{id}',      [MegaMenuController::class, 'update'])->name('update');
        Route::delete('/{id}',   [MegaMenuController::class, 'destroy'])->name('destroy');
        Route::post('/reorder',  [MegaMenuController::class, 'reorder'])->name('reorder');

        // Mega menu items (nested under a menu)
        Route::prefix('/{menuId}/items')->name('items.')->group(function () {
            Route::post('/',            [MegaMenuItemController::class, 'store'])->name('store');
            Route::put('/{itemId}',     [MegaMenuItemController::class, 'update'])->name('update');
            Route::delete('/{itemId}',  [MegaMenuItemController::class, 'destroy'])->name('destroy');
            Route::post('/reorder',     [MegaMenuItemController::class, 'reorder'])->name('reorder');
        });
    });

    // ── Navigation Builder ─────────────────────────────────────────────────
    Route::prefix('theme/navigation')->name('navigation.')->group(function () {
        Route::get('/',          [NavigationController::class, 'index'])->name('index');
        Route::post('/',         [NavigationController::class, 'store'])->name('store');
        Route::put('/{id}',      [NavigationController::class, 'update'])->name('update');
        Route::delete('/{id}',   [NavigationController::class, 'destroy'])->name('destroy');
        Route::post('/reorder',  [NavigationController::class, 'reorder'])->name('reorder');
    });

    // ── White-Label Settings ───────────────────────────────────────────────
    Route::prefix('admin/theme/white-label')->name('white-label.')->group(function () {
        Route::get('/',  [WhiteLabelController::class, 'index'])->name('index');
        Route::post('/', [WhiteLabelController::class, 'update'])->name('update');
    });

    // ── Sidebar Menu Builder (Menu v2.2.0) ────────────────────────────────
    Route::prefix('admin/menu')->name('menu.')->group(function () {
        Route::get('/',         [MenuController::class, 'index'])->name('index');
        Route::delete('/{menu}', [MenuController::class, 'delete'])->name('delete');
    });

    // ── Admin Mega Menu (MegaMenu v1.1.0) ─────────────────────────────────
    Route::prefix('admin/mega-menu')->name('admin.mega-menu.')->group(function () {
        Route::get('/',          [MegaMenuController::class, 'index'])->name('index');
        Route::get('/create',    [MegaMenuController::class, 'create'])->name('create');
        Route::post('/',         [MegaMenuController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [MegaMenuController::class, 'edit'])->name('edit');
        Route::put('/{id}',      [MegaMenuController::class, 'update'])->name('update');

        // Mega menu items (nested under a menu)
        Route::prefix('/{menuId}/items')->name('items.')->group(function () {
            Route::post('/',           [MegaMenuItemController::class, 'store'])->name('store');
            Route::put('/{itemId}',    [MegaMenuItemController::class, 'update'])->name('update');
            Route::delete('/{itemId}', [MegaMenuItemController::class, 'destroy'])->name('destroy');
            Route::post('/reorder',    [MegaMenuItemController::class, 'reorder'])->name('reorder');
        });
    });
});
