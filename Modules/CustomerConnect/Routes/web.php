<?php



use Illuminate\Support\Facades\Route;
use Modules\CustomerConnect\Http\Controllers\Portal\PortalBookingController;
use Modules\CustomerConnect\Http\Controllers\Portal\PortalDashboardController;
use Modules\CustomerConnect\Http\Controllers\Portal\PortalInvoiceController;
use Modules\CustomerConnect\Http\Controllers\Portal\PortalPaymentController;
use Modules\CustomerConnect\Http\Controllers\Portal\PortalPreferencesController;
use Modules\CustomerConnect\Http\Controllers\Portal\PortalPropertyController;
use Modules\CustomerConnect\Http\Controllers\Portal\PortalReviewController;
use Modules\CustomerConnect\Http\Controllers\AudienceController;
use Modules\CustomerConnect\Http\Controllers\BulkActionsController;
use Modules\CustomerConnect\Http\Controllers\CampaignController;
use Modules\CustomerConnect\Http\Controllers\CampaignStepController;
use Modules\CustomerConnect\Http\Controllers\DashboardController;
use Modules\CustomerConnect\Http\Controllers\DeliveryController;
use Modules\CustomerConnect\Http\Controllers\ExportController;
use Modules\CustomerConnect\Http\Controllers\HealthController;
use Modules\CustomerConnect\Http\Controllers\HistoryController;
use Modules\CustomerConnect\Http\Controllers\InboxController;
use Modules\CustomerConnect\Http\Controllers\PrivacyExportController;
use Modules\CustomerConnect\Http\Controllers\RecipeController;
use Modules\CustomerConnect\Http\Controllers\RunController;
use Modules\CustomerConnect\Http\Controllers\SavedFiltersController;
use Modules\CustomerConnect\Http\Controllers\SavedFiltersManagerController;
use Modules\CustomerConnect\Http\Controllers\SuppressionController;
use Modules\CustomerConnect\Http\Controllers\TagController;
use Modules\CustomerConnect\Http\Controllers\ThreadController;
use Modules\CustomerConnect\Http\Controllers\ThreadEventsController;
use Modules\CustomerConnect\Http\Controllers\UnsubscribeController;

Route::middleware(['web','auth'])->prefix('account')->group(function () {
/*
|--------------------------------------------------------------------------
| CustomerConnect Web Routes (WorkSuite tenant UI)
|--------------------------------------------------------------------------
| Rules:
| - All tenant UI must be account-prefixed (handled by 'account' middleware).
| - Webhook / provider callbacks live in webhooks.php (api middleware only).
| - TitanZero integration: CustomerConnect must NOT define its own AI routes.
*/

Route::group([
    'middleware' => ['web', 'auth', 'account'],
    'prefix'     => 'account/customer-connect',
    'as'         => 'customerconnect.',
], function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    // Health
    Route::get('/health', [HealthController::class, 'index'])->name('health.index');

    // ── Inbox ────────────────────────────────────────────────────────────────

    Route::get('/inbox', [InboxController::class, 'index'])->name('inbox.index');
    Route::get('/inbox/new', [InboxController::class, 'create'])->name('inbox.create');
    Route::post('/inbox', [InboxController::class, 'store'])->name('inbox.store');

    Route::post('/inbox/bulk', [BulkActionsController::class, 'apply'])->name('inbox.bulk');
    Route::post('/inbox/filters', [SavedFiltersController::class, 'store'])->name('filters.store');
    Route::delete('/inbox/filters/{id}', [SavedFiltersController::class, 'delete'])->name('filters.delete');

    Route::get('/inbox/threads/{thread}', [ThreadController::class, 'show'])->name('inbox.threads.show');
    Route::post('/inbox/threads/{thread}/send', [ThreadController::class, 'send'])->name('inbox.threads.send');
    Route::post('/inbox/threads/{thread}/assign', [ThreadController::class, 'assign'])->name('inbox.threads.assign');
    Route::post('/inbox/threads/{thread}/close', [ThreadController::class, 'close'])->name('inbox.threads.close');
    Route::get('/inbox/threads/{thread}/events', [ThreadEventsController::class, 'index'])->name('inbox.threads.events');

    // ── Campaigns ────────────────────────────────────────────────────────────

    Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
    Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');

    Route::post('/campaigns/{campaign}/activate', [CampaignController::class, 'activate'])->name('campaigns.activate');
    Route::post('/campaigns/{campaign}/pause', [CampaignController::class, 'pause'])->name('campaigns.pause');
    Route::get('/campaigns/{campaign}/preview', [CampaignController::class, 'preview'])->name('campaigns.preview');

    // ── Campaign Steps ────────────────────────────────────────────────────────

    Route::get('/campaigns/{campaign}/steps', [CampaignStepController::class, 'index'])->name('steps.index');
    Route::post('/campaigns/{campaign}/steps', [CampaignStepController::class, 'store'])->name('steps.store');
    Route::get('/campaigns/{campaign}/steps/{step}/edit', [CampaignStepController::class, 'edit'])->name('steps.edit');
    Route::put('/campaigns/{campaign}/steps/{step}', [CampaignStepController::class, 'update'])->name('steps.update');
    Route::delete('/campaigns/{campaign}/steps/{step}', [CampaignStepController::class, 'destroy'])->name('steps.destroy');
    Route::post('/campaigns/{campaign}/steps/reorder', [CampaignStepController::class, 'reorder'])->name('steps.reorder');

    // ── Audiences ────────────────────────────────────────────────────────────

    Route::get('/audiences', [AudienceController::class, 'index'])->name('audiences.index');
    Route::get('/audiences/create', [AudienceController::class, 'create'])->name('audiences.create');
    Route::post('/audiences', [AudienceController::class, 'store'])->name('audiences.store');
    Route::get('/audiences/{audience}/edit', [AudienceController::class, 'edit'])->name('audiences.edit');
    Route::put('/audiences/{audience}', [AudienceController::class, 'update'])->name('audiences.update');
    Route::delete('/audiences/{audience}', [AudienceController::class, 'destroy'])->name('audiences.destroy');

    // ── Runs & Deliveries ─────────────────────────────────────────────────────

    Route::get('/runs', [RunController::class, 'index'])->name('runs.index');
    Route::get('/runs/{run}', [RunController::class, 'show'])->name('runs.show');
    Route::post('/campaigns/{campaign}/runs/build', [RunController::class, 'build'])->name('runs.build');

    Route::get('/deliveries', [DeliveryController::class, 'index'])->name('deliveries.index');
    Route::get('/deliveries/{delivery}', [DeliveryController::class, 'show'])->name('deliveries.show');

    // ── Recipes ───────────────────────────────────────────────────────────────

    Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
    Route::post('/recipes/install', [RecipeController::class, 'install'])->name('recipes.install');

    // ── History (newsletter history — FIX BUG 5: routes were missing) ─────────

    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    Route::get('/history/{id}', [HistoryController::class, 'show'])->name('history.show');
    Route::delete('/history/{id}', [HistoryController::class, 'destroy'])->name('history.destroy');

    // ── Settings ──────────────────────────────────────────────────────────────

    Route::get('/settings/suppressions', [SuppressionController::class, 'index'])->name('settings.suppressions.index');
    Route::post('/settings/suppressions', [SuppressionController::class, 'store'])->name('settings.suppressions.store');
    Route::delete('/settings/suppressions/{id}', [SuppressionController::class, 'destroy'])->name('settings.suppressions.destroy');

    Route::get('/settings/unsubscribes', [UnsubscribeController::class, 'index'])->name('settings.unsubscribes.index');
    Route::post('/settings/unsubscribes', [UnsubscribeController::class, 'store'])->name('settings.unsubscribes.store');
    Route::delete('/settings/unsubscribes/{id}', [UnsubscribeController::class, 'destroy'])->name('settings.unsubscribes.destroy');

    // Tags (FIX BUG 4: TagController now exists)
    Route::get('/settings/tags', [TagController::class, 'index'])->name('settings.tags.index');
    Route::post('/settings/tags', [TagController::class, 'store'])->name('settings.tags.store');
    Route::put('/settings/tags/{id}', [TagController::class, 'update'])->name('settings.tags.update');
    Route::delete('/settings/tags/{id}', [TagController::class, 'destroy'])->name('settings.tags.destroy');

    // Saved filters manager (FIX BUG 4: SavedFiltersManagerController now exists)
    Route::get('/settings/filters', [SavedFiltersManagerController::class, 'index'])->name('settings.filters.index');
    Route::post('/settings/filters/{id}/default', [SavedFiltersManagerController::class, 'setDefault'])->name('settings.filters.default');
    Route::delete('/settings/filters/{id}', [SavedFiltersManagerController::class, 'destroy'])->name('settings.filters.destroy');

    // ── Exports & Privacy ─────────────────────────────────────────────────────

    Route::get('/exports/deliveries.csv', [ExportController::class, 'deliveries'])->name('exports.deliveries');
    Route::get('/privacy/contacts/{contactId}/export.csv', [PrivacyExportController::class, 'exportContactCsv'])->name('privacy.contact.export');
});
});

// ─────────────────────────────────────────────────────────────────────────────
// Customer Self-Service Portal Routes
// ─────────────────────────────────────────────────────────────────────────────
// These routes are available to authenticated customers (clients) and provide
// the self-service portal: bookings, invoices, payments, properties, preferences.
// All routes are behind standard web + auth middleware.

Route::middleware(['web', 'auth'])
    ->prefix('portal/customer')
    ->name('customerconnect.portal.')
    ->group(function () {

        // ── Portal Dashboard ──────────────────────────────────────────────────
        Route::get('/', [PortalDashboardController::class, 'index'])->name('dashboard');

        // ── My Bookings ───────────────────────────────────────────────────────
        Route::get('/bookings', [PortalBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/rebook', [PortalBookingController::class, 'rebook'])->name('bookings.rebook');
        Route::post('/bookings/rebook', [PortalBookingController::class, 'storeRebook'])->name('bookings.rebook.store');
        Route::post('/bookings/{id}/cancel', [PortalBookingController::class, 'cancel'])->name('bookings.cancel');

        // ── Invoices ──────────────────────────────────────────────────────────
        Route::get('/invoices', [PortalInvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/{id}/download', [PortalInvoiceController::class, 'download'])->name('invoices.download');

        // ── Payments ──────────────────────────────────────────────────────────
        Route::get('/payments', [PortalPaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments/invoice/{invoiceId}', [PortalPaymentController::class, 'payInvoice'])->name('payments.pay-invoice');

        // ── My Properties ─────────────────────────────────────────────────────
        Route::get('/properties', [PortalPropertyController::class, 'index'])->name('properties.index');
        Route::get('/properties/create', [PortalPropertyController::class, 'create'])->name('properties.create');
        Route::post('/properties', [PortalPropertyController::class, 'store'])->name('properties.store');
        Route::get('/properties/{id}/edit', [PortalPropertyController::class, 'edit'])->name('properties.edit');
        Route::put('/properties/{id}', [PortalPropertyController::class, 'update'])->name('properties.update');

        // ── Preferences & Settings ────────────────────────────────────────────
        Route::get('/preferences', [PortalPreferencesController::class, 'index'])->name('preferences.index');
        Route::put('/preferences', [PortalPreferencesController::class, 'update'])->name('preferences.update');

        // ── Reviews ───────────────────────────────────────────────────────────
        Route::get('/reviews/create', [PortalReviewController::class, 'create'])->name('reviews.create');
        Route::post('/reviews', [PortalReviewController::class, 'store'])->name('reviews.store');
    });
