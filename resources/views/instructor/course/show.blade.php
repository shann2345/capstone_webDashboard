{{-- resources/views/courses/show.blade.php --}}

<x-layout>
    <div class="p-2">
        <div class="text-sm text-gray-500 mb-4">
            <a href="{{ route('instructor.myCourse') }}" class="hover:underline">My Courses</a> &gt;
            <span>Course Details</span>
        </div>

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">{{ $course->title }}</h1>
                <div class="flex items-center mt-2 text-gray-600">
                    <span class="mr-2">{{ $course->course_code }}</span>
                    <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">{{ ucfirst($course->status) }}</span>
                </div>
            </div>
        </div>

        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="{{ route('instructor.courseDetails', $course->id) }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Course Info
                </a>
                <a href="#" class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" aria-current="page">
                    Course Content
                </a>
                <a href="{{ route('instructor.courseEnrollee', $course->id) }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Enrolled Students
                </a>
            </nav>
        </div>
    </div>

    <!-- Course Content Header with Add Topic Button -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-blue-100 py-6 px-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Course Content</h2>
                <p class="text-gray-600">Organize your course materials, activities, and assessments by topics</p>
            </div>
            <button
                type="button"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                title="Add Topic"
                id="add-topic-button"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Topic
            </button>
        </div>
    </div>

    @php
        // Merge and sort all items by created_at
        $allItems = collect($course->materials)
            ->map(function($item) { $item->item_type = 'material'; return $item; })
            ->merge(
                $independentAssessments->map(function($item) { $item->item_type = 'assessment'; return $item; })
            )
            ->sortBy('created_at')
            ->groupBy(function($item) {
                return $item->created_at->format('F d, Y');
            });
    @endphp

    <div class="p-6">
        {{-- No Topics Empty State --}}
        @if($topics->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-50 mb-4">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No topics yet</h3>
                <p class="text-gray-500 mb-6">Get started by creating your first topic to organize course content</p>
                <button class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200" onclick="document.getElementById('add-topic-button').click()">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create First Topic
                </button>
            </div>
        @else
            {{-- Topics Section --}}
            <div class="space-y-6">
                @foreach($topics as $index => $topic)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 topic-section" data-topic-id="{{ $topic->id }}">
                        {{-- Topic Header --}}
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-3 flex-grow">
                                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-full font-semibold text-sm">
                                        {{ $index + 1 }}
                                    </div>
                                    <button type="button"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full text-gray-500 hover:bg-gray-200 hover:text-blue-600 transition-colors duration-200 edit-topic-button"
                                        data-topic-id="{{ $topic->id }}"
                                        title="Edit topic name"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </button>
                                    <input
                                        type="text"
                                        value="{{ $topic->name }}"
                                        class="text-xl font-semibold text-gray-900 bg-transparent border-none focus:bg-white focus:border focus:border-blue-300 focus:rounded-lg px-2 py-1 focus:outline-none transition-all duration-200 topic-name-input flex-grow"
                                        disabled
                                        data-topic-id="{{ $topic->id }}"
                                    >
                                </div>
                                {{-- Add Content Dropdown --}}
                                <div class="relative inline-block text-left">
                                    <button type="button"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 topic-add-menu-button"
                                        aria-expanded="false"
                                        aria-haspopup="true"
                                        data-topic-id="{{ $topic->id }}"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Add Content
                                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden z-10 topic-add-menu border"
                                        role="menu"
                                        aria-orientation="vertical"
                                        tabindex="-1"
                                        data-topic-id="{{ $topic->id }}"
                                    >
                                        <div class="py-2" role="none">
                                            <a href="{{ route('materials.create', ['course' => $course->id, 'topic_id' => $topic->id]) }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-150" role="menuitem" tabindex="-1">
                                                <span class="flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 rounded-lg mr-3">📄</span>
                                                <div>
                                                    <div class="font-medium">Material/Resource</div>
                                                    <div class="text-xs text-gray-500">Add documents, links, or files</div>
                                                </div>
                                            </a>
                                            <a href="{{ route('assessments.create.assignment', ['course'=>$course->id, 'typeAct'=>'activity', 'topic_id'=>$topic->id]) }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-150" role="menuitem" tabindex="-1">
                                                <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-lg mr-3">⚡</span>
                                                <div>
                                                    <div class="font-medium">Activity</div>
                                                    <div class="text-xs text-gray-500">Interactive learning activity</div>
                                                </div>
                                            </a>
                                            <a href="{{ route('assessments.create.assignment', ['course'=>$course->id, 'typeAct'=>'assignment', 'topic_id'=>$topic->id]) }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-150" role="menuitem" tabindex="-1">
                                                <span class="flex items-center justify-center w-8 h-8 bg-yellow-100 text-yellow-600 rounded-lg mr-3">📝</span>
                                                <div>
                                                    <div class="font-medium">Assignment</div>
                                                    <div class="text-xs text-gray-500">Graded submission task</div>
                                                </div>
                                            </a>
                                            <a href="{{ route('assessments.create.quiz', ['course'=>$course->id, 'type'=>'exam', 'topic_id'=>$topic->id]) }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-150" role="menuitem" tabindex="-1">
                                                <span class="flex items-center justify-center w-8 h-8 bg-red-100 text-red-600 rounded-lg mr-3">🎯</span>
                                                <div>
                                                    <div class="font-medium">Exam</div>
                                                    <div class="text-xs text-gray-500">Formal examination</div>
                                                </div>
                                            </a>
                                            <a href="{{ route('assessments.create.assignment', ['course'=>$course->id, 'typeAct'=>'project', 'topic_id'=>$topic->id]) }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-150" role="menuitem" tabindex="-1">
                                                <span class="flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-600 rounded-lg mr-3">🚀</span>
                                                <div>
                                                    <div class="font-medium">Project</div>
                                                    <div class="text-xs text-gray-500">Long-term project work</div>
                                                </div>
                                            </a>
                                            <a href="{{ route('assessments.create.quiz', ['course'=>$course->id, 'type'=>'quiz', 'topic_id'=>$topic->id]) }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-150" role="menuitem" tabindex="-1">
                                                <span class="flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg mr-3">❓</span>
                                                <div>
                                                    <div class="font-medium">Quiz</div>
                                                    <div class="text-xs text-gray-500">Quick knowledge check</div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Topic Content --}}
                        @php
                            $topicItems = collect($course->materials)
                                ->where('topic_id', $topic->id)
                                ->map(function($item) { $item->item_type = 'material'; return $item; })
                                ->merge(
                                    $course->assessments->where('topic_id', $topic->id)->map(function($item) { $item->item_type = 'assessment'; return $item; })
                                )
                                ->sortBy('created_at');
                        @endphp

                        @if($topicItems->isEmpty())
                            <div class="px-6 py-8 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <p class="text-sm">No content added yet</p>
                                <p class="text-xs text-gray-400 mt-1">Click "Add Content" to get started</p>
                            </div>
                        @else
                            <div class="divide-y divide-gray-100">
                                @foreach ($topicItems as $item)
                                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-start space-x-4 flex-grow">
                                                {{-- Item Icon --}}
                                                <div class="flex-shrink-0 mt-1">
                                                    @if ($item->item_type == 'material')
                                                        <span class="flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 rounded-lg text-sm">📄</span>
                                                    @elseif ($item->item_type == 'assessment')
                                                        @if ($item->type == 'quiz')
                                                            <span class="flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg text-sm">❓</span>
                                                        @elseif ($item->type == 'exam')
                                                            <span class="flex items-center justify-center w-8 h-8 bg-red-100 text-red-600 rounded-lg text-sm">🎯</span>
                                                        @elseif ($item->type == 'assignment')
                                                            <span class="flex items-center justify-center w-8 h-8 bg-yellow-100 text-yellow-600 rounded-lg text-sm">📝</span>
                                                        @elseif ($item->type == 'project')
                                                            <span class="flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-600 rounded-lg text-sm">🚀</span>
                                                        @else
                                                            <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-lg text-sm">⚡</span>
                                                        @endif
                                                    @endif
                                                </div>
                                                
                                                {{-- Item Content --}}
                                                <div class="flex-grow min-w-0">
                                                    <div class="font-medium text-gray-900 hover:text-blue-600 transition-colors duration-150">
                                                        @if ($item->item_type == 'material')
                                                            <a href="{{ route('materials.show', $item->id) }}" class="block">
                                                                {{ $item->title }}
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 ml-2">Material</span>
                                                            </a>
                                                        @elseif ($item->item_type == 'assessment')
                                                            @if ($item->type == 'quiz' || $item->type == 'exam')
                                                                <a href="{{ route('assessments.show.quiz', ['course' => $course->id, 'assessment' => $item->id]) }}" class="block">
                                                                    {{ $item->title }}
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 ml-2">{{ ucfirst($item->type) }}</span>
                                                                </a>
                                                            @else
                                                                <a href="{{ route('assessments.show.assignment', ['course' => $course->id, 'assessment' => $item->id]) }}" class="block">
                                                                    {{ $item->title }}
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 ml-2">{{ ucfirst($item->type) }}</span>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    @if($item->description)
                                                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $item->description }}</p>
                                                    @endif
                                                    <div class="flex items-center mt-2 text-xs text-gray-500">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        {{ $item->created_at->format('M d, Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            {{-- Actions Dropdown --}}
                                            <div class="relative inline-block text-left ml-4">
                                                <button type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 item-action-button transition-colors duration-150" id="menu-button-{{ $item->id }}" aria-expanded="false" aria-haspopup="true">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                    </svg>
                                                </button>
                                                <div class="item-action-menu origin-top-right absolute right-0 mt-2 w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden z-10 border" role="menu" aria-orientation="vertical" aria-labelledby="menu-button-{{ $item->id }}" tabindex="-1">
                                                    <div class="py-2" role="none">
                                                        @if ($item->item_type == 'material')
                                                            <a href="{{ route('materials.show', $item->id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150" role="menuitem" tabindex="-1">
                                                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                </svg>
                                                                View
                                                            </a>
                                                            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150" role="menuitem" tabindex="-1">
                                                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                                </svg>
                                                                Edit
                                                            </a>
                                                        @elseif ($item->item_type == 'assessment')
                                                            @if ($item->type == 'quiz' || $item->type == 'exam')
                                                                <a href="{{ route('assessments.edit.quiz', ['course' => $course->id, 'assessment' => $item->id]) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150" role="menuitem" tabindex="-1">
                                                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                                    </svg>
                                                                    Edit
                                                                </a>
                                                            @else
                                                                <a href="{{ route('assessments.edit.assignment', ['course' => $course->id, 'assessment' => $item->id]) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150" role="menuitem" tabindex="-1">
                                                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                                    </svg>
                                                                    Edit
                                                                </a>
                                                            @endif
                                                            <form action="{{ route('assessments.destroy', ['course' => $course->id, 'assessment' => $item->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this assessment?');" class="block" role="none">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50 transition-colors duration-150" role="menuitem" tabindex="-1">
                                                                    <svg class="w-4 h-4 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                    </svg>
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <div class="flex justify-end mt-8">
            <a href="{{ route('instructor.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>

    {{-- Add Topic Modal --}}
    <div id="add-topic-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden backdrop-blur-sm">
        <div class="bg-white p-6 rounded-xl shadow-2xl w-96 mx-4 transform transition-all duration-200 scale-95">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-10 h-10 bg-blue-100 text-blue-600 rounded-full mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900">Add New Topic</h2>
            </div>
            <p class="text-gray-600 mb-4">Create a new topic to organize your course content</p>
            <input 
                type="text" 
                id="new-topic-name" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200" 
                placeholder="Enter topic name..."
                maxlength="100"
            >
            <div class="flex justify-end gap-3 mt-6">
                <button id="cancel-add-topic" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">
                    Cancel
                </button>
                <button id="submit-add-topic" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200">
                    Add Topic
                </button>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // --- Global "Add Activity/Resource" Dropdown ---
                const globalMenuButton = document.getElementById('global-add-menu-button');
                const globalAddMenu = document.getElementById('globalAddMenu');

                if (globalMenuButton && globalAddMenu) {
                    globalMenuButton.addEventListener('click', function(event) {
                        event.stopPropagation();
                        globalAddMenu.classList.toggle('hidden');
                    });
                }

                // --- Edit Topic Button and Input (Corrected) ---
                document.querySelectorAll('.edit-topic-button').forEach(button => {
                    button.addEventListener('click', function(event) {
                        const topicId = this.getAttribute('data-topic-id');
                        const topicNameInput = document.querySelector(`.topic-name-input[data-topic-id="${topicId}"]`);

                        if (topicNameInput) {
                            topicNameInput.disabled = false;
                            topicNameInput.focus();
                            topicNameInput.select(); // Select the text for easier editing
                        }
                    });
                });

                document.querySelectorAll('.topic-name-input').forEach(input => {
                    input.addEventListener('blur', function(event) {
                        const updatedTopicName = this.value.trim();
                        const topicId = this.getAttribute('data-topic-id');

                        // Only send request if the name has actually changed
                        if (updatedTopicName && this.defaultValue !== updatedTopicName) {
                            fetch(`/topics/${topicId}`, { // Use dynamic topicId in the URL
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ name: updatedTopicName })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log('Topic updated:', data);
                                // Set defaultValue to the new value so subsequent blur events only fire on actual changes
                                this.defaultValue = updatedTopicName;
                                // Show success feedback
                                this.classList.add('bg-green-50', 'border-green-300');
                                setTimeout(() => {
                                    this.classList.remove('bg-green-50', 'border-green-300');
                                }, 1000);
                            })
                            .catch(error => {
                                console.error('Error updating topic:', error);
                                alert('Error updating topic: ' + error.message);
                                // Revert to original value if update fails
                                this.value = this.defaultValue;
                            })
                            .finally(() => {
                                // Always disable the input after blur, regardless of success/failure
                                this.disabled = true;
                            });
                        } else {
                            // If no change or empty, just disable
                            this.disabled = true;
                        }
                    });

                    // Also, disable on 'keydown' if 'Enter' is pressed
                    input.addEventListener('keydown', function(event) {
                        if (event.key === 'Enter') {
                            event.preventDefault(); // Prevent new line in input
                            this.blur(); // Trigger blur to save changes
                        }
                        if (event.key === 'Escape') {
                            // Revert changes on escape
                            this.value = this.defaultValue;
                            this.disabled = true;
                        }
                    });
                });

                // --- Per-topic "Add Activity/Resource" Dropdowns (corrected to use forEach) ---
                document.querySelectorAll('.topic-add-menu-button').forEach(button => {
                    button.addEventListener('click', function(event) {
                        event.stopPropagation();
                        const topicId = this.getAttribute('data-topic-id');
                        const menu = document.querySelector(`.topic-add-menu[data-topic-id="${topicId}"]`);

                        // Close other topic menus
                        document.querySelectorAll('.topic-add-menu').forEach(openMenu => {
                            if (openMenu !== menu) {
                                openMenu.classList.add('hidden');
                            }
                        });

                        if (menu) {
                            menu.classList.toggle('hidden');
                        }
                    });
                });

                // --- Add Topic Modal Logic ---
                const addTopicButton = document.getElementById('add-topic-button');
                const addTopicModal = document.getElementById('add-topic-modal');
                const cancelAddTopic = document.getElementById('cancel-add-topic');
                const submitAddTopic = document.getElementById('submit-add-topic');
                const newTopicName = document.getElementById('new-topic-name');
                const courseId = {{ $course->id }};

                if (addTopicButton && addTopicModal) {
                    addTopicButton.addEventListener('click', function() {
                        addTopicModal.classList.remove('hidden');
                        addTopicModal.querySelector('.transform').classList.remove('scale-95');
                        addTopicModal.querySelector('.transform').classList.add('scale-100');
                        newTopicName.value = '';
                        newTopicName.focus();
                    });
                }
                
                if (cancelAddTopic) {
                    cancelAddTopic.addEventListener('click', function() {
                        addTopicModal.querySelector('.transform').classList.add('scale-95');
                        addTopicModal.querySelector('.transform').classList.remove('scale-100');
                        setTimeout(() => {
                            addTopicModal.classList.add('hidden');
                        }, 150);
                    });
                }
                
                if (submitAddTopic) {
                    submitAddTopic.addEventListener('click', function() {
                        const name = newTopicName.value.trim();
                        if (!name) {
                            alert('Please enter a topic name.');
                            newTopicName.focus();
                            return;
                        }
                        
                        // Disable button and show loading state
                        submitAddTopic.disabled = true;
                        submitAddTopic.innerHTML = 'Adding...';
                        
                        fetch('{{ route('topics.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ name: name, course_id: courseId })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('New topic added:', data);
                            location.reload(); // Simplest: reload to show new topic
                        })
                        .catch(error => {
                            console.error('Error adding topic:', error);
                            alert('Error adding topic: ' + error.message);
                        })
                        .finally(() => {
                            // Re-enable button
                            submitAddTopic.disabled = false;
                            submitAddTopic.innerHTML = 'Add Topic';
                        });
                    });
                }

                // Handle Enter key in topic name input
                if (newTopicName) {
                    newTopicName.addEventListener('keydown', function(event) {
                        if (event.key === 'Enter') {
                            event.preventDefault();
                            submitAddTopic.click();
                        }
                        if (event.key === 'Escape') {
                            cancelAddTopic.click();
                        }
                    });
                }

                // Close modal when clicking outside
                addTopicModal?.addEventListener('click', function(event) {
                    if (event.target === addTopicModal) {
                        cancelAddTopic.click();
                    }
                });

                window.addEventListener('click', function(event) {
                    // Close global menu
                    if (globalMenuButton && globalAddMenu && !globalMenuButton.contains(event.target) && !globalAddMenu.contains(event.target)) {
                        globalAddMenu.classList.add('hidden');
                    }

                    // Close all topic add menus
                    document.querySelectorAll('.topic-add-menu').forEach(menu => {
                        const menuButton = menu.previousElementSibling;
                        if (menuButton && !menuButton.contains(event.target) && !menu.contains(event.target)) {
                            menu.classList.add('hidden');
                        }
                    });

                    // Close all item action menus
                    document.querySelectorAll('.item-action-menu').forEach(menu => {
                        // Find the parent container that holds both the button and the menu
                        const parentContainer = menu.closest('.relative.inline-block.text-left');
                        if (parentContainer && !parentContainer.contains(event.target)) {
                            menu.classList.add('hidden');
                        }
                    });
                });

                // --- Item Action Dropdown Logic ---
                document.querySelectorAll('.item-action-button').forEach(button => {
                    button.addEventListener('click', function(event) {
                        event.stopPropagation(); // Prevent click from bubbling up and closing other menus

                        // Find the associated menu using the aria-labelledby attribute
                        const menuId = this.id;
                        const menu = document.querySelector(`.item-action-menu[aria-labelledby="${menuId}"]`);

                        // Close other item action menus
                        document.querySelectorAll('.item-action-menu').forEach(openMenu => {
                            if (openMenu !== menu) {
                                openMenu.classList.add('hidden');
                            }
                        });

                        if (menu) {
                            menu.classList.toggle('hidden');
                        }
                    });
                });
            });
        </script>
    </x-slot>
</x-layout>