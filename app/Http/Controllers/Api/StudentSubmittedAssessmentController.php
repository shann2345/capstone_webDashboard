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
     * Start a new quiz attempt for an assessment.
     *
     * @param Assessment $assessment The assessment to attempt.
     * @return \Illuminate\Http\JsonResponse
     */
    public function startQuizAttempt(Assessment $assessment)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $normalizedAssessmentType = strtolower(trim($assessment->type));
        if (!in_array($normalizedAssessmentType, ['quiz', 'exam'])) {
            return response()->json(['message' => 'This assessment is not a quiz or exam type.'], 400);
        }

        if (!method_exists($user, 'isEnrolledInCourse') || !$user->isEnrolledInCourse($assessment->course_id)) {
            return response()->json(['message' => 'Unauthorized: Not enrolled in the course for this assessment.'], 403);
        }

        if (!$this->isAssessmentAvailable($assessment)) {
            return response()->json([
                'message' => 'Assessment is not yet available.',
                'available_at' => $assessment->available_at
            ], 400);
        }

        // Check for an existing in-progress attempt
        $existingInProgressAttempt = SubmittedAssessment::where('student_id', $user->id)
                                            ->where('assessment_id', $assessment->id)
                                            ->where('status', 'in_progress')
                                            ->first();

        if ($existingInProgressAttempt) {
            return response()->json([
                'message' => 'Resuming existing attempt.',
                'submitted_assessment' => $existingInProgressAttempt->load('submittedQuestions.submittedOptions')
            ], 200);
        }

        // Count completed attempts for the current user and assessment
        $completedAttemptsCount = SubmittedAssessment::where('student_id', $user->id)
                                             ->where('assessment_id', $assessment->id)
                                             ->whereIn('status', ['completed', 'graded']) // Consider 'graded' as well if it's a final state
                                             ->count();
        
        // Determine the next attempt number
        $nextAttemptNumber = $completedAttemptsCount + 1;

        // Check if max attempts limit is reached
        if ($assessment->max_attempts !== null && $completedAttemptsCount >= $assessment->max_attempts) {
            return response()->json([
                'message' => 'Maximum attempt limit reached. You have used ' . $completedAttemptsCount . ' out of ' . $assessment->max_attempts . ' attempts.',
                'max_attempts' => $assessment->max_attempts, 
                'attempts_made' => $completedAttemptsCount,
                'can_attempt' => false // Explicitly state that no more attempts are allowed
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Create a new submitted assessment record
            $submittedAssessment = SubmittedAssessment::create([
                'student_id' => $user->id,
                'assessment_id' => $assessment->id,
                'status' => 'in_progress',
                'attempt_number' => $nextAttemptNumber, // Set the current attempt number
                // Removed 'max_attempts_allowed' and 'attempts_remaining' from here
                'started_at' => now(),
            ]);

            // Fetch questions for the assessment
            $questions = Question::where('assessment_id', $assessment->id)
                                 ->with('questionOptions')
                                 ->get();

            if ($questions->isEmpty()) {
                DB::rollBack();
                return response()->json(['message' => 'No questions found for this quiz/exam.'], 404);
            }

            // Populate submitted questions and options
            foreach ($questions as $question) {
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

                if (in_array($question->question_type, ['multiple_choice', 'true_false'])) {
                    foreach ($question->questionOptions as $option) {
                        SubmittedQuestionOption::create([
                            'submitted_question_id' => $submittedQuestion->id,
                            'question_option_id' => $option->id,
                            'option_text' => $option->option_text,
                            'is_correct_option' => (bool) $option->is_correct,
                            'is_selected' => false, 
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
     * Submit an assignment file.
     *
     * @param Request $request The incoming request.
     * @param Assessment $assessment The assessment to submit for.
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitAssignment(Request $request, Assessment $assessment)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

       $normalizedAssessmentType = strtolower(trim($assessment->type));
        if (!in_array($normalizedAssessmentType, ['assignment', 'activity', 'project'])) {
            return response()->json(['message' => 'This assessment is not an assignment, activity, or project type.'], 400);
        }

        if (!method_exists($user, 'isEnrolledInCourse') || !$user->isEnrolledInCourse($assessment->course_id)) {
            return response()->json(['message' => 'Unauthorized: Not enrolled in the course for this assignment.'], 403);
        }

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

        $filePath = null;
        if ($request->hasFile('assignment_file')) {
            $file = $request->file('assignment_file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('submitted_assignments/' . $user->id . '/' . $assessment->id, $fileName, 'public');
        }

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
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            Log::error('Error submitting assignment: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Failed to submit assignment. Please try again.'], 500);
        }
    }

    /**
     * Display a submitted assessment.
     *
     * @param SubmittedAssessment $submittedAssessment The submitted assessment to display.
     * @return \Illuminate\Http\JsonResponse
     */
    public function showSubmittedAssessment(SubmittedAssessment $submittedAssessment)
    {
        $user = Auth::user();
        if (!$user || $submittedAssessment->student_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to view this submission.'], 403);
        }

        $submittedAssessment->load([
            'assessment',
            'submittedQuestions.submittedOptions'
        ]);

        return response()->json(['submitted_assessment' => $submittedAssessment]);
    }

    /**
     * Update an answer for a submitted question.
     *
     * @param Request $request The incoming request.
     * @param SubmittedQuestion $submittedQuestion The submitted question to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSubmittedQuestionAnswer(Request $request, SubmittedQuestion $submittedQuestion)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Check if the submitted question belongs to the authenticated user
        $submittedAssessment = $submittedQuestion->submittedAssessment;
        if ($submittedAssessment->student_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to update this answer.'], 403);
        }

        // Check if the assessment is still in progress
        if ($submittedAssessment->status !== 'in_progress') {
            return response()->json(['message' => 'Cannot update answers for a completed assessment.'], 400);
        }

        try {
            if (in_array($submittedQuestion->question_type, ['multiple_choice', 'true_false'])) {
                // Handle multiple choice/true false
                $request->validate([
                    'selected_option_ids' => 'required|array',
                    'selected_option_ids.*' => 'integer|exists:submitted_question_options,question_option_id'
                ]);

                DB::beginTransaction();

                // Reset all options for this question
                SubmittedQuestionOption::where('submitted_question_id', $submittedQuestion->id)
                    ->update(['is_selected' => false]);

                // Set selected options
                if (!empty($request->selected_option_ids)) {
                    SubmittedQuestionOption::where('submitted_question_id', $submittedQuestion->id)
                        ->whereIn('question_option_id', $request->selected_option_ids)
                        ->update(['is_selected' => true]);
                }

                DB::commit();

            } else {
                // Handle text-based questions
                $request->validate([
                    'submitted_answer' => 'nullable|string|max:5000'
                ]);

                $submittedQuestion->update([
                    'submitted_answer' => $request->submitted_answer
                ]);
            }

            return response()->json([
                'message' => 'Answer updated successfully.',
                'submitted_question' => $submittedQuestion->fresh(['submittedOptions'])
            ]);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating submitted question answer: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Failed to update answer. Please try again.'], 500);
        }
    }

    /**
     * Finalize a quiz attempt.
     *
     * @param Request $request The incoming request.
     * @param SubmittedAssessment $submittedAssessment The submitted assessment to finalize.
     * @return \Illuminate\Http\JsonResponse
     */
    public function finalizeQuizAttempt(Request $request, SubmittedAssessment $submittedAssessment)
    {
        $user = Auth::user();
        if (!$user || $submittedAssessment->student_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to finalize this assessment.'], 403);
        }

        if ($submittedAssessment->status !== 'in_progress') {
            return response()->json(['message' => 'Assessment is not in progress.'], 400);
        }

        try {
            DB::beginTransaction();

            // Update the submitted assessment status
            $submittedAssessment->update([
                'status' => 'completed',
                'completed_at' => now(),
                'submitted_at' => now(),
            ]);

            // Auto-grade multiple choice and true/false questions
            $submittedQuestions = $submittedAssessment->submittedQuestions()->with('submittedOptions')->get();
            
            foreach ($submittedQuestions as $submittedQuestion) {
                if (in_array($submittedQuestion->question_type, ['multiple_choice', 'true_false'])) {
                    $selectedOptions = $submittedQuestion->submittedOptions()->where('is_selected', true)->get();
                    $correctOptions = $submittedQuestion->submittedOptions()->where('is_correct_option', true)->get();
                    
                    // Check if selected options match correct options
                    $selectedCorrectCount = $selectedOptions->where('is_correct_option', true)->count();
                    $totalCorrectCount = $correctOptions->count();
                    $selectedIncorrectCount = $selectedOptions->where('is_correct_option', false)->count();
                    
                    $isCorrect = ($selectedCorrectCount === $totalCorrectCount) && ($selectedIncorrectCount === 0);
                    $scoreEarned = $isCorrect ? $submittedQuestion->max_points : 0;
                    
                    $submittedQuestion->update([
                        'is_correct' => $isCorrect,
                        'score_earned' => $scoreEarned
                    ]);
                }
            }

            // Calculate total score for auto-graded questions
            $totalScore = $submittedQuestions->whereNotNull('score_earned')->sum('score_earned');
            $submittedAssessment->update(['score' => $totalScore]);

            // Removed attempts_remaining decrement logic from here
            // The overall attempts_remaining is handled by getAttemptStatus and frontend logic.

            DB::commit();

            return response()->json([
                'message' => 'Quiz completed successfully!',
                'submitted_assessment' => $submittedAssessment->fresh(['submittedQuestions.submittedOptions'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error finalizing quiz attempt: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Failed to finalize quiz. Please try again.'], 500);
        }
    }

    /**
     * Get the attempt status for a given assessment for the authenticated user.
     *
     * @param Assessment $assessment The assessment to check.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttemptStatus(Assessment $assessment)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Only applicable for quiz/exam types
        $normalizedAssessmentType = strtolower(trim($assessment->type));
        if (!in_array($normalizedAssessmentType, ['quiz', 'exam'])) {
            return response()->json(['message' => 'Attempt status is only applicable for quiz or exam types.'], 400);
        }

        // Count completed attempts
        $completedAttemptsCount = SubmittedAssessment::where('student_id', $user->id)
                                             ->where('assessment_id', $assessment->id)
                                             ->whereIn('status', ['completed', 'graded'])
                                             ->count();
        
        // Check for an in-progress attempt
        $inProgressAttempt = SubmittedAssessment::where('student_id', $user->id)
                                            ->where('assessment_id', $assessment->id)
                                            ->where('status', 'in_progress')
                                            ->first();

        $maxAttempts = $assessment->max_attempts;
        $canAttempt = true;
        $attemptsRemaining = null;

        if ($maxAttempts !== null) {
            $attemptsRemaining = $maxAttempts - $completedAttemptsCount;
            if ($attemptsRemaining <= 0) {
                $canAttempt = false;
            }
        }

        // If there's an in-progress attempt, the user can always resume it, so 'canAttempt' should be true
        // and attempts remaining should reflect the state of that in-progress attempt if it's the last one.
        if ($inProgressAttempt) {
            $canAttempt = true; // User can always resume an in-progress attempt
            // The logic for attemptsRemaining should remain based on completedAttemptsCount
            // as this reflects how many *new* attempts can be started.
            // For a resumed attempt, it doesn't consume another attempt count.
        }


        return response()->json([
            'max_attempts' => $maxAttempts,
            'attempts_made' => $completedAttemptsCount,
            'attempts_remaining' => $attemptsRemaining,
            'can_start_new_attempt' => $canAttempt && !$inProgressAttempt, // Can start new if allowed AND no in-progress
            'has_in_progress_attempt' => (bool) $inProgressAttempt,
            'in_progress_submitted_assessment_id' => $inProgressAttempt ? $inProgressAttempt->id : null,
        ]);
    }

    /**
     * Check if the assessment is currently available.
     *
     * @param Assessment $assessment The assessment to check.
     * @return bool
     */
    private function isAssessmentAvailable(Assessment $assessment)
    {
        $now = now();
        
        if ($assessment->available_at && $now->lt($assessment->available_at)) {
            return false;
        }
        
        if ($assessment->unavailable_at && $now->gt($assessment->unavailable_at)) {
            return false;
        }
        
        return true;
    }
}
