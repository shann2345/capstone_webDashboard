<?php

// app/Http/Controllers/RegisterController.php

namespace App\Http\Controllers;

use App\Models\User; // Import your User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Import Hash facade
use Illuminate\Support\Facades\Auth; // Import Auth facade

class RegisterController extends Controller
{
    // Method to show the instructor signup form
    public function showInstructorRegistrationForm()
    {
        return view('auth.instructor_signup'); // Returns the blade file for instructor signup
    }

    // Method to handle the instructor registration form submission
    public function registerInstructor(Request $request)
    {
        // 1. Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Email must be unique in 'users' table
            'password' => 'required|string|min:8|confirmed', // 'confirmed' means it requires a 'password_confirmation' field
        ]);

        // 2. Create a new user in the database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // ALWAYS hash passwords for security!
            'role' => 'instructor', // Assign the 'instructor' role
        ]);

        // 3. Log the user in immediately after registration (optional, but common)
        Auth::login($user);

        // 4. Redirect the user to their respective dashboard
        return redirect()->route('instructor.dashboard')->with('success', 'Registration successful! Welcome, Instructor!');
    }
}