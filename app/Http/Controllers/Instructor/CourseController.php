<?php

// app/Http/Controllers/Instructor/CourseController.php

namespace App\Http\Controllers\Instructor;

use App\Models\Course;
use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Program;
use App\Models\Topic;
use App\Models\Assessment;
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
        $request->validate([
            'title' => 'required|string|max:255',
            'course_code' => 'required|string|max:50|unique:courses,course_code',
            'description' => 'nullable|string',
            'credits' => 'nullable|integer|min:1',
            'program_name' => 'required|string|max:255',
            'status' => 'required|in:draft,published,archived',
        ]);

        $programName = Str::upper($request->program_name);

        $program = Program::firstOrCreate(
            ['name' => $programName]
        );

        $programId = $program->id;

        Course::create([
            'title' => $request->title,
            'course_code' => $request->course_code,
            'description' => $request->description,
            'credits' => $request->credits,
            'program_id' => $programId,
            'instructor_id' => Auth::id(),
            'status' => $request->status,
        ]);

        return redirect()->route('instructor.dashboard')->with('success', 'Course created successfully!');
    }

    public function show(Course $course)
    {
        $course->load(['program', 'instructor']);
        $topics = $course->topics()->with(['materials', 'assessments'])->get();
        $independentAssessments = $course->assessments()
                                        ->whereNull('topic_id')
                                        ->get();

        return view('instructor.course.show', compact('course', 'topics', 'independentAssessments'));
    }

    /**
     * Search for courses by title or course_code.
     * Course code search is strict (case-sensitive, exact match).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
}