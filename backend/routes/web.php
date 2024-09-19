<?php

use App\Http\Controllers\TelegramBotController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramController;


Route::get('/', function () {
    return view('welcome');
});



Route::post('/telegram/webhook', [TelegramBotController::class, 'webhook']);
Route::post('/api/telegram/click-coin', [TelegramBotController::class, 'clickCoinApi']);
