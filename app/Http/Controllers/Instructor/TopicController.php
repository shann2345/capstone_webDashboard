<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TopicController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'name' => 'required|string|max:255',
        ]);

        $topic = Topic::create([
            'course_id' => $request->course_id,
            'name' => $request->name,
        ]);

        return response()->json($topic);
    }

    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $topic->update([
            'name' => $request->input('name'),
        ]);

        return response()->json($topic);
    }

    public function destroy(Topic $topic)
    {
        // Check if the topic belongs to a course owned by the authenticated instructor
        $course = $topic->course;
        if ($course->instructor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try {
            // Delete all materials associated with this topic
            foreach ($topic->materials as $material) {
                // Delete the material file if it exists and is not a link
                if ($material->material_type !== 'link' && $material->file_path && Storage::disk('public')->exists($material->file_path)) {
                    Storage::disk('public')->delete($material->file_path);
                }
                $material->delete();
            }

            // Delete all assessments associated with this topic
            foreach ($topic->assessments as $assessment) {
                // Delete the assessment file if it exists
                if ($assessment->assessment_file_path && Storage::disk('public')->exists($assessment->assessment_file_path)) {
                    Storage::disk('public')->delete($assessment->assessment_file_path);
                }

                // Delete related questions and their options
                foreach ($assessment->questions as $question) {
                    $question->options()->delete(); // Delete question options
                    $question->delete(); // Delete the question
                }

                // Delete related submitted assessments and their questions
                foreach ($assessment->submittedAssessments as $submittedAssessment) {
                    foreach ($submittedAssessment->submittedQuestions as $submittedQuestion) {
                        $submittedQuestion->submittedOptions()->delete(); // Delete submitted question options
                        $submittedQuestion->delete(); // Delete submitted question
                    }
                    $submittedAssessment->delete(); // Delete submitted assessment
                }

                $assessment->delete(); // Delete the assessment
            }

            // Finally, delete the topic itself
            $topic->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Topic and all its content deleted successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Topic deletion failed: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return response()->json(['success' => false, 'message' => 'Failed to delete topic: ' . $e->getMessage()], 500);
        }
    }
}
