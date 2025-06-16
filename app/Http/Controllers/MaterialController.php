<?php

namespace App\Http\Controllers;

use App\Models\Course; // To fetch the course
use App\Models\Material; // To work with Material model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // For file storage operations
use Illuminate\Support\Facades\Response; // For file downloads
use Illuminate\Support\Str;

class MaterialController extends Controller
{
    /**
     * Display a listing of materials for a specific course, and the upload form.
     */
    public function showMaterial(Course $course)
    {
        // Load materials for this course, ordered by creation date
        $materials = $course->materials()->latest()->get();

        // Pass the course and its materials to the view
        return view('instructor.course.uploadMaterials', compact('course', 'materials'));
    }

    /**
     * Store a newly created material in storage.
     */
    public function store(Request $request, Course $course)
    {
        // 1. Validate the incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'material_file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,txt,java,js,py,mp4,mov,avi|max:20480', // 20MB max file size
            'material_type' => 'nullable|string|in:pdf,doc,docx,ppt,pptx,txt,java,js,py,mp4,mov,avi,document,video,code', // Custom types based on uploaded file
            'available_at' => 'nullable|date',
            'unavailable_at' => 'nullable|date|after:available_at', // Unavailable must be after available
        ]);

        $filePath = null;
        $fileType = null;
        $originalFilename = null;

        // 2. Handle file upload
        if ($request->hasFile('material_file')) {
            $file = $request->file('material_file');
            $originalFilename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            // Determine material_type based on extension if not explicitly set
            if (!$request->filled('material_type')) {
                if (in_array($extension, ['pdf'])) {
                    $fileType = 'pdf';
                } elseif (in_array($extension, ['doc', 'docx', 'ppt', 'pptx', 'txt'])) {
                    $fileType = 'document';
                } elseif (in_array($extension, ['mp4', 'mov', 'avi'])) {
                    $fileType = 'video';
                } elseif (in_array($extension, ['java', 'js', 'py'])) {
                    $fileType = 'code';
                } else {
                    $fileType = 'other'; // Fallback
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

        // 3. Create the Material record in the database
        $material = new Material();
        $material->course_id = $course->id;
        $material->title = $request->title;
        $material->description = $request->description;
        $material->file_path = $filePath; // Store the relative path
        $material->material_type = $fileType;
        $material->original_filename = $originalFilename; // Store original filename for download
        $material->available_at = $request->available_at ? now()->parse($request->available_at) : null;
        $material->unavailable_at = $request->unavailable_at ? now()->parse($request->unavailable_at) : null;
        $material->save();

        // 4. Redirect back with success message
        return redirect()->route('materials.showMaterial', $course->id)->with('success', 'Material uploaded successfully!');
    }

    /**
     * Download a specific material file.
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
