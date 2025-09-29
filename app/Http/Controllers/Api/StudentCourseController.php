<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Program;
use App\Models\Topic;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StudentCourseController extends Controller
{
    public function show(Course $course)
    {
        // Eager load relationships for topics and their nested materials/assessments
        // Ensure 'available_at' is loaded if it's a column on Material/Assessment models
        $course->load([
            'program',
            'instructor',
            'topics' => function ($query) {
                $query->with(['materials', 'assessments'])->orderBy('created_at', 'asc'); // Sort topics by creation date
            },
            // Load independent assessments (not tied to any specific topic)
            'assessments' => function ($query) use ($course) {
                $query->whereNull('topic_id') // Assuming independent assessments have topic_id as NULL
                      ->where('course_id', $course->id) // Ensure they belong to this course
                      ->orderBy('created_at', 'asc'); // Sort independent assessments by creation date
            }
        ]);

        $combinedItems = collect();

        // Add topics to the combined collection
        foreach ($course->topics as $topic) {
            $combinedItems->push((object)[
                'id' => $topic->id,
                'title' => $topic->name,
                'description' => $topic->description,
                'type' => 'topic',
                'created_at' => $topic->created_at,
                // Include materials and assessments nested within the topic for frontend to render
                'materials' => $topic->materials->map(function($material) {
                    return (object)[
                        'id' => $material->id,
                        'title' => $material->title,
                        'file_path' => $material->file_path,
                        'content' => $material->content,
                        'type' => 'material',
                        'created_at' => $material->created_at,
                        'available_at' => $material->available_at, // <--- ADDED THIS LINE
                        'isNested' => true, // Mark as nested for frontend styling
                    ];
                })->sortBy('created_at')->values()->all(), // Sort nested materials
                'assessments' => $topic->assessments->map(function($assessment) {
                    return (object)[
                        'id' => $assessment->id,
                        'title' => $assessment->title,
                        'type' => 'assessment',
                        'created_at' => $assessment->created_at,
                        'access_code' => $assessment->access_code,
                        'available_at' => $assessment->available_at, // <--- ADDED THIS LINE
                        'isNested' => true, // Mark as nested for frontend styling
                    ];
                })->sortBy('created_at')->values()->all(), // Sort nested assessments
            ]);
        }

        // Add independent assessments to the combined collection
        foreach ($course->assessments as $assessment) {
            $combinedItems->push((object)[
                'id' => $assessment->id,
                'title' => $assessment->title,
                'type' => 'assessment',
                'created_at' => $assessment->created_at,
                'access_code' => $assessment->access_code,
                'available_at' => $assessment->available_at, // <--- ADDED THIS LINE
                'isNested' => false, // Mark as not nested
            ]);
        }

        // Add independent materials if they exist (assuming similar structure to independent assessments)
        $independentMaterials = Material::whereNull('topic_id')
                                      ->where('course_id', $course->id)
                                      ->orderBy('created_at', 'asc')
                                      ->get();

        foreach ($independentMaterials as $material) {
            $combinedItems->push((object)[
                'id' => $material->id,
                'title' => $material->title,
                'file_path' => $material->file_path,
                'content' => $material->content,
                'type' => 'material',
                'created_at' => $material->created_at,
                'available_at' => $material->available_at, // <--- ADDED THIS LINE
                'isNested' => false, // Mark as not nested
            ]);
        }

        // Sort the entire combined collection by created_at
        $sortedCombinedItems = $combinedItems->sortBy('created_at')->values()->all();

        // Return the course details along with the sorted combined items
        return response()->json([
            'course' => array_merge($course->toArray(), ['sorted_content' => $sortedCombinedItems])
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json(['courses' => []]);
        }

        $courses = Course::where('status', 'published') // Only show published courses
                         ->where(function($q) use ($query) {
                             $q->where('title', 'LIKE', '%' . $query . '%')
                               ->orWhereRaw('BINARY course_code = ?', [$query]);
                         })
                         ->with(['program', 'instructor'])
                         ->limit(10)
                         ->get();

        return response()->json(['courses' => $courses]);
    }
}