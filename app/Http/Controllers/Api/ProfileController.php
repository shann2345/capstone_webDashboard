<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use App\Models\Material;
use App\Models\Assessment;
use App\Models\Course;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user's profile information
     */
    public function show(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            // Load relationships
            $user->load(['program', 'section']);
            
            // Format the response
            $profileData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'bio' => $user->bio,
                'department' => $user->department,
                'title' => $user->title,
                'birth_date' => $user->birth_date,
                'gender' => $user->gender,
                'address' => $user->address,
                'profile_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
                'role' => $user->role,
                'program' => $user->program ? [
                    'id' => $user->program->id,
                    'name' => $user->program->name,
                    'code' => $user->program->code ?? null,
                ] : null,
                'section' => $user->section ? [
                    'id' => $user->section->id,
                    'name' => $user->section->name,
                ] : null,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];

            return response()->json([
                'success' => true,
                'profile' => $profileData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve profile information',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the authenticated user's profile information
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            // Validation rules
            $rules = [
                'name' => 'sometimes|required|string|max:255',
                'phone' => 'sometimes|nullable|string|max:20',
                'bio' => 'sometimes|nullable|string|max:1000',
                'department' => 'sometimes|nullable|string|max:255',
                'title' => 'sometimes|nullable|string|max:255',
                'birth_date' => 'sometimes|nullable|date',
                'gender' => 'sometimes|nullable|in:male,female,other',
                'address' => 'sometimes|nullable|string|max:500',
                'profile_image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            ];

            // Only allow students to update certain fields
            if ($user->role === 'student') {
                unset($rules['department'], $rules['title']);
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = [];

            // Handle text fields
            $textFields = ['name', 'phone', 'bio', 'department', 'title', 'birth_date', 'gender', 'address'];
            foreach ($textFields as $field) {
                if ($request->has($field)) {
                    $updateData[$field] = $request->input($field);
                }
            }

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                // Delete old profile image if exists
                if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                    Storage::disk('public')->delete($user->profile_image);
                }

                // Store new image
                $imagePath = $request->file('profile_image')->store('profile_images', 'public');
                $updateData['profile_image'] = $imagePath;
            }

            // Update user data
            $user->update($updateData);

            // Reload user with relationships for response
            $user->load(['program', 'section']);

            // Format the response
            $profileData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'bio' => $user->bio,
                'department' => $user->department,
                'title' => $user->title,
                'birth_date' => $user->birth_date,
                'gender' => $user->gender,
                'address' => $user->address,
                'profile_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
                'role' => $user->role,
                'program' => $user->program ? [
                    'id' => $user->program->id,
                    'name' => $user->program->name,
                    'code' => $user->program->code ?? null,
                ] : null,
                'section' => $user->section ? [
                    'id' => $user->section->id,
                    'name' => $user->section->name,
                ] : null,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'profile' => $profileData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete profile image
     */
    public function deleteProfileImage(): JsonResponse
    {
        try {
            $user = Auth::user();

            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
                $user->update(['profile_image' => null]);

                return response()->json([
                    'success' => true,
                    'message' => 'Profile image deleted successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No profile image to delete'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete profile image',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getNotifications(): JsonResponse
    {
        try {
            $student = Auth::user();
            
            // Use the relationship method defined in User model
            $enrolledCourses = $student->courses()->get();
            
            // Get activities from the last 30 days for notifications
            $dateRange = [
                'startDate' => Carbon::now()->subDays(30),
                'endDate' => Carbon::now()
            ];
            
            $activities = $this->getStudentActivities($enrolledCourses, $dateRange);
            
            // Get read notifications from session
            $readNotifications = session('read_notifications', []);
            
            // Add read status to each activity
            $notifications = $activities->map(function ($activity) use ($readNotifications) {
                $notificationId = md5($activity['type'] . $activity['description'] . $activity['date']);
                $activity['id'] = $notificationId;
                $activity['read'] = in_array($notificationId, $readNotifications);
                return $activity;
            });
            
            $unreadCount = $notifications->where('read', false)->count();
            
            return response()->json([
                'success' => true,
                'notifications' => $notifications->values(),
                'unread_count' => $unreadCount
            ]);
            
        } catch (\Exception $e) {
            // Add more detailed error logging
            Log::error('Student notifications error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function markNotificationAsRead(Request $request): JsonResponse
    {
        try {
            $notificationId = $request->input('notification_id');
            $readNotifications = session('read_notifications', []);
            
            if (!in_array($notificationId, $readNotifications)) {
                $readNotifications[] = $notificationId;
                session(['read_notifications' => $readNotifications]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function markAllNotificationsAsRead(Request $request): JsonResponse
    {
        try {
            $notificationIds = $request->input('notification_ids', []);
            $readNotifications = session('read_notifications', []);
            
            $readNotifications = array_unique(array_merge($readNotifications, $notificationIds));
            session(['read_notifications' => $readNotifications]);
            
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all notifications as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getStudentActivities($courses, $dateRange)
    {
        $activities = collect();
        
        $courseIds = $courses->pluck('id');
        
        if ($courseIds->isEmpty()) {
            return $activities;
        }
        
        // Add recent materials from enrolled courses
        $recentMaterials = Material::whereIn('course_id', $courseIds)
            ->with(['course']) // Ensure course relationship is loaded
            ->whereBetween('created_at', [$dateRange['startDate'], $dateRange['endDate']])
            ->where(function($query) {
                $query->whereNull('available_at')
                    ->orWhere('available_at', '<=', now());
            })
            ->where(function($query) {
                $query->whereNull('unavailable_at')
                    ->orWhere('unavailable_at', '>=', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        foreach ($recentMaterials as $material) {
            $courseName = $material->course ? $material->course->title : 'Course not found'; // Ensure correct course title
            $activities->push([
                'type' => 'material',
                'description' => "New material \"{$material->title}\" added to {$courseName}",
                'date' => $material->created_at,
                'course' => $courseName,
                'item_id' => $material->id,
                'course_id' => $material->course_id,
            ]);
        }
        
        // Add recent assessments from enrolled courses
        $recentAssessments = Assessment::whereIn('course_id', $courseIds)
            ->with(['course']) // Ensure course relationship is loaded
            ->whereBetween('created_at', [$dateRange['startDate'], $dateRange['endDate']])
            ->where('available_at', '<=', now())
            ->where(function($query) {
                $query->whereNull('unavailable_at')
                    ->orWhere('unavailable_at', '>=', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        foreach ($recentAssessments as $assessment) {
            $courseName = $assessment->course ? $assessment->course->title : 'Course not found'; // Ensure correct course title
            $description = "New assessment \"{$assessment->title}\" is now available";
            
            if ($assessment->unavailable_at) {
                $unavailableAt = Carbon::parse($assessment->unavailable_at);
                $now = Carbon::now();
                if ($unavailableAt > $now) {
                    $daysLeft = $unavailableAt->diffInDays($now);
                    if ($daysLeft == 0) {
                        $description .= " (Due today)";
                    } elseif ($daysLeft == 1) {
                        $description .= " (Due tomorrow)";
                    } else {
                        $description .= " (Due in {$daysLeft} days)";
                    }
                }
            }
            $description .= " in {$courseName}";
            
            $activities->push([
                'type' => 'assessment',
                'description' => $description,
                'date' => $assessment->created_at,
                'course' => $courseName,
                'item_id' => $assessment->id,
                'course_id' => $assessment->course_id,
            ]);
        }
        
        return $activities->sortByDesc('date')->take(20);
    }
}