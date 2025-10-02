<x-layout>
    <x-slot name="title">
        @isset($assessment)
            Edit {{ ucfirst($assessment->type) }} - {{ $course->title }}
        @else
            Create {{ ucfirst($assessmentType) }} - {{ $course->title }}
        @endisset
    </x-slot>

    <div class="container mx-auto px-4 py-6">
        <div class="flex flex-col">
            <div class="w-full">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">
                            @isset($assessment)
                                Edit {{ ucfirst($assessment->type) }}
                            @else
                                Create {{ ucfirst($assessmentType) }}
                            @endisset
                            </h1>
                        <p class="text-gray-600 text-lg">
                            Course: {{ $course->title }}
                        </p>
                    </div>
                    <a href="{{ route('courses.show', $course->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition duration-300 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Course
                    </a>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-xl mb-8">
                    <h2 class="text-2xl font-semibold text-gray-700 mb-6">
                        @isset($assessment)
                            {{ ucfirst($assessment->type) }} Details
                        @else
                            {{ ucfirst($assessmentType) }} Details
                        @endisset
                    </h2>
                    <form method="POST" action="@isset($assessment){{ route('assessments.update.assignment', ['course' => $course->id, 'assessment' => $assessment->id]) }}@else{{ route('assessments.store.assignment', $course->id) }}@endisset" enctype="multipart/form-data" id="createAssessmentForm">
                        @csrf
                        @isset($assessment)
                            @method('PUT')
                        @endisset

                        <input type="hidden" name="type" value="{{ $assessment->type ?? $assessmentType }}">
                        <input type="hidden" name="topic_id" value="{{ $assessment->topic_id ?? $topicId }}">

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

                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">
                                @isset($assessment)
                                    {{ ucfirst($assessment->type) }} Title <span class="text-red-500">*</span>
                                @else
                                    {{ ucfirst($assessmentType) }} Title <span class="text-red-500">*</span>
                                @endisset
                            </label>
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror"
                                   id="title" name="title" value="{{ old('title', $assessment->title ?? '') }}" required>
                            @error('title')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description / Instructions:</label>
                            <textarea id="description" name="description" rows="5"
                                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror"
                                      placeholder="Enter {{ $assessment->type ?? $assessmentType }} description...">{{ old('description', $assessment->description ?? '') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">A description or an uploaded file is required for this assessment type.</p>
                            @error('description')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="fileUploadSection" class="mt-6 p-6 border border-gray-200 rounded-lg bg-gray-50 shadow-sm">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Upload Assessment File (Optional)</h2>
                            @isset($assessment)
                                @if ($assessment->assessment_file_path)
                                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-md text-blue-800 flex items-center justify-between">
                                        <span>Current File: <a href="{{ Storage::url($assessment->assessment_file_path) }}" target="_blank" class="text-blue-600 hover:underline">{{ basename($assessment->assessment_file_path) }}</a></span>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="clear_assessment_file" id="clear_assessment_file" value="1" class="form-checkbox h-4 w-4 text-red-600">
                                            <label for="clear_assessment_file" class="ml-2 text-red-700 text-sm">Remove current file</label>
                                        </div>
                                    </div>
                                @endif
                            @endisset
                            <div class="mb-4">
                                <label for="assessment_file" class="block text-gray-700 text-sm font-bold mb-2">Upload File (e.g., PDF, Word, Excel for briefs):</label>
                                <input type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('assessment_file') border-red-500 @enderror"
                                       id="assessment_file" name="assessment_file"
                                       accept="*">
                                <p class="mt-1 text-sm text-gray-500">
                                    Supported formats: Any file type (Max: 100MB).
                                </p>
                                @error('assessment_file')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 p-6 border border-gray-200 rounded-lg bg-gray-50 shadow-sm">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Assessment Settings</h2>
                            
                            {{-- Total Points Field - Required for assignments --}}
                            <div class="mb-6">
                                <label for="total_points" class="block text-gray-700 text-sm font-bold mb-2">
                                    Total Points <span class="text-red-500">*</span>
                                </label>
                                <input type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('total_points') border-red-500 @enderror"
                                       id="total_points" name="total_points" value="{{ old('total_points', $assessment->total_points ?? '100') }}" min="1" required>
                                <p class="text-gray-500 text-xs italic mt-1">Set the maximum points for this {{ $assessment->type ?? $assessmentType }}.</p>
                                @error('total_points')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label for="duration_minutes" class="block text-gray-700 text-sm font-bold mb-2">Duration in Minutes (Optional):</label>
                                    <input type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('duration_minutes') border-red-500 @enderror"
                                           id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $assessment->duration_minutes ?? '') }}" min="0">
                                    <p class="text-gray-500 text-xs italic mt-1">Set a time limit for the assessment.</p>
                                    @error('duration_minutes')
                                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="access_code" class="block text-gray-700 text-sm font-bold mb-2">Access Code (Optional):</label>
                                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('access_code') border-red-500 @enderror"
                                           id="access_code" name="access_code" value="{{ old('access_code', $assessment->access_code ?? '') }}">
                                    <p class="text-gray-500 text-xs italic mt-1">Require students to enter a code to start.</p>
                                    @error('access_code')
                                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4 mt-6">
                            <div>
                                <label for="available_at" class="block text-gray-700 text-sm font-bold mb-2">Available From (Optional Date/Time):</label>
                                <input type="datetime-local" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('available_at') border-red-500 @enderror"
                                       id="available_at" name="available_at" value="{{ old('available_at', isset($assessment->available_at) ? \Carbon\Carbon::parse($assessment->available_at)->format('Y-m-d\TH:i') : '') }}">
                                <p class="text-gray-500 text-xs italic mt-1">Leave empty for immediate availability</p>
                                @error('available_at')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="unavailable_at" class="block text-gray-700 text-sm font-bold mb-2">Available Until (Optional Date/Time):</label>
                                <input type="datetime-local" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('unavailable_at') border-red-500 @enderror"
                                       id="unavailable_at" name="unavailable_at" value="{{ old('unavailable_at', isset($assessment->unavailable_at) ? \Carbon\Carbon::parse($assessment->unavailable_at)->format('Y-m-d\TH:i') : '') }}">
                                <p class="text-gray-500 text-xs italic mt-1">Leave empty for no end date</p>
                                @error('unavailable_at')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-4 mt-8">
                            <a href="{{ route('courses.show', $course->id) }}" class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition duration-300 ease-in-out">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition duration-300 ease-in-out" id="submitAssessmentButton">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-3m-7-3l7-7m-7 7h7m-7 0L7 8"></path></svg>
                                @isset($assessment)
                                    Update {{ ucfirst($assessment->type) }}
                                @else
                                    Create {{ ucfirst($assessmentType) }}
                                @endisset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="scripts">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('createAssessmentForm');
                const submitButton = document.getElementById('submitAssessmentButton');
                const formErrorsDiv = document.getElementById('form-errors');
                const errorList = document.getElementById('error-list');

                function resetSubmitButtonAndModal() {
                    submitButton.disabled = false;
                    @isset($assessment)
                        submitButton.innerHTML = `<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-3m-7-3l7-7m-7 7h7m-7 0L7 8"></path></svg>
                                                  Update {{ ucfirst($assessment->type) }}`;
                    @else
                        submitButton.innerHTML = `<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-3m-7-3l7-7m-7 7h7m-7 0L7 8"></path></svg>
                                                  Create {{ ucfirst($assessmentType) }}`;
                    @endisset
                }

                form.addEventListener('submit', function(event) {
                    event.preventDefault(); // Prevent default form submission

                    submitButton.disabled = true;
                    submitButton.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12V4a2 2 0 00-2 2v6a10 10 0 0010 10h6a2 2 0 002-2v-8l-4-4h-8zm2 0h2v4H6v-4zm6 0h2v4h-2v-4z"></path>
                                            </svg>
                                            Processing...`;
                    formErrorsDiv.classList.add('hidden');
                    errorList.innerHTML = ''; // Clear previous errors

                    const formData = new FormData(form);

                    const xhr = new XMLHttpRequest();
                    // Determine the HTTP method. For forms with file uploads, it must be POST.
                    // Laravel's @method('PUT') handles the actual HTTP method spoofing on the server.
                    const actualMethod = 'POST'; // Corrected line
                    xhr.open(actualMethod, form.action, true);
                    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                    xhr.onload = function() {
                        resetSubmitButtonAndModal();

                        if (xhr.status >= 200 && xhr.status < 300) {
                            const response = JSON.parse(xhr.responseText);
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else {
                                // Handle success without redirect if needed
                                console.log('Form submitted successfully, no redirect specified.');
                            }
                        } else if (xhr.status === 422) {
                            const errors = JSON.parse(xhr.responseText).errors;
                            for (const key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    errors[key].forEach(error => {
                                        const li = document.createElement('li');
                                        li.textContent = error;
                                        errorList.appendChild(li);
                                    });
                                }
                            }
                            formErrorsDiv.classList.remove('hidden');
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        } else {
                            // General error handling for 500 or other statuses
                            let serverErrors = [];
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.message) {
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