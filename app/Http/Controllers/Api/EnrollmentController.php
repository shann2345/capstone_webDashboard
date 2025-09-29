<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User; // Assuming students are Users
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function enroll(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'course_code' => 'required|string',
        ]);

        $user = Auth::user();

                // Find the course by ID
        $course = Course::find($request->course_id);

        // Check if the course exists, is published, and if the provided course_code matches exactly (case-sensitive)
        if (!$course || $course->status !== 'published' || $course->course_code !== $request->course_code) {
            return response()->json(['message' => 'Invalid course ID, course not available, or incorrect course code provided.'], 404);
        }

        // Check if already enrolled
        if ($user->courses()->where('course_id', $course->id)->exists()) {
            return response()->json(['message' => 'You are already enrolled in this course.'], 409); // 409 Conflict
        }

        try {
            // Attach the course to the user with pivot data
            $user->courses()->attach($course->id, [
                'enrollment_date' => now(), // Set enrollment date
                'status' => 'enrolled',     // Default status
            ]);

            // If the user (student) doesn't have a program_id, set it based on the course.
            if (is_null($user->program_id) && !is_null($course->program_id)) {
                $user->program_id = $course->program_id;
                $user->save();
            }

            return response()->json(['message' => 'Successfully enrolled in course.', 'course' => $course], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Enrollment failed.', 'error' => $e->getMessage()], 500);
        }
    }

    public function unenroll(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = Auth::user();
        $course = Course::findOrFail($request->course_id);

        if (!$user->courses()->where('course_id', $course->id)->exists()) {
            return response()->json(['message' => 'You are not enrolled in this course.'], 404);
        }

        try {
            $user->courses()->detach($course->id);
            return response()->json(['message' => 'Successfully unenrolled from course.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unenrollment failed.', 'error' => $e->getMessage()], 500);
        }
    }

    public function myCourses(Request $request)
    {
        // Get the authenticated user (assuming a student role for this endpoint)
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Fetch courses the user is enrolled in, eager loading program and instructor
        $enrolledCourses = $user->courses()
                                ->with(['program', 'instructor']) // <-- *** THIS IS CRUCIAL ***
                                ->get();

        return response()->json(['courses' => $enrolledCourses]);
    }
}