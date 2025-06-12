<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InstructorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 


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


// --- Authenticated Routes (requires login) ---

// Handle logout (should be POST for security)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Generic dashboard route (for any logged-in user, redirects based on role)
Route::middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'instructor') {
            return redirect()->route('instructor.dashboard');
        }
        // Fallback for other roles (e.g., 'student' if you had one)
        return 'THIS IS ERROR, GO BACK'; // Shows a basic user dashboard
    })->name('dashboard');
});

// Admin specific routes (requires authentication AND admin role)
// The 'role' middleware will be created in the next step
Route::middleware(['auth:web', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    // Add other admin routes here (e.g., Route::get('/admin/users', [AdminController::class, 'manageUsers']);)
});

// Instructor specific routes (requires authentication AND instructor role)
Route::middleware(['auth:web', 'role:instructor'])->group(function () {
    Route::get('/instructor/dashboard', [InstructorController::class, 'index'])->name('instructor.dashboard');
    // Add other instructor routes here (e.g., Route::get('/instructor/courses', [InstructorController::class, 'manageCourses']);)
});