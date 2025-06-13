<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade (optional, for displaying user info)

class InstructorController extends Controller
{
    public function index()
    {
        // Get the currently authenticated user (which should be an instructor here)
        $instructor = Auth::user();

        // Load the courses taught by this instructor using the 'taughtCourses' relationship
        // We also eager load the 'department' relationship for each course
        // to display the department name if needed.
        $courses = $instructor->taughtCourses()->with('department')->get();

        // Pass the instructor object and their courses to the dashboard view
        return view('instructor.dashboard', compact('instructor', 'courses'));
    }

}
