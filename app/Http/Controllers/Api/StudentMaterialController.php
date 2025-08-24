<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\URL;

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

    private function authenticateUser(Request $request)
    {
        // First try normal Sanctum authentication
        $user = Auth::user();
        
        if ($user) {
            return $user;
        }
        
        // If no user from header, try token from query parameter
        $token = $request->query('token');
        
        if (!$token) {
            return null;
        }
        
        try {
            // Find the token in personal access tokens
            $accessToken = PersonalAccessToken::findToken($token);
            
            if (!$accessToken) {
                return null;
            }
            
            // Check if token is still valid
            if ($accessToken->expires_at && $accessToken->expires_at->isPast()) {
                return null;
            }
            
            return $accessToken->tokenable;
        } catch (\Exception $e) {
            Log::error('Token authentication failed: ' . $e->getMessage());
            return null;
        }
    }

    public function view(Material $material, Request $request)
    {
        // Use custom authentication method that supports query tokens
        $user = $this->authenticateUser($request);
        
        if (!$user) {
            // If accessed via browser with no auth, redirect to login
            if ($request->acceptsHtml()) {
                return redirect()->route('login')->with('error', 'Please login to view this material.');
            }
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Get course ID from material or topic
        $courseId = $material->course_id ?? ($material->topic ? $material->topic->course_id : null);
        
        if (!$courseId) {
            return response()->json(['message' => 'Material course association not found.'], 404);
        }

        if (!$user->isEnrolledInCourse($courseId)) {
            if ($request->acceptsHtml()) {
                return redirect()->back()->with('error', 'You are not enrolled in this course.');
            }
            return response()->json(['message' => 'Unauthorized to view this material.'], 403);
        }

        // Check availability
        if ($material->available_at && now()->lessThan($material->available_at)) {
            if ($request->acceptsHtml()) {
                return redirect()->back()->with('error', 'This material is not yet available.');
            }
            return response()->json(['message' => 'Material is not yet available.'], 403);
        }

        if (empty($material->file_path)) {
            if ($request->acceptsHtml()) {
                return redirect()->back()->with('error', 'No file attached to this material.');
            }
            return response()->json(['message' => 'No file attached to this material.'], 404);
        }

        // Fix: The file_path is already relative to public disk
        // If it starts with 'materials/', use it directly
        // If it's a full URL, convert it
        $filePath = $material->file_path;
        
        // If the file_path contains a full URL, extract the relative path
        if (str_contains($filePath, '/storage/')) {
            $filePath = str_replace(url('/storage/'), '', $filePath);
        }
        
        // Ensure the path doesn't start with 'public/' since we're using the public disk
        if (str_starts_with($filePath, 'public/')) {
            $filePath = str_replace('public/', '', $filePath);
        }

        // Debug logging
        Log::info('File Path Debug', [
            'material_id' => $material->id,
            'user_id' => $user->id,
            'original_file_path' => $material->file_path,
            'processed_file_path' => $filePath,
            'file_exists' => Storage::disk('public')->exists($filePath),
            'storage_path' => Storage::disk('public')->path($filePath),
            'auth_method' => $request->query('token') ? 'query_token' : 'header_token'
        ]);

        if (!Storage::disk('public')->exists($filePath)) {
            Log::error('File not found', [
                'material_id' => $material->id,
                'file_path' => $filePath,
                'full_path' => Storage::disk('public')->path($filePath)
            ]);
            
            if ($request->acceptsHtml()) {
                return redirect()->back()->with('error', 'File not found on server.');
            }
            return response()->json(['message' => 'File not found on server.'], 404);
        }

        $fileContent = Storage::disk('public')->get($filePath);
        $mimeType = Storage::disk('public')->mimeType($filePath);
        $fileName = basename($material->file_path);
        
        $headers = [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'X-Frame-Options' => 'SAMEORIGIN',
            'Cache-Control' => 'private, max-age=3600',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Authorization, Content-Type',
            'Cross-Origin-Resource-Policy' => 'cross-origin',
        ];

        // For PDFs specifically, add these headers
        if ($mimeType === 'application/pdf') {
            $headers['Content-Disposition'] = 'inline; filename="' . $fileName . '"';
            $headers['X-Content-Type-Options'] = 'nosniff';
            $headers['Content-Security-Policy'] = "frame-ancestors 'self' https://docs.google.com";
        }

        // Log successful access
        Log::info('File accessed successfully', [
            'material_id' => $material->id,
            'user_id' => $user->id,
            'file_name' => $fileName,
            'mime_type' => $mimeType
        ]);

        return Response::make($fileContent, 200, $headers);
    }

    // public function download(Material $material, Request $request)
    // {
    //     // Use custom authentication method that supports query tokens
    //     $user = $this->authenticateUser($request);
        
    //     if (!$user) {
    //         if ($request->acceptsHtml()) {
    //             return redirect()->route('login')->with('error', 'Please login to download this material.');
    //         }
    //         return response()->json(['message' => 'Unauthenticated.'], 401);
    //     }

    //     // Get course ID from material or topic
    //     $courseId = $material->course_id ?? ($material->topic ? $material->topic->course_id : null);
        
    //     if (!$courseId) {
    //         return response()->json(['message' => 'Material course association not found.'], 404);
    //     }

    //     if (!$user->isEnrolledInCourse($courseId)) {
    //         if ($request->acceptsHtml()) {
    //             return redirect()->back()->with('error', 'You are not enrolled in this course.');
    //         }
    //         return response()->json(['message' => 'Unauthorized to download this material.'], 403);
    //     }

    //     // Check availability
    //     if ($material->available_at && now()->lessThan($material->available_at)) {
    //         if ($request->acceptsHtml()) {
    //             return redirect()->back()->with('error', 'This material is not yet available.');
    //         }
    //         return response()->json(['message' => 'Material is not yet available.'], 403);
    //     }

    //     if (empty($material->file_path)) {
    //         if ($request->acceptsHtml()) {
    //             return redirect()->back()->with('error', 'No file attached to this material.');
    //         }
    //         return response()->json(['message' => 'No file attached to this material.'], 404);
    //     }

    //     // Fix: The file_path is already relative to public disk
    //     $filePath = $material->file_path;
        
    //     // If the file_path contains a full URL, extract the relative path
    //     if (str_contains($filePath, '/storage/')) {
    //         $filePath = str_replace(url('/storage/'), '', $filePath);
    //     }
        
    //     // Ensure the path doesn't start with 'public/' since we're using the public disk
    //     if (str_starts_with($filePath, 'public/')) {
    //         $filePath = str_replace('public/', '', $filePath);
    //     }

    //     if (!Storage::disk('public')->exists($filePath)) {
    //         Log::error('Download file not found', [
    //             'material_id' => $material->id,
    //             'file_path' => $filePath
    //         ]);
            
    //         if ($request->acceptsHtml()) {
    //             return redirect()->back()->with('error', 'File not found on server.');
    //         }
    //         return response()->json(['message' => 'File not found on server.'], 404);
    //     }

    //     $fileName = $material->original_filename ?? basename($material->file_path);

    //     // Log successful download
    //     Log::info('File downloaded', [
    //         'material_id' => $material->id,
    //         'user_id' => $user->id,
    //         'file_name' => $fileName
    //     ]);

    //     return Storage::disk('public')->download($filePath, $fileName);
    // }

    public function generateViewLink(Material $material, Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        
        // Check permissions (same validation as view method)
        $courseId = $material->course_id ?? ($material->topic ? $material->topic->course_id : null);
        
        if (!$courseId) {
            return response()->json(['message' => 'Material course association not found.'], 404);
        }

        if (!$user->isEnrolledInCourse($courseId)) {
            return response()->json(['message' => 'Unauthorized: Not enrolled in this course.'], 403);
        }

        if ($material->available_at && now()->lessThan($material->available_at)) {
            return response()->json(['message' => 'Material is not yet available.'], 403);
        }

        if (empty($material->file_path)) {
            return response()->json(['message' => 'No file attached to this material.'], 404);
        }
        
        // Generate a temporary signed URL (valid for 2 hours)
        $signedUrl = URL::temporarySignedRoute(
            'materials.view.signed', 
            now()->addHours(2),
            ['material' => $material->id]
        );
        
        Log::info('Generated signed URL for material', [
            'material_id' => $material->id,
            'user_id' => $user->id,
            'signed_url' => $signedUrl
        ]);
        
        return response()->json(['url' => $signedUrl]);
    }

    public function viewSigned(Material $material, Request $request)
    {
        if (!$request->hasValidSignature()) {
            return response()->json(['message' => 'Invalid or expired link.'], 403);
        }

        if (empty($material->file_path)) {
            return response()->json(['message' => 'No file attached to this material.'], 404);
        }

        // Process file path
        $filePath = $material->file_path;
        if (str_contains($filePath, '/storage/')) {
            $filePath = str_replace(url('/storage/'), '', $filePath);
        }
        if (str_starts_with($filePath, 'public/')) {
            $filePath = str_replace('public/', '', $filePath);
        }

        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['message' => 'File not found on server.'], 404);
        }

        $fileContent = Storage::disk('public')->get($filePath);
        $mimeType = Storage::disk('public')->mimeType($filePath);
        $fileName = basename($material->file_path);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Define which files can be viewed inline in mobile browsers
        $inlineViewableTypes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg', 
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'txt' => 'text/plain',
            'html' => 'text/html',
            'htm' => 'text/html'
        ];

        // Check if this file type can be viewed inline
        $canViewInline = isset($inlineViewableTypes[$fileExtension]);
        
        // Base headers
        $headers = [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'private, max-age=3600',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Authorization, Content-Type',
            'Cross-Origin-Resource-Policy' => 'cross-origin',
        ];

        // Set appropriate Content-Disposition based on file type and request
        $forceDownload = $request->query('download', false);
        
        if ($forceDownload || !$canViewInline) {
            // Force download for unsupported types or when explicitly requested
            $headers['Content-Disposition'] = 'attachment; filename="' . $fileName . '"';
            Log::info('File served as download', [
                'material_id' => $material->id,
                'file_name' => $fileName,
                'reason' => $forceDownload ? 'explicit_download' : 'unsupported_inline_type'
            ]);
        } else {
            // Try to display inline for supported types
            $headers['Content-Disposition'] = 'inline; filename="' . $fileName . '"';
            $headers['X-Frame-Options'] = 'SAMEORIGIN';
            
            // Special handling for PDFs
            if ($fileExtension === 'pdf') {
                $headers['X-Content-Type-Options'] = 'nosniff';
                $headers['Content-Security-Policy'] = "frame-ancestors 'self' https://docs.google.com";
            }
            
            Log::info('File served inline', [
                'material_id' => $material->id,
                'file_name' => $fileName,
                'mime_type' => $mimeType
            ]);
        }

        return Response::make($fileContent, 200, $headers);
    }

    public function metadata(Material $material)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $courseId = $material->course_id ?? ($material->topic ? $material->topic->course_id : null);
        
        if (!$courseId || !$user->isEnrolledInCourse($courseId)) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if (empty($material->file_path)) {
            return response()->json(['message' => 'No file attached.'], 404);
        }

        $filePath = str_replace(url('/storage'), 'public', $material->file_path);
        
        if (!Storage::exists($filePath)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        $mimeType = Storage::mimeType($filePath);
        $fileSize = Storage::size($filePath);
        $fileName = basename($material->file_path);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Determine file category
        $fileCategory = 'other';
        if (str_starts_with($mimeType, 'image/')) {
            $fileCategory = 'image';
        } elseif ($mimeType === 'application/pdf') {
            $fileCategory = 'pdf';
        } elseif (str_starts_with($mimeType, 'video/')) {
            $fileCategory = 'video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            $fileCategory = 'audio';
        } elseif (in_array($mimeType, [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain',
            'application/rtf'
        ])) {
            $fileCategory = 'document';
        }

        return response()->json([
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'file_size_human' => $this->formatBytes($fileSize),
            'mime_type' => $mimeType,
            'file_extension' => $fileExtension,
            'file_category' => $fileCategory,
            'can_preview' => in_array($fileCategory, ['image', 'pdf', 'video', 'audio']),
            'view_url' => route('api.materials.view', $material->id),
            'download_url' => route('api.materials.download', $material->id),
        ]);
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}