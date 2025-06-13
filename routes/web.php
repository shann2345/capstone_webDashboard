<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\CourseController; 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


Route::get('/', function () {
    return view('welcome');
});

// Display creating course dashboard
Route::view('/instructor/courseCreation', 'instructor.createCourse')->name('courseCreation');
Route::view('/instructor/dashboard', 'instructor.dashboard')->name('dashboard');

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

// Instructor specific routes (requires authentication AND instructor role AND verification)
Route::middleware(['auth:web', 'role:instructor', 'verified'])->group(function () { // <-- ADD 'verified' middleware here
    Route::get('/instructor/dashboard', [InstructorController::class, 'index'])->name('instructor.dashboard');
});
Route::middleware(['auth:web', 'role:instructor', 'verified'])->group(function () {
    // Show the course creation form
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');

    // Handle the submission of the course creation form
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');

    // You might add routes for 'edit', 'update', 'delete', 'show' courses later
});