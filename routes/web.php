<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Instructor\InstructorController;
use App\Http\Controllers\Instructor\CourseController;
use App\Http\Controllers\Instructor\MaterialController;
use App\Http\Controllers\Instructor\AssessmentController;
use App\Http\Controllers\Instructor\TopicController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\VerificationController; // NEW: Import the new controller

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::get('/instructor/register', [RegisterController::class, 'showInstructorRegistrationForm'])->name('instructor.register.get');

Route::post('/instructor/register', [RegisterController::class, 'registerInstructor'])->name('instructor.register.post');

Route::get('/auth/google/redirect', [SocialLoginController::class, 'redirectToGoogle'])->name('socialite.google.redirect');
Route::get('/auth/google/callback', [SocialLoginController::class, 'handleGoogleCallback']);

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// NEW: Route to handle code verification
Route::post('/email/verify', [VerificationController::class, 'verifyCode'])->middleware(['auth', 'throttle:6,1'])->name('verification.verify.code');

Route::post('/email/verification-notification', function (Request $request) {
    // Re-generate a new code and send it
    $verificationCode = random_int(100000, 999999);
    $expiresAt = Carbon\Carbon::now()->addMinutes(60); // Set expiry
    $request->user()->update([
        'email_verification_code' => $verificationCode,
        'email_verification_code_expires_at' => $expiresAt,
    ]);
    $request->user()->notify(new App\Notifications\VerifyEmailWithCode($verificationCode));
    return back()->with('status', 'A new verification code has been sent to your email address.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// --- Authenticated Routes (requires login) ---

// Handle logout (should be POST for security)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Generic dashboard route (for any logged-in user, redirects based on role)
Route::middleware(['auth:web', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'instructor') {
            return redirect()->route('instructor.dashboard');
        }
        return "This is errorrr, Go back";
    })->name('dashboard');
});

// Admin specific routes (requires authentication AND admin role AND verification)
Route::middleware(['auth:web', 'role:admin', 'verified'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});



// In routes/web.php
Route::middleware(['auth:web', 'role:instructor', 'verified'])->group(function () {
    Route::get('/instructor/dashboard', [InstructorController::class, 'index'])->name('instructor.dashboard');
    Route::get('/instructor/myCourse', [InstructorController::class, 'show'])->name('instructor.myCourse');
    Route::get('/instructor/myCourse/{course}', [InstructorController::class, 'showCourseDetails'])->name('instructor.courseDetails');
    Route::get('/instructor/myCourse/{course}/enrollee', [InstructorController::class, 'showCourseEnrollee'])->name('instructor.courseEnrollee');
    Route::post('/instructor/courses/{course}/add-students', [InstructorController::class, 'addStudents'])->name('instructor.addStudents');
    Route::delete('/instructor/courses/{course}/students/{student}', [InstructorController::class, 'removeStudent'])->name('instructor.removeStudent');
    
    Route::get('/instructor/studentManagement', [InstructorController::class, 'showStudentManagement'])->name('instructor.studenManagement');
    Route::get('/instructor/studentProgress', [InstructorController::class, 'showStudentProgress'])->name('instructor.studentProgress');
    Route::get('/instructor/studentDetails/{student?}', [InstructorController::class, 'showStudentDetails'])->name('instructor.studentDetails');
    Route::get('/instructor/submission/{submission}/details', [InstructorController::class, 'getSubmissionDetails'])->name('instructor.submissionDetails');
    Route::post('/instructor/submission/{submission}/grade', [InstructorController::class, 'updateGrade'])->name('instructor.updateGrade');
    Route::get('/instructor/submission/{submission}/download', [InstructorController::class, 'downloadSubmission'])->name('instructor.downloadSubmission');
    Route::post('/instructor/submitted-question/{submittedQuestion}/grade', [InstructorController::class, 'updateQuestionGrade'])->name('instructor.updateQuestionGrade');
    Route::post('/instructor/submitted-question/{submittedQuestion}/points', [InstructorController::class, 'updateQuestionPoints'])->name('instructor.updateQuestionPoints');
    Route::post('/instructor/assign-section/{student}', [InstructorController::class, 'assignSection'])->name('instructor.assignSection');
    Route::post('/instructor/course/{course}/section/create', [InstructorController::class, 'createSection'])->name('instructor.createSection');
    Route::post('/instructor/course/{course}/bulk-assign-section', [InstructorController::class, 'bulkAssignSection'])->name('instructor.bulkAssignSection');
    Route::post('/instructor/bulk-remove-students', [InstructorController::class, 'bulkRemoveStudents'])->name('instructor.bulkRemoveStudents');
    Route::delete('/instructor/section/{section}', [InstructorController::class, 'deleteSection'])->name('instructor.deleteSection');

    Route::get('/instructor/profile', [InstructorController::class, 'showProfile'])->name('instructor.showProfile');
    Route::put('/instructor/profile', [InstructorController::class, 'updateProfile'])->name('instructor.updateProfile');
    Route::post('/instructor/profile/upload-image', [InstructorController::class, 'uploadProfileImage'])->name('instructor.uploadProfileImage');
    Route::delete('/instructor/profile/image', [InstructorController::class, 'deleteProfileImage'])->name('instructor.deleteProfileImage');
    Route::post('/instructor/profile/change-password', [InstructorController::class, 'changePassword'])->name('instructor.changePassword');
    Route::get('/instructor/notifications', [InstructorController::class, 'getNotifications'])->name('instructor.notifications');
    Route::post('/instructor/notifications/mark-read', [InstructorController::class, 'markNotificationAsRead'])->name('instructor.markNotificationAsRead');
    Route::post('/instructor/notifications/mark-all-read', [InstructorController::class, 'markAllNotificationsAsRead'])->name('instructor.markAllNotificationsAsRead');
    
    Route::get('/course', [CourseController::class, 'create'])->name('course.create');
    Route::post('/course/createCourse', [CourseController::class, 'store'])->name('course.store');
    Route::get('/course/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::put('/course/{course}', [CourseController::class, 'update'])->name('course.update'); 

    Route::post('/topics', [TopicController::class, 'store'])->name('topics.store');
    Route::patch('/topics/{topic}', [TopicController::class, 'update'])->name('topics.update');

    Route::get('/course/{course}/materials', [MaterialController::class, 'create'])->name('materials.create');
    Route::post('/course/{course}/materials', [MaterialController::class, 'store'])->name('materials.store');
    Route::get('/materials/{material}', [MaterialController::class, 'show'])->name('materials.show');
    Route::get('/materials/{material}/edit', [MaterialController::class, 'edit'])->name('materials.edit');
    Route::put('/materials/{material}', [MaterialController::class, 'update'])->name('materials.update');
    Route::get('/materials/{material}/download', [MaterialController::class, 'download'])->name('materials.download');

    Route::get('/courses/{course}/assessments/withQ/{type}', [AssessmentController::class, 'createQuiz'])->name('assessments.create.quiz');
    Route::post('/courses/{course}/assessments/store/quiz', [AssessmentController::class, 'storeQuiz'])->name('assessments.store.quiz');
    Route::get('/courses/{course}/assessments/{assessment}/showQ', [AssessmentController::class, 'showQuiz'])->name('assessments.show.quiz');
    Route::get('/courses/{course}/assessments/{assessment}/edit/quizType', [AssessmentController::class, 'editQuiz'])->name('assessments.edit.quiz');
    Route::put('/courses/{course}/assessments/{assessment}/update/quizType', [AssessmentController::class, 'updateQuiz'])->name('assessments.update.quiz');
    Route::delete('/{assessment}', [AssessmentController::class, 'destroy'])->name('assessments.destroy');

    Route::get('/courses/{course}/assessments/withOutQ/{typeAct}', [AssessmentController::class, 'createAssignment'])->name('assessments.create.assignment');
    Route::post('/courses/{course}/assessments/store/assignment', [AssessmentController::class, 'storeAssignment'])->name('assessments.store.assignment');
    Route::get('/courses/{course}/assessments/{assessment}/showWoutQ', [AssessmentController::class, 'showAssignment'])->name('assessments.show.assignment');
    Route::get('/courses/{course}/assessments/{assessment}/edit/assignmentType', [AssessmentController::class, 'editAssignment'])->name('assessments.edit.assignment');
    Route::put('/courses/{course}/assessments/{assessment}/update/assignmentType', [AssessmentController::class, 'updateAssignment'])->name('assessments.update.assignment');
});