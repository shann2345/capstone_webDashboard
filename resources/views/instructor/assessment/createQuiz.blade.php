{{-- resources/views/instructor/assessment/createQuiz.blade.php --}}

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
                        <p class="text-gray-600 text-lg">Course: {{ $course->title }}</p>
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
                    <form method="POST" action="
                        @isset($assessment)
                            {{ route('assessments.update.quiz', ['course' => $course->id, 'assessment' => $assessment->id]) }}
                        @else
                            {{ route('assessments.store.quiz', $course->id) }}
                        @endisset
                    " enctype="multipart/form-data" id="createAssessmentForm">
                        @csrf
                        @isset($assessment)
                            @method('PUT') {{-- Use PUT method for updates --}}
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="mb-4">
                                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">{{ ucfirst($assessment->type ?? $assessmentType) }} Title <span class="text-red-500">*</span></label>
                                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror"
                                       id="title" name="title" value="{{ old('title', $assessment->title ?? '') }}" required>
                                @error('title')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description / Instructions:</label>
                                <textarea id="description" name="description" rows="3"
                                          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror"
                                          placeholder="Enter {{ $assessment->type ?? $assessmentType }} description...">{{ old('description', $assessment->description ?? '') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div id="quizSpecificSection" class="mt-6 p-6 border border-gray-200 rounded-lg bg-gray-50 shadow-sm">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ ucfirst($assessment->type ?? $assessmentType) }} Settings</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="mb-4">
                                    <label for="duration_minutes" class="block text-gray-700 text-sm font-bold mb-2">Duration in Minutes (Optional):</label>
                                    <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $assessment->duration_minutes ?? '') }}"
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('duration_minutes') border-red-500 @enderror" min="0">
                                    <p class="text-gray-500 text-xs italic mt-1">Leave empty for no time limit</p>
                                    @error('duration_minutes')
                                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="access_code" class="block text-gray-700 text-sm font-bold mb-2">Access Code (Optional):</label>
                                    <input type="text" id="access_code" name="access_code" value="{{ old('access_code', $assessment->access_code ?? '') }}"
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('access_code') border-red-500 @enderror"
                                           placeholder="Optional access code">
                                    <p class="mt-1 text-sm text-gray-500">Students will need this code to unlock the assessment.</p>
                                    @error('access_code')
                                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="questionBuilderSection" class="mt-6 p-6 border border-blue-200 rounded-lg bg-blue-50 shadow-sm">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Online {{ ucfirst($assessment->type ?? $assessmentType) }} Questions</h2>
                            <p class="text-sm text-gray-600 mb-4">
                                Build your questions directly here. You can add multiple choice, identification, or true/false questions.
                            </p>

                            <div id="questionsContainer" class="space-y-6">
                                </div>

                            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mt-4 hidden" id="no-questions-alert">
                                <span class="block sm:inline">No questions added yet. Click "Add Question" to start creating your {{ $assessment->type ?? $assessmentType }}.</span>
                            </div>

                            <button type="button" id="addQuestionButton" class="mt-6 inline-flex items-center px-4 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition duration-300 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 6v6m0 0v6m0-6h6m-6 0H6\"></path></svg>
                                Add Question
                            </button>
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
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4\"></path></svg>
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

    {{-- Upload Progress Modal --}}
    <div id="uploadProgressModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-sm">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                @isset($assessment)
                    Updating Assessment...
                @else
                    Creating Assessment...
                @endisset
            </h3>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div id="progressBar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300 ease-in-out" style="width: 0%"></div>
            </div>
            <p id="progressText" class="text-sm text-gray-600 text-center">0% Complete</p>
        </div>
    </div>

    <template id="question-template">
        <div class="question-item bg-white p-6 rounded-lg shadow-md border border-gray-200 relative">
            <button type="button" class="remove-question-button absolute top-3 right-3 text-red-500 hover:text-red-700 text-2xl font-bold leading-none focus:outline-none">&times;</button>
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Question <span class="question-number"></span></h4>

            {{-- ADD THIS HIDDEN INPUT FOR QUESTION ID --}}
            <input type="hidden" name="questions[IDX][id]" value="">

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Question Type <span class="text-red-500">*</span></label>
                <select class="question-type-select shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="questions[IDX][question_type]" required>
                    <option value="">Select Question Type</option>
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="identification">Identification</option>
                    <option value="true_false">True/False</option>
                    <option value="essay">Essay</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Question Text <span class="text-red-500">*</span></label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="questions[IDX][question_text]" rows="3" required placeholder="Enter your question here..."></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Points <span class="text-red-500">*</span></label>
                <input type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="questions[IDX][points]" min="1" required value="1">
            </div>

            <div class="question-specific-fields">
                </div>
        </div>
    </template>

    <template id="multiple-choice-options-template">
        <div class="multiple-choice-options mt-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
            <label class="block text-gray-700 text-sm font-bold mb-2">Options: <span class="text-red-500">*</span></label>
            <div class="space-y-3 options-container">
                <div class="flex items-center space-x-2">
                    <input type="radio" name="questions[IDX][correct_answer]" value="0" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500" required>
                    <input type="text" name="questions[IDX][options][0][option_text]" placeholder="Option A" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <input type="hidden" name="questions[IDX][options][0][option_order]" value="0">
                    {{-- ADD THIS HIDDEN INPUT FOR OPTION ID --}}
                    <input type="hidden" name="questions[IDX][options][0][id]" value="">
                    <button type="button" class="remove-option-button text-red-500 hover:text-red-700 hidden">&times;</button>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="radio" name="questions[IDX][correct_answer]" value="1" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500">
                    <input type="text" name="questions[IDX][options][1][option_text]" placeholder="Option B" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-shadow-outline" required>
                    <input type="hidden" name="questions[IDX][options][1][option_order]" value="1">
                    {{-- ADD THIS HIDDEN INPUT FOR OPTION ID --}}
                    <input type="hidden" name="questions[IDX][options][1][id]" value="">
                    <button type="button" class="remove-option-button text-red-500 hover:text-red-700 hidden">&times;</button>
                </div>
            </div>
            <button type="button" class="add-option-button mt-3 inline-flex items-center px-3 py-1 bg-blue-500 text-white text-sm font-semibold rounded-md hover:bg-blue-600">Add Option</button>
        </div>
    </template>

    <template id="identification-answer-template">
        <div class="identification-answer mt-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
            <label class="block text-gray-700 text-sm font-bold mb-2">Correct Answer <span class="text-red-500">*</span></label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="questions[IDX][correct_answer]" placeholder="Enter correct answer" required>
        </div>
    </template>

    <template id="true-false-answer-template">
        <div class="true-false-answer mt-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
            <label class="block text-gray-700 text-sm font-bold mb-2">Correct Answer <span class="text-red-500">*</span></label>
            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="questions[IDX][correct_answer]" required>
                <option value="">Select Answer</option>
                <option value="True">True</option>
                <option value="False">False</option>
            </select>
        </div>
    </template>

    <template id="essay-points-template">
        {{-- For Essay, points are already handled in the main question template, nothing extra needed here --}}
    </template>


    <x-slot name="scripts">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('createAssessmentForm');
                const submitButton = document.getElementById('submitAssessmentButton');
                const uploadProgressModal = document.getElementById('uploadProgressModal');
                const progressBar = document.getElementById('progressBar');
                const progressText = document.getElementById('progressText');
                const formErrorsDiv = document.getElementById('form-errors');
                const errorList = document.getElementById('error-list');

                // --- NEW FUNCTION FOR VALIDATION ---
                function validateMultipleChoiceOptions() {
                    const questionItems = questionsContainer.querySelectorAll('.question-item');
                    let hasErrors = false;
                    const errorMessages = [];

                    // Clear previous error states
                    questionsContainer.querySelectorAll('input.border-red-500').forEach(input => {
                        input.classList.remove('border-red-500');
                        input.classList.add('border'); // Ensure default border is present
                    });

                    questionItems.forEach((questionItem, index) => {
                        const questionTypeSelect = questionItem.querySelector('.question-type-select');
                        if (questionTypeSelect.value === 'multiple_choice') {
                            const optionsContainer = questionItem.querySelector('.options-container');
                            const optionInputs = optionsContainer.querySelectorAll('input[name*="[option_text]"]');
                            const seenOptions = new Set();
                            
                            optionInputs.forEach(input => {
                                const optionText = input.value.trim().toLowerCase();
                                if (optionText) {
                                    if (seenOptions.has(optionText)) {
                                        hasErrors = true;
                                        errorMessages.push(`Duplicate options found for Question ${index + 1}. All options must be unique.`);
                                        input.classList.add('border-red-500');
                                    }
                                    seenOptions.add(optionText);
                                }
                            });
                        }
                    });

                    // Clear and display errors
                    errorList.innerHTML = '';
                    if (hasErrors) {
                        formErrorsDiv.classList.remove('hidden');
                        errorMessages.forEach(msg => {
                            const li = document.createElement('li');
                            li.textContent = msg;
                            errorList.appendChild(li);
                        });
                        window.scrollTo(0, 0); // Scroll to top to show errors
                        return false;
                    } else {
                        formErrorsDiv.classList.add('hidden');
                        return true;
                    }
                }
                
                form.addEventListener('submit', function(e) {
                    // Pre-submission validation for unique MC options
                    if (!validateMultipleChoiceOptions()) {
                        e.preventDefault();
                        return;
                    }

                    e.preventDefault();
                    submitButton.disabled = true;
                    uploadProgressModal.classList.remove('hidden');

                    const formData = new FormData(form);
                    const xhr = new XMLHttpRequest();

                    xhr.open(form.method, form.action, true);

                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            let percent = (e.loaded / e.total) * 100;
                            progressBar.style.width = percent + '%';
                            progressText.textContent = Math.round(percent) + '% Complete';
                        }
                    });

                    xhr.addEventListener('load', function() {
                        uploadProgressModal.classList.add('hidden');
                        submitButton.disabled = false;

                        if (xhr.status >= 200 && xhr.status < 300) {
                            const response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                window.location.href = response.redirect;
                            } else {
                                // Handle general success but with a message that's not a redirect
                                // (This block might not be strictly necessary if all successes redirect)
                                console.log(response.message);
                            }
                        } else {
                            // Handle HTTP errors or validation errors
                            const response = JSON.parse(xhr.responseText);
                            formErrorsDiv.classList.remove('hidden');
                            errorList.innerHTML = ''; // Clear previous errors

                            if (response.errors) {
                                for (const field in response.errors) {
                                    response.errors[field].forEach(error => {
                                        const li = document.createElement('li');
                                        li.textContent = error;
                                        errorList.appendChild(li);
                                    });
                                }
                            } else {
                                const li = document.createElement('li');
                                li.textContent = response.message || 'An unexpected error occurred.';
                                errorList.appendChild(li);
                            }
                            // Scroll to top to show errors
                            window.scrollTo(0, 0);
                        }
                    });

                    xhr.addEventListener('error', function() {
                        uploadProgressModal.classList.add('hidden');
                        submitButton.disabled = false;
                        formErrorsDiv.classList.remove('hidden');
                        errorList.innerHTML = '<li>Network error. Please try again.</li>';
                        window.scrollTo(0, 0);
                    });

                    xhr.send(formData);
                });

                // --- Question Builder Logic ---
                const questionsContainer = document.getElementById('questionsContainer');
                const addQuestionButton = document.getElementById('addQuestionButton');
                const questionTemplate = document.getElementById('question-template');
                const noQuestionsAlert = document.getElementById('no-questions-alert');

                const mcOptionsTemplate = document.getElementById('multiple-choice-options-template');
                const identificationAnswerTemplate = document.getElementById('identification-answer-template');
                const trueFalseAnswerTemplate = document.getElementById('true-false-answer-template');

                let questionCounter = 0; // Initialize to 0

                function updateQuestionNumbers() {
                    const questionItems = questionsContainer.querySelectorAll('.question-item');
                    if (questionItems.length === 0) {
                        noQuestionsAlert.classList.remove('hidden');
                    } else {
                        noQuestionsAlert.classList.add('hidden');
                    }
                    questionItems.forEach((item, index) => {
                        item.querySelector('.question-number').textContent = index + 1;
                        // Update names for all inputs within the question item
                        item.querySelectorAll('[name*="questions[IDX]"]').forEach(input => {
                            const currentName = input.getAttribute('name');
                            input.setAttribute('name', currentName.replace(/questions\[IDX\]/g, `questions[${index}]`));
                        });
                    });
                }

                function addQuestion(questionData = null) {
                    const clone = questionTemplate.content.cloneNode(true);
                    const questionItem = clone.querySelector('.question-item');

                    // Replace IDX placeholder in the cloned template for initial setup
                    questionItem.innerHTML = questionItem.innerHTML.replace(/questions\[IDX\]/g, `questions[${questionCounter}]`);

                    // Append the new question item before pre-filling to ensure it's in the DOM
                    questionsContainer.appendChild(clone);

                    // Get the newly added question item to work with it directly
                    const currentQuestionItem = questionsContainer.children[questionsContainer.children.length - 1]; // This is the new item
                    const questionTypeSelect = currentQuestionItem.querySelector('.question-type-select');
                    const questionSpecificFields = currentQuestionItem.querySelector('.question-specific-fields');
                    const removeButton = currentQuestionItem.querySelector('.remove-question-button');

                    // Capture current index for this question (now based on its actual position)
                    const currentQuestionIndex = questionCounter;

                    // Pre-fill question data if provided (for editing)
                    if (questionData) {
                        // Set question ID if it's an existing question
                        if (questionData.id) {
                            currentQuestionItem.querySelector('input[name*="[id]"]').value = questionData.id;
                        }
                        currentQuestionItem.querySelector('[name*="[question_text]"]').value = questionData.question_text || '';
                        currentQuestionItem.querySelector('[name*="[points]"]').value = questionData.points || 1;
                        questionTypeSelect.value = questionData.question_type || '';

                        // Trigger change to load specific fields
                        updateQuestionSpecificFields(questionTypeSelect, questionSpecificFields, currentQuestionIndex);

                        // Pre-fill specific fields based on type
                        setTimeout(() => { // Give a small delay for fields to render
                            if (questionData.question_type === 'multiple_choice') {
                                const optionsContainer = questionSpecificFields.querySelector('.options-container');
                                // Clear default options from template (if any were initially added by template)
                                optionsContainer.innerHTML = '';

                                if (questionData.options && questionData.options.length > 0) {
                                    // Sort options by option_order to maintain consistency
                                    questionData.options.sort((a, b) => a.option_order - b.option_order);
                                    questionData.options.forEach((option, optIdx) => {
                                        addMultipleChoiceOption(questionSpecificFields, currentQuestionIndex, option.option_text, optIdx, option.id); // Pass option ID
                                    });
                                }
                                // Select correct answer
                                // Note: correct_answer for MC stores the *index* of the correct option
                                const mcCorrectRadio = questionSpecificFields.querySelector(`input[name="questions[${currentQuestionIndex}][correct_answer]"][value="${questionData.correct_answer}"]`);
                                if (mcCorrectRadio) {
                                    mcCorrectRadio.checked = true;
                                } else {
                                    // Fallback if the correct_answer index is somehow invalid or not found
                                    // This might happen if options were deleted or reordered and correct_answer wasn't re-indexed on backend
                                    // For robustness, you might select the first option or show an alert.
                                    // For now, let's ensure the radio button's value matches the correct_answer string.
                                    const allMCRadios = questionSpecificFields.querySelectorAll(`input[name="questions[${currentQuestionIndex}][correct_answer]"]`);
                                    for (let i = 0; i < allMCRadios.length; i++) {
                                        if (allMCRadios[i].value == questionData.correct_answer) { // Use == for loose comparison as value might be string
                                            allMCRadios[i].checked = true;
                                            break;
                                        }
                                    }
                                }

                            } else if (questionData.question_type === 'identification') {
                                questionSpecificFields.querySelector('[name*="[correct_answer]"]').value = questionData.correct_answer || '';
                            } else if (questionData.question_type === 'true_false') {
                                const tfSelect = questionSpecificFields.querySelector('select[name*="[correct_answer]"]');
                                if (tfSelect) tfSelect.value = questionData.correct_answer || '';
                            }
                        }, 50); // Small delay
                    }

                    questionTypeSelect.addEventListener('change', function() {
                        updateQuestionSpecificFields(this, questionSpecificFields, currentQuestionIndex);
                    });

                    removeButton.addEventListener('click', function() {
                        // No need to add to deleted_questions array if the backend handles deletion by absence
                        questionItem.remove();
                        updateQuestionNumbers();
                    });

                    questionCounter++;
                    updateQuestionNumbers();
                }

                function updateQuestionSpecificFields(selectElement, container, questionIndex) {
                    container.innerHTML = ''; // Clear previous fields
                    const selectedType = selectElement.value;

                    let templateToUse = null;
                    if (selectedType === 'multiple_choice') {
                        templateToUse = mcOptionsTemplate;
                    } else if (selectedType === 'identification') {
                        templateToUse = identificationAnswerTemplate;
                    } else if (selectedType === 'true_false') {
                        templateToUse = trueFalseAnswerTemplate;
                    }

                    if (templateToUse) {
                        const clone = templateToUse.content.cloneNode(true);
                        // Replace IDX placeholder in the cloned specific template
                        const htmlString = clone.firstElementChild.outerHTML.replace(/questions\[IDX\]/g, `questions[${questionIndex}]`);
                        container.insertAdjacentHTML('beforeend', htmlString);

                        // Add event listeners for new buttons if they exist
                        if (selectedType === 'multiple_choice') {
                            const newQuestionItem = questionsContainer.children[questionIndex];
                            const optionsContainer = newQuestionItem.querySelector('.multiple-choice-options .options-container');
                            const addOptionButton = newQuestionItem.querySelector('.add-option-button');

                            addOptionButton.addEventListener('click', function() {
                                addMultipleChoiceOption(newQuestionItem, questionIndex);
                            });

                            // Attach remove listeners to default options (if any)
                            newQuestionItem.querySelectorAll('.remove-option-button').forEach(button => {
                                button.addEventListener('click', function() {
                                    this.closest('.flex.items-center.space-x-2').remove();
                                    updateMultipleChoiceOptionNumbers(optionsContainer, questionIndex);
                                });
                            });
                            updateMultipleChoiceOptionNumbers(optionsContainer, questionIndex); // Initial numbering
                        }
                    }
                }

                function addMultipleChoiceOption(questionItem, questionIndex, optionText = '', optionOrder = null, optionId = null) { // Added optionId
                    const optionsContainer = questionItem.querySelector('.multiple-choice-options .options-container');
                    let optionCount = optionsContainer.children.length; // Use current number of children as new index
                    // If optionOrder is explicitly provided, use it, useful for loading existing options
                    if (optionOrder !== null) {
                        optionCount = optionOrder;
                    }

                    const newOptionDiv = document.createElement('div');
                    newOptionDiv.className = 'flex items-center space-x-2';
                    newOptionDiv.innerHTML = `
                        <input type="radio" name="questions[${questionIndex}][correct_answer]" value="${optionCount}" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500">
                        <input type="text" name="questions[${questionIndex}][options][${optionCount}][option_text]" placeholder="Option ${String.fromCharCode(65 + optionCount)}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required value="${optionText}">
                        <input type="hidden" name="questions[${questionIndex}][options][${optionCount}][option_order]" value="${optionCount}">
                        {{-- ADD THIS HIDDEN INPUT FOR OPTION ID --}}
                        <input type="hidden" name="questions[${questionIndex}][options][${optionCount}][id]" value="${optionId || ''}">
                        <button type="button" class="remove-option-button text-red-500 hover:text-red-700">&times;</button>
                    `;
                    optionsContainer.appendChild(newOptionDiv);

                    // Show remove button for all options if more than 2
                    // This check should be applied after adding the new option
                    optionsContainer.querySelectorAll('.remove-option-button').forEach(button => {
                        if (optionsContainer.children.length > 2) {
                            button.classList.remove('hidden');
                        } else {
                            button.classList.add('hidden'); // Ensure hidden if 2 or less
                        }
                    });


                    newOptionDiv.querySelector('.remove-option-button').addEventListener('click', function() {
                        this.closest('.flex.items-center.space-x-2').remove();
                        updateMultipleChoiceOptionNumbers(optionsContainer, questionIndex);
                    });

                    updateMultipleChoiceOptionNumbers(optionsContainer, questionIndex); // Re-index after adding
                }

                function updateMultipleChoiceOptionNumbers(optionsContainer, questionIndex) {
                    optionsContainer.querySelectorAll('.flex.items-center.space-x-2').forEach((optionDiv, optionIdx) => {
                        optionDiv.querySelector('input[type="radio"]').value = optionIdx;
                        optionDiv.querySelector('input[name*="[option_text]"]').name = `questions[${questionIndex}][options][${optionIdx}][option_text]`;
                        optionDiv.querySelector('input[name*="[option_order]"]').value = optionIdx;
                        optionDiv.querySelector('input[name*="[option_order]"]').name = `questions[${questionIndex}][options][${optionIdx}][option_order]`;
                        optionDiv.querySelector('input[name*="[option_text]"]').placeholder = `Option ${String.fromCharCode(65 + optionIdx)}`;
                        // Update the name for the hidden ID input
                        const idInput = optionDiv.querySelector('input[name*="[id]"]');
                        if (idInput) {
                            idInput.name = `questions[${questionIndex}][options][${optionIdx}][id]`;
                        }


                        // Hide remove button if only two options remain
                        if (optionsContainer.children.length <= 2) {
                            optionDiv.querySelector('.remove-option-button').classList.add('hidden');
                        } else {
                            optionDiv.querySelector('.remove-option-button').classList.remove('hidden');
                        }
                    });
                }

                // Pre-fill form if an assessment object is passed (for editing)
                @isset($assessment)
                    const assessmentData = @json($assessment);
                    // Populate basic fields (title, description, duration, access code, timestamps)
                    document.getElementById('title').value = assessmentData.title || '';
                    document.getElementById('description').value = assessmentData.description || '';
                    document.getElementById('duration_minutes').value = assessmentData.duration_minutes || '';
                    document.getElementById('access_code').value = assessmentData.access_code || '';

                    if (assessmentData.available_at) {
                        document.getElementById('available_at').value = new Date(assessmentData.available_at).toISOString().slice(0, 16);
                    }
                    if (assessmentData.unavailable_at) {
                        document.getElementById('unavailable_at').value = new Date(assessmentData.unavailable_at).toISOString().slice(0, 16);
                    }

                    // Populate questions
                    if (assessmentData.questions && assessmentData.questions.length > 0) {
                        assessmentData.questions.sort((a, b) => a.order - b.order); // Ensure order
                        assessmentData.questions.forEach(question => {
                            addQuestion(question); // Pass the whole question object
                        });
                        // After loading all existing questions, set questionCounter to the total count
                        // This ensures new questions added will have correct sequential indices.
                        questionCounter = assessmentData.questions.length;
                        updateQuestionNumbers(); // Initial call to show/hide no-questions-alert for loaded questions
                    }
                @endisset

                // Add initial question button listener
                addQuestionButton.addEventListener('click', () => addQuestion());
                // This initial call is important even if no questions are pre-filled,
                // to correctly show/hide the 'no-questions-alert' and set up initial numbering.
                updateQuestionNumbers();
            });
        </script>
    </x-slot>
</x-layout>