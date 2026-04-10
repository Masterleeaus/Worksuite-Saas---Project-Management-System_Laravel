
<?php
use Illuminate\Support\Facades\Route;
use Modules\Aitools\Http\Controllers\ChatController;

Route::middleware(['web','auth'])->prefix('account/aitools')->group(function(){
    Route::post('/chat',[ChatController::class,'chat']);
    Route::get('/chat/{id}',[ChatController::class,'history']);
});
