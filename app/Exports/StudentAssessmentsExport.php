<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Assessment;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class StudentAssessmentsExport
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Generate the export data as array with assessments as columns and students as rows
     * Format: Student Name | Email | Program | Section | Quiz: Midterm | Total Points | Quiz: Final | Total Points | Quiz Average | Overall Average | Performance Level
     */
    public function getData()
    {
        $instructor = Auth::user();
        
        // Get courses based on filters
        $coursesQuery = Course::where('instructor_id', $instructor->id);
        
        if (!empty($this->filters['course_id']) && $this->filters['course_id'] !== 'all') {
            $coursesQuery->where('id', $this->filters['course_id']);
        }
        
        $courses = $coursesQuery->with(['assessments' => function($query) {
            $query->with('questions')->orderBy('type')->orderBy('title');
        }])->get();
        
        // Get all assessments from selected courses
        $assessments = $courses->flatMap(function($course) {
            return $course->assessments->map(function($assessment) use ($course) {
                $assessment->course_name = $course->title;
                return $assessment;
            });
        })->sortBy(function($assessment) {
            // Sort by type (quiz, exam, assignment, etc.) then by title
            return $assessment->type . '_' . $assessment->title;
        });
        
        // Get students enrolled in these courses
        $studentsQuery = User::where('role', 'student')
            ->whereHas('courses', function($query) use ($courses) {
                $query->whereIn('course_id', $courses->pluck('id'));
            })
            ->with([
                'submittedAssessments' => function($query) use ($assessments) {
                    $query->whereIn('assessment_id', $assessments->pluck('id'));
                },
                'program',
                'section'
            ]);
        
        // Apply additional filters
        if (!empty($this->filters['section_id']) && $this->filters['section_id'] !== 'all') {
            $studentsQuery->where('section_id', $this->filters['section_id']);
        }
        
        if (!empty($this->filters['program_id']) && $this->filters['program_id'] !== 'all') {
            $studentsQuery->where('program_id', $this->filters['program_id']);
        }
        
        $students = $studentsQuery->orderBy('name')->get();
        
        $data = [];
        
        // Create headers row
        $headers = [
            'Student Name',
            'Email', 
            'Program',
            'Section'
        ];
        
        // Group assessments by type for better organization
        $assessmentsByType = $assessments->groupBy('type');
        
        // Add assessment columns grouped by type
        foreach ($assessmentsByType as $type => $typeAssessments) {
            foreach ($typeAssessments as $assessment) {
                $headers[] = ucfirst($type) . ': ' . $assessment->title;
                $headers[] = 'Total Points';
            }
            
            // Add type totals
            if ($typeAssessments->count() > 1) {
                $headers[] = ucfirst($type) . ' Average';
            }
        }
        
        // Add overall statistics
        $headers[] = 'Overall Average';
        $headers[] = 'Performance Level';
        
        $data[] = $headers;
        
        // Add data rows for each student
        foreach ($students as $student) {
            $row = [
                $student->name,
                $student->email,
                $student->program->name ?? 'N/A',
                $student->section->name ?? 'No Section'
            ];
            
            // Create lookup for student's submissions
            $submissions = $student->submittedAssessments->keyBy('assessment_id');
            
            $typeScores = []; // Track scores by assessment type for averages
            
            // Add scores for each assessment grouped by type
            foreach ($assessmentsByType as $type => $typeAssessments) {
                $typeScoresList = [];
                
                foreach ($typeAssessments as $assessment) {
                    $submission = $submissions->get($assessment->id);
                    
                    if ($submission && $submission->score !== null) {
                        $score = round($submission->score, 1);
                        $row[] = $score . '%';
                        $typeScoresList[] = $score;
                    } else {
                        $row[] = $submission ? 'Pending' : 'Not Submitted';
                    }
                    
                    // Add total possible points for this assessment
                    $totalPoints = $this->calculateAssessmentTotalPoints($assessment);
                    $row[] = $totalPoints;
                }
                
                // Add type average if more than one assessment of this type
                if ($typeAssessments->count() > 1) {
                    if (!empty($typeScoresList)) {
                        $typeAverage = array_sum($typeScoresList) / count($typeScoresList);
                        $row[] = round($typeAverage, 1) . '%';
                        $typeScores[$type] = $typeAverage;
                    } else {
                        $row[] = 'N/A';
                    }
                } else {
                    // If only one assessment of this type, use its score for type average
                    if (!empty($typeScoresList)) {
                        $typeScores[$type] = $typeScoresList[0];
                    }
                }
            }
            
            // Calculate overall average
            if (!empty($typeScores)) {
                $overallAverage = array_sum($typeScores) / count($typeScores);
                $row[] = round($overallAverage, 1) . '%';
                
                // Determine performance level
                if ($overallAverage >= 90) $performanceLevel = 'Outstanding';
                elseif ($overallAverage >= 85) $performanceLevel = 'Excellent';
                elseif ($overallAverage >= 75) $performanceLevel = 'Good';
                elseif ($overallAverage >= 60) $performanceLevel = 'Satisfactory';
                else $performanceLevel = 'Needs Improvement';
                
                $row[] = $performanceLevel;
            } else {
                $row[] = 'N/A';
                $row[] = 'No Data';
            }
            
            $data[] = $row;
        }
        
        return $data;
    }

    /**
     * Calculate total points for an assessment
     * 
     * For quizzes/exams:
     * - If total_points is explicitly set, use it (instructor override)
     * - Otherwise, auto-calculate from sum of question points
     * - Default to 100 if no questions or zero points
     * 
     * For assignments/activities/projects:
     * - Use the total_points field (required when creating assignments)
     * - Default to 100 if somehow missing
     */
    private function calculateAssessmentTotalPoints($assessment)
    {
        // Check if total_points is explicitly set
        if (!is_null($assessment->total_points) && $assessment->total_points > 0) {
            return $assessment->total_points;
        }
        
        // For quiz/exam assessments, calculate from questions if no override
        if (in_array(strtolower($assessment->type), ['quiz', 'exam'])) {
            $totalPoints = $assessment->questions->sum('points');
            return $totalPoints > 0 ? $totalPoints : 100; // Default to 100 if no questions or zero points
        }
        
        // For assignments, activities, projects - should have total_points set, but provide fallback
        return $assessment->total_points ?? 100;
    }
}
