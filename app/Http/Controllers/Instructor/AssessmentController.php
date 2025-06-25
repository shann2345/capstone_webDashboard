<?php

namespace App\Http\Controllers\Instructor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Course; // Import the Course model
use App\Models\Assessment; // Import the Assessment model
use App\Models\Question; // Make sure to import Question model
use App\Models\Option; // Make sure to import Option model
use App\Models\Material; // Import the Material model
use Illuminate\Support\Facades\Storage; // For file storage
use Illuminate\Support\Facades\Crypt;   // For encryption/decryption
use Illuminate\Support\Str;              // For string manipulation (e.g., file naming)
use Illuminate\Support\Facades\Response; // For file downloads
use Illuminate\Support\Facades\Auth; // For authentication
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    public function index(Course $course)
    {
        $assessments = $course->assessments()->latest()->get(); // Fetch assessments for the course
        return view('instructor.course.show', compact('course', 'assessments'));
    }

    public function create(Course $course, Request $request)
    {
        // Get the material_id from the query parameters, if present
        $materialId = $request->query('material_id');

        // You might want to load the material if you need its details for the view,
        // or just pass the ID directly. For pre-selecting in the dropdown, the ID is enough.
        $selectedMaterial = null;
        if ($materialId) {
            $selectedMaterial = Material::find($materialId);
        }

        // It's good practice to load materials for the dropdown in createAssessment.blade.php
        // Ensure Course model has materials relationship:
        // public function materials() { return $this->hasMany(Material::class); }
        $course->load('materials');

        return view('instructor.assessment.createAssessment', [
            'course' => $course,
            'selectedMaterialId' => $materialId, // Pass the ID to the view
        ]);
    }

    public function store(Request $request, Course $course)
    {
        // Add material_id to validation rules
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:quiz,activity,exam,assignment,other',
            'description' => 'nullable|string',
            'material_id' => 'nullable|exists:materials,id', // Validate material_id
            'duration_minutes' => 'nullable|integer|min:0',
            'access_code' => 'nullable|string|max:255',
            'available_at' => 'nullable|date',
            'unavailable_at' => 'nullable|date|after_or_equal:available_at',
            'assessment_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar|max:20480', // 20MB
            // Add validation for questions if you have a question builder
            'questions' => 'nullable|array',
            'questions.*.type' => 'required_with:questions|in:multiple_choice,identification,true_false',
            'questions.*.text' => 'required_with:questions|string',
            'questions.*.points' => 'required_with:questions|integer|min:1',
            'questions.*.correct_answer_identification' => 'required_if:questions.*.type,identification|string',
            'questions.*.correct_answer_true_false' => 'required_if:questions.*.type,true_false|in:true,false',
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array|min:2',
            'questions.*.options.*.text' => 'required|string',
            'questions.*.options.*.order' => 'required|integer', // This should be controlled by frontend logic
            'questions.*.correct_option_index' => 'required_if:questions.*.type,multiple_choice|integer',
        ]);

        // Handle file upload
        if ($request->hasFile('assessment_file')) {
            $validatedData['file_path'] = $request->file('assessment_file')->store('assessments', 'public');
        }

        // Set the course_id explicitly
        $validatedData['course_id'] = $course->id;

        $assessment = Assessment::create($validatedData);

        // Handle questions if provided
        if (isset($validatedData['questions'])) {
            foreach ($validatedData['questions'] as $questionData) {
                $question = $assessment->questions()->create([
                    'type' => $questionData['type'],
                    'text' => $questionData['text'],
                    'points' => $questionData['points'],
                    'correct_answer_identification' => $questionData['correct_answer_identification'] ?? null,
                    'correct_answer_true_false' => $questionData['correct_answer_true_false'] ?? null,
                ]);

                if ($questionData['type'] === 'multiple_choice' && isset($questionData['options'])) {
                    foreach ($questionData['options'] as $index => $optionData) {
                        $question->options()->create([
                            'text' => $optionData['text'],
                            'order' => $optionData['order'],
                            'is_correct' => ($index == $questionData['correct_option_index']),
                        ]);
                    }
                }
            }
        }

        return redirect()->route('assessments.index', $course->id)->with('success', 'Assessment created successfully!');
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

        // 2. Validate access (e.g., instructor, or student with proper conditions)
        // For now, let's assume instructor access for simplicity.
        // In a real app, you'd check Auth::user()->isInstructor() or student enrollment/availability.
        if (Auth::user()->role !== 'instructor' && !$assessment->isAvailable()) { // Example for students
            return back()->with('error', 'This assessment is not currently available for download.');
        }
        // You'll need more sophisticated access control for students (e.g., if they entered access code)

        // 3. Read and decrypt the file content
        try {
            $encryptedContent = Storage::disk('local')->get($assessment->encrypted_file_path);
            $decryptedContent = Crypt::decryptString($encryptedContent);

            // 4. Detect MIME type using finfo
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($decryptedContent) ?: 'application/octet-stream';

            // 5. Return the decrypted content as a downloadable file
            return Response::make($decryptedContent, 200, [
                'Content-Type'        => $mimeType, // Use detected mime type, fallback to generic
                'Content-Disposition' => 'attachment; filename="' . $assessment->original_filename . '"',
            ]);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return back()->with('error', 'Failed to decrypt assessment file. It might be corrupted or the encryption key has changed.');
        } catch (\Exception $e) {
            return back()->with('error', 'An unexpected error occurred during download: ' . $e->getMessage());
        }
    }
}