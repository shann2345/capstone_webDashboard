{{-- resources/views/courses/show.blade.php --}}

<x-layout>
    <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $course->title }} ({{ $course->course_code }})</h1>
    <p class="text-gray-600 mb-2">Department: {{ $course->department->name ?? 'N/A' }}</p>
    <p class="text-gray-600 mb-4">Instructor: {{ $course->instructor->name ?? 'N/A' }}</p>

    <h2 class="text-xl font-semibold text-gray-700 mb-2">Description</h2>
    <p class="text-gray-700 mb-4">{{ $course->description ?: 'No description provided.' }}</p>

    <p class="text-gray-600 mb-2">Credits: {{ $course->credits ?: 'N/A' }}</p>
    <p class="text-gray-600 mb-4">Status: <span class="px-2 py-1 rounded-full text-xs font-semibold
        @if($course->status === 'published') bg-green-200 text-green-800
        @elseif($course->status === 'draft') bg-yellow-200 text-yellow-800
        @else bg-red-200 text-red-800
        @endif">
        {{ ucfirst($course->status) }}
    </span></p>

    <a href="{{ route('instructor.dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
        Back to Dashboard
    </a>
</x-layout>