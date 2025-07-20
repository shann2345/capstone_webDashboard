<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material; // Assuming you have a Material model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // For handling file paths

class StudentMaterialController extends Controller
{
    /**
     * Display the specified material.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Material $material)
    {
        // You might want to eager load relationships if materials have, e.g., an author, or related assessments
        // $material->load(['author']);

        // IMPORTANT: Ensure the file_path is accessible.
        // If your files are stored in `storage/app/public`, you need to use Storage::url()
        // or ensure your web server is configured to serve them directly.
        // For demonstration, we'll assume file_path is already a direct URL or relative to public storage.
        // If it's a relative path like 'materials/document.pdf', you might convert it to a full URL:
        $fileUrl = null;
        if ($material->file_path) {
            // Option 1: If file_path is already a full URL
            // $fileUrl = $material->file_path;

            // Option 2: If files are stored in storage/app/public and symlinked to public/storage
            $fileUrl = Storage::url($material->file_path);

            // Option 3: If files are directly in public folder (less common/recommended for uploads)
            // $fileUrl = asset($material->file_path);
        }

        return response()->json([
            'material' => [
                'id' => $material->id,
                'title' => $material->title,
                'content' => $material->content,
                'file_path' => $fileUrl, // Send the full URL if applicable
                'type' => $material->type, // Assuming material has a 'type' column
                'created_at' => $material->created_at->format('Y-m-d\TH:i:sP'), // Ensure consistent date format
                'available_at' => $material->available_at ? $material->available_at->format('Y-m-d\TH:i:sP') : null,
                // Add any other relevant material details you want to display
            ]
        ]);
    }

    // You might add other methods here later, e.g., for downloading files
    // public function download(Material $material) { ... }
}