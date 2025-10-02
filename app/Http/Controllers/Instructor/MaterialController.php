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
        $allowedDocumentExtensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt', 'rtf', 'odt', 'xls', 'xlsx', 'csv'];
        $allowedCodeExtensions = ['java', 'js', 'py', 'php', 'html', 'css', 'json', 'xml', 'cpp', 'c', 'h', 'hpp', 'cs', 'rb', 'go', 'swift', 'kt', 'scala', 'r', 'sql', 'sh', 'bat', 'ps1', 'yml', 'yaml', 'toml', 'ini', 'cfg', 'conf', 'md', 'rst', 'tex'];
        $allowedVideoExtensions = ['mp4', 'mov', 'avi', 'webm', 'ogg', 'mkv', 'flv', 'wmv', 'm4v', '3gp', 'mpg', 'mpeg'];
        $allowedAudioExtensions = ['mp3', 'wav', 'ogg', 'aac', 'flac', 'm4a', 'wma', 'opus', 'aiff', 'au'];
        $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp', 'tiff', 'tif', 'ico', 'heic', 'heif'];
        $allowedArchiveExtensions = ['zip', 'rar', '7z', 'tar', 'gz', 'bz2', 'xz', 'lzma', 'cab', 'iso'];
        $allowedOtherExtensions = ['apk', 'exe', 'msi', 'deb', 'rpm', 'dmg', 'pkg', 'bin', 'jar', 'war', 'ear'];

        $allAllowedExtensions = array_merge(
            $allowedDocumentExtensions,
            $allowedCodeExtensions,
            $allowedVideoExtensions,
            $allowedAudioExtensions,
            $allowedImageExtensions,
            $allowedArchiveExtensions,
            $allowedOtherExtensions
        );

        // 1. Validate the incoming request data
        $validated = $request->validate([
            'topic_id' => 'nullable|exists:topics,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'upload_type' => 'required|in:file,link', // New field to choose between file and link
            'material_file' => [
                'required_if:upload_type,file',
                'file',
                'max:102400', // 100MB max file size (100 * 1024 = 102400 KB)
                function ($attribute, $value, $fail) use ($allAllowedExtensions, $request) {
                    if ($request->upload_type === 'file' && $value) {
                        $extension = strtolower($value->getClientOriginalExtension());
                        if (!in_array($extension, $allAllowedExtensions)) {
                            $fail('The file type is not supported. Allowed types: ' . implode(', ', $allAllowedExtensions));
                        }
                    }
                },
            ],
            'material_link' => 'required_if:upload_type,link|nullable|url|max:2048', // New field for links
            'material_type' => [
                'nullable',
                'string',
                Rule::in([
                    'pdf', 'document', 'video', 'audio', 'image', 'code', 'archive', 'executable', 'other', 'link' // Added 'link' type
                ]),
            ],
            'available_at' => 'nullable|date',
            'unavailable_at' => 'nullable|date|after_or_equal:available_at', // Unavailable must be after or equal to available
        ]);

        $filePath = null;
        $fileType = null;
        $originalFilename = null;

        // 2. Handle file upload or link
        if ($validated['upload_type'] === 'link') {
            // Handle link upload
            $filePath = $validated['material_link'];
            $fileType = 'link';
            $originalFilename = $validated['material_link']; // Store the URL as filename for consistency
        } elseif ($request->hasFile('material_file')) {
            // Handle file upload (existing logic)
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
                } elseif (in_array($extension, $allowedOtherExtensions)) {
                    $fileType = 'executable';
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

    public function show(Material $material)
    {
        return view('instructor.material.showMaterial', compact('material'));
    }

    public function download(Material $material)
    {
        // Check if the material is currently available based on timestamps
        if (!$material->isAvailable()) {
            return back()->with('error', 'This material is not currently available for download.');
        }

        // Handle link materials - redirect to the URL
        if ($material->material_type === 'link') {
            return redirect()->away($material->file_path);
        }

        // Handle file materials - download the file
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            // Return the file for download
            $file = Storage::disk('public')->path($material->file_path);
            return Response::download($file, $material->original_filename);
        }

        return back()->with('error', 'Material file not found.');
    }

    public function edit(Material $material)
    {
        $course = $material->course;
        $topicId = $material->topic_id;
        
        return view('instructor.material.createMaterials', compact('course', 'material', 'topicId'));
    }

    public function update(Request $request, $id)
    {
        // Find the material to update
        $material = Material::findOrFail($id);
        
        // Define allowed extensions (same as store method)
        $allowedDocumentExtensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt', 'rtf', 'odt', 'xls', 'xlsx', 'csv'];
        $allowedCodeExtensions = ['java', 'js', 'py', 'php', 'html', 'css', 'json', 'xml', 'cpp', 'c', 'h', 'hpp', 'cs', 'rb', 'go', 'swift', 'kt', 'scala', 'r', 'sql', 'sh', 'bat', 'ps1', 'yml', 'yaml', 'toml', 'ini', 'cfg', 'conf', 'md', 'rst', 'tex'];
        $allowedVideoExtensions = ['mp4', 'mov', 'avi', 'webm', 'ogg', 'mkv', 'flv', 'wmv', 'm4v', '3gp', 'mpg', 'mpeg'];
        $allowedAudioExtensions = ['mp3', 'wav', 'ogg', 'aac', 'flac', 'm4a', 'wma', 'opus', 'aiff', 'au'];
        $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp', 'tiff', 'tif', 'ico', 'heic', 'heif'];
        $allowedArchiveExtensions = ['zip', 'rar', '7z', 'tar', 'gz', 'bz2', 'xz', 'lzma', 'cab', 'iso'];
        $allowedOtherExtensions = ['apk', 'exe', 'msi', 'deb', 'rpm', 'dmg', 'pkg', 'bin', 'jar', 'war', 'ear'];

        $allAllowedExtensions = array_merge(
            $allowedDocumentExtensions,
            $allowedCodeExtensions,
            $allowedVideoExtensions,
            $allowedAudioExtensions,
            $allowedImageExtensions,
            $allowedArchiveExtensions,
            $allowedOtherExtensions
        );

        // Validation rules for update
        $validated = $request->validate([
            'topic_id' => 'nullable|exists:topics,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'upload_type' => 'required|in:file,link', // New field to choose between file and link
            'material_file' => [
                'nullable', // File is optional for updates
                'file',
                'max:102400', // 100MB max file size
                function ($attribute, $value, $fail) use ($allAllowedExtensions, $request) {
                    if ($value && $request->upload_type === 'file') { // Only validate if file is provided and upload_type is file
                        $extension = strtolower($value->getClientOriginalExtension());
                        if (!in_array($extension, $allAllowedExtensions)) {
                            $fail('The file type is not supported. Allowed types: ' . implode(', ', $allAllowedExtensions));
                        }
                    }
                },
            ],
            'material_link' => 'required_if:upload_type,link|nullable|url|max:2048', // New field for links
            'material_type' => [
                'nullable',
                'string',
                Rule::in([
                    'pdf', 'document', 'video', 'audio', 'image', 'code', 'archive', 'executable', 'other', 'link'
                ]),
            ],
            'available_at' => 'nullable|date',
            'unavailable_at' => 'nullable|date|after_or_equal:available_at',
        ]);

        // Handle file upload or link if new content is provided
        if ($validated['upload_type'] === 'link') {
            // Handle link update
            // Delete old file if it exists (when switching from file to link)
            if ($material->material_type !== 'link' && $material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }
            
            $material->file_path = $validated['material_link'];
            $material->material_type = 'link';
            $material->original_filename = $validated['material_link'];
        } elseif ($request->hasFile('material_file')) {
            // Handle file upload (existing logic)
            // Delete old file if it exists
            if ($material->file_path && $material->material_type !== 'link' && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            $file = $request->file('material_file');
            $originalFilename = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());

            // Determine material_type based on extension if not explicitly set
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
                } elseif (in_array($extension, $allowedOtherExtensions)) {
                    $fileType = 'executable';
                } else {
                    $fileType = 'other';
                }
            } else {
                $fileType = $request->material_type;
            }

            $fileName = Str::slug(pathinfo($originalFilename, PATHINFO_FILENAME)) . '-' . time() . '.' . $extension;
            $directory = 'materials/' . $material->course_id;
            $filePath = Storage::disk('public')->putFileAs($directory, $file, $fileName);

            $material->file_path = $filePath;
            $material->material_type = $fileType;
            $material->original_filename = $originalFilename;
        }

        // Update other fields
        $localTimezone = 'Asia/Manila';
        $availableAt = $validated['available_at'] ? Carbon::parse($validated['available_at'], $localTimezone)->setTimezone('UTC') : null;
        $unavailableAt = $validated['unavailable_at'] ? Carbon::parse($validated['unavailable_at'], $localTimezone)->setTimezone('UTC') : null;

        $material->topic_id = $request->input('topic_id');
        $material->title = $validated['title'];
        $material->description = $validated['description'];
        $material->available_at = $availableAt;
        $material->unavailable_at = $unavailableAt;
        $material->save();

        return redirect()->route('materials.show', $material->id)->with('success', 'Material updated successfully!');
    }
}
