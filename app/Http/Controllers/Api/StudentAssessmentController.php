<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import Storage facade
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Illuminate\Support\Facades\Log; // Import Log facade for debugging

class StudentAssessmentController extends Controller
{
    public function show(Assessment $assessment) {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if (!method_exists($user, 'isEnrolledInCourse') || !$user->isEnrolledInCourse($assessment->course_id)) {
            return response()->json(['message' => 'Unauthorized: Not enrolled in the course for this assessment.'], 403);
        }

        // Eager load questions and their options (important for quizzes/exams)
        $assessment = Assessment::with('questions.options')->find($assessment->id);

        // Initialize assessmentFileUrl
        $assessmentFileUrl = null;

        // Check if there's an assessment_file_path and if the file exists on disk
        if ($assessment->assessment_file_path) {
            if (Storage::disk('public')->exists($assessment->assessment_file_path)) {
                $assessmentFileUrl = asset(Storage::url($assessment->assessment_file_path));
                Log::info("Generated assessment_file_url: {$assessmentFileUrl} for path: {$assessment->assessment_file_path}");
            } else {
                Log::warning("Assessment file path exists in DB but file not found on disk: {$assessment->assessment_file_path}");
            }
        } else {
            Log::info("No assessment_file_path set for assessment ID: {$assessment->id}");
        }

        // Return the assessment data, merging in the new assessment_file_url
        return response()->json([
            'assessment' => array_merge(
                $assessment->toArray(), // Convert the assessment model to an array
                ['assessment_file_url' => $assessmentFileUrl] // Add the generated URL
            )
        ]);
    }

    public function download(Assessment $assessment)
    {
        if (!$assessment->assessment_file_path || !Storage::exists($assessment->assessment_file_path)) {
            return response()->json(['message' => 'Assessment file not found.'], 404);
        }

        // You might add authorization here (e.g., only enrolled students can download)
        // if (!Auth::user()->isEnrolledInCourse($assessment->course_id)) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        $path = $assessment->assessment_file_path;
        $fileName = basename($path); // Get just the file name

        return Storage::download($path, $fileName);
    }
}
