<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VerificationController;
use App\Http\Controllers\Api\StudentCourseController;
use App\Http\Controllers\Api\EnrollmentController; // Add this line

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require Sanctum authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user/verification-status', [VerificationController::class, 'getStatus']);
    Route::post('/email/verification-notification', [VerificationController::class, 'sendVerificationEmail']);
    Route::post('/email/verify-code', [VerificationController::class, 'verifyCode']);

    // Get authenticated user details
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/courses/search', [StudentCourseController::class, 'search']);

    // Enrollment Routes
    Route::post('/enroll', [EnrollmentController::class, 'enroll']);
    Route::post('/unenroll', [EnrollmentController::class, 'unenroll']); // Optional: for dropping courses
    Route::get('/my-courses', [EnrollmentController::class, 'myCourses']); // To list enrolled courses
    Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('api.courses.show');

    // ... other authenticated routes
});