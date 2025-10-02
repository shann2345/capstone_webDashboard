{{-- resources/views/instructor/assessment/showQuiz.blade.php --}}

<x-layout>
    <x-slot name="title">
        {{ $assessment->title }} - Assessment Details
    </x-slot>

    <div class="max-w-6xl mx-auto">
        {{-- Header Section --}}
        <div class="bg-gradient-to-r from-purple-600 to-indigo-700 text-white p-8 rounded-t-xl shadow-lg">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm border border-white/30 text-white mr-4">
                            {{ ucfirst($assessment->type) }}
                        </span>
                        @if($assessment->topic)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/10 backdrop-blur-sm border border-white/20 text-white">
                                {{ $assessment->topic->name }}
                            </span>
                        @endif
                    </div>
                    <h1 class="text-4xl font-bold mb-3">{{ $assessment->title }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-purple-100">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Created: {{ $assessment->created_at->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}
                        </div>
                        @if($assessment->creator)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                by {{ $assessment->creator->name }}
                            </div>
                        @endif
                    </div>
                </div>
                
                {{-- Action Buttons --}}
                <div class="flex gap-3 ml-6">
                    <a href="{{ route('assessments.edit.quiz', ['course' => $course->id, 'assessment' => $assessment->id]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg font-medium text-white hover:bg-white/30 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit {{ ucfirst($assessment->type) }}
                    </a>
                    
                    @if ($assessment->assessment_file_path)
                        <a href="{{ Storage::url($assessment->assessment_file_path) }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg font-medium text-white transition-all duration-200 shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download File
                        </a>
                    @endif
                    
                    <a href="{{ route('courses.show', $course->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-lg font-medium text-white hover:bg-white/20 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Course
                    </a>
                </div>
            </div>
        </div>

        {{-- Content Section --}}
        <div class="bg-white rounded-b-xl shadow-lg">
            {{-- Assessment Information --}}
            <div class="p-8 border-b border-gray-200">
                @if($assessment->description)
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-3">Description</h2>
                        <div class="prose max-w-none text-gray-700">
                            <p>{{ $assessment->description }}</p>
                        </div>
                    </div>
                @endif

                {{-- Assessment Details --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {{-- Total Points Display --}}
                    @php
                        $totalPoints = $assessment->total_points;
                        $calculatedPoints = $assessment->questions->sum('points');
                        $isOverride = !is_null($totalPoints) && $totalPoints > 0;
                        $displayPoints = $isOverride ? $totalPoints : ($calculatedPoints > 0 ? $calculatedPoints : 100);
                    @endphp
                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-purple-900">Total Points</span>
                        </div>
                        <p class="text-lg font-semibold text-purple-900 mt-1">{{ $displayPoints }} points</p>
                        @if($isOverride)
                            <p class="text-xs text-purple-600 mt-1">Custom override</p>
                        @else
                            <p class="text-xs text-purple-600 mt-1">Auto-calculated</p>
                        @endif
                    </div>

                    @if($assessment->duration_minutes)
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-blue-900">Duration</span>
                            </div>
                            <p class="text-lg font-semibold text-blue-900 mt-1">{{ $assessment->duration_minutes }} minutes</p>
                        </div>
                    @endif

                    @if($assessment->access_code)
                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span class="text-sm font-medium text-yellow-900">Access Code</span>
                            </div>
                            <p class="text-lg font-semibold text-yellow-900 mt-1">{{ $assessment->access_code }}</p>
                        </div>
                    @endif

                    @if($assessment->available_at)
                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm font-medium text-green-900">Available From</span>
                            </div>
                            <p class="text-sm font-semibold text-green-900 mt-1">{{ $assessment->available_at->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}</p>
                        </div>
                    @endif

                    @if($assessment->unavailable_at)
                        <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm font-medium text-red-900">Available Until</span>
                            </div>
                            <p class="text-sm font-semibold text-red-900 mt-1">{{ $assessment->unavailable_at->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Questions Section --}}
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Quiz Questions</h2>
                    @if(!$assessment->questions->isEmpty())
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            {{ $assessment->questions->count() }} {{ Str::plural('Question', $assessment->questions->count()) }}
                        </span>
                    @endif
                </div>

                @if($assessment->questions->isEmpty())
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Questions Yet</h3>
                        <p class="text-gray-500 mb-6">No questions have been added to this quiz yet.</p>
                        <a href="{{ route('assessments.edit.quiz', ['course' => $course->id, 'assessment' => $assessment->id]) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Questions
                        </a>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($assessment->questions->sortBy('order') as $question)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gradient-to-r from-gray-100 to-gray-50 px-6 py-4 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Question {{ $loop->iteration }}
                                        </h3>
                                        <div class="flex items-center space-x-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
                                            </span>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $question->points }} {{ Str::plural('point', $question->points) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="p-6">
                                    <p class="text-gray-900 font-medium mb-4">{{ $question->question_text }}</p>
                                    
                                    @if($question->question_type === 'multiple_choice')
                                        <div class="space-y-2">
                                            @foreach($question->options->sortBy('option_order') as $option)
                                                <div class="flex items-center p-3 rounded-lg {{ $question->correct_answer == $option->option_order ? 'bg-green-50 border border-green-200' : 'bg-white border border-gray-200' }}">
                                                    <span class="flex items-center justify-center w-6 h-6 rounded-full {{ $question->correct_answer == $option->option_order ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }} text-xs font-medium mr-3">
                                                        {{ chr(65 + $option->option_order) }}
                                                    </span>
                                                    <span class="text-gray-900">{{ $option->option_text }}</span>
                                                    @if($question->correct_answer == $option->option_order)
                                                        <span class="ml-auto inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            Correct
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($question->question_type === 'identification' || $question->question_type === 'true_false')
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-green-800">Correct Answer:</span>
                                                <span class="ml-2 font-semibold text-green-900">{{ $question->correct_answer }}</span>
                                            </div>
                                        </div>
                                    @elseif($question->question_type === 'essay')
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-blue-800">Students will provide a written response</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>