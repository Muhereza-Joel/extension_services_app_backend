<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ExtensionServiceController;
use App\Http\Controllers\Api\PesapalController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    // Login route without Sanctum middleware
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/roles', [AuthController::class, 'getRoles']);
    Route::post('/register', [UserController::class, 'store']);

    // Grouped routes with Sanctum middleware
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::get('/extension/services', [ExtensionServiceController::class, 'index']);
        Route::get('/extension/services/{id}', [ExtensionServiceController::class, 'show']);
        Route::get('/extension/services/{id}/meetings', [ExtensionServiceController::class, 'serviceMeetings']);
        Route::get('/extension/services/meetings/all', [ExtensionServiceController::class, 'allMeetings']);
        Route::get('/extension/services/meetings/{id}', [ExtensionServiceController::class, 'showMeeting']);
        Route::post('/extension/services/tickets', [ExtensionServiceController::class, 'storeTicket']);
        Route::get('/my-bookings', [ExtensionServiceController::class, 'myBookings'])->middleware('auth:sanctum');
        Route::post('/profile', [ProfileController::class, 'store']);
        Route::get('/profile', [ProfileController::class, 'index']);
        Route::get('/profile/{id}', [ProfileController::class, 'show']);
        Route::put('/profile/{id}', [ProfileController::class, 'update']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::post('/pesapal/create-payment', [PesapalController::class, 'createPayment']);
        Route::get('/pesapal/callback', [PesapalController::class, 'handleIPN']);

        Route::post('/chats', [ChatController::class, 'createChat']);
        Route::get('/chats/{chatId}/messages', [ChatController::class, 'getMessages']);
        Route::post('/chats/{chatId}/messages', [ChatController::class, 'sendMessage']);
    });
});
