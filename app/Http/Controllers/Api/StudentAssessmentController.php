<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import Storage facade
use Illuminate\Support\Facades\Auth; // Import Auth facade

class StudentAssessmentController extends Controller
{
    /**
     * Display the specified assessment.
     * Eager loads questions and their options if it's a quiz/exam.
     *
     * @param  \App\Models\Assessment  $assessment
     * @return \Illuminate\Http\Response
     */
    public function show(Assessment $assessment) {
        // Eager load questions and their options
        $assessment = Assessment::with('questions.options')->find($assessment->id);

        return response()->json([
            'assessment' => $assessment
        ]);
    }

    /**
     * Download the specified assessment file.
     *
     * @param  \App\Models\Assessment  $assessment
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\StreamedResponse
     */
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

    /**
     * Handle student submission for an assessment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Assessment  $assessment
     * @return \Illuminate\Http\Response
     */
    // public function uploadSubmission(Request $request, Assessment $assessment)
    // {
    //     // Validate the request
    //     $request->validate([
    //         'submission_file' => 'required|file|max:10240', // Max 10MB file
    //         // Add other validations as needed, e.g., allowed mimetypes
    //     ]);

    //     // You might add authorization here (e.g., only enrolled students can submit)
    //     // if (!Auth::user()->isEnrolledInCourse($assessment->course_id)) {
    //     //     return response()->json(['message' => 'Unauthorized'], 403);
    //     // }

    //     // Determine the storage path for submissions
    //     // Example: submissions/course_{course_id}/assessment_{assessment_id}/user_{user_id}/filename.ext
    //     $filePath = $request->file('submission_file')->store(
    //         'submissions/courses/' . $assessment->course_id . '/assessments/' . $assessment->id . '/' . Auth::id(),
    //         'public' // Use the 'public' disk
    //     );

    //     // Here you would typically save this file path to a 'submission' record
    //     // associated with the student and the assessment.
    //     // For example, if you have a Submission model:
    //     // Submission::create([
    //     //     'user_id' => Auth::id(),
    //     //     'assessment_id' => $assessment->id,
    //     //     'file_path' => $filePath,
    //     //     'submitted_at' => now(),
    //     // ]);

    //     return response()->json([
    //         'message' => 'Submission uploaded successfully!',
    //         'file_path' => Storage::url($filePath) // Return public URL
    //     ], 200);
    // }
}