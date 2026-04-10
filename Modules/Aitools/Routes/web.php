<?php

use Illuminate\Support\Facades\Route;
use Modules\Aitools\Http\Controllers\AiToolsSettingController;
use Modules\Aitools\Http\Controllers\AiRephraseController;
use Modules\Aitools\Http\Controllers\AiToolController;
use Modules\Aitools\Http\Controllers\AiPromptController;
use Modules\Aitools\Http\Controllers\AiPromptRunController;
use Modules\Aitools\Http\Controllers\AiProviderController;
use Modules\Aitools\Http\Controllers\AiKbController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth', 'prefix' => 'account'], function () {
    Route::resource('settings/ai-tools-settings', AiToolsSettingController::class)->only(['index', 'update']);
    Route::post('ai-tools-settings/test-chat', [AiToolsSettingController::class, 'testChat'])->name('ai-tools-settings.test-chat');
    Route::post('ai-tools-settings/refresh-usage', [AiToolsSettingController::class, 'refreshUsage'])->name('ai-tools-settings.refresh-usage');
    Route::post('ai-tools-settings/reset-usage', [AiToolsSettingController::class, 'resetUsage'])->name('ai-tools-settings.reset-usage');
    Route::post('ai-tools-settings/update-company-used-tokens', [AiToolsSettingController::class, 'updateCompanyUsedTokens'])->name('ai-tools-settings.update-company-used-tokens');
    
    
    // Pass 1: Providers & Models
    Route::get('settings/ai-tools-providers', [AiProviderController::class, 'index'])->name('ai-tools.providers.index');
    Route::post('settings/ai-tools-providers/provider', [AiProviderController::class, 'storeProvider'])->name('ai-tools.providers.store');
    Route::post('settings/ai-tools-providers/model', [AiProviderController::class, 'storeModel'])->name('ai-tools.models.store');

    // Pass 3: Prompts (Templates)
    Route::get('settings/ai-tools-prompts', [AiPromptController::class, 'index'])->name('ai-tools.prompts.index');
    Route::post('settings/ai-tools-prompts', [AiPromptController::class, 'store'])->name('ai-tools.prompts.store');
    Route::post('settings/ai-tools-prompts/run', [AiPromptRunController::class, 'run'])->name('ai-tools.prompts.run');

    // Pass 4: Tools registry + dispatch
    Route::get('settings/ai-tools-tools', [AiToolController::class, 'index'])->name('ai-tools.tools.index');
    Route::post('settings/ai-tools-tools', [AiToolController::class, 'store'])->name('ai-tools.tools.store');
    Route::post('ai-tools-tools/dispatch', [AiToolController::class, 'dispatch'])->name('ai-tools.tools.dispatch');

    // Company-level usage and history page
    Route::get('settings/ai-tools-usage', [AiToolsSettingController::class, 'companyUsage'])->name('ai-tools-usage.index');

    // Pass 5/6: Knowledge Base (Sources, Documents, Search)
    Route::get('settings/ai-tools-kb/sources', [AiKbController::class, 'sources'])->name('ai-tools.kb.sources.index');
    Route::post('settings/ai-tools-kb/sources', [AiKbController::class, 'storeSource'])->name('ai-tools.kb.sources.store');

    Route::get('settings/ai-tools-kb/documents', [AiKbController::class, 'documents'])->name('ai-tools.kb.documents.index');
    Route::post('settings/ai-tools-kb/documents', [AiKbController::class, 'storeDocument'])->name('ai-tools.kb.documents.store');

    Route::post('settings/ai-tools-kb/search', [AiKbController::class, 'search'])->name('ai-tools.kb.search');
    
    // Rephrase text route - available under projects prefix for backward compatibility
    Route::group(['prefix' => 'projects'], function () {
        Route::post('rephrase-text', [AiRephraseController::class, 'rephraseText'])->name('projects.rephrase-text');
    });
});


