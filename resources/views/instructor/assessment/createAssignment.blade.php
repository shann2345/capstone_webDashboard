{{-- resources/views/instructor/assessment/createAssignment.blade.php --}}

<x-layout>
    <x-slot name="title">
        Create {{ ucfirst($assessmentType) }} - {{ $course->title }}
    </x-slot>

    <div class="container mx-auto px-4 py-6">
        <div class="flex flex-col">
            <div class="w-full">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Create {{ ucfirst($assessmentType) }}</h1>
                        <p class="text-gray-600 text-lg">Course: {{ $course->title }}</p>
                    </div>
                    <a href="{{ route('courses.show', $course->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition duration-300 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Course
                    </a>
                </div>

                <!-- Main Form Card -->
                <div class="bg-white p-8 rounded-lg shadow-xl mb-8">
                    <h2 class="text-2xl font-semibold text-gray-700 mb-6">{{ ucfirst($assessmentType) }} Details</h2>
                    <form method="POST" action="{{ route('assessments.store.assignment', $course->id) }}" enctype="multipart/form-data" id="createAssessmentForm">
                        @csrf

                        {{-- Hidden input for assessment type --}}
                        <input type="hidden" name="type" value="{{ $assessmentType }}">

                        {{-- Display Validation Errors --}}
                        <div id="form-errors" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
                            <h6 class="font-bold mb-2">Please fix the following errors:</h6>
                            <ul id="error-list" class="list-disc list-inside">
                                {{-- Errors will be dynamically inserted here --}}
                            </ul>
                        </div>
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <h6 class="font-bold mb-2">Please fix the following errors:</h6>
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Associated Material -->
                        <div class="mb-4">
                            <label for="material_id" class="block text-gray-700 text-sm font-bold mb-2">Associated Material (Optional)</label>
                            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('material_id') border-red-500 @enderror" id="material_id" name="material_id">
                                <option value="">No associated material</option>
                                @foreach($course->materials as $material)
                                    <option value="{{ $material->id }}"
                                            {{ (old('material_id') == $material->id) ? 'selected' : '' }}>
                                        {{ $material->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('material_id')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Basic Assessment Information -->
                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">{{ ucfirst($assessmentType) }} Title <span class="text-red-500">*</span></label>
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror"
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description / Instructions:</label>
                            <textarea id="description" name="description" rows="5"
                                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror"
                                      placeholder="Enter {{ $assessmentType }} description...">{{ old('description') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">A description or an uploaded file is required for this assessment type.</p>
                            @error('description')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Upload Section -->
                        <div id="fileUploadSection" class="mt-6 p-6 border border-gray-200 rounded-lg bg-gray-50 shadow-sm">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Upload Assessment File (Optional)</h2>
                            <div class="mb-4">
                                <label for="assessment_file" class="block text-gray-700 text-sm font-bold mb-2">Upload File (e.g., PDF, Word, Excel for briefs):</label>
                                <input type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('assessment_file') border-red-500 @enderror"
                                       id="assessment_file" name="assessment_file"
                                       accept=".pdf,.doc,.docx,.xlsx,.xls,.ppt,.pptx,.txt,.zip,.rar">
                                <p class="mt-1 text-sm text-gray-500">
                                    Supported formats: PDF, DOC, DOCX, XLSX, XLS, PPT, PPTX, TXT, ZIP, RAR (Max: 20MB).
                                    A description or an uploaded file is required for this assessment type.
                                </p>
                                @error('assessment_file')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Availability Timestamps -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4 mt-6">
                            <div>
                                <label for="available_at" class="block text-gray-700 text-sm font-bold mb-2">Available From (Optional Date/Time):</label>
                                <input type="datetime-local" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('available_at') border-red-500 @enderror"
                                       id="available_at" name="available_at" value="{{ old('available_at') }}">
                                <p class="text-gray-500 text-xs italic mt-1">Leave empty for immediate availability</p>
                                @error('available_at')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="unavailable_at" class="block text-gray-700 text-sm font-bold mb-2">Available Until (Optional Date/Time):</label>
                                <input type="datetime-local" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('unavailable_at') border-red-500 @enderror"
                                       id="unavailable_at" name="unavailable_at" value="{{ old('unavailable_at') }}">
                                <p class="text-gray-500 text-xs italic mt-1">Leave empty for no end date</p>
                                @error('unavailable_at')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end gap-4 mt-8">
                            <a href="{{ route('courses.show', $course->id) }}" class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition duration-300 ease-in-out">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition duration-300 ease-in-out" id="submitAssessmentButton">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Create {{ ucfirst($assessmentType) }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Upload Progress Modal --}}
    <div id="uploadProgressModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-sm">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Creating Assessment...</h3>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div id="progressBar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300 ease-in-out" style="width: 0%"></div>
            </div>
            <p id="progressText" class="text-sm text-gray-600 text-center">0% Complete</p>
        </div>
    </div>

    <x-slot name="scripts">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('createAssessmentForm');
                const submitButton = document.getElementById('submitAssessmentButton');
                const uploadProgressModalElement = document.getElementById('uploadProgressModal');
                const progressBar = document.getElementById('progressBar');
                const progressText = document.getElementById('progressText');
                const formErrorsDiv = document.getElementById('form-errors');
                const errorList = document.getElementById('error-list');
                const assessmentFileField = document.getElementById('assessment_file');
                const descriptionField = document.getElementById('description');

                // Helper function to reset submit button and hide modal
                function resetSubmitButtonAndModal() {
                    submitButton.removeAttribute('disabled');
                    submitButton.innerHTML = `<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg> Create {{ ucfirst($assessmentType) }}`;
                    uploadProgressModalElement.classList.add('hidden');
                }

                // --- Form Submission Logic ---
                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    errorList.innerHTML = '';
                    formErrorsDiv.classList.add('hidden');

                    let clientErrors = [];

                    if (!document.getElementById('title').value.trim()) {
                        clientErrors.push('Assessment Title is required.');
                    }

                    // Specific validation for Assignment/Activity form
                    const hasFile = assessmentFileField.files.length > 0;
                    const hasDescription = descriptionField.value.trim().length > 0;

                    if (!hasFile && !hasDescription) {
                        clientErrors.push('For Assignment or Activity, either a description or an assessment file is required.');
                    }

                    if (clientErrors.length > 0) {
                        clientErrors.forEach(error => {
                            const li = document.createElement('li');
                            li.textContent = error;
                            errorList.appendChild(li);
                        });
                        formErrorsDiv.classList.remove('hidden');
                        resetSubmitButtonAndModal();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        return;
                    }

                    submitButton.setAttribute('disabled', 'disabled');
                    submitButton.innerHTML = `<svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 0020 14c0 1.577-.384 3.064-1.07 4.394M16 4.606A8.001 8.001 0 004 10c0 1.577.384 3.064 1.07 4.394"></path></svg> Creating...`;
                    uploadProgressModalElement.classList.remove('hidden');

                    progressBar.style.width = '0%';
                    progressText.innerText = '0% Complete';

                    const formData = new FormData(form);
                    const xhr = new XMLHttpRequest();

                    xhr.open('POST', form.action);
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken.getAttribute('content'));
                    } else {
                        alert('CSRF token missing. Please ensure your layout has <meta name="csrf-token" content="{{ csrf_token() }}">');
                        resetSubmitButtonAndModal();
                        return;
                    }

                    xhr.upload.onprogress = function(event) {
                        if (event.lengthComputable) {
                            const percentComplete = (event.loaded / event.total) * 100;
                            progressBar.style.width = percentComplete.toFixed(0) + '%';
                            progressText.innerText = percentComplete.toFixed(0) + '% Complete';
                        }
                    };

                    xhr.onload = function() {
                        resetSubmitButtonAndModal();

                        if (xhr.status >= 200 && xhr.status < 300) {
                            window.location.href = "{{ route('courses.show', $course->id) }}";
                        } else {
                            let serverErrors = [];
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (xhr.status === 422 && response.errors) {
                                    for (const field in response.errors) {
                                        const readableFieldName = field.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase());
                                        response.errors[field].forEach(msg => {
                                            serverErrors.push(`${readableFieldName}: ${msg}`);
                                        });
                                    }
                                } else if (response.message) {
                                    serverErrors.push(`Server Error: ${response.message}`);
                                } else {
                                    serverErrors.push(`Server Error (${xhr.status}): Unknown error.`);
                                }
                            } catch (e) {
                                console.error('Error parsing server response or unexpected response:', e);
                                serverErrors.push('An unexpected error occurred. Please try again. (Check console for details)');
                            }

                            serverErrors.forEach(error => {
                                const li = document.createElement('li');
                                li.textContent = error;
                                errorList.appendChild(li);
                            });
                            formErrorsDiv.classList.remove('hidden');
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                    };

                    xhr.onerror = function() {
                        resetSubmitButtonAndModal();
                        errorList.innerHTML = '<li>Network error or server unreachable. Please try again.</li>';
                        formErrorsDiv.classList.remove('hidden');
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    };

                    xhr.send(formData);
                });
            });
        </script>
    </x-slot>
</x-layout>
