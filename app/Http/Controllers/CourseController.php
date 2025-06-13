<?php

// app/Http/Controllers/CourseController.php

namespace App\Http\Controllers;

use App\Models\Course;    // Don't forget to import the Course model
use App\Models\Department; // Don't forget to import the Department model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // For getting the logged-in user's ID

class CourseController extends Controller
{
    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        // Fetch all departments from the database to populate the dropdown
        $departments = Department::all();

        // Pass the departments data to the view
        return view('instructor.createCourse', compact('departments'));
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'course_code' => 'required|string|max:50|unique:courses,course_code', // Unique in courses table
            'description' => 'nullable|string',
            'credits' => 'nullable|integer|min:1',
            'department_id' => 'required|exists:departments,id', // Must exist in departments table
            'status' => 'required|in:draft,published,archived', // Must be one of these values
        ]);

        // 2. Create the new Course record
        Course::create([
            'title' => $request->title,
            'course_code' => $request->course_code,
            'description' => $request->description,
            'credits' => $request->credits,
            'department_id' => $request->department_id,
            'instructor_id' => Auth::id(), // Automatically assign the logged-in user as instructor
            'status' => $request->status,
        ]);

        // 3. Redirect back with a success message
        return redirect()->route('instructor.dashboard')->with('success', 'Course created successfully!');
        // You might want to redirect to a 'my courses' list later
    }
}
