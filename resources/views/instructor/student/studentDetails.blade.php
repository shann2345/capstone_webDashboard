<x-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div class="min-h-screen bg-gray-50">
        {{-- Header Section --}}
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Student Details & Assessment Tracking</h1>
                        <p class="text-gray-600 mt-1">
                            @if($selectedStudent)
                                Detailed view for {{ $selectedStudent->name }}
                            @else
                                Search and view detailed progress for individual students
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('instructor.studentProgress') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back to Progress Overview
                        </a>
                        @if($selectedStudent)
                            <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Send Message
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{-- Search & Filter Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                <form method="GET" action="{{ route('instructor.studentDetails') }}">
                    <div class="flex flex-wrap items-center gap-4 mb-4">
                        {{-- Search Bar --}}
                        <div class="flex-1 min-w-64">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ $searchTerm ?? '' }}" placeholder="Search students by name, email, or ID..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            </div>
                        </div>

                        {{-- Course Filter --}}
                        <div>
                            <select name="course_id" class="block w-48 pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="all" {{ request('course_id') == 'all' || !request('course_id') ? 'selected' : '' }}>All Courses</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Performance Status Filter --}}
                        <div>
                            <select name="status" class="block w-48 pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>All Students</option>
                                <option value="excellent" {{ request('status') == 'excellent' ? 'selected' : '' }}>Excellent (85%+)</option>
                                <option value="good" {{ request('status') == 'good' ? 'selected' : '' }}>Good (75-84%)</option>
                                <option value="needs-improvement" {{ request('status') == 'needs-improvement' ? 'selected' : '' }}>Needs Improvement (60-74%)</option>
                                <option value="at-risk" {{ request('status') == 'at-risk' ? 'selected' : '' }}>At Risk (<60%)</option>
                            </select>
                        </div>

                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Search
                        </button>
                    </div>
                </form>

                {{-- Students Search Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Courses Enrolled
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Average Grade
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Performance Status
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($allStudents as $student)
                                <tr class="hover:bg-gray-50 {{ $selectedStudent && $selectedStudent->id == $student->id ? 'bg-blue-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-blue-600">
                                                        {{ strtoupper(substr($student->name, 0, 2)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $student->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">{{ $student->enrolled_courses_count }} Courses</div>
                                        @if($student->section)
                                            <div class="text-xs text-gray-500 mt-1">Section: {{ $student->section->name }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $student->average_grade }}%</div>
                                        <div class="w-16 bg-gray-200 rounded-full h-1.5 mt-1">
                                            @php
                                                $gradeColor = $student->average_grade >= 85 ? 'bg-green-500' : ($student->average_grade >= 75 ? 'bg-yellow-500' : 'bg-red-500');
                                            @endphp
                                            <div class="{{ $gradeColor }} h-1.5 rounded-full" style="width: {{ $student->average_grade }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'excellent' => 'bg-green-100 text-green-800',
                                                'good' => 'bg-blue-100 text-blue-800',
                                                'needs-improvement' => 'bg-yellow-100 text-yellow-800',
                                                'at-risk' => 'bg-red-100 text-red-800'
                                            ];
                                            $statusLabels = [
                                                'excellent' => 'Excellent',
                                                'good' => 'Good',
                                                'needs-improvement' => 'Needs Improvement',
                                                'at-risk' => 'At Risk'
                                            ];
                                            $statusColor = $statusColors[$student->performance_status] ?? 'bg-gray-100 text-gray-800';
                                            $statusLabel = $statusLabels[$student->performance_status] ?? 'Unknown';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('instructor.studentDetails', $student->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">No students found</h3>
                                            <p class="mt-1 text-sm text-gray-500">No students match your search criteria.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($selectedStudent)
                {{-- Student Details Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 h-16 w-16">
                                    <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-xl font-medium text-blue-600">
                                            {{ strtoupper(substr($selectedStudent->name, 0, 2)) }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900">{{ $selectedStudent->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $selectedStudent->email }}</p>
                                    <div class="flex items-center space-x-4 mt-1">
                                        <span class="text-xs text-gray-500">ID: {{ $selectedStudent->id }}</span>
                                        @if($selectedStudent->program)
                                            <span class="text-xs text-gray-500">Program: {{ $selectedStudent->program->name }}</span>
                                        @endif
                                        @if($selectedStudent->section)
                                            <span class="text-xs text-gray-500">Section: {{ $selectedStudent->section->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($studentProgress)
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-gray-900">{{ $studentProgress->average_grade }}%</div>
                                    <div class="text-sm text-gray-500">Overall Average</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Tabs Section --}}
                    <div class="px-6 py-4">
                        <div class="flex space-x-8 border-b border-gray-200 mb-6">
                            <button id="progress-tab" class="tab-button text-blue-600 font-medium border-b-2 border-blue-600 pb-2">
                                Progress Overview
                            </button>
                            <button id="assessments-tab" class="tab-button text-gray-600 font-medium hover:text-blue-600 pb-2">
                                Assessments & Submissions
                            </button>
                        </div>

                        {{-- Progress Tab Content --}}
                        <div id="progress-content" class="tab-content">
                            @if($studentProgress)
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    {{-- Performance Metrics --}}
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Performance Metrics</h4>
                                        <div class="space-y-4">
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <div class="flex justify-between items-center mb-2">
                                                    <span class="text-sm font-medium text-gray-700">Courses Enrolled</span>
                                                    <span class="text-lg font-bold text-gray-900">{{ $studentProgress->enrolled_courses_count }}</span>
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <div class="flex justify-between items-center mb-2">
                                                    <span class="text-sm font-medium text-gray-700">Assessment Completion</span>
                                                    <span class="text-lg font-bold text-gray-900">{{ round($studentProgress->completion_rate, 1) }}%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $studentProgress->completion_rate }}%"></div>
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <div class="flex justify-between items-center mb-2">
                                                    <span class="text-sm font-medium text-gray-700">Performance Status</span>
                                                    @php
                                                        $statusColors = [
                                                            'excellent' => 'bg-green-100 text-green-800',
                                                            'good' => 'bg-blue-100 text-blue-800',
                                                            'needs-improvement' => 'bg-yellow-100 text-yellow-800',
                                                            'at-risk' => 'bg-red-100 text-red-800'
                                                        ];
                                                        $statusLabels = [
                                                            'excellent' => 'Excellent',
                                                            'good' => 'Good',
                                                            'needs-improvement' => 'Needs Improvement',
                                                            'at-risk' => 'At Risk'
                                                        ];
                                                        $statusColor = $statusColors[$studentProgress->performance_status] ?? 'bg-gray-100 text-gray-800';
                                                        $statusLabel = $statusLabels[$studentProgress->performance_status] ?? 'Unknown';
                                                    @endphp
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                                                        {{ $statusLabel }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Course Progress --}}
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Course Progress</h4>
                                        <div class="space-y-4">
                                            @foreach($studentCourses as $course)
                                                @php
                                                    $studentEnrollment = $course->students->where('id', $selectedStudent->id)->first();
                                                    $courseGrade = $studentEnrollment && $studentEnrollment->pivot->grade ? (float) $studentEnrollment->pivot->grade : 0;
                                                @endphp
                                                <div class="border border-gray-200 rounded-lg p-4">
                                                    <div class="flex justify-between items-start mb-2">
                                                        <div>
                                                            <h5 class="font-medium text-gray-900">{{ $course->title }}</h5>
                                                            <p class="text-sm text-gray-500">{{ $course->course_code }}</p>
                                                        </div>
                                                        <span class="text-lg font-bold text-gray-900">{{ $courseGrade }}%</span>
                                                    </div>
                                                    <div class="mt-3">
                                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                                            @php
                                                                $gradeColor = $courseGrade >= 85 ? 'bg-green-500' : ($courseGrade >= 75 ? 'bg-yellow-500' : 'bg-red-500');
                                                            @endphp
                                                            <div class="{{ $gradeColor }} h-2 rounded-full" style="width: {{ $courseGrade }}%"></div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 text-xs text-gray-500">
                                                        {{ $course->assessments->count() }} assessments available
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Assessments Tab Content --}}
                        <div id="assessments-content" class="tab-content hidden">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Assessment History</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Assessment
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Course
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Type
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Submitted Date
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Score
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($studentAssessments as $assessment)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $assessment->title }}</div>
                                                    <div class="text-sm text-gray-500">{{ Str::limit($assessment->description, 50) }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $assessment->course_info->title }}</div>
                                                    <div class="text-sm text-gray-500">{{ $assessment->course_info->course_code }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ ucfirst($assessment->type) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        @if($assessment->submitted_at)
                                                            {{ \Carbon\Carbon::parse($assessment->submitted_at)->format('M d, Y g:i A') }}
                                                        @else
                                                            Not submitted
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($assessment->student_submitted)
                                                        @php
                                                            $assessmentType = strtolower($assessment->type);
                                                            $statusText = 'Submitted';
                                                            $statusColor = 'bg-green-100 text-green-800';
                                                            
                                                            if (in_array($assessmentType, ['quiz', 'exam'])) {
                                                                $statusText = 'Completed';
                                                                $statusColor = 'bg-blue-100 text-blue-800';
                                                            } elseif (in_array($assessmentType, ['assignment', 'activity', 'project'])) {
                                                                $statusText = 'Submitted';
                                                                $statusColor = 'bg-green-100 text-green-800';
                                                            }
                                                            
                                                            // If graded, override status
                                                            if ($assessment->submission_status === 'graded') {
                                                                $statusText = 'Graded';
                                                                $statusColor = 'bg-purple-100 text-purple-800';
                                                            }
                                                        @endphp
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                                            {{ $statusText }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Pending
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        @if($assessment->student_score !== null)
                                                            {{ $assessment->student_score }}%
                                                        @else
                                                            --
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    @if($assessment->student_submitted)
                                                        @php
                                                            // Clean the assessment object for safe JSON encoding
                                                            $cleanAssessment = [
                                                                'id' => $assessment->id,
                                                                'title' => $assessment->title,
                                                                'type' => $assessment->type,
                                                                'unavailable_at' => $assessment->unavailable_at,
                                                                'student_score' => $assessment->student_score,
                                                                'student_submitted' => $assessment->student_submitted,
                                                                'submission_id' => $assessment->submission_id,
                                                                'submitted_at' => $assessment->submitted_at,
                                                                'submitted_file' => $assessment->submitted_file,
                                                                'submission_status' => $assessment->submission_status,
                                                                'course_info' => [
                                                                    'id' => $assessment->course_info->id,
                                                                    'title' => $assessment->course_info->title
                                                                ]
                                                            ];
                                                        @endphp
                                                        <button onclick='openAssessmentModal(@json($cleanAssessment))' class="text-blue-600 hover:text-blue-900 font-medium mr-2">
                                                            Review Submission
                                                        </button>
                                                    @else
                                                        <span class="text-gray-400 text-sm">Not Submitted</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-6 py-12 text-center">
                                                    <div class="text-gray-500">
                                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No assessments found</h3>
                                                        <p class="mt-1 text-sm text-gray-500">This student has no assessments in your courses.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{-- No Student Selected --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Select a student to view details</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Search for a student using the table above and click "View Details" to see their progress and assessment history.
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- Assessment Review Modal --}}
    <div id="assessmentModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center p-4 hidden z-50">
        <div class="relative w-full max-w-6xl max-h-[90vh] bg-white rounded-2xl shadow-2xl overflow-hidden">
            {{-- Modal Header --}}
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 id="modalTitle" class="text-xl font-bold text-gray-900">Assessment Review</h3>
                        <p id="modalSubtitle" class="text-sm text-gray-600 mt-1">Review student submission</p>
                    </div>
                    <button onclick="closeAssessmentModal()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full p-2 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Scrollable Modal Content --}}
            <div class="overflow-y-auto max-h-[calc(90vh-200px)] p-6">
                {{-- Enhanced Four-Tab Navigation --}}
                <div class="border-b border-gray-200 bg-gray-50 -mx-6 mb-6">
                    <nav class="flex space-x-8 px-6" id="modalTabNavigation">
                        <button data-tab="info" onclick="showModalTab('info')" class="border-blue-500 text-blue-600 bg-blue-50 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Assessment Info</span>
                        </button>
                        <button data-tab="quiz" onclick="showModalTab('quiz')" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Quiz Questions</span>
                        </button>
                        <button data-tab="assignment" onclick="showModalTab('assignment')" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Assignment Files</span>
                        </button>
                        <button data-tab="grading" onclick="showModalTab('grading')" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            <span>Grading & Feedback</span>
                        </button>
                    </nav>
                </div>

                {{-- Tab 1: Assessment Info --}}
                <div id="infoTab" class="modal-tab-content">
                    {{-- Assessment Info --}}
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Assessment Type</label>
                                <p id="modalAssessmentType" class="text-base font-medium text-gray-900">-</p>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Submitted Date</label>
                                <p id="modalDueDate" class="text-base font-medium text-gray-900">-</p>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Current Score</label>
                                <p id="modalCurrentScore" class="text-base font-medium text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tab 2: Quiz Questions --}}
                <div id="quizTab" class="modal-tab-content hidden">
                    <div id="quizSubmission" class="space-y-6">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
                            <div class="flex items-center space-x-4">
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-blue-900">Quiz Questions & Student Answers</h4>
                                    <p class="text-sm text-blue-700">Review student responses and manual grading options</p>
                                </div>
                            </div>
                        </div>

                        {{-- Quiz Questions Container --}}
                        <div id="quizQuestions" class="space-y-6">
                            {{-- Questions will be dynamically loaded here --}}
                        </div>
                    </div>
                </div>

                {{-- Tab 3: Assignment Files --}}
                <div id="assignmentTab" class="modal-tab-content hidden">
                    <div id="assignmentSubmission" class="space-y-6">
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                                <h4 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span>Submitted Files</span>
                                </h4>
                            </div>
                            <div id="submittedFileInfo" class="p-6">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-blue-100 p-4 rounded-xl">
                                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p id="fileName" class="text-lg font-semibold text-gray-900">filename.pdf</p>
                                        <p id="fileSize" class="text-sm text-gray-500">2.4 MB</p>
                                    </div>
                                    <button id="downloadBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span>Download</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Submission Date & Time</label>
                            <p id="submissionDate" class="text-base text-gray-900 font-medium">-</p>
                        </div>
                    </div>
                </div>

                {{-- Tab 4: Grading & Feedback --}}
                <div id="gradingTab" class="modal-tab-content hidden">
                    <form id="gradingForm" class="space-y-8">
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <label for="newScore" class="block text-sm font-semibold text-gray-700 mb-3">Score (%)</label>
                            <input type="number" id="newScore" name="score" min="0" max="100" step="0.1" 
                                   class="block w-full px-4 py-3 text-lg border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <label for="feedback" class="block text-sm font-semibold text-gray-700 mb-3">Instructor Feedback</label>
                            <textarea id="feedback" name="feedback" rows="6" 
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                      placeholder="Provide detailed feedback to help the student improve..."></textarea>
                        </div>

                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" id="returnToStudent" name="return_to_student" 
                                       class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="returnToStudent" class="text-sm font-medium text-gray-700">Return graded assessment to student with feedback</label>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            
            {{-- Sticky Modal Footer --}}
            <div class="sticky bottom-0 bg-white border-t border-gray-200 px-6 py-4 flex items-center justify-end space-x-3">
                <button onclick="closeAssessmentModal()" class="px-6 py-3 border border-gray-300 text-sm font-semibold rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                    Close
                </button>
                <button id="saveGradeBtn" onclick="saveGrade()" class="px-6 py-3 border border-transparent text-sm font-semibold rounded-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-sm">
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <script>
        // Page load verification
        console.log('StudentDetails page script loaded');
        
        // Tab switching functionality for main page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded');
            
            const progressTab = document.getElementById('progress-tab');
            const assessmentsTab = document.getElementById('assessments-tab');
            const progressContent = document.getElementById('progress-content');
            const assessmentsContent = document.getElementById('assessments-content');

            if (progressTab && assessmentsTab) {
                progressTab.addEventListener('click', function() {
                    // Switch tab styles
                    progressTab.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                    progressTab.classList.remove('text-gray-600');
                    assessmentsTab.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                    assessmentsTab.classList.add('text-gray-600');

                    // Switch content
                    progressContent.classList.remove('hidden');
                    assessmentsContent.classList.add('hidden');
                });

                assessmentsTab.addEventListener('click', function() {
                    // Switch tab styles
                    assessmentsTab.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                    assessmentsTab.classList.remove('text-gray-600');
                    progressTab.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                    progressTab.classList.add('text-gray-600');

                    // Switch content
                    assessmentsContent.classList.remove('hidden');
                    progressContent.classList.add('hidden');
                });
            }
        });

        // Assessment Modal Functions
        let currentAssessment = null;

        function openAssessmentModal(assessment) {
            try {
                console.log('Opening assessment modal for:', assessment);
                
                const modal = document.getElementById('assessmentModal');
                if (!modal) {
                    console.error('Modal element not found!');
                    alert('Error: Modal not found');
                    return;
                }
                
                currentAssessment = assessment;
                
                // Update modal header
                const modalTitle = document.getElementById('modalTitle');
                const modalSubtitle = document.getElementById('modalSubtitle');
                
                if (!modalTitle || !modalSubtitle) {
                    console.error('Modal header elements not found');
                    alert('Error: Modal header elements missing');
                    return;
                }
                
                modalTitle.textContent = assessment.title;
                modalSubtitle.textContent = `Review submission for ${assessment.course_info.title}`;
                
                // Update assessment info
                const modalAssessmentType = document.getElementById('modalAssessmentType');
                const modalDueDate = document.getElementById('modalDueDate');
                const modalCurrentScore = document.getElementById('modalCurrentScore');
                
                if (!modalAssessmentType || !modalDueDate || !modalCurrentScore) {
                    console.error('Modal info elements not found');
                    alert('Error: Modal info elements missing');
                    return;
                }
                
                modalAssessmentType.textContent = assessment.type.charAt(0).toUpperCase() + assessment.type.slice(1);
                modalDueDate.textContent = assessment.submitted_at ? 
                    new Date(assessment.submitted_at).toLocaleDateString() + ' ' + new Date(assessment.submitted_at).toLocaleTimeString() : 'Not submitted';
                modalCurrentScore.textContent = assessment.student_score ? 
                    `${assessment.student_score}%` : 'Not graded';

                // Show/hide tabs based on assessment type
                updateTabVisibility(assessment.type);

                // Show appropriate submission content based on type
                const assignmentSubmission = document.getElementById('assignmentSubmission');
                const quizSubmission = document.getElementById('quizSubmission');
                
                if (!assignmentSubmission || !quizSubmission) {
                    console.error('Submission content elements not found');
                    alert('Error: Submission content elements missing');
                    return;
                }
                
                console.log('Assessment type:', assessment.type.toLowerCase());
                console.log('Has submission_id:', assessment.submission_id);
                
                if (['assignment', 'activity', 'project'].includes(assessment.type.toLowerCase())) {
                    console.log('Loading assignment submission');
                    assignmentSubmission.classList.remove('hidden');
                    quizSubmission.classList.add('hidden');
                    loadAssignmentSubmission(assessment);
                } else if (['quiz', 'exam'].includes(assessment.type.toLowerCase())) {
                    console.log('Loading quiz submission');
                    quizSubmission.classList.remove('hidden');
                    assignmentSubmission.classList.add('hidden');
                    loadQuizSubmission(assessment);
                }

                // Load current grades if available (only for non-quiz assessments)
                const newScoreElement = document.getElementById('newScore');
                if (assessment.student_score && newScoreElement && !['quiz', 'exam'].includes(assessment.type.toLowerCase())) {
                    newScoreElement.value = assessment.student_score;
                }

                // Show modal
                console.log('Showing modal...');
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                
                // Initialize with info tab active
                showModalTab('info');
                console.log('Modal should now be visible with info tab active');
                
            } catch (error) {
                console.error('Error in openAssessmentModal:', error);
                alert('Error opening modal: ' + error.message);
            }
        }

        function updateTabVisibility(assessmentType) {
            const gradingTab = document.querySelector('[data-tab="grading"]');
            const saveBtn = document.getElementById('saveGradeBtn');
            
            if (['quiz', 'exam'].includes(assessmentType.toLowerCase())) {
                // Hide grading tab for quiz/exam
                if (gradingTab) {
                    gradingTab.style.display = 'none';
                }
                // Change save button text and functionality
                if (saveBtn) {
                    saveBtn.textContent = 'Save Changes';
                }
            } else {
                // Show grading tab for other assessments
                if (gradingTab) {
                    gradingTab.style.display = 'flex';
                }
                // Reset save button text
                if (saveBtn) {
                    saveBtn.textContent = 'Save Grade & Feedback';
                }
            }
        }

        function closeAssessmentModal() {
            const modal = document.getElementById('assessmentModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentAssessment = null;
            
            // Reset form
            document.getElementById('gradingForm').reset();
        }

        function showModalTab(tabName) {
            // Hide all tabs
            const tabContents = ['info', 'quiz', 'assignment', 'grading'];
            tabContents.forEach(tab => {
                const element = document.getElementById(tab + 'Tab');
                if (element) {
                    element.classList.add('hidden');
                }
            });
            
            // Remove active state from all tab buttons
            const tabButtons = document.querySelectorAll('[data-tab]');
            tabButtons.forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600', 'bg-blue-50');
                button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });
            
            // Show selected tab
            const targetTab = document.getElementById(tabName + 'Tab');
            if (targetTab) {
                targetTab.classList.remove('hidden');
            }
            
            // Activate selected tab button
            const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
            if (activeButton) {
                activeButton.classList.add('border-blue-500', 'text-blue-600', 'bg-blue-50');
                activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            }
        }

        function loadAssignmentSubmission(assessment) {
            if (!assessment.submission_id) {
                document.getElementById('fileName').textContent = 'No file submitted';
                document.getElementById('fileSize').textContent = '';
                document.getElementById('submissionDate').textContent = 'Not submitted';
                document.getElementById('downloadBtn').style.display = 'none';
                return;
            }

            // Fetch assignment submission details
            fetch(`/instructor/submission/${assessment.submission_id}/details`)
                .then(response => response.json())
                .then(data => {
                    // Update file information
                    document.getElementById('fileName').textContent = data.submitted_file || 'No file submitted';
                    document.getElementById('fileSize').textContent = data.file_size || '';
                    document.getElementById('submissionDate').textContent = assessment.submitted_at ? 
                        new Date(assessment.submitted_at).toLocaleDateString() + ' ' + new Date(assessment.submitted_at).toLocaleTimeString() : 'Not submitted';
                    
                    // Set up download and view buttons
                    const downloadBtn = document.getElementById('downloadBtn');
                    if (data.submitted_file && assessment.submission_id) {
                        downloadBtn.style.display = 'flex';
                        downloadBtn.onclick = function() {
                            window.open(`/instructor/submission/${assessment.submission_id}/download`, '_blank');
                        };
                        
                        // Add view button if it doesn't exist
                        let viewBtn = document.getElementById('viewBtn');
                        if (!viewBtn) {
                            viewBtn = document.createElement('button');
                            viewBtn.id = 'viewBtn';
                            viewBtn.className = 'bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2 ml-3';
                            viewBtn.innerHTML = `
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span>View</span>
                            `;
                            downloadBtn.parentNode.appendChild(viewBtn);
                        }
                        
                        viewBtn.style.display = 'flex';
                        viewBtn.onclick = function() {
                            window.open(`/instructor/submission/${assessment.submission_id}/download?view=1`, '_blank');
                        };
                    } else {
                        downloadBtn.style.display = 'none';
                        const viewBtn = document.getElementById('viewBtn');
                        if (viewBtn) viewBtn.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error loading assignment submission:', error);
                    document.getElementById('fileName').textContent = 'Error loading file information';
                    document.getElementById('fileSize').textContent = '';
                    document.getElementById('downloadBtn').style.display = 'none';
                });
        }

        function loadQuizSubmission(assessment) {
            const questionsContainer = document.getElementById('quizQuestions');
            questionsContainer.innerHTML = '<div class="text-center py-8 text-gray-500">Loading quiz submission...</div>';
            
            if (!assessment.submission_id) {
                questionsContainer.innerHTML = '<div class="text-center py-8 text-gray-500">No submission found</div>';
                return;
            }
            
            // Fetch actual submission details
            fetch(`/instructor/submission/${assessment.submission_id}/details`)
                .then(response => response.json())
                .then(data => {
                    // Update modal with percentage score information
                    if (data.percentage_score !== undefined) {
                        document.getElementById('modalCurrentScore').textContent = `${data.percentage_score}%`;
                    }
                    
                    if (data.submitted_questions && data.submitted_questions.length > 0) {
                        const totalQuestions = data.submitted_questions.length;
                        const correctAnswers = data.submitted_questions.filter(q => q.is_correct).length;
                        
                        questionsContainer.innerHTML = `
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-blue-900">Assessment Summary</h4>
                                        <p class="text-sm text-blue-700 mt-1">
                                            Score: ${data.percentage_score}% (${data.earned_points}/${data.total_points} points)
                                        </p>
                                        <p class="text-sm text-blue-700">
                                            Questions: ${totalQuestions}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        ` + data.submitted_questions.map((submittedQ, index) => {
                            const isCorrect = submittedQ.is_correct;
                            const statusClass = isCorrect ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                            const statusText = isCorrect ? 'Correct' : 'Incorrect';
                            const questionId = submittedQ.id;
                            const questionType = submittedQ.question_type || 'multiple_choice';
                            const maxPoints = submittedQ.max_points || (submittedQ.question ? submittedQ.question.points : 1);
                            const earnedPoints = submittedQ.score_earned || 0;
                            
                            return `
                                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden" data-question-id="${questionId}">
                                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h4 class="text-lg font-semibold text-gray-900">Question ${index + 1} (${questionType})</h4>
                                                <p class="text-gray-700 mt-2">${submittedQ.question_text || submittedQ.question.question_text}</p>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                ${questionType.toLowerCase() === 'essay' ? `
                                                    <div class="flex flex-col items-end space-y-2">
                                                        <div class="flex items-center space-x-2">
                                                            <label class="text-sm font-medium text-gray-700">Points:</label>
                                                            <input type="number" 
                                                                id="points_${questionId}" 
                                                                min="0" 
                                                                max="${maxPoints}" 
                                                                step="0.1"
                                                                value="${earnedPoints}"
                                                                class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                                onchange="updateQuestionPoints(${questionId}, this.value, ${maxPoints})">
                                                            <span class="text-sm text-gray-600">/ ${maxPoints}</span>
                                                        </div>
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                            Essay Question
                                                        </span>
                                                    </div>
                                                ` : `
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${statusClass}">
                                                        ${statusText}
                                                    </span>
                                                    <div class="flex items-center space-x-2">
                                                        <button onclick="markQuestionCorrect(${questionId}, true)" 
                                                                class="px-3 py-1 text-xs font-medium rounded-md ${isCorrect ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-green-100'} transition-colors">
                                                            ✓ Correct
                                                        </button>
                                                        <button onclick="markQuestionCorrect(${questionId}, false)" 
                                                                class="px-3 py-1 text-xs font-medium rounded-md ${!isCorrect ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-red-100'} transition-colors">
                                                            ✗ Incorrect
                                                        </button>
                                                    </div>
                                                `}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-6 space-y-4">
                                        <div class="bg-blue-50 rounded-lg p-4">
                                            <h5 class="text-sm font-semibold text-blue-900 mb-2">Student Answer:</h5>
                                            <p class="text-blue-800 whitespace-pre-wrap">${submittedQ.submitted_answer || 'No answer provided'}</p>
                                        </div>
                                        ${(submittedQ.question && submittedQ.question.correct_answer && questionType.toLowerCase() !== 'essay') ? `
                                        <div class="bg-green-50 rounded-lg p-4">
                                            <h5 class="text-sm font-semibold text-green-900 mb-2">Correct Answer:</h5>
                                            <p class="text-green-800">${submittedQ.question.correct_answer}</p>
                                        </div>
                                        ` : ''}
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <h5 class="text-sm font-semibold text-gray-900 mb-2">Points:</h5>
                                            <p class="text-gray-800">${earnedPoints}/${maxPoints} points</p>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                    } else {
                        questionsContainer.innerHTML = '<div class="text-center py-8 text-gray-500">No questions found in submission</div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading quiz submission:', error);
                    questionsContainer.innerHTML = '<div class="text-center py-8 text-red-500">Error loading submission details</div>';
                });
        }

        // New function to update question points for essay questions
        function updateQuestionPoints(questionId, points, maxPoints) {
            if (!currentAssessment || !currentAssessment.submission_id) {
                alert('No submission loaded');
                return;
            }

            // Validate points
            const pointsValue = parseFloat(points);
            if (pointsValue < 0 || pointsValue > maxPoints) {
                alert(`Points must be between 0 and ${maxPoints}`);
                document.getElementById(`points_${questionId}`).value = 0;
                return;
            }

            // Send update to server
            fetch(`/instructor/submitted-question/${questionId}/points`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
                body: JSON.stringify({
                    points: pointsValue
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    console.log('Question points updated successfully');
                    // Update the modal score display
                    if (data.new_score !== undefined) {
                        document.getElementById('modalCurrentScore').textContent = `${data.new_score}%`;
                    }
                    // Update the assessment summary
                    const summaryScore = document.querySelector('.bg-blue-50 .text-blue-700');
                    if (summaryScore && data.earned_points !== undefined && data.total_points !== undefined) {
                        summaryScore.innerHTML = `Score: ${data.new_score}% (${data.earned_points}/${data.total_points} points)`;
                    }
                } else {
                    console.error('Server response error:', data);
                    alert('Error updating question points: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Network error updating question points:', error);
                alert('Network error updating question points. Please check your connection and try again.');
            });
        }

        function saveGrade() {
            if (!currentAssessment || !currentAssessment.submission_id) {
                alert('No submission to grade');
                return;
            }
            
            const assessmentType = currentAssessment.type.toLowerCase();
            
            // For quiz/exam assessments, we don't need manual grade input
            if (['quiz', 'exam'].includes(assessmentType)) {
                // Just recalculate and save the current score based on question points
                const saveBtn = document.getElementById('saveGradeBtn');
                const originalText = saveBtn.textContent;
                saveBtn.textContent = 'Saving...';
                saveBtn.disabled = true;
                
                fetch(`/instructor/submission/${currentAssessment.submission_id}/grade`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success || data.message) {
                        alert('Quiz score updated successfully');
                        closeAssessmentModal();
                    } else {
                        alert('Error updating quiz score: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error updating quiz score:', error);
                    alert('Error updating quiz score. Please try again.');
                })
                .finally(() => {
                    saveBtn.textContent = originalText;
                    saveBtn.disabled = false;
                });
                
                return;
            }
            
            // For other assessments, use the original grading logic
            const score = document.getElementById('newScore').value;
            const feedback = document.getElementById('feedback').value;
            const returnToStudent = document.getElementById('returnToStudent').checked;
            
            // Validate that at least a score is provided for assignment grading
            if (!score || score.trim() === '') {
                alert('Please provide a score for this assessment');
                return;
            }
            
            // Validate score range
            if (score && (score < 0 || score > 100)) {
                alert('Score must be between 0 and 100');
                return;
            }
            
            // Show loading state
            const saveBtn = document.getElementById('saveGradeBtn');
            const originalText = saveBtn.textContent;
            saveBtn.textContent = 'Saving...';
            saveBtn.disabled = true;
            
            // Send data to server
            fetch(`/instructor/submission/${currentAssessment.submission_id}/grade`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
                body: JSON.stringify({
                    score: score ? parseFloat(score) : null,
                    feedback: feedback,
                    return_to_student: returnToStudent
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.message || data.success) {
                    alert(data.message || 'Grade saved successfully');
                    
                    // Update the assessment table row if score was provided
                    if (score) {
                        const assessmentRows = document.querySelectorAll('#assessments-content tbody tr');
                        assessmentRows.forEach(row => {
                            const titleCell = row.querySelector('td:first-child .text-sm.font-medium');
                            if (titleCell && titleCell.textContent.trim() === currentAssessment.title.trim()) {
                                const scoreCell = row.querySelector('td:nth-child(6) .text-sm');
                                if (scoreCell) {
                                    scoreCell.textContent = `${score}%`;
                                }
                            }
                        });
                        
                        document.getElementById('modalCurrentScore').textContent = `${score}%`;
                    }
                    
                    closeAssessmentModal();
                } else {
                    alert('Error saving grade: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error saving grade:', error);
                alert('Error saving grade. Please try again.');
            })
            .finally(() => {
                saveBtn.textContent = originalText;
                saveBtn.disabled = false;
            });
        }

        // Manual grading function for individual quiz questions
        function markQuestionCorrect(questionId, isCorrect) {
            if (!currentAssessment || !currentAssessment.submission_id) {
                alert('No submission loaded');
                return;
            }

            // Update the UI immediately for better UX
            const questionElement = document.querySelector(`[data-question-id="${questionId}"]`);
            if (questionElement) {
                const correctBtn = questionElement.querySelector('button[onclick*="true"]');
                const incorrectBtn = questionElement.querySelector('button[onclick*="false"]');
                const statusBadge = questionElement.querySelector('.inline-flex.items-center.px-3.py-1');
                
                if (isCorrect) {
                    correctBtn.className = 'px-3 py-1 text-xs font-medium rounded-md bg-green-600 text-white transition-colors';
                    incorrectBtn.className = 'px-3 py-1 text-xs font-medium rounded-md bg-gray-200 text-gray-700 hover:bg-red-100 transition-colors';
                    statusBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800';
                    statusBadge.textContent = 'Correct';
                } else {
                    incorrectBtn.className = 'px-3 py-1 text-xs font-medium rounded-md bg-red-600 text-white transition-colors';
                    correctBtn.className = 'px-3 py-1 text-xs font-medium rounded-md bg-gray-200 text-gray-700 hover:bg-green-100 transition-colors';
                    statusBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800';
                    statusBadge.textContent = 'Incorrect';
                }
            }

            // Send update to server
            fetch(`/instructor/submitted-question/${questionId}/grade`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
                body: JSON.stringify({
                    is_correct: isCorrect
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    console.log('Question grading updated successfully');
                    // Update the modal score display
                    if (data.new_score !== undefined) {
                        document.getElementById('modalCurrentScore').textContent = `${data.new_score}%`;
                    }
                    // Update the assessment summary
                    const summaryScore = document.querySelector('.bg-blue-50 .text-blue-700');
                    if (summaryScore && data.earned_points !== undefined && data.total_points !== undefined) {
                        summaryScore.innerHTML = `Score: ${data.new_score}% (${data.earned_points}/${data.total_points} points)`;
                    }
                } else {
                    console.error('Server response error:', data);
                    alert('Error updating question grade: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Network error updating question grade:', error);
                alert('Network error updating question grade. Please check your connection and try again.');
            });
        }


        // Close modal when clicking outside
        document.getElementById('assessmentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAssessmentModal();
            }
        });

        // Handle escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('assessmentModal').classList.contains('hidden')) {
                closeAssessmentModal();
            }
        });
    </script>
</x-layout>