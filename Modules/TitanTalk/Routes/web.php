<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanTalk\Http\Controllers\RoomController;
use Modules\TitanTalk\Http\Controllers\MessageController;
use Modules\TitanTalk\Http\Controllers\ThreadController;
use Modules\TitanTalk\Http\Controllers\ReactionController;
use Modules\TitanTalk\Http\Controllers\UserStatusController;
use Modules\TitanTalk\Http\Controllers\SearchController;

Route::middleware(['web', 'auth'])
    ->prefix('account/titan-talk')
    ->name('titan.talk.')
    ->group(function () {

        // Main app shell
        Route::get('/', [RoomController::class, 'index'])->name('index');
        Route::get('/room/{room}', [RoomController::class, 'show'])->name('room.show');

        // Room CRUD
        Route::post('/rooms', [RoomController::class, 'store'])->name('room.store');
        Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('room.update');
        Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('room.destroy');
        Route::post('/rooms/{room}/join', [RoomController::class, 'join'])->name('room.join');
        Route::post('/rooms/{room}/leave', [RoomController::class, 'leave'])->name('room.leave');
        Route::post('/rooms/{room}/invite', [RoomController::class, 'invite'])->name('room.invite');
        Route::delete('/rooms/{room}/members/{user}', [RoomController::class, 'removeMember'])->name('room.member.remove');
        Route::post('/rooms/{room}/mute', [RoomController::class, 'mute'])->name('room.mute');
        Route::post('/rooms/{room}/unmute', [RoomController::class, 'unmute'])->name('room.unmute');

        // Messages
        Route::get('/rooms/{room}/messages', [MessageController::class, 'index'])->name('message.index');
        Route::post('/rooms/{room}/messages', [MessageController::class, 'store'])->name('message.store');
        Route::put('/messages/{message}', [MessageController::class, 'update'])->name('message.update');
        Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('message.destroy');
        Route::post('/messages/{message}/pin', [MessageController::class, 'pin'])->name('message.pin');
        Route::delete('/messages/{message}/pin', [MessageController::class, 'unpin'])->name('message.unpin');
        Route::post('/messages/{message}/save', [MessageController::class, 'save'])->name('message.save');
        Route::delete('/messages/{message}/save', [MessageController::class, 'unsave'])->name('message.unsave');
        Route::get('/rooms/{room}/pinned', [MessageController::class, 'pinned'])->name('message.pinned');
        Route::get('/saved', [MessageController::class, 'saved'])->name('message.saved');

        // Threads
        Route::get('/messages/{message}/thread', [ThreadController::class, 'index'])->name('thread.index');
        Route::post('/messages/{message}/thread', [ThreadController::class, 'store'])->name('thread.store');

        // Reactions
        Route::post('/messages/{message}/reactions', [ReactionController::class, 'store'])->name('reaction.store');
        Route::delete('/messages/{message}/reactions/{emoji}', [ReactionController::class, 'destroy'])->name('reaction.destroy');

        // User status
        Route::post('/status', [UserStatusController::class, 'update'])->name('status.update');
        Route::get('/status/{user}', [UserStatusController::class, 'show'])->name('status.show');

        // Search
        Route::get('/search', [SearchController::class, 'index'])->name('search.index');

        // Unread counts
        Route::get('/unread-counts', [RoomController::class, 'unreadCounts'])->name('unread.counts');
    });
