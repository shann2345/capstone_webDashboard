<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Question;
use App\Models\SubmittedAssessment;
use App\Models\SubmittedQuestion;
use App\Models\SubmittedQuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class StudentSubmittedAssessmentController extends Controller
{
    /**
     * Start a quiz/exam attempt for the authenticated student.
     * This method creates the submitted_assessment and snapshots questions/options.
     *
     * @param  \App\Models\Assessment  $assessment The original assessment
     * @return \Illuminate\Http\Response
     */
    public function startQuizAttempt(Assessment $assessment)
    {
        // 1. Authorization & Validation
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Normalize the assessment type from the database for robust comparison
        $normalizedAssessmentType = strtolower(trim($assessment->type));

        // Check if assessment is a quiz/exam type
        if (!in_array($normalizedAssessmentType, ['quiz', 'exam'])) {
            return response()->json(['message' => 'This assessment is not a quiz or exam type.'], 400);
        }

        // Check enrollment (assuming you have this method on User model)
        if (!method_exists($user, 'isEnrolledInCourse') || !$user->isEnrolledInCourse($assessment->course_id)) {
            return response()->json(['message' => 'Unauthorized: Not enrolled in the course for this assessment.'], 403);
        }

        // Check if assessment is available
        if (!$this->isAssessmentAvailable($assessment)) {
            return response()->json([
                'message' => 'Assessment is not yet available.',
                'available_at' => $assessment->available_at
            ], 400);
        }

        // Check if there's an existing 'in_progress' attempt for this assessment by this student
        $existingAttempt = SubmittedAssessment::where('student_id', $user->id)
                                            ->where('assessment_id', $assessment->id)
                                            ->where('status', 'in_progress')
                                            ->first();

        if ($existingAttempt) {
            // If an in-progress attempt exists, return it to allow resumption
            return response()->json([
                'message' => 'Resuming existing attempt.',
                'submitted_assessment' => $existingAttempt->load('submittedQuestions.submittedOptions')
            ], 200);
        }

        // Check max attempts limit
        if ($assessment->max_attempts) {
            $attemptCount = SubmittedAssessment::where('student_id', $user->id)
                                             ->where('assessment_id', $assessment->id)
                                             ->whereIn('status', ['submitted', 'completed'])
                                             ->count();
            
            if ($attemptCount >= $assessment->max_attempts) {
                return response()->json([
                    'message' => 'Maximum attempt limit reached.',
                    'max_attempts' => $attemptCount, // Send current count for clarity
                    'limit' => $assessment->max_attempts // Send the limit for clarity
                ], 400);
            }
        }

        // 2. Create Submitted Assessment and Snapshot Questions/Options
        try {
            DB::beginTransaction();

            $submittedAssessment = SubmittedAssessment::create([
                'student_id' => $user->id,
                'assessment_id' => $assessment->id,
                'status' => 'in_progress',
                'started_at' => now(),
            ]);

            // Get all questions related to the original assessment with their options
            $questions = Question::where('assessment_id', $assessment->id)
                                 ->with('questionOptions')
                                 ->get();

            if ($questions->isEmpty()) {
                DB::rollBack();
                return response()->json(['message' => 'No questions found for this quiz/exam.'], 404);
            }

            foreach ($questions as $question) {
                // Check if the question type is 'multiple_choice' or 'true_false' for options
                if (!in_array($question->question_type, ['multiple_choice', 'true_false']) && $question->questionOptions->isNotEmpty()) {
                    Log::warning("Question ID {$question->id} has type '{$question->question_type}' but also has options. Options will be ignored for non-MC/TF types.");
                }

                $submittedQuestion = SubmittedQuestion::create([
                    'submitted_assessment_id' => $submittedAssessment->id,
                    'question_id' => $question->id,
                    'question_text' => $question->question_text,
                    'question_type' => $question->question_type,
                    'max_points' => $question->points,
                    'submitted_answer' => null,
                    'is_correct' => null,
                    'score_earned' => null,
                ]);

                // Only create submitted options if the original question is multiple choice or true/false
                if (in_array($question->question_type, ['multiple_choice', 'true_false'])) {
                    foreach ($question->questionOptions as $option) {
                        SubmittedQuestionOption::create([
                            'submitted_question_id' => $submittedQuestion->id,
                            'question_option_id' => $option->id,
                            'option_text' => $option->option_text,
                            'is_correct_option' => (bool) $option->is_correct, // FIX: Explicitly cast to boolean
                            'is_selected' => false, // Student hasn't selected it yet
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Quiz attempt started successfully.',
                'submitted_assessment' => $submittedAssessment->load('submittedQuestions.submittedOptions')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error starting quiz attempt: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Failed to start quiz attempt. Please try again.'], 500);
        }
    }

    /**
     * Submit an assignment file for the authenticated student.
     * This method creates or updates a submitted_assessment for assignments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Assessment  $assessment The original assessment
     * @return \Illuminate\Http\Response
     */
    public function submitAssignment(Request $request, Assessment $assessment)
    {
        // 1. Authorization & Validation
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

       $normalizedAssessmentType = strtolower(trim($assessment->type));
        if (!in_array($normalizedAssessmentType, ['assignment', 'activity', 'project'])) {
            return response()->json(['message' => 'This assessment is not an assignment, activity, or project type.'], 400);
        }

        // Check enrollment
        if (!method_exists($user, 'isEnrolledInCourse') || !$user->isEnrolledInCourse($assessment->course_id)) {
            return response()->json(['message' => 'Unauthorized: Not enrolled in the course for this assignment.'], 403);
        }

        // Check if assessment is available
        if (!$this->isAssessmentAvailable($assessment)) {
            return response()->json([
                'message' => 'Assessment is not yet available.',
                'available_at' => $assessment->available_at
            ], 400);
        }

        try {
            $request->validate([
                'assignment_file' => 'required|file|max:10240|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar,jpg,jpeg,png',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        // 2. Handle File Upload
        $filePath = null;
        if ($request->hasFile('assignment_file')) {
            $file = $request->file('assignment_file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('submitted_assignments/' . $user->id . '/' . $assessment->id, $fileName, 'public');
        }

        // 3. Create or Update Submitted Assessment Record
        try {
            DB::beginTransaction();

            $existingSubmission = SubmittedAssessment::where('student_id', $user->id)
                                                   ->where('assessment_id', $assessment->id)
                                                   ->first();

            $submittedAssessment = SubmittedAssessment::updateOrCreate(
                [
                    'student_id' => $user->id,
                    'assessment_id' => $assessment->id,
                ],
                [
                    'status' => 'submitted',
                    'submitted_at' => now(),
                    'submitted_file_path' => $filePath,
                    'score' => null,
                    'started_at' => $existingSubmission->started_at ?? now(),
                    'completed_at' => now(),
                ]
            );

            // Delete old file if exists and new file is uploaded
            if ($existingSubmission && $existingSubmission->submitted_file_path && $filePath) {
                Storage::disk('public')->delete($existingSubmission->submitted_file_path);
            }

            DB::commit();

            return response()->json([
                'message' => 'Assignment submitted successfully!',
                'submitted_assessment' => $submittedAssessment
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            // Delete the uploaded file if database transaction fails
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            Log::error('Error submitting assignment: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Failed to submit assignment. Please try again.'], 500);
        }
    }

    /**
     * Retrieve a specific submitted assessment (for viewing results/resume quiz).
     *
     * @param \App\Models\SubmittedAssessment $submittedAssessment
     * @return \Illuminate\Http\Response
     */
    public function showSubmittedAssessment(SubmittedAssessment $submittedAssessment)
    {
        $user = Auth::user();
        if (!$user || $submittedAssessment->student_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to view this submission.'], 403);
        }

        // Eager load related data for the frontend
        $submittedAssessment->load([
            'assessment',
            'submittedQuestions.submittedOptions'
        ]);

        return response()->json(['submitted_assessment' => $submittedAssessment]);
    }

    /**
     * Check if assessment is currently available based on dates
     *
     * @param \App\Models\Assessment $assessment
     * @return bool
     */
    private function isAssessmentAvailable(Assessment $assessment)
    {
        $now = now();
        
        // Check if assessment has started
        if ($assessment->available_at && $now->lt($assessment->available_at)) {
            return false;
        }
        
        // Check if assessment has ended
        if ($assessment->unavailable_at && $now->gt($assessment->unavailable_at)) {
            return false;
        }
        
        return true;
    }
}
