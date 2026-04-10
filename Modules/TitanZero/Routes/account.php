

use Modules\TitanZero\Http\Controllers\Account\CoachController;

Route::get('/coaches', [CoachController::class, 'index'])->name('coaches.index');
Route::get('/coaches/{coachKey}', [CoachController::class, 'show'])->name('coaches.show');
Route::post('/coaches/{coachKey}/ask', [CoachController::class, 'ask'])->name('coaches.ask');


// Convenience alias: Standards & Guides chat (routes to compliance coach)
Route::get('/standards', function() {
    return redirect()->to('account/titan/zero/coaches/compliance');
})->name('standards');


// Titan Zero Intent Engine (foundation)
Route::post('/intent/resolve', [IntentController::class, 'resolve'])->name('intent.resolve');
Route::post('/intent/route', [IntentController::class, 'route'])->name('intent.route');

Route::post('/intent/confirm', [IntentController::class, 'confirm'])->name('intent.confirm');


// Titan Zero Wizards (structured UI)
Route::get('/wizards', [WizardController::class, 'index'])->name('wizards.index');
Route::post('/wizards/explain', [WizardController::class, 'explainPage'])->name('wizards.explain');
Route::post('/wizards/standards', [WizardController::class, 'standardsQa'])->name('wizards.standards');
