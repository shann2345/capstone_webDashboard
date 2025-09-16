<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Assessment;
use App\Models\SubmittedAssessment;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // Get dashboard statistics
        $stats = $this->getDashboardStats();
        $recentActivities = $this->getRecentActivities();
        $systemHealth = $this->getSystemHealth();

        return view('admin.dashboard', compact('stats', 'recentActivities', 'systemHealth'));
    }

    private function getDashboardStats()
    {
        return [
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'total_instructors' => User::where('role', 'instructor')->count(),
            'total_students' => User::where('role', 'student')->count(),
            'active_courses' => Course::where('status', 'active')->count(),
            'total_assessments' => Assessment::count(),
            'submitted_assessments' => SubmittedAssessment::count(),
            'recent_registrations' => User::where('created_at', '>=', Carbon::now()->subDays(7))->count(),
        ];
    }

    private function getRecentActivities()
    {
        $activities = [];

        // Recent user registrations
        $recentUsers = User::orderBy('created_at', 'desc')->limit(3)->get();
        foreach ($recentUsers as $user) {
            $activities[] = [
                'type' => 'user_registration',
                'description' => "New {$user->role} '{$user->name}' registered",
                'time' => $user->created_at,
                'icon' => 'fas fa-user-plus',
            ];
        }

        // Recent course creations
        $recentCourses = Course::with('instructor')->orderBy('created_at', 'desc')->limit(3)->get();
        foreach ($recentCourses as $course) {
            $activities[] = [
                'type' => 'course_creation',
                'description' => "Course '{$course->title}' created by {$course->instructor->name}",
                'time' => $course->created_at,
                'icon' => 'fas fa-book',
            ];
        }

        // Recent assessment submissions
        $recentSubmissions = SubmittedAssessment::with(['student', 'assessment'])
            ->orderBy('created_at', 'desc')->limit(3)->get();
        foreach ($recentSubmissions as $submission) {
            $activities[] = [
                'type' => 'assessment_submission',
                'description' => "{$submission->student->name} submitted '{$submission->assessment->title}'",
                'time' => $submission->created_at,
                'icon' => 'fas fa-file-alt',
            ];
        }

        // Sort all activities by time (most recent first)
        usort($activities, function($a, $b) {
            return $b['time']->timestamp - $a['time']->timestamp;
        });

        return array_slice($activities, 0, 5); // Return top 5 activities
    }

    private function getSystemHealth()
    {
        // Calculate storage usage
        $totalStorage = 20 * 1024 * 1024 * 1024; // 20 GB in bytes
        $usedStorage = $this->calculateUsedStorage();
        $storagePercentage = ($usedStorage / $totalStorage) * 100;

        return [
            'storage_used' => $this->formatBytes($usedStorage),
            'storage_total' => $this->formatBytes($totalStorage),
            'storage_percentage' => round($storagePercentage, 1),
            'server_uptime' => $this->getServerUptime(),
            'last_backup' => $this->getLastBackupTime(),
        ];
    }

    private function calculateUsedStorage()
    {
        try {
            $publicSize = $this->getDirectorySize(public_path());
            $storageSize = $this->getDirectorySize(storage_path());
            return $publicSize + $storageSize;
        } catch (\Exception $e) {
            return 15.2 * 1024 * 1024 * 1024; // Default to 15.2 GB if calculation fails
        }
    }

    private function getDirectorySize($directory)
    {
        $size = 0;
        if (is_dir($directory)) {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory)) as $file) {
                $size += $file->getSize();
            }
        }
        return $size;
    }

    private function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        return round($size, $precision) . ' ' . $units[$i];
    }

    private function getServerUptime()
    {
        if (function_exists('sys_getloadavg')) {
            return '99.9%'; // Placeholder for actual uptime calculation
        }
        return 'N/A';
    }

    private function getLastBackupTime()
    {
        // This would typically check your backup system
        return Carbon::now()->subHours(2); // Placeholder: 2 hours ago
    }
}