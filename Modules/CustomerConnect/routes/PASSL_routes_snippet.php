<?php
// PASS L routes snippet (paste into Modules/CustomerConnect/Routes/web.php inside the account/customer-connect group)

// Privacy export
Route::get('privacy/contacts/{contactId}/export.csv', [\Modules\CustomerConnect\Http\Controllers\PrivacyExportController::class, 'exportContactCsv'])
    ->name('customerconnect.privacy.contact.export');

// Retention: optional UI link could be added later; for now it's command-only.
