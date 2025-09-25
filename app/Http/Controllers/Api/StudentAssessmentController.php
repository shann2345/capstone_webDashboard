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
        if ($assessment->available_at && now()->lessThan($assessment->available_at)) {
            return response()->json(['message' => 'Assessment is not yet available.'], 403);
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
        }

        // Return the assessment data, including the file URL
        return response()->json([
            'assessment' => array_merge(
                $assessment->toArray(),
                ['assessment_file_url' => $assessmentFileUrl]
            )
        ]);
    }

    public function getQuestions(Assessment $assessment) 
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if (!method_exists($user, 'isEnrolledInCourse') || !$user->isEnrolledInCourse($assessment->course_id)) {
            return response()->json(['message' => 'Unauthorized: Not enrolled in the course for this assessment.'], 403);
        }

        // Only allow questions for quiz and exam types
        if (!in_array($assessment->type, ['quiz', 'exam'])) {
            return response()->json(['message' => 'Questions are only available for quiz and exam assessments.'], 403);
        }

        if ($assessment->available_at && now()->lessThan($assessment->available_at)) {
            return response()->json(['message' => 'Assessment is not yet available.'], 403);
        }

        // Load questions with their options
        $assessment->load('questions.options');

        return response()->json([
            'questions' => $assessment->questions
        ]);
    }

    public function download(Assessment $assessment)
    {
        if (!$assessment->assessment_file_path || !Storage::disk('public')->exists($assessment->assessment_file_path)) {
            return response()->json(['message' => 'Assessment file not found.'], 404);
        }

        $path = $assessment->assessment_file_path;
        $fileName = basename($path);

        return Storage::disk('public')->download($path, $fileName);
    }
}
