<?php

// app/Http/Controllers/Instructor/CourseController.php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Program;
use App\Models\Topic;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StudentCourseController extends Controller
{

    public function show(Course $course)
    {
        // Eager load relationships needed for the course details page
        $course->load([
            'program',
            'instructor',
            'topics' => function ($query) {
                $query->with(['materials', 'assessments']); // Eager load materials and assessments for each topic
            },
            'assessments' // Also load assessments not tied to topics (independent ones)
        ]);

        return response()->json([
            'course' => $course
        ]);
    }
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json(['courses' => []]);
        }

        $courses = Course::where('title', 'LIKE', '%' . $query . '%')
                         // For strict, case-sensitive course_code match (MySQL specific)
                         ->orWhereRaw('BINARY course_code = ?', [$query])
                         ->with(['program', 'instructor'])
                         ->limit(10)
                         ->get();

        return response()->json(['courses' => $courses]);
    }

}