<?php

use App\Http\Controllers\TelegramBotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth
:sanctum');

Route::get('/set-webhook', function () {
    $response = Telegram::setWebhook(['url' => 'https://848c-213-230-78-131.ngrok-free.app/api/set-webhook']);
    return $response;
});


// Route::get('/telegram/set-webhook', [TelegramBotController::class, 'setWebhook'])->name('telegram.setWebhook');

// Handle webhook requests from Telegram
Route::post('/telegram/webhook', [TelegramBotController::class, 'handleWebhook'])->name('telegram.webhook');

// API route to handle clicking coin
Route::post('/telegram/click-coin', [TelegramBotController::class, 'clickCoinApi'])->name('telegram.clickCoin');

// API route to get the current points
Route::get('/telegram/get-points', [TelegramBotController::class, 'getPoints'])->name('telegram.getPoints');