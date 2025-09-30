<x-layout>
    <div class="min-h-screen bg-gray-50">
        {{-- Header Section --}}
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">My Courses</h1>
                        <p class="text-gray-600 mt-1">Manage and oversee all the courses you are teaching</p>
                    </div>
                    <a href="{{ route('course.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium flex items-center space-x-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Create New Course</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{-- Course List Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Course List</h2>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-4">
                            {{-- Course Filter Dropdown --}}
                            <div class="relative">
                                <select id="courseFilter" class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 pr-8 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            {{-- View Toggle Buttons --}}
                            <div class="flex items-center space-x-2">
                                <button id="gridViewBtn" class="p-2 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                    </svg>
                                </button>
                                <button id="listViewBtn" class="p-2 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    @if ($courses->isEmpty())
                        {{-- Empty State --}}
                        <div class="text-center py-12">
                            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.405 9.176 5 7 5 4.823 5 3.168 5.405 2 6.253v13C3.168 18.595 4.823 18 7 18c2.176 0 3.832.405 5 1.253m0-13C13.168 5.405 14.823 5 17 5c2.176 0 3.832.405 5 1.253v13C20.832 18.595 19.176 18 17 18c-2.177 0-3.832.405-5 1.253"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Start Creating Your First Course</h3>
                            <p class="text-gray-600 mb-6 max-w-md mx-auto">You haven't created any courses yet. Begin your teaching journey by creating your first course and sharing knowledge with students.</p>
                            <a href="{{ route('course.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create Your First Course
                            </a>
                        </div>
                    @else
                        {{-- Course Cards Grid --}}
                        <div id="courseGrid" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                            @foreach ($courses as $course)
                                <div class="course-card" data-status="{{ $course->status }}">
                                    <div class="bg-gray-50 rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200 overflow-hidden">
                                        {{-- Course Header --}}
                                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-20 relative">
                                            <div class="absolute top-3 right-3">
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                    @if($course->status === 'published') bg-green-100 text-green-800
                                                    @elseif($course->status === 'draft') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($course->status) }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Course Content --}}
                                        <div class="p-6">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                                                {{ $course->title }}
                                            </h3>
                                            <p class="text-sm text-gray-600 mb-3">
                                                {{ $course->course_code }}
                                                @if($course->program)
                                                    • {{ $course->program->name }}
                                                @endif
                                            </p>
                                            <p class="text-gray-700 text-sm leading-relaxed mb-4 line-clamp-2">
                                                {{ Str::limit($course->description ?: 'No description provided for this course.', 80) }}
                                            </p>

                                            {{-- Course Stats --}}
                                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                                <div class="flex items-center space-x-4">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                        </svg>
                                                        {{ $course->students->count() }} students
                                                    </span>
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                        </svg>
                                                        {{ $course->credits ?: 3 }} credits
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Action Buttons --}}
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('instructor.courseDetails', $course->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex-1 text-center">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Course List View --}}
                        <div id="courseList" class="space-y-4">
                            @foreach ($courses as $course)
                                <div class="course-card bg-white border border-gray-200 rounded-lg hover:shadow-md transition-all duration-200" data-status="{{ $course->status }}">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between">
                                            {{-- Course Info --}}
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <h3 class="text-lg font-semibold text-gray-900">{{ $course->title }}</h3>
                                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                        @if($course->status === 'published') bg-green-100 text-green-800
                                                        @elseif($course->status === 'draft') bg-yellow-100 text-yellow-800
                                                        @else bg-red-100 text-red-800
                                                        @endif">
                                                        {{ ucfirst($course->status) }}
                                                    </span>
                                                </div>

                                                <p class="text-sm font-medium text-gray-600 mb-2">{{ $course->course_code }}</p>

                                                <p class="text-gray-700 text-sm leading-relaxed mb-4 line-clamp-2">
                                                    {{ $course->description ?: 'A comprehensive introduction to programming concepts using Python. Learn fundamentals of...' }}
                                                </p>

                                                {{-- Course Stats --}}
                                                <div class="flex items-center space-x-6 text-sm text-gray-500">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                        </svg>
                                                        {{ $course->students->count() }} Students Enrolled
                                                    </span>
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                        </svg>
                                                        12 Content Items
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Action Buttons --}}
                                            <div class="flex items-center space-x-2 ml-6">
                                                <a href="{{ route('instructor.courseDetails', $course->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const gridViewBtn = document.getElementById('gridViewBtn');
            const listViewBtn = document.getElementById('listViewBtn');
            const courseGrid = document.getElementById('courseGrid');
            const courseList = document.getElementById('courseList');
            const courseFilter = document.getElementById('courseFilter');

            function applyViewStyles(view) {
                if (view === 'grid') {
                    gridViewBtn.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                    gridViewBtn.classList.remove('text-gray-400', 'hover:text-gray-600');
                    listViewBtn.classList.add('text-gray-400', 'hover:text-gray-600');
                    listViewBtn.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                } else {
                    listViewBtn.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                    listViewBtn.classList.remove('text-gray-400', 'hover:text-gray-600');
                    gridViewBtn.classList.add('text-gray-400', 'hover:text-gray-600');
                    gridViewBtn.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                }
            }

            function toggleView(view) {
                if (view === 'grid') {
                    courseGrid.classList.remove('hidden');
                    courseList.classList.add('hidden');
                } else {
                    courseGrid.classList.add('hidden');
                    courseList.classList.remove('hidden');
                }
                applyViewStyles(view);
                localStorage.setItem('courseView', view); // Save the preference
            }

            function filterCourses(status) {
                const courseCards = document.querySelectorAll('.course-card');
                let visibleCount = 0;
                
                courseCards.forEach(card => {
                    const cardStatus = card.getAttribute('data-status');
                    if (cardStatus === status) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Update the course count display or show empty state if needed
                updateEmptyState(visibleCount);
            }
            
            function updateEmptyState(visibleCount) {
                const emptyState = document.querySelector('.empty-state');
                const courseGrid = document.getElementById('courseGrid');
                const courseList = document.getElementById('courseList');
                
                if (visibleCount === 0) {
                    // Show empty state for filtered results
                    if (!document.querySelector('.filter-empty-state')) {
                        const filterEmptyState = document.createElement('div');
                        filterEmptyState.className = 'filter-empty-state text-center py-12';
                        filterEmptyState.innerHTML = `
                            <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No courses found</h3>
                            <p class="text-gray-500 mb-4">No courses match the selected filter criteria.</p>
                        `;
                        
                        const container = document.querySelector('#courseGrid').parentElement;
                        container.appendChild(filterEmptyState);
                    }
                    document.querySelector('.filter-empty-state').style.display = 'block';
                } else {
                    // Hide empty state
                    const filterEmptyState = document.querySelector('.filter-empty-state');
                    if (filterEmptyState) {
                        filterEmptyState.style.display = 'none';
                    }
                }
            }

            // Load the saved preference from local storage on page load
            const savedView = localStorage.getItem('courseView');
            if (savedView) {
                toggleView(savedView);
            } else {
                // Default view if no preference is saved
                toggleView('list');
            }

            // Event listeners
            gridViewBtn.addEventListener('click', function() {
                toggleView('grid');
            });

            listViewBtn.addEventListener('click', function() {
                toggleView('list');
            });
            
            courseFilter.addEventListener('change', function() {
                const selectedStatus = this.value;
                filterCourses(selectedStatus);
                localStorage.setItem('courseFilter', selectedStatus); // Save filter preference
            });
            
            // Load saved filter preference
            const savedFilter = localStorage.getItem('courseFilter');
            if (savedFilter && (savedFilter === 'published' || savedFilter === 'draft')) {
                courseFilter.value = savedFilter;
                filterCourses(savedFilter);
            } else {
                // Default to published courses
                courseFilter.value = 'published';
                filterCourses('published');
            }
        });
    </script>
</x-layout>