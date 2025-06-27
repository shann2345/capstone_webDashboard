<x-layout>
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
    <div class="flex justify-end mb-8">
        <div class="relative inline-block text-left">
            <div>
                <button type="button" class="inline-flex justify-center w-full border-transparent shadow-sm px-4 py-2 text-black font-medium hover:bg-blue-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="global-add-menu-button" aria-expanded="true" aria-haspopup="true">
                    + Add an Activity or Resource
                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden z-10" role="menu" aria-orientation="vertical" aria-labelledby="global-add-menu-button" tabindex="-1" id="globalAddMenu">
                <div class="py-1" role="none">
                    <a href="{{ route('materials.create', $course->id) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                        <span class="inline-block w-5 mr-2 text-center">&#128193;</span> Add Material/Resource
                    </a>
                    <a href="{{ route('assessments.create', $course->id) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                        <span class="inline-block w-5 mr-2 text-center">&#128220;</span> Add Quiz/Activity (Independent)
                    </a>
                </div>
            </div>
        </div>
    </div>
    {{-- End Global Dropdown --}}

    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Course Content</h2>

    {{-- List Existing Materials --}}
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Materials</h2>
        @if ($course->materials->isEmpty())
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative text-center" role="alert">
                <span class="block sm:inline">No materials have been uploaded for this course yet.</span>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Availability</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($course->materials as $material)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $material->title }}</div>
                                    @if($material->description)
                                        <div class="text-sm text-gray-500">{{ Str::limit($material->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ ucfirst($material->material_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($material->available_at || $material->unavailable_at)
                                        @if($material->isAvailable())
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Available</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Scheduled</span>
                                        @endif
                                        @if($material->available_at)
                                            <div class="text-xs text-gray-400">From: {{ $material->available_at->format('M d, Y H:i A') }}</div>
                                        @endif
                                        @if($material->unavailable_at)
                                            <div class="text-xs text-gray-400">Until: {{ $material->unavailable_at->format('M d, Y H:i A') }}</div>
                                        @endif
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Always Available</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $material->created_at->format('M d, Y') }}
                                </td>
                                {{-- Actions Column with new dropdown below existing links --}}
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex justify-end items-center mb-2">
                                        @if($material->file_path)
                                            <a href="{{ route('materials.download', $material->id) }}" class="text-blue-600 hover:text-blue-900 mr-4">Download</a>
                                        @endif
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                                        <form action="#" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </div>
                                    {{-- NEW: Per-Material Add Assessment Dropdown --}}
                                    <div class="relative inline-block text-left mt-2">
                                        <div> {{-- This div wraps the button --}}
                                            <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 material-add-button" data-material-id="{{ $material->id }}" aria-expanded="true" aria-haspopup="true">
                                                Add Assessment
                                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>

                                        {{-- This is the menu div, sibling to the button's wrapper div --}}
                                        <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden z-10 material-add-menu" role="menu" aria-orientation="vertical" tabindex="-1">
                                            <div class="py-1" role="none">
                                                {{-- Pass course_id AND material_id to the create assessment form --}}
                                                <a href="{{ route('assessments.create', ['course' => $course->id, 'material_id' => $material->id]) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                                                    <span class="inline-block w-5 mr-2 text-center">&#128220;</span> Add Quiz/Activity
                                                </a>
                                                {{-- Add more assessment types here if needed, linking to createAssessment with this material_id --}}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Section to list standalone Assessments --}}
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Assessments (Independent)</h2>
        @if ($independentAssessments->isEmpty())
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative text-center" role="alert">
                <span class="block sm:inline">No independent assessments have been created for this course yet.</span>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Associated Material</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($independentAssessments as $assessment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $assessment->title }}</div>
                                    @if($assessment->description)
                                        <div class="text-sm text-gray-500">{{ Str::limit($assessment->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        {{ ucfirst($assessment->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($assessment->material)
                                        {{-- Link to material's show page if you have one, otherwise just display title --}}
                                        <a href="#" class="text-blue-600 hover:text-blue-900">{{ $assessment->material->title }}</a>
                                    @else
                                        N/A (Independent)
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $assessment->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-4">View/Edit</a>
                                    <form action="#" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>


    <div class="flex justify-end mt-6">
        <a href="{{ route('instructor.dashboard') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
            Back to Dashboard
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Global "Add Activity/Resource" Dropdown ---
            const globalMenuButton = document.getElementById('global-add-menu-button');
            const globalAddMenu = document.getElementById('globalAddMenu');

            if (globalMenuButton && globalAddMenu) {
                globalMenuButton.addEventListener('click', function(event) {
                    event.stopPropagation(); // Prevents this click from closing other dropdowns or the window listener
                    globalAddMenu.classList.toggle('hidden');
                });
            }

            // --- Per-Material "Add Assessment" Dropdowns ---
            const materialAddButtons = document.querySelectorAll('.material-add-button');
            materialAddButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.stopPropagation(); // Prevent document click from immediately closing

                    // CRITICAL CHANGE: Get the menu element correctly based on your HTML structure.
                    // The button is inside a div, and the menu is a sibling of that div.
                    const menu = this.parentElement.nextElementSibling; // Get parent div of button, then its next sibling

                    // Close any other open material menus
                    document.querySelectorAll('.material-add-menu').forEach(openMenu => {
                        if (openMenu !== menu) { // Only hide if it's a different menu
                            openMenu.classList.add('hidden');
                        }
                    });
                    menu.classList.toggle('hidden'); // Toggle the clicked menu
                });
            });

            // Close all dropdowns if the user clicks outside of any dropdown button or menu
            window.addEventListener('click', function(event) {
                // Close global menu
                if (globalMenuButton && globalAddMenu && !globalMenuButton.contains(event.target) && !globalAddMenu.contains(event.target)) {
                    globalAddMenu.classList.add('hidden');
                }

                // Close all material menus
                document.querySelectorAll('.material-add-menu').forEach(menu => {
                    // The menu's parent is the 'relative inline-block' div.
                    // The button's parent is the 'div' inside that 'relative inline-block' div.
                    // We need to check if the click target is within the button's direct parent, or the menu itself.
                    const menuContainer = menu.parentElement;
                    const buttonWrapper = menuContainer ? menuContainer.querySelector('.material-add-button').parentElement : null; // Get the div wrapping the button

                    if (buttonWrapper && !buttonWrapper.contains(event.target) && !menu.contains(event.target)) {
                        menu.classList.add('hidden');
                    }
                });
            });
        });
    </script>
</x-layout>