<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;

class StudentMaterialController extends Controller
{
    /**
     * Display the specified material.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function show(Material $material)
    {
        // Ensure material belongs to a course (if applicable)
        if (empty($material->course_id) && $material->topic && empty($material->topic->course_id)) {
            return response()->json(['message' => 'Material not linked to a course.'], 404);
        }

        // Get the course ID from the material or its associated topic
        $courseId = $material->course_id ?? ($material->topic ? $material->topic->course_id : null);

        if (!$courseId) {
            return response()->json(['message' => 'Material course association not found.'], 404);
        }

        // Authorization check: Only enrolled students can view materials
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Assuming User model has an 'isEnrolledInCourse' method or a direct relationship
        // You would need to implement this method on your User model.
        // For example, by checking the 'course_enrollments' table.
        // Example: public function isEnrolledInCourse($courseId) { return $this->enrolledCourses()->where('course_id', $courseId)->exists(); }
        if (!$user->isEnrolledInCourse($courseId)) {
            return response()->json(['message' => 'Unauthorized: Not enrolled in this course.'], 403);
        }

        // Check material availability date
        if ($material->available_at && now()->lessThan($material->available_at)) {
            return response()->json(['message' => 'Material is not yet available.'], 403);
        }

        return response()->json([
            'material' => $material
        ]);
    }
    public function download(Material $material)
    {
        // Authorization check: Ensure the user is enrolled in the material's course
        $user = Auth::user();
        if (!$user->isEnrolledInCourse($material->course_id)) {
            return response()->json(['message' => 'Unauthorized to download this material.'], 403);
        }

        // Check if the material has a file path
        if (empty($material->file_path)) {
            return response()->json(['message' => 'No file attached to this material.'], 404);
        }

        // Determine the disk and path
        // Assuming file_path stores a relative path within a storage disk (e.g., 'public/materials/file.pdf')
        // You might need to adjust 'public' based on your Laravel filesystem configuration
        $filePath = str_replace(url('/storage'), 'public', $material->file_path); // Adjust if your file_path already stores 'public/...'

        if (!Storage::exists($filePath)) {
            return response()->json(['message' => 'File not found on server.'], 404);
        }

        // Get the original file name for download
        $fileName = basename($material->file_path);

        // Return the file for download
        return Storage::download($filePath, $fileName);
    }
}