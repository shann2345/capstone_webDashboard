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
                <a href="{{ route('courses.show', ['course'=>$course->id]) }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Course Content
                </a>
                <a href="#" class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" aria-current="page">
                    Enrolled Students
                </a>
            </nav>
        </div>

        <div class="mt-6 bg-white p-6 rounded-lg shadow-sm">
            {{-- <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">Section & Student Management</h2>
                    <p class="text-sm text-gray-600 mt-1">Manage sections and assign students to organize your course</p>
                </div>
            </div> --}}

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Create New Program and Section</h3>
                    <form action="{{ route('instructor.createSection', $course->id) }}" method="POST" class="flex space-x-2">
                        @csrf
                        <input type="text" name="name" placeholder="Example: (BSIT-4D, BSCS-3A, etc.)" 
                               class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Create Section
                        </button>
                    </form>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Existing Program and Sections</h3>
                    @if($sections->count() > 0)
                        <div class="space-y-2">
                            @foreach($sections as $section)
                                <div class="flex justify-between items-center bg-white px-3 py-2 rounded border">
                                    <div>
                                        <span class="font-medium">{{ $section->name }}</span>
                                        <span class="text-sm text-gray-500 ml-2">({{ $section->students->count() }} students)</span>
                                    </div>
                                    <form action="{{ route('instructor.deleteSection', $section->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm" 
                                                onclick="return confirm('Are you sure? Students will be moved to no section.')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No sections created yet</p>
                    @endif
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Bulk Section Assignment</h3>
                <form id="bulkAssignForm" action="{{ route('instructor.bulkAssignSection', $course->id) }}" method="POST">
                    @csrf
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select Section:</label>
                            <select name="section_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">No Section</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-shrink-0 pt-6">
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500" disabled id="bulkAssignBtn">
                                Assign Selected Students
                            </button>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">Select students from the table below, then choose a section to assign them to.</p>
                </form>
            </div>

            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Enrolled Students</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $course->students->count() }} student{{ $course->students->count() !== 1 ? 's' : '' }} enrolled in this course</p>
                </div>
                <div class="flex space-x-2">
                    <button onclick="selectAllStudents()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Select All</button>
                    <button onclick="deselectAllStudents()" class="text-gray-600 hover:text-gray-800 text-sm font-medium">Deselect All</button>
                    <button onclick="openAddStudentsModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                        </svg>
                        Add Students
                    </button>
                </div>
            </div>

            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                <input type="checkbox" id="selectAll" onchange="toggleAllStudents()" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold text-gray-900 sm:pl-6">STUDENT NAME</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-900">EMAIL</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-900">CURRENT SECTION</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-900">INDIVIDUAL ASSIGN</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-900">ENROLLED ON</th>       
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($course->students as $student)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                    <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" 
                                           class="student-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                                           onchange="updateBulkAssignButton()">
                                </td>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                    {{ $student->name }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $student->email }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    @if ($student->section)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $student->section->name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            No Section
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <form action="{{ route('instructor.assignSection', $student->id) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        <select name="section_id" class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">No Section</option>
                                            @foreach ($sections as $section)
                                                <option value="{{ $section->id }}" {{ $student->section_id == $section->id ? 'selected' : '' }}>
                                                    {{ $section->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            Assign
                                        </button>
                                    </form>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $student->pivot->enrollment_date ? \Carbon\Carbon::parse($student->pivot->enrollment_date)->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-sm font-medium sm:pr-6">
                                    <form action="{{ route('instructor.removeStudent', [$course->id, $student->id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to remove this student from the course?')">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No students enrolled</h3>
                                        <p class="mt-1 text-sm text-gray-500">Get started by adding students to this course.</p>
                                        <div class="mt-6">
                                            <button onclick="openAddStudentsModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                                </svg>
                                                Add Students
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="addStudentsModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeAddStudentsModal()"></div>

            <div class="relative inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Add Students to Course
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Enter email addresses to add students to {{ $course->title }}. You can add multiple emails separated by commas or line breaks.
                            </p>
                        </div>
                    </div>
                </div>

                <form id="addStudentsForm" action="{{ route('instructor.addStudents', $course->id) }}" method="POST" class="mt-5">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="emails" class="block text-sm font-medium text-gray-700">Student Email Addresses</label>
                            <textarea 
                                name="emails" 
                                id="emails" 
                                rows="4"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm p-2" 
                                placeholder="student1@example.com&#10;student2@example.com&#10;student3@example.com"
                                required
                            ></textarea>
                            <p class="mt-1 text-xs text-gray-500">Separate multiple email addresses with commas, semicolons, or line breaks</p>
                        </div>

                        <div>
                            <label for="modal_section_id" class="block text-sm font-medium text-gray-700">Assign to Section (Optional)</label>
                            <select name="section_id" id="modal_section_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                <option value="">No Section</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Students will be automatically assigned to this section after enrollment</p>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="send_notification" value="1" class="rounded border-gray-300 text-green-600 focus:ring-green-500" checked>
                                <span class="ml-2 text-sm text-gray-700">Send enrollment notification email to students</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:col-start-2 sm:text-sm">
                            Add Students
                        </button>
                        <button type="button" onclick="closeAddStudentsModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Modal functions
        function openAddStudentsModal() {
            document.getElementById('addStudentsModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            // Focus on the email textarea
            setTimeout(() => {
                document.getElementById('emails').focus();
            }, 100);
        }

        function closeAddStudentsModal() {
            document.getElementById('addStudentsModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Clear the form
            document.getElementById('addStudentsForm').reset();
        }

        // Close modal when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAddStudentsModal();
            }
        });

        // Update bulk assign button state
        function updateBulkAssignButton() {
            const checkboxes = document.querySelectorAll('.student-checkbox:checked');
            const bulkAssignBtn = document.getElementById('bulkAssignBtn');
            const bulkForm = document.getElementById('bulkAssignForm');
            
            // Remove existing hidden inputs
            const existingInputs = bulkForm.querySelectorAll('input[name="student_ids[]"]');
            existingInputs.forEach(input => input.remove());
            
            if (checkboxes.length > 0) {
                bulkAssignBtn.disabled = false;
                bulkAssignBtn.textContent = `Assign Selected Students (${checkboxes.length})`;
                
                // Add hidden inputs for selected students
                checkboxes.forEach(checkbox => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'student_ids[]';
                    hiddenInput.value = checkbox.value;
                    bulkForm.appendChild(hiddenInput);
                });
            } else {
                bulkAssignBtn.disabled = true;
                bulkAssignBtn.textContent = 'Assign Selected Students';
            }
        }

        // Toggle all students
        function toggleAllStudents() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            
            studentCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            
            updateBulkAssignButton();
        }

        // Select all students
        function selectAllStudents() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            
            selectAllCheckbox.checked = true;
            studentCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            
            updateBulkAssignButton();
        }

        // Deselect all students
        function deselectAllStudents() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            
            selectAllCheckbox.checked = false;
            studentCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            
            updateBulkAssignButton();
        }

        // Update select all checkbox state based on individual checkboxes
        function updateSelectAllState() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            const checkedCheckboxes = document.querySelectorAll('.student-checkbox:checked');
            
            if (checkedCheckboxes.length === studentCheckboxes.length && studentCheckboxes.length > 0) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else if (checkedCheckboxes.length > 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
        }

        // Add event listeners to individual checkboxes
        document.addEventListener('DOMContentLoaded', function() {
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            studentCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateBulkAssignButton();
                    updateSelectAllState();
                });
            });
        });
    </script>
</x-layout>