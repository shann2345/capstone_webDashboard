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
                $assessment->course_id = $course->id;
                return $assessment;
            });
        })->sortBy([
            ['course_name', 'asc'],  // Sort by course name first
            ['type', 'asc'],         // Then by assessment type
            ['title', 'asc']         // Finally by assessment title
        ]);
        
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
        
        // Create two header rows for better organization
        $courseHeaders = [
            'Student Info',
            '',
            '',
            ''
        ];
        
        $assessmentHeaders = [
            'Student Name',
            'Email', 
            'Program',
            'Section'
        ];
        
        // Group assessments by course first, then by type
        $assessmentsByCourse = $assessments->groupBy('course_name');
        
        // Add course and assessment columns
        foreach ($assessmentsByCourse as $courseName => $courseAssessments) {
            $assessmentsByType = $courseAssessments->groupBy('type');
            
            // Count total columns for this course (including type averages and course overall)
            $courseColumnCount = 0;
            foreach ($assessmentsByType as $type => $typeAssessments) {
                $courseColumnCount += $typeAssessments->count() * 2; // Each assessment has score + total points
                if ($typeAssessments->count() > 1) {
                    $courseColumnCount += 1; // Type average column
                }
            }
            $courseColumnCount += 2; // Add 2 for course overall average and performance
            
            // Add course name spanning across all its assessment columns
            $courseHeaders[] = $courseName;
            for ($i = 1; $i < $courseColumnCount; $i++) {
                $courseHeaders[] = ''; // Empty cells for course name spanning
            }
            
            // Add assessment columns for this course
            foreach ($assessmentsByType as $type => $typeAssessments) {
                foreach ($typeAssessments as $assessment) {
                    $assessmentHeaders[] = ucfirst($type) . ': ' . $assessment->title;
                    $assessmentHeaders[] = 'Total Points';
                }
                
                // Add type average if more than one assessment of this type
                if ($typeAssessments->count() > 1) {
                    $assessmentHeaders[] = ucfirst($type) . ' Average';
                }
            }
            
            // Add course-level overall statistics
            $assessmentHeaders[] = 'Course Average';
            $assessmentHeaders[] = 'Course Performance';
        }
        
        // Add overall statistics only if not exporting all courses
        $isExportingAllCourses = empty($this->filters['course_id']) || $this->filters['course_id'] === 'all';
        if (!$isExportingAllCourses) {
            $courseHeaders[] = 'Overall Stats';
            $courseHeaders[] = '';
            $assessmentHeaders[] = 'Overall Average';
            $assessmentHeaders[] = 'Performance Level';
        }
        
        // Add both header rows to data
        $data[] = $courseHeaders;
        $data[] = $assessmentHeaders;
        
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
            
            $allTypeScores = []; // Track all percentage scores for overall average
            
            // Process assessments grouped by course
            foreach ($assessmentsByCourse as $courseName => $courseAssessments) {
                $assessmentsByType = $courseAssessments->groupBy('type');
                $courseScores = []; // Track scores for this specific course
                
                // Add scores for each assessment type within this course
                foreach ($assessmentsByType as $type => $typeAssessments) {
                    $typeScoresList = [];
                    
                    foreach ($typeAssessments as $assessment) {
                        $submission = $submissions->get($assessment->id);
                        
                        if ($submission && $submission->score !== null) {
                            $score = round($submission->score, 1);
                            $row[] = $score;
                            
                            // Convert raw score to percentage for average calculations
                            $totalPoints = $this->calculateAssessmentTotalPoints($assessment);
                            if ($totalPoints > 0) {
                                $percentageScore = ($score / $totalPoints) * 100;
                                $typeScoresList[] = $percentageScore;
                                $courseScores[] = $percentageScore;
                                $allTypeScores[] = $percentageScore;
                            }
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
                        } else {
                            $row[] = 'N/A';
                        }
                    }
                }
                
                // Calculate and add course-level average and performance
                if (!empty($courseScores)) {
                    $courseAverage = array_sum($courseScores) / count($courseScores);
                    $row[] = round($courseAverage, 1) . '%';
                    
                    // Determine course performance level
                    if ($courseAverage >= 90) $coursePerformance = 'Outstanding';
                    elseif ($courseAverage >= 85) $coursePerformance = 'Excellent';
                    elseif ($courseAverage >= 75) $coursePerformance = 'Good';
                    elseif ($courseAverage >= 60) $coursePerformance = 'Satisfactory';
                    else $coursePerformance = 'Needs Improvement';
                    
                    $row[] = $coursePerformance;
                } else {
                    $row[] = 'N/A';
                    $row[] = 'No Data';
                }
            }
            
            // Add overall statistics only if not exporting all courses
            $isExportingAllCourses = empty($this->filters['course_id']) || $this->filters['course_id'] === 'all';
            if (!$isExportingAllCourses) {
                // Calculate overall average across all courses and assessment types
                if (!empty($allTypeScores)) {
                    $overallAverage = array_sum($allTypeScores) / count($allTypeScores);
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
