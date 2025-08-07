{{-- resources/views/assessments/show.blade.php --}}

<x-layout>
    <x-slot name="title">
        {{ $assessment->title }} - Assessment Details
    </x-slot>
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $assessment->title }}</h1>
            <p class="text-gray-600 mb-4">{{ $assessment->description }}</p>

            @if($assessment->duration_minutes)
                <p class="text-gray-700 mb-2"><strong>Duration:</strong> {{ $assessment->duration_minutes }} minutes</p>
            @endif

            @if($assessment->access_code)
                <p class="text-gray-700 mb-2"><strong>Access Code:</strong> {{ $assessment->access_code }}</p>
            @endif

            @if($assessment->available_at)
                <p class="text-gray-700 mb-2"><strong>Available From:</strong> {{ $assessment->available_at->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}</p>
            @endif

            @if($assessment->unavailable_at)
                <p class="text-gray-700 mb-4"><strong>Available Until:</strong> {{ $assessment->unavailable_at->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}</p>
            @endif

            @if($assessment->assessment_file_path)
                <div class="mb-4">
                    <p class="text-gray-700 mb-2"><strong>Attachment:</strong></p>
                    <a href="{{ Storage::url($assessment->assessment_file_path) }}" target="_blank" class="text-blue-500 hover:underline">Download File</a>
                </div>
            @endif
    </div>
</x-layout>