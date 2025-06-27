<?php

namespace App\Http\Controllers\Instructor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Assessment;
use App\Models\Question; // NEW: Import Question model
use App\Models\QuestionOption; // NEW: Import QuestionOption model
use App\Models\Material; // NEW: Import Material model for material_id validation
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB; // NEW: For database transactions
use Illuminate\Support\Facades\Auth; // For Auth::user() check in download method
use Illuminate\Support\Facades\Log; // For logging errors

class AssessmentController extends Controller
{
    /**
     * Display a listing of assessments for a specific course.
     * This method is called by assessments.index route, though in new flow
     * assessments are primarily viewed on courses.show. This might be less used.
     */
    public function index(Course $course)
    {
        // Eager load material relationship for assessments to show material title if linked
        $assessments = $course->assessments()->with('material')->latest()->get();
        return view('instructor.assessment.createAssessment', compact('course', 'assessments'));
    }

    /**
     * Show the form for creating a new assessment for a specific course.
     * Can receive a 'material_id' query parameter to pre-select a material.
     *
     * @param  \App\Models\Course  $course
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Course $course, Request $request)
    {
        // Load materials for the dropdown list in the form
        $course->load('materials');

        // Get material_id from query parameters if present (e.g., when clicking "Add Assessment" from a material row)
        $selectedMaterialId = $request->query('material_id');

        return view('instructor.assessment.createAssessment', compact('course', 'selectedMaterialId'));
    }

    /**
     * Store a newly created assessment (and its questions/options) in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Course $course)
    {
        Log::info('Request Data:', $request->all());
        // Use a database transaction to ensure atomicity (all or nothing)
        return DB::transaction(function () use ($request, $course) {
            // 1. Validate the main assessment data
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'material_id' => 'nullable|exists:materials,id', // Validate if material_id exists and is valid
                'type' => 'required|in:quiz,activity,exam,assignment,other', // Matches enum in migration
                'available_at' => 'nullable|date_format:Y-m-d\TH:i', // Matches datetime-local input format
                // Ensure unavailable_at is after or equal to available_at if both are provided
                'unavailable_at' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:available_at',
                'duration_minutes' => 'nullable|integer|min:0',
                'access_code' => 'nullable|string|max:255|unique:assessments,access_code', // Unique across assessments table
                'assessment_file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,ppt,pptx,txt,zip,rar|max:20480', // Max 20MB
            ]);

            $encryptedFilePath = null;
            $originalFilename = null;

            // 2. Handle file upload and encryption if a file is provided
            if ($request->hasFile('assessment_file')) {
                $file = $request->file('assessment_file');
                $originalFilename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();

                $fileContent = file_get_contents($file->getRealPath());
                $encryptedContent = Crypt::encryptString($fileContent); // Encrypt the content

                // Define storage path: 'assessments/encrypted/{course_id}/'
                $fileName = Str::slug(pathinfo($originalFilename, PATHINFO_FILENAME)) . '-' . time() . '.' . $extension . '.encrypted';
                $directory = 'assessments/encrypted/' . $course->id;

                Storage::disk('local')->put($directory . '/' . $fileName, $encryptedContent); // Store encrypted content
                $encryptedFilePath = $directory . '/' . $fileName; // Store relative path in DB
            }

            // 3. Create the main Assessment record
            $assessment = Assessment::create([
                'course_id'           => $course->id, // Linked via route model binding
                'material_id'         => $validatedData['material_id'] ?? null, // Use null if not provided
                'title'               => $validatedData['title'],
                'description'         => $validatedData['description'],
                'encrypted_file_path' => $encryptedFilePath,
                'original_filename'   => $originalFilename,
                'type'                => $validatedData['type'],
                // Parse dates to Carbon instances only if they are not null
                'available_at'        => $validatedData['available_at'] ? now()->parse($validatedData['available_at']) : null,
                'unavailable_at'      => $validatedData['unavailable_at'] ? now()->parse($validatedData['unavailable_at']) : null,
                'duration_minutes'    => $validatedData['duration_minutes'],
                'access_code'         => $validatedData['access_code'],
            ]);

            // 4. Handle nested questions data if assessment type is quiz or exam AND questions are provided
            if (($assessment->type === 'quiz' || $assessment->type === 'exam') && $request->has('questions')) {
                // Validate questions specific to quizzes/exams
                $request->validate([
                    'questions' => 'required|array|min:1', // At least one question if using builder
                    'questions.*.type' => 'required|in:multiple_choice,identification,true_false',
                    'questions.*.text' => 'required|string',
                    'questions.*.points' => 'required|integer|min:1',

                    // Conditional validation for multiple choice options
                    'questions.*.correct_option_index' => 'required_if:questions.*.type,multiple_choice|integer|min:0',
                    'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array|min:2', // At least 2 options for MC
                    'questions.*.options.*.text' => 'required_if:questions.*.type,multiple_choice|string', // Text for each option

                    // Conditional validation for identification answer
                    'questions.*.correct_answer_identification' => 'required_if:questions.*.type,identification|string',

                    // Conditional validation for true/false answer
                    'questions.*.correct_answer_true_false' => 'required_if:questions.*.type,true_false|in:true,false',
                ]);

                foreach ($request->input('questions') as $index => $questionData) {
                    $question = $assessment->questions()->create([
                        'question_text' => $questionData['text'],
                        'question_type' => $questionData['type'],
                        'points'        => $questionData['points'],
                        'order'         => $index, // Save the order of questions as submitted
                        'correct_answer_text' => match($questionData['type']) {
                            'identification' => $questionData['correct_answer_identification'] ?? null,
                            'true_false'     => $questionData['correct_answer_true_false'] ?? null,
                            default          => null, // Multiple choice answers are handled in question_options
                        },
                    ]);

                    // If it's a multiple choice question, save options
                    if ($questionData['type'] === 'multiple_choice' || $questionData['type'] === 'true_false') {
                        $correctOptionIndex = (int)($questionData['correct_option_index'] ?? -1); // Cast to int
                        if (isset($questionData['options']) && is_array($questionData['options'])) {
                            foreach ($questionData['options'] as $optionIndex => $optionTextData) {
                                // Ensure 'text' key exists and is not empty if it's a required option
                                if (isset($optionTextData['text'])) {
                                    $question->options()->create([
                                        'option_text' => $optionTextData['text'],
                                        'is_correct'  => ($optionIndex === $correctOptionIndex),
                                        'order'       => $optionIndex, // Save the order of options
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            // 5. Redirect with success message
            return redirect()->route('courses.show', $course->id)->with('success', 'Assessment created successfully!');
        }); // End of DB::transaction
    }

    /**
     * Download a specific assessment file (decrypts on the fly).
     *
     * @param  \App\Models\Assessment  $assessment
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     */
    public function download(Assessment $assessment)
    {
        // 1. Check if the assessment file path exists and the file is actually stored
        if (!$assessment->encrypted_file_path || !Storage::disk('local')->exists($assessment->encrypted_file_path)) {
            return back()->with('error', 'Assessment file not found or corrupted.');
        }

        // 2. Basic access check (enhance this as needed for student roles)
        // For students, you'd check enrollment, availability, and maybe an access code if applicable.
        if (Auth::check() && Auth::user()->role !== 'instructor') { // Example for non-instructors
            if (!$assessment->isAvailable()) {
                return back()->with('error', 'This assessment is not currently available for download.');
            }
            // Add checks for access code here if needed
        }

        // 3. Read and decrypt the file content
        try {
            $encryptedContent = Storage::disk('local')->get($assessment->encrypted_file_path);
            $decryptedContent = Crypt::decryptString($encryptedContent);

            // 4. Return the decrypted content as a downloadable file
            return Response::make($decryptedContent, 200, [
                'Content-Type'        => \Illuminate\Support\Facades\File::mimeType($assessment->original_filename) ?? 'application/octet-stream', // Determine mime type from original filename
                'Content-Disposition' => 'attachment; filename="' . $assessment->original_filename . '"',
            ]);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return back()->with('error', 'Failed to decrypt assessment file. It might be corrupted or the encryption key has changed.');
        } catch (\Exception $e) {
            // Log the actual exception for debugging in storage/logs/laravel.log
            Log::error("Assessment download error for ID {$assessment->id}: " . $e->getMessage());
            return back()->with('error', 'An unexpected error occurred during download. Please try again or contact support.');
        }
    }
}
