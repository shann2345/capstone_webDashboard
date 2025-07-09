<x-layout>
    <x-slot name="title">
        {{ $assessment->title }} - Quiz Details
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
            <p class="text-gray-700 mb-2"><strong>Available From:</strong> {{ $assessment->available_at->format('M d, Y h:i A') }}</p>
        @endif

        @if($assessment->unavailable_at)
            <p class="text-gray-700 mb-4"><strong>Available Until:</strong> {{ $assessment->unavailable_at->format('M d, Y h:i A') }}</p>
        @endif

        @if($assessment->assessment_file_path)
            <div class="mb-4">
                <p class="text-gray-700 mb-2"><strong>Attachment:</strong></p>
                <a href="{{ Storage::url($assessment->assessment_file_path) }}" target="_blank" class="text-blue-500 hover:underline">Download File</a>
            </div>
        @endif

        <h2 class="text-2xl font-bold text-gray-800 mb-4 mt-6">Questions</h2>

        @if($assessment->questions->isEmpty())
            <p class="text-gray-600">No questions have been added to this quiz yet.</p>
        @else
            <div class="space-y-6">
                @foreach($assessment->questions->sortBy('order') as $question)
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-lg font-semibold text-gray-800 mb-2">
                            Q{{ $loop->iteration }}: {{ $question->question_text }}
                            <span class="text-sm font-normal text-gray-500">({{ $question->points }} points)</span>
                        </p>
                        <p class="text-sm text-gray-600 mb-3">Type: {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</p>

                        @if($question->question_type === 'multiple_choice')
                            <div class="ml-4 space-y-2">
                                @foreach($question->options->sortBy('option_order') as $option)
                                    <div class="flex items-center">
                                        <input type="radio" id="option_{{ $option->id }}" name="question_{{ $question->id }}" value="{{ $option->option_text }}" class="mr-2 cursor-not-allowed" disabled>
                                        <label for="option_{{ $option->id }}" class="text-gray-700">{{ $option->option_text }}</label>
                                        @if($question->correct_answer === $option->option_text)
                                            <span class="ml-2 text-green-600 font-semibold">(Correct Answer)</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @elseif($question->question_type === 'identification' || $question->question_type === 'true_false')
                            <p class="text-gray-700 ml-4"><strong>Correct Answer:</strong> {{ $question->correct_answer }}</p>
                        @elseif($question->question_type === 'essay')
                            <p class="text-gray-700 ml-4 italic">Students will provide a written response.</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
        <div class="flex justify-end mt-6">
            <a href="{{ route('assessments.edit.quiz',  ['course' => $course->id, 'assessment' => $assessment->id]) }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800 mr-4">
                Edit
            </a>
            <a href="{{ route('courses.show', $assessment->course_id) }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Back to Course Details
            </a>
        </div>
    </div>
</x-layout>