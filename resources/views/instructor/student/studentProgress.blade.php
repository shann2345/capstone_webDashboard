<x-layout>
    <div class="min-h-screen bg-gray-50">
        {{-- Header Section --}}
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Student Progress Tracking</h1>
                        <p class="text-sm sm:text-base text-gray-600 mt-1">Monitor academic performance and learning outcomes</p>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
                        {{-- All Students Details Button --}}
                        <a href="{{ route('instructor.studentDetails') }}" class="inline-flex justify-center items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            All Students Details
                        </a>
                        {{-- <button class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export Report
                        </button>
                        <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H9.414a1 1 0 01-.707-.293l-2-2A1 1 0 005.586 6H4a2 2 0 00-2 2v6a2 2 0 002 2h2m3 4h6m-6 0v-4m6 4v-4"></path>
                            </svg>
                            Print Report
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{-- Filters Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-6 sm:mb-8">
                <form method="GET" action="{{ route('instructor.studentProgress') }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        {{-- Course Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                            <select name="course_id" onchange="this.form.submit();" class="block w-full pl-3 pr-10 py-2 text-sm sm:text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md">
                                <option value="all" {{ request('course_id') == 'all' || !request('course_id') ? 'selected' : '' }}>All Courses</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->title }} ({{ $course->students->count() }} students)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Time Period Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Time Period</label>
                            <select name="period" onchange="this.form.submit();" class="block w-full pl-3 pr-10 py-2 text-sm sm:text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md">
                                <option value="7" {{ request('period') == '7' ? 'selected' : '' }}>Last 7 days</option>
                                <option value="30" {{ request('period') == '30' || !request('period') ? 'selected' : '' }}>Last 30 days</option>
                                <option value="90" {{ request('period') == '90' ? 'selected' : '' }}>Last 90 days</option>
                                <option value="semester" {{ request('period') == 'semester' ? 'selected' : '' }}>This Semester</option>
                            </select>
                        </div>

                        {{-- Performance Status Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Performance Status</label>
                            <select name="status" onchange="this.form.submit();" class="block w-full pl-3 pr-10 py-2 text-sm sm:text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md">
                                <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>All Students</option>
                                <option value="excellent" {{ request('status') == 'excellent' ? 'selected' : '' }}>Excellent (85%+)</option>
                                <option value="good" {{ request('status') == 'good' ? 'selected' : '' }}>Good (75-84%)</option>
                                <option value="needs-improvement" {{ request('status') == 'needs-improvement' ? 'selected' : '' }}>Needs Improvement (60-74%)</option>
                                <option value="at-risk" {{ request('status') == 'at-risk' ? 'selected' : '' }}>At Risk (<60%)</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Overview Statistics --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
                {{-- Total Students --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-600">Total Students</p>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">{{ $overallStats['totalStudents'] }}</p>
                        </div>
                        <div class="bg-blue-100 p-2 sm:p-3 rounded-full">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-medium">{{ round($overallStats['averageCompletion'], 1) }}%</span>
                        <span class="text-gray-500 ml-2">avg completion</span>
                    </div>
                </div>

                {{-- Average Grade --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-600">Average Grade</p>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">{{ round($overallStats['averageGrade'], 1) }}%</p>
                        </div>
                        <div class="bg-green-100 p-2 sm:p-3 rounded-full">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        @php
                            $gradeColor = $overallStats['averageGrade'] >= 85 ? 'text-green-600' : ($overallStats['averageGrade'] >= 75 ? 'text-yellow-600' : 'text-red-600');
                            $gradeStatus = $overallStats['averageGrade'] >= 85 ? 'Excellent' : ($overallStats['averageGrade'] >= 75 ? 'Good' : 'Needs attention');
                        @endphp
                        <span class="{{ $gradeColor }} font-medium">{{ $gradeStatus }}</span>
                    </div>
                </div>

                {{-- Excellent Performers --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-600">Excellent Performers</p>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">{{ $overallStats['performanceDistribution']['excellent'] }}</p>
                        </div>
                        <div class="bg-purple-100 p-2 sm:p-3 rounded-full">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-purple-600 font-medium">85%+ average</span>
                    </div>
                </div>

                {{-- At Risk Students --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-600">At Risk Students</p>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">{{ $overallStats['performanceDistribution']['at-risk'] }}</p>
                        </div>
                        <div class="bg-red-100 p-2 sm:p-3 rounded-full">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.18 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-red-600 font-medium">Need intervention</span>
                    </div>
                </div>
            </div>

            {{-- Performance Distribution Chart --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 mb-6 sm:mb-8">
                {{-- Performance Distribution --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 sm:mb-6">Performance Distribution</h3>
                    <div class="space-y-3 sm:space-y-4">
                        @php
                            $total = $overallStats['totalStudents'];
                            $distribution = $overallStats['performanceDistribution'];
                        @endphp
                        
                        {{-- Excellent --}}
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div class="w-3 h-3 sm:w-4 sm:h-4 bg-green-500 rounded-full"></div>
                                <span class="text-xs sm:text-sm font-medium text-gray-700">Excellent (85%+)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs sm:text-sm text-gray-600">{{ $distribution['excellent'] }}</span>
                                <div class="w-24 sm:w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $total > 0 ? ($distribution['excellent'] / $total) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Good --}}
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div class="w-3 h-3 sm:w-4 sm:h-4 bg-blue-500 rounded-full"></div>
                                <span class="text-xs sm:text-sm font-medium text-gray-700">Good (75-84%)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs sm:text-sm text-gray-600">{{ $distribution['good'] }}</span>
                                <div class="w-24 sm:w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $total > 0 ? ($distribution['good'] / $total) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Needs Improvement --}}
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div class="w-3 h-3 sm:w-4 sm:h-4 bg-yellow-500 rounded-full"></div>
                                <span class="text-xs sm:text-sm font-medium text-gray-700">Needs Improvement (60-74%)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs sm:text-sm text-gray-600">{{ $distribution['needs-improvement'] }}</span>
                                <div class="w-24 sm:w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $total > 0 ? ($distribution['needs-improvement'] / $total) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>

                        {{-- At Risk --}}
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div class="w-3 h-3 sm:w-4 sm:h-4 bg-red-500 rounded-full"></div>
                                <span class="text-xs sm:text-sm font-medium text-gray-700">At Risk (<60%)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs sm:text-sm text-gray-600">{{ $distribution['at-risk'] }}</span>
                                <div class="w-24 sm:w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ $total > 0 ? ($distribution['at-risk'] / $total) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Course Performance Overview --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 sm:mb-6">Course Performance Overview</h3>
                    <div class="space-y-3 sm:space-y-4">
                        @foreach($coursePerformanceData as $courseData)
                            <div class="border border-gray-200 rounded-lg p-3 sm:p-4">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-2 space-y-1 sm:space-y-0">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $courseData['course_name'] }}</h4>
                                        <p class="text-sm text-gray-500">{{ $courseData['course_code'] }}</p>
                                    </div>
                                    <span class="text-lg font-bold text-gray-900">{{ $courseData['average_grade'] }}%</span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Students:</span>
                                        <span class="font-medium">{{ $courseData['student_count'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Assessments:</span>
                                        <span class="font-medium">{{ $courseData['assessment_count'] }}</span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        @php
                                            $gradeColor = $courseData['average_grade'] >= 85 ? 'bg-green-500' : ($courseData['average_grade'] >= 75 ? 'bg-yellow-500' : 'bg-red-500');
                                        @endphp
                                        <div class="{{ $gradeColor }} h-2 rounded-full" style="width: {{ $courseData['average_grade'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Student Progress Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Individual Student Progress</h3>
                    <p class="text-sm text-gray-600 mt-1">Detailed progress tracking for each student</p>
                </div>
                
                {{-- Mobile/Tablet horizontal scroll for table --}}
                <div class="overflow-x-auto sm:overflow-x-visible">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[180px]">
                                    Student
                                </th>
                                <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                                    Courses Enrolled
                                </th>
                                <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[110px]">
                                    Average Grade
                                </th>
                                <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                                    Completion Rate
                                </th>
                                <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[130px]">
                                    Performance Status
                                </th>
                                <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px]">
                                    Program
                                </th>
                                <th scope="col" class="relative px-3 sm:px-6 py-3 min-w-[80px]">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($studentsWithProgress as $student)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 sm:px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 sm:h-10 sm:w-10">
                                                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <span class="text-xs sm:text-sm font-medium text-blue-600">
                                                        {{ strtoupper(substr($student->name, 0, 2)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-3 sm:ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('instructor.studentDetails', $student->id) }}" class="text-blue-600 hover:text-blue-900 hover:underline">
                                                        {{ $student->name }}
                                                    </a>
                                                </div>
                                                <div class="text-xs sm:text-sm text-gray-500">{{ $student->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-4">
                                        <div class="text-sm text-gray-900 font-medium">{{ $student->enrolled_courses_count }} Courses</div>
                                        @if($student->section)
                                            <div class="text-xs text-gray-500 mt-1">Section: {{ $student->section->name }}</div>
                                        @endif
                                    </td>
                                    <td class="px-3 sm:px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $student->average_grade }}%</div>
                                        <div class="w-12 sm:w-16 bg-gray-200 rounded-full h-1.5 mt-1">
                                            @php
                                                $gradeColor = $student->average_grade >= 85 ? 'bg-green-500' : ($student->average_grade >= 75 ? 'bg-yellow-500' : 'bg-red-500');
                                            @endphp
                                            <div class="{{ $gradeColor }} h-1.5 rounded-full" style="width: {{ $student->average_grade }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ round($student->completion_rate, 1) }}%</div>
                                        <div class="text-xs text-gray-500">{{ $student->completed_assessments }}/{{ $student->total_assessments }} assessments</div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-4">
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
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-3 sm:px-6 py-4">
                                        @if($student->program)
                                            <div class="text-sm text-gray-900">{{ $student->program->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $student->program->code ?? 'N/A' }}</div>
                                        @else
                                            <div class="text-sm text-gray-500">No program assigned</div>
                                        @endif
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-1 sm:space-x-2">
                                            <a href="{{ route('instructor.studentDetails', $student->id) }}" class="text-blue-600 hover:text-blue-900" title="View Student Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <button class="text-green-600 hover:text-green-900" title="Send Feedback">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 sm:px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">No progress data</h3>
                                            <p class="mt-1 text-sm text-gray-500">No students found matching your criteria.</p>
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
</x-layout>