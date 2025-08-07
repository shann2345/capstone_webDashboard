<?php

namespace App\Http\Controllers\Instructor;

use App\Models\Course; // To fetch the course
use App\Models\Material; // To work with Material model
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage; // For file storage operations
use Illuminate\Support\Facades\Response; // For file downloads
use Illuminate\Support\Str;
use Illuminate\Validation\Rule; // Import Rule for validation
use Carbon\Carbon;

class MaterialController extends Controller
{
    /**
     * Display a listing of materials for a specific course, and the upload form.
     */
    public function create(Course $course, Request $request)
    {
        // Load materials for this course, ordered by creation date
        $materials = $course->materials()->latest()->get();
        $topicId = $request->query('topic_id');
        return view('instructor.material.createMaterials', compact('course', 'materials', 'topicId'));
    }

    public function store(Request $request, Course $course)
    {
        // Define allowed extensions and their categories for validation and type determination
        $allowedDocumentExtensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt'];
        $allowedCodeExtensions = ['java', 'js', 'py', 'php', 'html', 'css', 'json', 'xml'];
        $allowedVideoExtensions = ['mp4', 'mov', 'avi', 'webm', 'ogg'];
        $allowedAudioExtensions = ['mp3', 'wav', 'ogg', 'aac', 'flac'];
        $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
        $allowedArchiveExtensions = ['zip', 'rar', '7z', 'tar', 'gz'];

        $allAllowedExtensions = array_merge(
            $allowedDocumentExtensions,
            $allowedCodeExtensions,
            $allowedVideoExtensions,
            $allowedAudioExtensions,
            $allowedImageExtensions,
            $allowedArchiveExtensions
        );

        // 1. Validate the incoming request data
        $validated = $request->validate([
            'topic_id' => 'nullable|exists:topics,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'material_file' => 'required|file|mimes:' . implode(',', $allAllowedExtensions) . '|max:20480', // 20MB max file size
            'material_type' => [
                'nullable',
                'string',
                Rule::in([
                    'pdf', 'document', 'video', 'audio', 'image', 'code', 'archive', 'other' // Expanded types
                ]),
            ],
            'available_at' => 'nullable|date',
            'unavailable_at' => 'nullable|date|after_or_equal:available_at', // Unavailable must be after or equal to available
        ]);

        $filePath = null;
        $fileType = null;
        $originalFilename = null;

        // 2. Handle file upload
        if ($request->hasFile('material_file')) {
            $file = $request->file('material_file');
            $originalFilename = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension()); // Ensure lowercase for comparison

            // Determine material_type based on extension if not explicitly set by user
            if (!$request->filled('material_type')) {
                if (in_array($extension, ['pdf'])) {
                    $fileType = 'pdf';
                } elseif (in_array($extension, $allowedDocumentExtensions)) {
                    $fileType = 'document';
                } elseif (in_array($extension, $allowedVideoExtensions)) {
                    $fileType = 'video';
                } elseif (in_array($extension, $allowedAudioExtensions)) {
                    $fileType = 'audio';
                } elseif (in_array($extension, $allowedImageExtensions)) {
                    $fileType = 'image';
                } elseif (in_array($extension, $allowedCodeExtensions)) {
                    $fileType = 'code';
                } elseif (in_array($extension, $allowedArchiveExtensions)) {
                    $fileType = 'archive';
                } else {
                    $fileType = 'other'; // Fallback for any other allowed but uncategorized type
                }
            } else {
                $fileType = $request->material_type; // Use provided type if available
            }

            // Define the storage path: public/materials/{course_id}/
            $fileName = Str::slug(pathinfo($originalFilename, PATHINFO_FILENAME)) . '-' . time() . '.' . $extension;
            $directory = 'materials/' . $course->id;

            // Store the file in 'storage/app/public/materials/{course_id}'
            $filePath = Storage::disk('public')->putFileAs($directory, $file, $fileName);
        }

        
        $localTimezone = 'Asia/Manila';
        $availableAt = $validated['available_at'] ? Carbon::parse($validated['available_at'], $localTimezone)->setTimezone('UTC') : null;
        $unavailableAt = $validated['unavailable_at'] ? Carbon::parse($validated['unavailable_at'], $localTimezone)->setTimezone('UTC') : null;

        $material = new Material();
        $material->course_id = $course->id;
        $material->topic_id = $request->input('topic_id');
        $material->title = $request->title;
        $material->description = $request->description;
        $material->file_path = $filePath; // Store the relative path
        $material->material_type = $fileType;
        $material->original_filename = $originalFilename; // Store original filename for download
        $material->available_at = $availableAt;
        $material->unavailable_at = $unavailableAt;
        $material->save();

        // 4. Redirect back with success message
        return redirect()->route('courses.show', $course->id)->with('success', 'Material uploaded successfully!');
    }

    /**
     * Display the specified material content.
     */
    public function show(Material $material)
    {
        return view('instructor.material.showMaterial', compact('material'));
    }

    /**
     * Download the specified material file.
     */
    public function download(Material $material)
    {
        // Check if the material is currently available based on timestamps
        if (!$material->isAvailable()) {
            return back()->with('error', 'This material is not currently available for download.');
        }

        // Check if file_path exists and the file actually exists in storage
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            // Return the file for download
            $file = Storage::disk('public')->path($material->file_path);
            return Response::download($file, $material->original_filename);
        }

        return back()->with('error', 'Material file not found.');
    }
}
