<?php

// app/Http/Controllers/CourseController.php

namespace App\Http\Controllers\Instructor;

use App\Models\Course;
use App\Http\Controllers\Controller;
use App\Models\Material; // Keep if still used elsewhere in the controller
use App\Models\Program;
use App\Models\Topic; // Make sure this is imported
use App\Models\Assessment; // Make sure this is imported
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function create()
    {
        return view('instructor.createCourse');
    }

    public function store(Request $request)
    {
        // 1. Validate the incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'course_code' => 'required|string|max:50|unique:courses,course_code',
            'description' => 'nullable|string',
            'credits' => 'nullable|integer|min:1',
            'program_name' => 'required|string|max:255', // Validate the new department name input
            'status' => 'required|in:draft,published,archived',
        ]);

        // 2. Process Department Name: Find existing or create new
        // Convert input department name to uppercase for consistent storage/lookup
        $programName = Str::upper($request->program_name);

        // Find the department by name, or create it if it doesn't exist
        $program = Program::firstOrCreate(
            ['name' => $programName]
        );

        // Get the department's ID
        $programId = $program->id;

        // 3. Create the new Course record
        Course::create([
            'title' => $request->title,
            'course_code' => $request->course_code,
            'description' => $request->description,
            'credits' => $request->credits,
            'program_id' => $programId, // Use the ID from the found/created department
            'instructor_id' => Auth::id(),
            'status' => $request->status,
        ]);

        // 4. Redirect back with a success message
        return redirect()->route('instructor.dashboard')->with('success', 'Course created successfully!');
    }

    public function show(Course $course)
    {
        // Eager load program and instructor for the course
        $course->load(['program', 'instructor']);

        // --- CORRECTED LINE HERE ---
        // Fetch only topics associated with THIS specific course,
        // and eager load their materials and assessments.
        $topics = $course->topics()->with(['materials', 'assessments'])->get();

        // Fetch independent assessments for this course (those not linked to any topic)
        $independentAssessments = $course->assessments()
                                        ->whereNull('topic_id')
                                        ->get();

        return view('instructor.course.show', compact('course', 'topics', 'independentAssessments'));
    }
}