<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; 
use App\Http\Controllers\Api\VerificationController; 

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/verification-status', [VerificationController::class, 'getStatus']);
    Route::post('/email/verification-notification', [VerificationController::class, 'sendVerificationEmail']);
});

