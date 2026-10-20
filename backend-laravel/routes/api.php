<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\DeviceTokenController;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:8,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/devices/fcm-token', [DeviceTokenController::class, 'store'])->middleware('throttle:30,1');
        Route::delete('/devices/fcm-token', [DeviceTokenController::class, 'destroy'])->middleware('throttle:30,1');
        Route::get('/me', [AuthController::class, 'me']);
        Route::get('/users/search', [UserController::class, 'search'])->middleware('throttle:60,1');
        Route::get('/conversations', [ConversationController::class, 'index']);
        Route::post('/conversations', [ConversationController::class, 'store'])->middleware('throttle:30,1');
        Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index']);
        Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store'])->middleware('throttle:60,1');
        Route::post('/conversations/{conversation}/read', [MessageController::class, 'markRead'])->middleware('throttle:60,1');
    });
});
