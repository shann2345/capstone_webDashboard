<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;

class StudentMaterialController extends Controller
{
    public function show(Material $material)
    {
        if (empty($material->course_id) && $material->topic && empty($material->topic->course_id)) {
            return response()->json(['message' => 'Material not linked to a course.'], 404);
        }

        $courseId = $material->course_id ?? ($material->topic ? $material->topic->course_id : null);

        if (!$courseId) {
            return response()->json(['message' => 'Material course association not found.'], 404);
        }
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

    
        if (!$user->isEnrolledInCourse($courseId)) {
            return response()->json(['message' => 'Unauthorized: Not enrolled in this course.'], 403);
        }

        if ($material->available_at && now()->lessThan($material->available_at)) {
            return response()->json(['message' => 'Material is not yet available.'], 403);
        }

        return response()->json([
            'material' => $material
        ]);
    }
    public function download(Material $material)
    {
        $user = Auth::user();
        if (!$user->isEnrolledInCourse($material->course_id)) {
            return response()->json(['message' => 'Unauthorized to download this material.'], 403);
        }

        if (empty($material->file_path)) {
            return response()->json(['message' => 'No file attached to this material.'], 404);
        }

        $filePath = str_replace(url('/storage'), 'public', $material->file_path); 

        if (!Storage::exists($filePath)) {
            return response()->json(['message' => 'File not found on server.'], 404);
        }

        $fileName = basename($material->file_path);

        return Storage::download($filePath, $fileName);
    }
}