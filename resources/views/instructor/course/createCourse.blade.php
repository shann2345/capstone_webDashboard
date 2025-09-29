{{-- resources/views/courses/create.blade.php --}}

<x-layout>

    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 mb-4 sm:mb-6">Create New Course</h1>

    {{-- Display success message --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 sm:px-4 sm:py-3 rounded relative mb-3 sm:mb-4 text-sm sm:text-base" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Display validation errors --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 sm:px-4 sm:py-3 rounded relative mb-3 sm:mb-4 text-sm sm:text-base" role="alert">
            <strong class="font-bold">Whoops!</strong>
            <span class="block sm:inline">There were some problems with your input.</span>
            <ul class="mt-2 sm:mt-3 list-disc list-inside text-xs sm:text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Course Information Card --}}
    {{-- Course Creation Form --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            {{-- Form Header --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-blue-100 px-4 sm:px-6 py-4 sm:py-5">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 text-blue-600 rounded-full mr-3">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Course Information</h2>
                </div>
            </div>

        <form action="{{ route('course.store') }}" method="POST" id="createCourseForm">
            @csrf
            <div class="p-3 sm:p-4 lg:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    {{-- Course Name --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Course Name</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="Introduction to Programming" required>
                    </div>

                    {{-- Course Code --}}
                    <div>
                        <label for="course_code" class="block text-sm font-medium text-gray-700 mb-2">Enrollment Key</label>
                        <input type="text" id="course_code" name="course_code" value="{{ old('course_code') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="CS101" required>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>

                    {{-- Program Name --}}
                    <div>
                        <label for="program_name" class="block text-sm font-medium text-gray-700 mb-2">Program Name</label>
                        <input type="text" id="program_name" name="program_name" value="{{ old('program_name') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="Computer Science" required>
                    </div>

                    {{-- Credits --}}
                    <div>
                        <label for="credits" class="block text-sm font-medium text-gray-700 mb-2">Credits (Optional)</label>
                        <input type="number" id="credits" name="credits" value="{{ old('credits') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="3" min="1">
                    </div>
                </div>

                {{-- Description --}}
                <div class="mt-4 sm:mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4" 
                              class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base" 
                              placeholder="A comprehensive introduction to programming concepts using Python...">{{ old('description') }}</textarea>
                </div>
                
                {{-- Submit Button --}}
                <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row justify-center sm:justify-end gap-3">
                    <a href="{{ route('instructor.dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 sm:px-6 sm:py-3 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg font-medium transition-colors duration-200 text-sm sm:text-base">
                        Cancel
                    </a>
                    <button type="submit" id="submitCourseButton" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 sm:px-6 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 text-sm sm:text-base">
                        Create Course
                    </button>
                </div>
            </div>
        </form>
    </div>



    <script>
        document.getElementById('createCourseForm').addEventListener('submit', function() {
            const submitButton = document.getElementById('submitCourseButton');
            submitButton.setAttribute('disabled', 'disabled');
            submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Creating...';
        });
    </script>
</x-layout>