<?php

// app/Http/Controllers/CourseController.php

namespace App\Http\Controllers\Instructor;

use App\Models\Course;
use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Program; // Still need to import Department model!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // Import Str facade for string manipulation (optional but good practice)

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
        // Eager load materials and assessments for the course.
        // Also eager load the 'material' relationship on assessments themselves
        // so we can display the associated material title in the view.
        $course->load(['materials', 'assessments.material']);

        // Filter assessments into independent (material_id is null)
        // and linked (material_id is not null).
        $independentAssessments = $course->assessments->filter(function ($assessment) {
            return $assessment->material_id === null;
        });

        // The linked assessments are still attached to their respective Material models.
        // We don't necessarily need to pass them as a separate variable if they are
        // always displayed implicitly through the material relationship on the assessment.
        // However, if you wanted a separate list of ALL assessments, you'd just pass $course->assessments.

        return view('instructor.course.show', compact('course', 'independentAssessments'));
    }
}
