{{-- resources/views/courses/show.blade.php --}}

<x-layout>
    <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $course->title }} ({{ $course->course_code }})</h1>
    <a href="" class="text-gray-800 font-bold py-2 px-4 rounded" style="background-color: #007bff; color: white"> + Add Materials</a>
    <p class="text-gray-600 mb-2">Program: {{ $course->program->name ?? 'N/A' }}</p>
    <p class="text-gray-600 mb-4">Instructor: {{ $course->instructor->name ?? 'N/A' }}</p>

    <h2 class="text-xl font-semibold text-gray-700 mb-2">Description</h2>
    <p class="text-gray-700 mb-4">{{ $course->description ?: 'No description provided.' }}</p>



    <a href="{{ route('instructor.dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
        Back to Dashboard
    </a>
</x-layout>