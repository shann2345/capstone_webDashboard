<x-layout>
    <div class="p-3 sm:p-4 lg:p-6">
        <div class="text-xs sm:text-sm text-gray-500 mb-3 sm:mb-4">
            <a href="{{ route('instructor.myCourse') }}" class="hover:underline">My Courses</a> &gt;
            <span>Course Details</span>
        </div>

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 space-y-3 sm:space-y-0">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 break-words">{{ $course->title }}</h1>
                <div class="flex flex-col sm:flex-row sm:items-center mt-2 text-sm sm:text-base text-gray-600 space-y-1 sm:space-y-0 sm:space-x-2">
                    <span class="font-medium">{{ $course->course_code }}</span>
                    <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full w-fit">{{ ucfirst($course->status) }}</span>
                </div>
            </div>
        </div>

        <div class="border-b border-gray-200 mb-4 sm:mb-6">
            <nav class="-mb-px flex space-x-4 sm:space-x-8 overflow-x-auto" aria-label="Tabs">
                <a href="#" class="border-blue-500 text-blue-600 whitespace-nowrap py-3 sm:py-4 px-1 border-b-2 font-medium text-sm" aria-current="page">
                    Course Info
                </a>
                <a href="{{ route('courses.show', ['course'=>$course->id]) }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-3 sm:py-4 px-1 border-b-2 font-medium text-sm">
                    Course Content
                </a>
                <a href="{{ route('instructor.courseEnrollee', $course->id) }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-3 sm:py-4 px-1 border-b-2 font-medium text-sm">
                    Enrolled Students
                </a>
            </nav>
        </div>

        {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                {{-- Students Enrolled --}}
                <div class="bg-blue-50 rounded-lg p-4 sm:p-6 text-center">
                    <div class="flex justify-center mb-2 sm:mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-8 sm:w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $course->students_count }}</div>
                    <div class="text-xs sm:text-sm text-blue-700 font-medium">Students Enrolled</div>
                </div>

                {{-- Content Items --}}
                <div class="bg-green-50 rounded-lg p-4 sm:p-6 text-center">
                    <div class="flex justify-center mb-2 sm:mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-8 sm:w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold text-green-600">
                        {{ $course->materials_count + $course->assessments_count }}
                    </div>
                    <div class="text-xs sm:text-sm text-green-700 font-medium">Content Items</div>
                </div>

                {{-- Assignments --}}
                <div class="bg-yellow-50 rounded-lg p-4 sm:p-6 text-center sm:col-span-2 lg:col-span-1">
                    <div class="flex justify-center mb-2 sm:mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-8 sm:w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold text-yellow-600">
                        {{ $course->assessments_count }}
                    </div>
                    <div class="text-xs sm:text-sm text-yellow-700 font-medium">Assessments</div>
                </div>
            </div>

        {{-- Course Information Card --}}
        <div class="mt-6 sm:mt-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-4 sm:p-6 border-b border-gray-200 space-y-3 sm:space-y-0">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Course Information</h2>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                        <button id="editBtn" class="inline-flex items-center justify-center px-3 py-2 sm:px-4 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Course
                        </button>
                        <button id="saveBtn" class="hidden inline-flex items-center justify-center px-3 py-2 sm:px-4 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Changes
                        </button>
                        <button id="cancelBtn" class="hidden inline-flex items-center justify-center px-3 py-2 sm:px-4 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel
                        </button>
                    </div>
                </div>

                <form id="editCourseForm" action="{{ route('course.update', $course->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-4 sm:p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                            {{-- Course Name --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Course Name</label>
                                <div id="title_display" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-900">
                                    {{ $course->title }}
                                </div>
                                <input type="text" id="title_input" name="title" value="{{ $course->title }}" 
                                       class="hidden w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       required>
                            </div>

                            {{-- Course Code --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Course Code</label>
                                <div id="course_code_display" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-900">
                                    {{ $course->course_code }}
                                </div>
                                <input type="text" id="course_code_input" name="course_code" value="{{ $course->course_code }}" 
                                       class="hidden w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       required>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <div id="status_display" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $course->status === 'published' ? 'bg-green-100 text-green-800' : 
                                           ($course->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($course->status) }}
                                    </span>
                                </div>
                                <select id="status_input" name="status" 
                                        class="hidden w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                        required>
                                    <option value="draft" {{ $course->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ $course->status === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ $course->status === 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>

                            {{-- Created Date --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Created Date</label>
                                <div class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-900">
                                    {{ $course->created_at ? $course->created_at->format('F j, Y') : 'N/A' }}
                                </div>
                            </div>

                            {{-- Program Name --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Program Name</label>
                                <div id="program_name_display" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-900">
                                    {{ $course->program->name ?? 'N/A' }}
                                </div>
                                <input type="text" id="program_name_input" name="program_name" value="{{ $course->program->name ?? '' }}" 
                                       class="hidden w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       required>
                            </div>

                            {{-- Credits --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Credits</label>
                                <div id="credits_display" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-900">
                                    {{ $course->credits ?? 'N/A' }}
                                </div>
                                <input type="number" id="credits_input" name="credits" value="{{ $course->credits }}" 
                                       class="hidden w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       min="1">
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <div id="description_display" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-900 min-h-[100px]">
                                {{ $course->description ?? 'No description provided.' }}
                            </div>
                            <textarea id="description_input" name="description" rows="4" 
                                      class="hidden w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $course->description }}</textarea>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if (session('success'))
        <div class="fixed top-4 right-4 left-4 sm:left-auto sm:right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative z-50" role="alert" id="successMessage">
            <span class="block sm:inline text-sm">{{ session('success') }}</span>
            <button type="button" class="absolute top-0 right-0 px-4 py-3" onclick="document.getElementById('successMessage').remove()">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="fixed top-4 right-4 left-4 sm:left-auto sm:right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative z-50" role="alert" id="errorMessage">
            <strong class="font-bold text-sm">Error!</strong>
            <ul class="mt-2 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="absolute top-0 right-0 px-4 py-3" onclick="document.getElementById('errorMessage').remove()">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editBtn = document.getElementById('editBtn');
            const saveBtn = document.getElementById('saveBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const form = document.getElementById('editCourseForm');

            // Store original values for cancel functionality
            const originalValues = {};

            editBtn.addEventListener('click', function() {
                // Store original values
                originalValues.title = document.getElementById('title_input').value;
                originalValues.course_code = document.getElementById('course_code_input').value;
                originalValues.status = document.getElementById('status_input').value;
                originalValues.program_name = document.getElementById('program_name_input').value;
                originalValues.credits = document.getElementById('credits_input').value;
                originalValues.description = document.getElementById('description_input').value;

                // Hide display elements and show input elements
                toggleEditMode(true);
            });

            cancelBtn.addEventListener('click', function() {
                // Restore original values
                document.getElementById('title_input').value = originalValues.title;
                document.getElementById('course_code_input').value = originalValues.course_code;
                document.getElementById('status_input').value = originalValues.status;
                document.getElementById('program_name_input').value = originalValues.program_name;
                document.getElementById('credits_input').value = originalValues.credits;
                document.getElementById('description_input').value = originalValues.description;

                toggleEditMode(false);
            });

            saveBtn.addEventListener('click', function() {
                // Submit the form
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Saving...';
                form.submit();
            });

            function toggleEditMode(isEditing) {
                const fields = ['title', 'course_code', 'status', 'program_name', 'credits', 'description'];
                
                fields.forEach(field => {
                    const display = document.getElementById(field + '_display');
                    const input = document.getElementById(field + '_input');
                    
                    if (display && input) {
                        if (isEditing) {
                            display.classList.add('hidden');
                            input.classList.remove('hidden');
                        } else {
                            display.classList.remove('hidden');
                            input.classList.add('hidden');
                        }
                    }
                });

                // Toggle buttons
                if (isEditing) {
                    editBtn.classList.add('hidden');
                    saveBtn.classList.remove('hidden');
                    cancelBtn.classList.remove('hidden');
                } else {
                    editBtn.classList.remove('hidden');
                    saveBtn.classList.add('hidden');
                    cancelBtn.classList.add('hidden');
                }
            }

            // Auto-hide success/error messages after 5 seconds
            setTimeout(function() {
                const successMsg = document.getElementById('successMessage');
                const errorMsg = document.getElementById('errorMessage');
                if (successMsg) successMsg.remove();
                if (errorMsg) errorMsg.remove();
            }, 5000);
        });
    </script>
</x-layout>