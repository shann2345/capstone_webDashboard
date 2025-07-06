<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Instructor\InstructorController;
use App\Http\Controllers\Instructor\CourseController;
use App\Http\Controllers\Instructor\MaterialController;
use App\Http\Controllers\Instructor\AssessmentController;
use App\Http\Controllers\Instructor\TopicController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


Route::get('/', function () {
    return view('welcome');
});

// Display the login form
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Handle the login form submission
Route::post('/login', [LoginController::class, 'login']);

// Display the instructor registration form
Route::get('/instructor/register', [RegisterController::class, 'showInstructorRegistrationForm'])->name('instructor.register.get');

// Handle the instructor registration form submission
Route::post('/instructor/register', [RegisterController::class, 'registerInstructor'])->name('instructor.register.post');

// Email Verification Routes (Place these before authenticated routes that require verification)
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard')->with('status', 'Your email has been verified!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// --- Authenticated Routes (requires login) ---

// Handle logout (should be POST for security)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Generic dashboard route (for any logged-in user, redirects based on role)
Route::middleware(['auth:web', 'verified'])->group(function () { // <-- ADD 'verified' middleware here
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
Route::middleware(['auth:web', 'role:admin', 'verified'])->group(function () { // <-- ADD 'verified' middleware here
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

// In routes/web.php
Route::middleware(['auth:web', 'role:instructor', 'verified'])->group(function () {
    Route::get('/instructor/dashboard', [InstructorController::class, 'index'])->name('instructor.dashboard');

    Route::get('/course', [CourseController::class, 'create'])->name('course.create');
    Route::post('/course/createCourse', [CourseController::class, 'store'])->name('course.store');
    Route::get('/course/{course}', [CourseController::class, 'show'])->name('courses.show');

    Route::post('/topics', [TopicController::class, 'store'])->name('topics.store');
    Route::patch('/topics/{topic}', [TopicController::class, 'update'])->name('topics.update');

    Route::get('/course/{course}/materials', [MaterialController::class, 'create'])->name('materials.create');
    Route::post('/course/{course}/materials', [MaterialController::class, 'store'])->name('materials.store');
    Route::get('/materials/{material}/download', [MaterialController::class, 'download'])->name('materials.download');

    Route::get('/courses/{course}/assessments/withQ/{type}', [AssessmentController::class, 'createQuiz'])->name('assessments.create.quiz');
    Route::post('/courses/{course}/assessments/store/quiz', [AssessmentController::class, 'storeQuiz'])->name('assessments.store.quiz');

    Route::get('/courses/{course}/assessments/withOutQ/{typeAct}', [AssessmentController::class, 'createAssignment'])->name('assessments.create.assignment');
    Route::post('/courses/{course}/assessments/store/assignment', [AssessmentController::class, 'storeAssignment'])->name('assessments.store.assignment');

});