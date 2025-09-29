<x-layout>
    <div class="min-h-screen bg-gray-50">
        {{-- Header Section --}}
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Student Management</h1>
                        <p class="text-gray-600 mt-1">Manage and monitor all students across your courses</p>
                    </div>
                    {{-- <div class="flex items-center space-x-3">
                        <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Students
                        </button>
                        <button class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export
                        </button>
                    </div> --}}
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">


            {{-- Filters and Search --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                <form method="GET" action="{{ route('instructor.studenManagement') }}">
                    <div class="flex flex-wrap items-center gap-4">
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
                            <select name="course_id" onchange="updateSections(this.value); this.form.submit();" class="block w-64 pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="all" {{ request('course_id') == 'all' || !request('course_id') ? 'selected' : '' }}>All Courses</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->title }} ({{ $course->students->count() }} students)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Section Filter --}}
                        <div>
                            <select name="section_id" onchange="this.form.submit();" id="sectionSelect" class="block w-64 pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="all" {{ request('section_id') == 'all' || !request('section_id') ? 'selected' : '' }}>All Sections</option>
                                @if(request('course_id') && request('course_id') !== 'all')
                                    @foreach($sections->where('course_id', request('course_id')) as $section)
                                        <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }} ({{ $section->students->count() }} students)
                                        </option>
                                    @endforeach
                                @else
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }} - {{ $section->course->title }} ({{ $section->students->count() }} students)
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        {{-- Sort Filter --}}
                        <div>
                            <select name="sort" onchange="this.form.submit();" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Sort by Name</option>
                                <option value="enrollment" {{ request('sort') == 'enrollment' ? 'selected' : '' }}>Sort by Enrollment Date</option>
                                <option value="section" {{ request('sort') == 'section' ? 'selected' : '' }}>Sort by Section</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            
            {{-- Statistics Overview --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                {{-- Total Students --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">
                                @if(request('course_id') && request('course_id') !== 'all')
                                    @if(request('section_id') && request('section_id') !== 'all')
                                        Students in Section
                                    @else
                                        Students in Course
                                    @endif
                                @else
                                    Total Students
                                @endif
                            </p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalStudents }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-medium">+{{ $recentEnrollments->count() }} new</span>
                        <span class="text-gray-500 ml-2">this month</span>
                    </div>
                </div>

                {{-- Students Without Sections --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Unassigned Students</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $studentsWithoutSections->count() }}</p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-full">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.18 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-orange-600 font-medium">Need assignment</span>
                        <span class="text-gray-500 ml-2">to sections</span>
                    </div>
                </div>

                {{-- Total Courses --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">My Courses</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $courses->count() }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-medium">{{ $sections->count() }}</span>
                        <span class="text-gray-500 ml-2">total sections</span>
                    </div>
                </div>

                {{-- Recent Enrollments --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Recent Enrollments</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $recentEnrollments->count() }}</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-purple-600 font-medium">Last 30 days</span>
                        <span class="text-gray-500 ml-2">new students</span>
                    </div>
                </div>
            </div>
            
            {{-- Students Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">All Students
                        <p class="text-sm text-gray-600 mt-1">Manage your students across all courses</p>
                    </h3>
                    <div class="flex items-center space-x-3">
                        <button onclick="selectAllStudents()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Select All</button>
                        <button onclick="deselectAllStudents()" class="text-gray-600 hover:text-gray-800 text-sm font-medium">Deselect All</button>
                        <button id="removeSelectedBtn" onclick="removeSelectedStudents()" class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed bg-gray-100 text-gray-400 border-gray-200" disabled>
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Remove Selected (<span id="selectedCount">0</span>)
                        </button>
                    </div>
                </div>
                
                {{-- Students Table with Enhanced Scrollable Container --}}
                <div class="relative">
                    <!-- Table container with smooth scrolling and enhanced styling -->
                    <div class="overflow-hidden rounded-lg shadow-sm border border-gray-200">
                        <div class="max-h-[600px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0 z-20 shadow-sm sticky-header">
                                    <tr>
                                        <th scope="col" class="w-4 px-6 py-3 bg-gray-50">
                                            <input type="checkbox" id="select-all" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                                            Student Info
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                                            Courses & Section
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                                            Enrollment Details
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                                            Program
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                                <tr class="hover:bg-gray-50">
                                    <td class="w-4 px-6 py-4">
                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded student-checkbox">
                                    </td>
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
                                                <div class="text-xs text-gray-400">ID: {{ $student->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <div class="font-medium">
                                                {{ $student->course_count ?? 0 }} 
                                                {{ ($student->course_count ?? 0) === 1 ? 'Course' : 'Courses' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Enrolled under you
                                            </div>
                                        </div>
                                        <div class="mt-1">
                                            @if($student->section)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $student->section->name }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    No Section
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @if($student->pivot && $student->pivot->enrollment_date)
                                                <div class="font-medium">{{ \Carbon\Carbon::parse($student->pivot->enrollment_date)->format('M d, Y') }}</div>
                                                <div class="text-xs text-gray-500">
                                                    {{ \Carbon\Carbon::parse($student->pivot->enrollment_date)->diffForHumans() }}
                                                </div>
                                            @else
                                                <div class="text-gray-500">No enrollment date</div>
                                            @endif
                                        </div>
                                        @if($student->pivot && $student->pivot->grade)
                                            <div class="text-xs text-green-600 mt-1">
                                                Grade: {{ $student->pivot->grade }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($student->pivot && $student->pivot->status)
                                            @php
                                                $statusColors = [
                                                    'enrolled' => 'bg-green-100 text-green-800',
                                                    'completed' => 'bg-blue-100 text-blue-800',
                                                    'dropped' => 'bg-red-100 text-red-800',
                                                    'inactive' => 'bg-gray-100 text-gray-800'
                                                ];
                                                $statusColor = $statusColors[$student->pivot->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                                {{ ucfirst($student->pivot->status) }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Unknown
                                            </span>
                                        @endif
                                        @if($student->email_verified_at)
                                            <div class="text-xs text-green-600 mt-1">✓ Verified</div>
                                        @else
                                            <div class="text-xs text-red-600 mt-1">✗ Unverified</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($student->program)
                                            <div class="text-sm text-gray-900">{{ $student->program->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $student->program->code ?? 'N/A' }}</div>
                                        @else
                                            <div class="text-sm text-gray-500">No program assigned</div>
                                        @endif
                                    </td>
                                    {{-- <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <button class="text-blue-600 hover:text-blue-900" title="View Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            <button class="text-red-600 hover:text-red-900" title="Remove Student">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">No students found</h3>
                                            @if($searchTerm)
                                                <p class="mt-1 text-sm text-gray-500">No students match your search "{{ $searchTerm }}"</p>
                                                <div class="mt-6">
                                                    <a href="{{ route('instructor.studenManagement') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                        Clear Search
                                                    </a>
                                                </div>
                                            @else
                                                <p class="mt-1 text-sm text-gray-500">Get started by adding students to your courses.</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                        </div>
                    </div>
                    
                    <!-- Fade overlay for visual indication of more content -->
                    <div class="absolute bottom-0 left-0 right-0 h-6 bg-gradient-to-t from-gray-50 to-transparent pointer-events-none opacity-0 transition-opacity duration-200" id="scrollFadeIndicator"></div>
                </div>

                {{-- Pagination --}}
                <div class="bg-white px-6 py-3 flex items-center justify-between border-t border-gray-200 rounded-b-lg">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if($pagination['prev_page_url'])
                            <a href="{{ $pagination['prev_page_url'] }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Previous</a>
                        @else
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">Previous</span>
                        @endif
                        @if($pagination['next_page_url'])
                            <a href="{{ $pagination['next_page_url'] }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Next</a>
                        @else
                            <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">Next</span>
                        @endif
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing <span class="font-medium">{{ $pagination['from'] }}</span> to <span class="font-medium">{{ $pagination['to'] }}</span> of <span class="font-medium">{{ $pagination['total'] }}</span> results
                            </p>
                        </div>
                        @if($pagination['has_pages'])
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                {{-- Previous Button --}}
                                @if($pagination['prev_page_url'])
                                    <a href="{{ $pagination['prev_page_url'] }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Previous</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @else
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                        <span class="sr-only">Previous</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @endif

                                {{-- Page Numbers --}}
                                @php
                                    $start = max(1, $pagination['current_page'] - 2);
                                    $end = min($pagination['last_page'], $pagination['current_page'] + 2);
                                @endphp
                                
                                @for ($page = $start; $page <= $end; $page++)
                                    @if ($page == $pagination['current_page'])
                                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">{{ $page }}</span>
                                    @else
                                        <a href="{{ request()->fullUrlWithQuery(['page' => $page]) }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">{{ $page }}</a>
                                    @endif
                                @endfor

                                {{-- Next Button --}}
                                @if($pagination['next_page_url'])
                                    <a href="{{ $pagination['next_page_url'] }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Next</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @else
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                        <span class="sr-only">Next</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @endif
                            </nav>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom scrollbar styling */
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Smooth scrolling behavior */
        .max-h-\[600px\] {
            scroll-behavior: smooth;
        }
        
        /* Enhanced table styling for better readability */
        .student-table-row:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }
        
        /* Checkbox styling enhancements */
        .student-checkbox:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        
        /* Bulk actions bar animation */
        #bulk-actions-bar {
            animation: slideUp 0.3s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translate(-50%, 20px);
            }
            to {
                opacity: 1;
                transform: translate(-50%, 0);
            }
        }
        
        /* Sticky header enhancement */
        .sticky-header {
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #e5e7eb;
        }
    </style>

    <script>
        // Section data for dynamic filtering
        const sectionsByCourse = @json($sections->groupBy('course_id'));
        
        function updateSections(courseId) {
            const sectionSelect = document.getElementById('sectionSelect');
            
            // Clear existing options except "All Sections"
            sectionSelect.innerHTML = '<option value="all">All Sections</option>';
            
            if (courseId && courseId !== 'all') {
                // Add sections for the selected course
                if (sectionsByCourse[courseId]) {
                    sectionsByCourse[courseId].forEach(section => {
                        const option = document.createElement('option');
                        option.value = section.id;
                        option.textContent = `${section.name} (${section.students.length} students)`;
                        sectionSelect.appendChild(option);
                    });
                }
            } else {
                // Add all sections with course names
                Object.values(sectionsByCourse).flat().forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.id;
                    option.textContent = `${section.name} - ${section.course.title} (${section.students.length} students)`;
                    sectionSelect.appendChild(option);
                });
            }
        }

        // Bulk selection functionality
        const selectAllCheckbox = document.getElementById('select-all');
        const studentCheckboxes = document.querySelectorAll('.student-checkbox');
        const bulkActionsBar = document.getElementById('bulk-actions-bar');
        const selectedCountSpan = document.getElementById('selected-count');
        const clearSelectionBtn = document.getElementById('clear-selection');

        // Define updateBulkActions function globally
        function updateBulkActions() {
            const checkedCount = document.querySelectorAll('.student-checkbox:checked').length;
            
            // Update bulk actions bar
            if (checkedCount > 0 && bulkActionsBar) {
                bulkActionsBar.classList.remove('hidden');
                if (selectedCountSpan) {
                    selectedCountSpan.textContent = `${checkedCount} student${checkedCount > 1 ? 's' : ''} selected`;
                }
            } else if (bulkActionsBar) {
                bulkActionsBar.classList.add('hidden');
            }
            
            // Update header remove button
            const removeBtn = document.getElementById('removeSelectedBtn');
            const countSpan = document.getElementById('selectedCount');
            
            if (removeBtn && countSpan) {
                countSpan.textContent = checkedCount;
                
                if (checkedCount > 0) {
                    removeBtn.disabled = false;
                    removeBtn.classList.remove('bg-gray-100', 'text-gray-400', 'border-gray-200');
                    removeBtn.classList.add('text-red-700', 'bg-white', 'border-red-300', 'hover:bg-red-50');
                } else {
                    removeBtn.disabled = true;
                    removeBtn.classList.add('bg-gray-100', 'text-gray-400', 'border-gray-200');
                    removeBtn.classList.remove('text-red-700', 'bg-white', 'border-red-300', 'hover:bg-red-50');
                }
            }
        }

        if (selectAllCheckbox && studentCheckboxes.length > 0) {
            // Select all functionality
            selectAllCheckbox.addEventListener('change', function() {
                studentCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActions();
            });

            // Individual checkbox functionality
            studentCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedCount = document.querySelectorAll('.student-checkbox:checked').length;
                    selectAllCheckbox.checked = checkedCount === studentCheckboxes.length;
                    selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < studentCheckboxes.length;
                    updateBulkActions();
                });
            });

            // Clear selection
            if (clearSelectionBtn) {
                clearSelectionBtn.addEventListener('click', function() {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                    studentCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                    updateBulkActions();
                });
            }
        }

        // Bulk remove students function
        function removeSelectedStudents() {
            const checkedCheckboxes = document.querySelectorAll('.student-checkbox:checked');
            
            if (checkedCheckboxes.length === 0) {
                alert('Please select at least one student to remove.');
                return;
            }

            if (!confirm(`Are you sure you want to remove ${checkedCheckboxes.length} selected student(s)?`)) {
                return;
            }

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/instructor/bulk-remove-students';
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add course ID if filtering by specific course
            const courseSelect = document.querySelector('select[name="course_id"]');
            if (courseSelect && courseSelect.value !== 'all') {
                const courseIdInput = document.createElement('input');
                courseIdInput.type = 'hidden';
                courseIdInput.name = 'course_id';
                courseIdInput.value = courseSelect.value;
                form.appendChild(courseIdInput);
            }
            
            // Add selected student IDs
            checkedCheckboxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'student_ids[]';
                input.value = checkbox.value;
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.submit();
        }

        // Helper functions for compatibility with existing HTML calls
        function toggleAllStudents() {
            const selectAllCheckbox = document.getElementById('select-all');
            if (selectAllCheckbox) {
                selectAllCheckbox.click();
            }
        }

        function selectAllStudents() {
            const selectAllCheckbox = document.getElementById('select-all');
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            
            if (selectAllCheckbox) selectAllCheckbox.checked = true;
            studentCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            updateBulkActions();
        }

        function deselectAllStudents() {
            const selectAllCheckbox = document.getElementById('select-all');
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
            studentCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateBulkActions();
        }

        function updateRemoveButtonState() {
            updateBulkActions();
        }

        // Enhanced scrolling functionality
        document.addEventListener('DOMContentLoaded', function() {
            const scrollContainer = document.querySelector('.max-h-\\[600px\\]');
            const fadeIndicator = document.getElementById('scrollFadeIndicator');
            
            if (scrollContainer && fadeIndicator) {
                // Show/hide fade indicator based on scroll position
                function updateScrollIndicator() {
                    const { scrollTop, scrollHeight, clientHeight } = scrollContainer;
                    const isAtBottom = scrollTop + clientHeight >= scrollHeight - 5;
                    
                    if (isAtBottom || scrollHeight <= clientHeight) {
                        fadeIndicator.style.opacity = '0';
                    } else {
                        fadeIndicator.style.opacity = '1';
                    }
                }
                
                // Initial check
                updateScrollIndicator();
                
                // Update on scroll
                scrollContainer.addEventListener('scroll', updateScrollIndicator);
                
                // Update on window resize
                window.addEventListener('resize', updateScrollIndicator);
            }
            
            // Add enhanced hover effects to table rows
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.classList.add('student-table-row');
            });
            
            // Initialize button states
            updateBulkActions();
        });
    </script>

    <!-- Enhanced Bulk Actions Bar -->
    <div id="bulk-actions-bar" class="hidden fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-white border border-gray-200 rounded-xl shadow-xl px-6 py-4 z-50 min-w-max backdrop-blur-sm bg-white/95">
        <div class="flex items-center space-x-4">
            <span id="selected-count" class="text-sm font-medium text-gray-700">0 students selected</span>
            <div class="h-4 w-px bg-gray-300"></div>
            <button type="button" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                Assign to Section
            </button>
            <button type="button" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors duration-200 shadow-sm">
                Send Message
            </button>
            <button type="button" onclick="removeSelectedStudents()" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-200 shadow-sm">
                Remove Students
            </button>
            <button type="button" id="clear-selection" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors duration-200">
                Clear
            </button>
        </div>
    </div>
    </script>
</x-layout>