{{-- resources/views/courses/show.blade.php --}}

<x-layout>
    <x-slot name="title">
        {{ $course->title }} - Course Details
    </x-slot>

    <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $course->title }} ({{ $course->course_code }})</h1>
    <p class="text-gray-600 mb-2">Program: {{ $course->program->name ?? 'N/A' }}</p>
    <p class="text-gray-600 mb-4">Instructor: {{ $course->instructor->name ?? 'N/A' }}</p>

    {{-- Display success message --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Global Dropdown Button for Adding Activity/Resource --}}
    <div class="flex justify-between items-center mb-4">
        <button
                type="button"
                class="inline-flex items-center justify-center rounded-full bg-blue-600 text-white w-8 h-8 text-2xl font-bold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                title="Add Topic"
                id="add-topic-button"
            >+</button>
        <div class="relative inline-block text-left">
            <div>
                <button type="button" class="inline-flex justify-center w-full border-transparent shadow-sm px-4 py-2 text-black font-medium hover:bg-blue-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="global-add-menu-button" aria-expanded="true" aria-haspopup="true">
                    + Activity or Resource
                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden z-10" role="menu" aria-orientation="vertical" aria-labelledby="global-add-menu-button" tabindex="-1" id="globalAddMenu">
                <div class="py-1" role="none">
                    <a href="{{ route('materials.create', $course->id) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                        <span class="inline-block w-5 mr-2 text-center">&#128193;</span> Material/Resource
                    </a>
                    {{-- Global Assessment creation links (without material_id) --}}
                    <a href="{{ route('assessments.create.assignment', ['course'=>$course->id, 'typeAct'=>'activity']) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                        <span class="inline-block w-5 mr-2 text-center">&#128220;</span> Activity
                    </a>
                    <a href="{{ route('assessments.create.assignment', ['course'=>$course->id, 'typeAct'=>'assignment']) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                        <span class="inline-block w-5 mr-2 text-center">&#128220;</span> Assignment
                    </a>
                    <a href="{{ route('assessments.create.quiz', ['course'=>$course->id, 'type'=>'exam']) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                        <span class="inline-block w-5 mr-2 text-center">&#128220;</span> Exam
                    </a>
                    <a href="{{ route('assessments.create.assignment', ['course'=>$course->id, 'typeAct'=>'project']) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                        <span class="inline-block w-5 mr-2 text-center">&#128220;</span> Project
                    </a>
                    <a href="{{ route('assessments.create.quiz', ['course'=>$course->id, 'type'=>'quiz']) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                        <span class="inline-block w-5 mr-2 text-center">&#128220;</span> Quiz
                    </a>
                </div>
            </div>
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

    {{--- Topic section ---}}
    @if($topics->isEmpty())
        <div class="bg-white p-6 shadow-md mb-8 text-center text-gray-500">
            No topics yet for this course. Click the '+' button above to add your first topic!
        </div>
    @else
        @foreach($topics as $topic)
            <div class="bg-white p-6 shadow_md overflow-visible topic-section mb-8" data-topic-id="{{ $topic->id }}">
                <div class="flex justify-between items-center mb-2">
                    <div class="flex items-center gap-2 text-left w-full">
                        <button type="button"
                            class="inline-flex items-center justify-center w-8 h-8 rounded-full border-transparent shadow-sm p-1 text-black font-medium
                                hover:bg-blue-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                                edit-topic-button"
                            data-topic-id="{{ $topic->id }}"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                        </button>
                        <input
                            type="text"
                            value="{{ $topic->name }}"
                            class="text-center text-2xl min-w-[70px] w-auto px-2 py-1 disabled:bg-transparent focus:bg-white focus:outline-none transition-all duration-200 topic-name-input"
                            disabled
                            data-topic-id="{{ $topic->id }}"
                        >
                    </div>
                    {{-- Per-topic Add Activity/Resource Dropdown --}}
                    <div class="relative inline-block text-left">
                        <button type="button"
                            class="inline-flex justify-center w-56 border-transparent shadow-sm px-4 text-black text-sm font-medium hover:bg-blue-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 topic-add-menu-button"
                            aria-expanded="false"
                            aria-haspopup="true"
                            data-topic-id="{{ $topic->id }}"
                        >
                            + Activity or Resource
                            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden z-10 topic-add-menu"
                            role="menu"
                            aria-orientation="vertical"
                            tabindex="-1"
                            data-topic-id="{{ $topic->id }}"
                        >
                            <div class="py-1" role="none">
                                <a href="{{ route('materials.create', ['course' => $course->id, 'topic_id' => $topic->id]) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                                    <span class="inline-block w-5 mr-2 text-center">&#128193;</span> Material/Resource
                                </a>
                                <a href="{{ route('assessments.create.assignment', ['course'=>$course->id, 'typeAct'=>'activity', 'topic_id'=>$topic->id]) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                                    <span class="inline-block w-5 mr-2 text-center">&#128220;</span> Activity
                                </a>
                                <a href="{{ route('assessments.create.assignment', ['course'=>$course->id, 'typeAct'=>'assignment', 'topic_id'=>$topic->id]) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                                    <span class="inline-block w-5 mr-2 text-center">&#128220;</span> Assignment
                                </a>
                                <a href="{{ route('assessments.create.quiz', ['course'=>$course->id, 'type'=>'exam', 'topic_id'=>$topic->id]) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                                    <span class="inline-block w-5 mr-2 text-center">&#128220;</span> Exam
                                </a>
                                <a href="{{ route('assessments.create.assignment', ['course'=>$course->id, 'typeAct'=>'project', 'topic_id'=>$topic->id]) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                                    <span class="inline-block w-5 mr-2 text-center">&#128220;</span> Project
                                </a>
                                <a href="{{ route('assessments.create.quiz', ['course'=>$course->id, 'type'=>'quiz', 'topic_id'=>$topic->id]) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                                    <span class="inline-block w-5 mr-2 text-center">&#128220;</span> Quiz
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Show materials/assessments for this topic --}}
                @php
                    $topicItems = collect($course->materials)
                        ->where('topic_id', $topic->id)
                        ->map(function($item) { $item->item_type = 'material'; return $item; })
                        ->merge(
                            $course->assessments->where('topic_id', $topic->id)->map(function($item) { $item->item_type = 'assessment'; return $item; })
                        )
                        ->sortBy('created_at')
                        ->groupBy(function($item) {
                            return $item->created_at->format('F d, Y');
                        });
                @endphp

                @foreach ($topicItems as $date => $items)
                    <div class="mb-6 ml-8">
                        <table class="min-w-full divide-y divide-gray-200 mb-4">
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- resources/views/courses/show.blade.php --}}

{{-- ... existing code ... --}}

{{-- Inside the loop where you display assessments --}}
@foreach ($items as $item)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap align-top">
            <div class="flex justify-between items-start">
                <div class="flex flex-col space-y-1">
                    <div class="text-md font-medium font-style-bold text-gray-900">
                        @if ($item->item_type == 'material')
                            <a href="{{ route('materials.show', $item->id) }}">&#128193; {{ $item->title }}</a>
                        @elseif ($item->item_type == 'assessment')
                            @if ($item->type == 'quiz' || $item->type == 'exam')
                                <a href="{{ route('assessments.show.quiz', ['course' => $course->id, 'assessment' => $item->id]) }}">&#128220; {{ $item->title }} ({{ ucfirst($item->type) }})</a>
                            @else
                                <a href="{{ route('assessments.show.assignment', ['course' => $course->id, 'assessment' => $item->id]) }}">&#128220; {{ $item->title }} ({{ ucfirst($item->type) }})</a>
                            @endif
                        @endif
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $item->description }}
                    </div>
                </div>
                {{-- Actions Dropdown for Materials/Assessments --}}
                <div class="relative inline-block text-left">
                    <div>
                        <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-2 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 item-action-button" id="menu-button-{{ $item->id }}" aria-expanded="true" aria-haspopup="true">
                            Options
                            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <div class="item-action-menu origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden z-10" role="menu" aria-orientation="vertical" aria-labelledby="menu-button-{{ $item->id }}" tabindex="-1">
                        <div class="py-1" role="none">
                            @if ($item->item_type == 'material')
                                {{-- Material actions --}}
                                <a href="{{ route('materials.show', $item->id) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">View</a>
                                <a href="" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">Edit</a>
                                {{-- Add delete form for materials if needed --}}
                            @elseif ($item->item_type == 'assessment')
                                {{-- Assessment actions --}}
                                @if ($item->type == 'quiz' || $item->type == 'exam')
                                    <a href="{{ route('assessments.edit.quiz', ['course' => $course->id, 'assessment' => $item->id]) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">Edit</a>
                                @else
                                    <a href="{{ route('assessments.edit.assignment', ['course' => $course->id, 'assessment' => $item->id]) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">Edit</a>
                                @endif
                                <form action="{{ route('assessments.destroy', ['course' => $course->id, 'assessment' => $item->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this assessment?');" class="block" role="none">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
@endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif


    {{-- Optionally, show materials/assessments with no topic --}}
    {{-- @php
        $untopicedItems = collect($course->materials)
            ->whereNull('topic_id')
            ->map(function($item) { $item->item_type = 'material'; return $item; })
            ->merge(
                $independentAssessments->whereNull('topic_id')->map(function($item) { $item->item_type = 'assessment'; return $item; })
            )
            ->sortBy('created_at')
            ->groupBy(function($item) {
                return $item->created_at->format('F d, Y');
            });
    @endphp

    @if($untopicedItems->count())
        <div class="bg-white p-6 shadow-md overflow-visible topic-section mb-8" data-topic-id="none">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Items Without a Topic</h2>
            @foreach ($untopicedItems as $date => $items)
                <div class="mb-6 ml-8">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">{{ $date }}</h3>
                    <table class="min-w-full divide-y divide-gray-200 mb-4">
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap align-top">
                                        <div class="flex flex-col space-y-1">
                                            <div class="text-xl font-medium font-style-bold text-gray-900">
                                                @if ($item->item_type == 'material')
                                                    <a href="{{ route('materials.show', $item->id) }}">&#128193; {{ $item->title }}</a>
                                                @elseif ($item->item_type == 'assessment')
                                                    <a href="{{ route('assessments.show', $item->id) }}">&#128220; {{ $item->title }} ({{ ucfirst($item->type) }})</a>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $item->description }}
                                            </div>
                                    
                                            <div class="relative inline-block text-left mt-2">
                                                <div>
                                                    <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-2 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 item-action-button" id="options-menu-button-{{ $item->item_type }}-{{ $item->id }}-untopiced" aria-expanded="true" aria-haspopup="true">
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden z-10 item-action-menu" role="menu" aria-orientation="vertical" aria-labelledby="options-menu-button-{{ $item->item_type }}-{{ $item->id }}-untopiced" tabindex="-1">
                                                    <div class="py-1" role="none">
                                                        @if($item->item_type === 'material')
                                                            @if($item->file_path)
                                                                <a href="{{ route('materials.download', $item->id) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">Download</a>
                                                            @endif
                                                            <a href="{{ route('materials.edit', $item->id) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">Edit</a>
                                                            <form action="{{ route('materials.destroy', $item->id) }}" method="POST" class="block" role="none">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 w-full text-left block px-4 py-2 text-sm hover:bg-red-100 hover:text-red-900" role="menuitem" tabindex="-1" onclick="return confirm('Are you sure you want to delete this material? This action cannot be undone.')">Delete</button>
                                                            </form>
                                                        @else 
                                                            <a href="{{ route('assessments.edit', $item->id) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">View/Edit</a>
                                                            <form action="{{ route('assessments.destroy', $item->id) }}" method="POST" class="block" role="none">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 w-full text-left block px-4 py-2 text-sm hover:bg-red-100 hover:text-red-900" role="menuitem" tabindex="-1" onclick="return confirm('Are you sure you want to delete this assessment? All student submissions and grades will be permanently lost.')">Delete</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    @endif --}}


    <div class="flex justify-end mt-6">
        <a href="{{ route('instructor.dashboard') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
            Back to Dashboard
        </a>
    </div>

    {{-- Add Topic Modal --}}
    <div id="add-topic-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-30 z-50 hidden">
        <div class="bg-white p-6 rounded shadow-md w-80">
            <h2 class="text-lg font-bold mb-2">Add Topic</h2>
            <input type="text" id="new-topic-name" class="border rounded w-full px-2 py-1 mb-4" placeholder="Topic name">
            <div class="flex justify-end gap-2">
                <button id="cancel-add-topic" class="px-3 py-1 bg-gray-300 rounded">Cancel</button>
                <button id="submit-add-topic" class="px-3 py-1 bg-blue-600 text-white rounded">Add</button>
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
                        newTopicName.value = '';
                        newTopicName.focus();
                    });
                }
                if (cancelAddTopic) {
                    cancelAddTopic.addEventListener('click', function() {
                        addTopicModal.classList.add('hidden');
                    });
                }
                if (submitAddTopic) {
                    submitAddTopic.addEventListener('click', function() {
                        const name = newTopicName.value.trim();
                        if (!name) {
                            alert('Please enter a topic name.');
                            return;
                        }
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
                            });
                    });
                }

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