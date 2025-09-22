<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VerificationController;
use App\Http\Controllers\Api\StudentCourseController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\StudentMaterialController;
use App\Http\Controllers\Api\StudentAssessmentController; 
use App\Http\Controllers\Api\StudentSubmittedAssessmentController; 
use App\Http\Controllers\Api\TimeController;
use App\Http\Controllers\Api\ProfileController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/google', [AuthController::class, 'handleGoogleAuth']);

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
    Route::post('/unenroll', [EnrollmentController::class, 'unenroll']);

    Route::get('/time', [TimeController::class, 'index']);

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile/image', [ProfileController::class, 'deleteProfileImage']);

    // course routes
    Route::get('/my-courses', [EnrollmentController::class, 'myCourses']);
    Route::get('/courses/search', [StudentCourseController::class, 'search']);
    Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('api.courses.show');

    // Material Routes
    Route::get('/materials/{material}', [StudentMaterialController::class, 'show']);
    Route::get('/materials/{material}/view', [StudentMaterialController::class, 'view']);
    Route::get('/materials/{material}/download', [StudentMaterialController::class, 'download']);
    Route::get('/materials/{material}/view-link', [StudentMaterialController::class, 'generateViewLink']); // NEW


    // Assessment Routes
    Route::get('/assessments/{assessment}', [StudentAssessmentController::class, 'show']);
    Route::get('/assessments/{assessment}/questions', [StudentAssessmentController::class, 'getQuestions']);

    
    Route::get('/assessments/{assessment}/attempt-status', [StudentSubmittedAssessmentController::class, 'getAttemptStatus']);
    Route::post('/assessments/{assessment}/start-quiz-attempt', [StudentSubmittedAssessmentController::class, 'startQuizAttempt']);
    Route::post('/assessments/{assessment}/submit-assignment', [StudentSubmittedAssessmentController::class, 'submitAssignment']);
    Route::get('/assessments/{assessment}/latest-assignment-submission', [StudentSubmittedAssessmentController::class, 'getLatestSubmittedAssignment']);
    Route::get('/submitted-assessments/{submittedAssessment}', [StudentSubmittedAssessmentController::class, 'showSubmittedAssessment']);
    Route::patch('/submitted-questions/{submittedQuestion}/answer', [StudentSubmittedAssessmentController::class, 'updateSubmittedQuestionAnswer']);
    Route::post('/submitted-assessments/{submittedAssessment}/finalize-quiz', [StudentSubmittedAssessmentController::class, 'finalizeQuizAttempt']);

    // Route for offline quiz
    Route::post('/assessments/{assessment}/sync-offline-quiz', [StudentSubmittedAssessmentController::class, 'syncOfflineQuiz']);
    
    Route::get('/student/notifications', [ProfileController::class, 'getNotifications']);
    Route::post('/student/mark-notification-as-read', [ProfileController::class, 'markNotificationAsRead']);
    Route::post('/student/mark-all-notifications-as-read', [ProfileController::class, 'markAllNotificationsAsRead']);
});

Route::get('/materials/{material}/view-signed', [StudentMaterialController::class, 'viewSigned'])
    ->name('materials.view.signed');