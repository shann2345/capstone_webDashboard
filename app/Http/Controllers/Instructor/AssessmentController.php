<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Course;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AssessmentController extends Controller
{
    /**
     * Show the form for creating a new Quiz or Exam assessment.
     * This form includes the question builder.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\View\View
     */
    public function createQuiz(Course $course, $type, Request $request)
    {
        $assessmentType = $type;
        $topicId = $request->query('topic_id');
        return view('instructor.assessment.createQuiz', compact('course', 'assessmentType', 'topicId'));
    }
    public function storeQuiz(Request $request, $courseId)
    {
        Log::info($request->all());
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:0',
            'access_code' => 'nullable|string|max:255',
            'assessment_file' => 'nullable|file|max:20480', // 20MB
            'available_at' => 'nullable|date',
            'unavailable_at' => 'nullable|date|after_or_equal:available_at',
            'questions' => 'nullable|array',
            'questions.*.question_type' => 'required_with:questions|string|in:multiple_choice,identification,true_false,essay',
            'questions.*.question_text' => 'required_with:questions|string',
            'questions.*.points' => 'required_with:questions|integer|min:1',
            'topic_id' => 'nullable|exists:topics,id', 
        ]);

        // Handle file upload if present
        $filePath = null;
        if ($request->hasFile('assessment_file')) {
            $filePath = $request->file('assessment_file')->store('assessments', 'public');
        }

        // Create the assessment
        $assessment = \App\Models\Assessment::create([
            'course_id' => $courseId,
            'topic_id' => $validated['topic_id'] ?? null,
            'title' => $validated['title'],
            'type' => $request->input('type', 'quiz'),
            'description' => $validated['description'] ?? null,
            'assessment_file_path' => $filePath,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'access_code' => $validated['access_code'] ?? null,
            'available_at' => $validated['available_at'] ?? null,
            'unavailable_at' => $validated['unavailable_at'] ?? null,
            'created_by' => Auth::id(),
        ]);

        // Save questions if present
        if ($request->has('questions')) {
            foreach ($request->input('questions') as $order => $q) {
                $question = new \App\Models\Question([
                    'assessment_id' => $assessment->id,
                    'question_text' => $q['question_text'],
                    'question_type' => $q['question_type'],
                    'points' => $q['points'],
                    'order' => $order,
                ]);

                // Handle correct_answer and options
                if ($q['question_type'] === 'multiple_choice') {
                    $question->correct_answer = $q['correct_answer'];
                    $question->save();

                    // Save options
                    if (isset($q['options']) && is_array($q['options'])) {
                        foreach ($q['options'] as $opt) {
                            if (!empty($opt['option_text'])) {
                                \App\Models\QuestionOption::create([
                                    'question_id' => $question->id,
                                    'option_text' => $opt['option_text'],
                                    'option_order' => $opt['option_order'],
                                ]);
                            }
                        }
                    }
                } elseif ($q['question_type'] === 'identification') {
                    $question->correct_answer = $q['correct_answer'];
                    $question->save();
                } elseif ($q['question_type'] === 'true_false') {
                    $question->correct_answer = $q['correct_answer']; // 'true' or 'false'
                    $question->save();
                } else {
                    $question->save();
                }
            }
        }

        return response()->json(['success' => true, 'redirect' => route('courses.show', $courseId)]);
    }

    public function createAssignment(Course $course, $typeAct, Request $request)
    {
        $assessmentType = $typeAct; 
        $topicId = $request->query('topic_id');
        return view('instructor.assessment.createAssignment', compact('course', 'assessmentType', 'topicId'));
    }


    public function storeAssignment(Request $request, Course $course)
    {
        Log::info($request->all());
        try {
            $validatedAssessmentData = $request->validate([
                'topic_id' => 'nullable|exists:topics,id',
                'title' => 'required|string|max:255',
                'type' => ['required', Rule::in(['assignment', 'activity', 'project'])], // Only allow assignment/activity here
                'description' => 'nullable|string',
                'assessment_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar|max:20480', // 20MB
                'available_at' => 'nullable|date',
                'unavailable_at' => 'nullable|date|after_or_equal:available_at',
            ]);

            // For assignment/activity, if no description, then file is required
            if (empty($validatedAssessmentData['description']) && !$request->hasFile('assessment_file')) {
                throw ValidationException::withMessages([
                    'description' => ['For Assignment or Activity, either a description or an assessment file is required.'],
                    'assessment_file' => ['For Assignment or Activity, either a description or an assessment file is required.'],
                ]);
            }

            DB::beginTransaction();

            $assessmentFilePath = null;
            if ($request->hasFile('assessment_file')) {
                $assessmentFilePath = $request->file('assessment_file')->store('assessments/' . $course->id, 'public');
            }

            $assessment = Assessment::create([
                'course_id' => $course->id,
                'topic_id' => $validatedAssessmentData['topic_id'] ?? null,
                'title' => $validatedAssessmentData['title'],
                'type' => $validatedAssessmentData['type'],
                'description' => $validatedAssessmentData['description'] ?? null,
                'assessment_file_path' => $assessmentFilePath,
                // These fields are not relevant for simple assignments/activities
                'duration_minutes' => null,
                'access_code' => null,
                'available_at' => $validatedAssessmentData['available_at'] ?? null,
                'unavailable_at' => $validatedAssessmentData['unavailable_at'] ?? null,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json(['success' => 'Assessment created successfully!', 'redirect' => route('courses.show', $course->id)]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($assessmentFilePath) && Storage::disk('public')->exists($assessmentFilePath)) {
                Storage::disk('public')->delete($assessmentFilePath);
            }
            Log::error('Assignment/Activity creation failed: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return response()->json(['error' => 'Failed to create assessment: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating an "Other" assessment type.
     * This is the simplest form.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\View\View
     */
    // public function createOther(Course $course)
    // {
    //     $course->load('materials');
    //     $assessmentType = 'other';
    //     return view('instructor.assessment.createOther', compact('course', 'assessmentType'));
    // }

    // /**
    //  * Store a newly created "Other" assessment.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Models\Course  $course
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function storeOther(Request $request, Course $course)
    // {
    //     try {
    //         $validatedAssessmentData = $request->validate([
    //             'material_id' => 'nullable|exists:materials,id',
    //             'title' => 'required|string|max:255',
    //             'type' => ['required', Rule::in(['other'])], // Only allow 'other' here
    //             'description' => 'nullable|string',
    //             'available_at' => 'nullable|date',
    //             'unavailable_at' => 'nullable|date|after_or_equal:available_at',
    //         ]);

    //         DB::beginTransaction();

    //         $assessment = Assessment::create([
    //             'course_id' => $course->id,
    //             'material_id' => $validatedAssessmentData['material_id'] ?? null,
    //             'title' => $validatedAssessmentData['title'],
    //             'type' => $validatedAssessmentData['type'],
    //             'description' => $validatedAssessmentData['description'] ?? null,
    //             'assessment_file_path' => null, // Not applicable for 'other' unless specified
    //             'duration_minutes' => null,
    //             'access_code' => null,
    //             'available_at' => $validatedAssessmentData['available_at'] ?? null,
    //             'unavailable_at' => $validatedAssessmentData['unavailable_at'] ?? null,
    //             'created_by' => Auth::id(),
    //         ]);

    //         DB::commit();

    //         return response()->json(['success' => 'Assessment created successfully!', 'redirect' => route('courses.show', $course->id)]);

    //     } catch (ValidationException $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'message' => 'The given data was invalid.',
    //             'errors' => $e->errors(),
    //         ], 422);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Other assessment creation failed: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
    //         return response()->json(['error' => 'Failed to create assessment: ' . $e->getMessage()], 500);
    //     }
    // }
}
