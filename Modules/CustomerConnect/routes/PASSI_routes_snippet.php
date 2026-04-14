<?php
// PASS I: paste inside the account-prefixed CustomerConnect group.

use Modules\CustomerConnect\Http\Controllers\ThreadEventsController;

Route::get('inbox/threads/{id}/events', [ThreadEventsController::class, 'index'])
    ->name('customerconnect.inbox.thread.events');
