{{-- resources/views/courses/create.blade.php --}}

<x-layout>

    <h1 class="text-3xl font-bold text-gray-800 mb-6">Create New Course</h1>

    {{-- Display success message --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Display validation errors --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Whoops!</strong>
            <span class="block sm:inline">There were some problems with your input.</span>
            <ul class="mt-3 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- !!! IMPORTANT: ADD id="createCourseForm" HERE !!! --}}
    <form action="{{ route('course.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md max-w-2xl mx-auto"
          id="createCourseForm"> {{-- <-- THIS WAS MISSING --}}
        @csrf {{-- CSRF token for security --}}

        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Course Title:</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="course_code" class="block text-gray-700 text-sm font-bold mb-2">Enrollment Key:</label>
            <input type="text" id="course_code" name="course_code" value="{{ old('course_code') }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
            <textarea id="description" name="description" rows="5"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description') }}</textarea>
        </div>

        <div class="mb-4">
            <label for="credits" class="block text-gray-700 text-sm font-bold mb-2">Credits (Optional):</label>
            <input type="number" id="credits" name="credits" value="{{ old('credits') }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" min="1">
        </div>

        <div class="mb-4">
            <label for="program_name" class="block text-gray-700 text-sm font-bold mb-2">Program Name:</label>
            <input type="text" id="program_name" name="program_name" value="{{ old('program_name') }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-6">
            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
            <select id="status" name="status"
                    class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    id="submitCourseButton">
                Create Course
            </button>
            <a href="{{ route('instructor.dashboard') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Cancel
            </a>
        </div>
    </form>

    <script>
        document.getElementById('createCourseForm').addEventListener('submit', function() {
            document.getElementById('submitCourseButton').setAttribute('disabled', 'disabled');
            document.getElementById('submitCourseButton').innerText = 'Creating...';
        });
    </script>
</x-layout>