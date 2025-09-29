<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StudentAssessmentsExport
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Generate the export data as array
     */
    public function getData()
    {
        $instructor = Auth::user();
        
        // Start with all students enrolled in instructor's courses
        $studentsQuery = User::where('role', 'student')
            ->whereHas('courses', function($query) use ($instructor) {
                $query->where('instructor_id', $instructor->id);
            })
            ->with([
                'submittedAssessments.assessment.course',
                'program',
                'section'
            ]);
        
        // Apply filters
        if (!empty($this->filters['course_id']) && $this->filters['course_id'] !== 'all') {
            $studentsQuery->whereHas('courses', function($query) {
                $query->where('course_id', $this->filters['course_id']);
            });
        }
        
        if (!empty($this->filters['section_id']) && $this->filters['section_id'] !== 'all') {
            $studentsQuery->where('section_id', $this->filters['section_id']);
        }
        
        if (!empty($this->filters['program_id']) && $this->filters['program_id'] !== 'all') {
            $studentsQuery->where('program_id', $this->filters['program_id']);
        }
        
        if (!empty($this->filters['performance_status']) && $this->filters['performance_status'] !== 'all') {
            // This would require additional logic based on grade calculations
        }
        
        $students = $studentsQuery->get();
        
        $data = [];
        
        // Add headers
        $data[] = [
            'Student Name',
            'Email',
            'Program',
            'Section',
            'Assessment Title',
            'Course',
            'Assessment Type',
            'Score',
            'Status',
            'Submitted At',
            'Performance Level'
        ];
        
        // Add data rows
        foreach ($students as $student) {
            $submissions = $student->submittedAssessments;
            
            if ($submissions->isEmpty()) {
                // Add student info even if no submissions
                $data[] = [
                    $student->name,
                    $student->email,
                    $student->program->name ?? 'N/A',
                    $student->section->name ?? 'No Section',
                    'No Assessments',
                    'N/A',
                    'N/A',
                    'N/A',
                    'N/A',
                    'N/A',
                    'N/A'
                ];
            } else {
                foreach ($submissions as $submission) {
                    // Calculate performance level
                    $performanceLevel = 'N/A';
                    if ($submission->score !== null) {
                        if ($submission->score >= 85) $performanceLevel = 'Excellent';
                        elseif ($submission->score >= 75) $performanceLevel = 'Good';
                        elseif ($submission->score >= 60) $performanceLevel = 'Needs Improvement';
                        else $performanceLevel = 'At Risk';
                    }
                    
                    $data[] = [
                        $student->name,
                        $student->email,
                        $student->program->name ?? 'N/A',
                        $student->section->name ?? 'No Section',
                        $submission->assessment->title ?? 'N/A',
                        $submission->assessment->course->title ?? 'N/A',
                        ucfirst($submission->assessment->type ?? 'N/A'),
                        $submission->score !== null ? $submission->score . '%' : 'Not Graded',
                        ucfirst($submission->status ?? 'N/A'),
                        $submission->submitted_at ? $submission->submitted_at->format('M d, Y h:i A') : 'N/A',
                        $performanceLevel
                    ];
                }
            }
        }
        
        return $data;
    }
}
