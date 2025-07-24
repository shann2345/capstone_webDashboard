<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VerificationController;
use App\Http\Controllers\Api\StudentCourseController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\StudentMaterialController;
use App\Http\Controllers\Api\StudentAssessmentController; // Ensure this is imported

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

    // Enrollment Routes
    Route::post('/enroll', [EnrollmentController::class, 'enroll']);
    Route::post('/unenroll', [EnrollmentController::class, 'unenroll']); // Optional: for dropping courses

    Route::get('/my-courses', [EnrollmentController::class, 'myCourses']); // To list enrolled courses
    Route::get('/courses/search', [StudentCourseController::class, 'search']);
    Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('api.courses.show');

    // Material Routes
    Route::get('/materials/{material}', [StudentMaterialController::class, 'show']);
    Route::get('/materials/{material}/download', [StudentMaterialController::class, 'download'])->name('api.materials.download');

    // Assessment Routes
    Route::get('/assessments/{assessment}', [StudentAssessmentController::class, 'show']);
    Route::get('/assessments/{assessment}/download', [StudentAssessmentController::class, 'download'])->name('api.assessments.download');
    Route::post('/assessments/{assessment}/submit', [StudentAssessmentController::class, 'uploadSubmission'])->name('api.assessments.submit');
});