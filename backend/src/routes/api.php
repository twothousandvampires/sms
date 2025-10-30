<?php

use App\Http\Controllers\SmsController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('api')->group(function () {
    // SMS верификация
    Route::post('/send-code', [SmsController::class, 'sendCode']);
    Route::post('/verify-code', [SmsController::class, 'verifyCode']);
});