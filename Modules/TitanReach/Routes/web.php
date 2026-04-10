<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanReach\Http\Controllers\DashboardController;
use Modules\TitanReach\Http\Controllers\InboxController;
use Modules\TitanReach\Http\Controllers\CampaignController;
use Modules\TitanReach\Http\Controllers\SmsController;
use Modules\TitanReach\Http\Controllers\CallCampaignController;
use Modules\TitanReach\Http\Controllers\WhatsappController;
use Modules\TitanReach\Http\Controllers\TelegramController;
use Modules\TitanReach\Http\Controllers\ContactController;
use Modules\TitanReach\Http\Controllers\ContactListController;
use Modules\TitanReach\Http\Controllers\SegmentController;
use Modules\TitanReach\Http\Controllers\TrainingController;

// Authenticated routes
Route::middleware(['auth', 'web'])
    ->prefix('account/titanreach')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('titanreach.dashboard');

        // Inbox
        Route::get('/inbox', [InboxController::class, 'index'])->name('titanreach.inbox.index');
        Route::get('/inbox/{id}', [InboxController::class, 'show'])->name('titanreach.inbox.show');
        Route::post('/inbox/{id}/assign', [InboxController::class, 'assign'])->name('titanreach.inbox.assign');
        Route::post('/inbox/{id}/status', [InboxController::class, 'updateStatus'])->name('titanreach.inbox.status');
        Route::post('/inbox/{id}/suggest-reply', [InboxController::class, 'suggestReply'])->name('titanreach.inbox.suggest-reply');

        // Campaigns
        Route::get('/campaigns', [CampaignController::class, 'index'])->name('titanreach.campaigns.index');
        Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('titanreach.campaigns.create');
        Route::post('/campaigns', [CampaignController::class, 'store'])->name('titanreach.campaigns.store');
        Route::get('/campaigns/{id}/edit', [CampaignController::class, 'edit'])->name('titanreach.campaigns.edit');
        Route::post('/campaigns/{id}', [CampaignController::class, 'update'])->name('titanreach.campaigns.update');
        Route::post('/campaigns/{id}/delete', [CampaignController::class, 'destroy'])->name('titanreach.campaigns.destroy');
        Route::post('/campaigns/{id}/run', [CampaignController::class, 'run'])->name('titanreach.campaigns.run');

        // SMS
        Route::get('/sms', [SmsController::class, 'index'])->name('titanreach.sms.index');
        Route::get('/sms/create', [SmsController::class, 'create'])->name('titanreach.sms.create');
        Route::post('/sms', [SmsController::class, 'store'])->name('titanreach.sms.store');
        Route::post('/sms/send', [SmsController::class, 'send'])->name('titanreach.sms.send');

        // Calls
        Route::get('/calls', [CallCampaignController::class, 'index'])->name('titanreach.calls.index');
        Route::get('/calls/create', [CallCampaignController::class, 'create'])->name('titanreach.calls.create');
        Route::post('/calls', [CallCampaignController::class, 'store'])->name('titanreach.calls.store');
        Route::get('/calls/{id}/edit', [CallCampaignController::class, 'edit'])->name('titanreach.calls.edit');
        Route::post('/calls/{id}', [CallCampaignController::class, 'update'])->name('titanreach.calls.update');
        Route::post('/calls/{id}/delete', [CallCampaignController::class, 'destroy'])->name('titanreach.calls.destroy');
        Route::post('/calls/{id}/run', [CallCampaignController::class, 'run'])->name('titanreach.calls.run');

        // WhatsApp
        Route::get('/whatsapp', [WhatsappController::class, 'index'])->name('titanreach.whatsapp.index');
        Route::get('/whatsapp/create', [WhatsappController::class, 'create'])->name('titanreach.whatsapp.create');
        Route::post('/whatsapp', [WhatsappController::class, 'store'])->name('titanreach.whatsapp.store');
        Route::post('/whatsapp/send', [WhatsappController::class, 'send'])->name('titanreach.whatsapp.send');

        // Telegram
        Route::get('/telegram', [TelegramController::class, 'index'])->name('titanreach.telegram.index');
        Route::get('/telegram/create', [TelegramController::class, 'create'])->name('titanreach.telegram.create');
        Route::post('/telegram', [TelegramController::class, 'store'])->name('titanreach.telegram.store');
        Route::post('/telegram/send', [TelegramController::class, 'send'])->name('titanreach.telegram.send');

        // Contacts
        Route::get('/contacts', [ContactController::class, 'index'])->name('titanreach.contacts.index');
        Route::get('/contacts/create', [ContactController::class, 'create'])->name('titanreach.contacts.create');
        Route::post('/contacts', [ContactController::class, 'store'])->name('titanreach.contacts.store');
        Route::get('/contacts/{id}/edit', [ContactController::class, 'edit'])->name('titanreach.contacts.edit');
        Route::post('/contacts/{id}', [ContactController::class, 'update'])->name('titanreach.contacts.update');
        Route::post('/contacts/{id}/delete', [ContactController::class, 'destroy'])->name('titanreach.contacts.destroy');

        // Contact Lists
        Route::get('/lists', [ContactListController::class, 'index'])->name('titanreach.lists.index');
        Route::get('/lists/create', [ContactListController::class, 'create'])->name('titanreach.lists.create');
        Route::post('/lists', [ContactListController::class, 'store'])->name('titanreach.lists.store');
        Route::get('/lists/{id}', [ContactListController::class, 'show'])->name('titanreach.lists.show');
        Route::post('/lists/{id}/contacts', [ContactListController::class, 'addContact'])->name('titanreach.lists.contacts.add');
        Route::post('/lists/{id}/contacts/{contactId}/remove', [ContactListController::class, 'removeContact'])->name('titanreach.lists.contacts.remove');
        Route::post('/lists/{id}/delete', [ContactListController::class, 'destroy'])->name('titanreach.lists.destroy');

        // Segments
        Route::get('/segments', [SegmentController::class, 'index'])->name('titanreach.segments.index');
        Route::get('/segments/create', [SegmentController::class, 'create'])->name('titanreach.segments.create');
        Route::post('/segments', [SegmentController::class, 'store'])->name('titanreach.segments.store');
        Route::get('/segments/{id}/edit', [SegmentController::class, 'edit'])->name('titanreach.segments.edit');
        Route::post('/segments/{id}', [SegmentController::class, 'update'])->name('titanreach.segments.update');
        Route::post('/segments/{id}/delete', [SegmentController::class, 'destroy'])->name('titanreach.segments.destroy');

        // Training
        Route::get('/training', [TrainingController::class, 'index'])->name('titanreach.training.index');
        Route::post('/training', [TrainingController::class, 'store'])->name('titanreach.training.store');
        Route::post('/training/{id}/delete', [TrainingController::class, 'destroy'])->name('titanreach.training.destroy');
    });
