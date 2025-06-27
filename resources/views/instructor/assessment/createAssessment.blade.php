{{-- resources/views/instructor/assessment/createAssessment.blade.php --}}

<x-layout>
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Create New Assessment for: <span class="text-blue-700">{{ $course->title }}</span></h1>
    <p class="text-gray-600 mb-8">Course Code: {{ $course->course_code }}</p>

    {{-- Display success message (Laravel's flash message) --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Display Laravel validation errors (if redirected back due to non-AJAX submission or specific backend error handling) --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Whoops!</strong>
            <span class="block sm:inline">There were some problems with your input.</span>
            <ul class="mt-3 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Assessment Details</h2>
        <form action="{{ route('assessments.store', $course->id) }}" method="POST" enctype="multipart/form-data" id="createAssessmentForm">
            @csrf

            {{-- Optional: Link to a Material --}}
            @if(isset($course->materials) && $course->materials->isNotEmpty())
                <div class="mb-4">
                    <label for="material_id" class="block text-gray-700 text-sm font-bold mb-2">Associate with Material (Optional):</label>
                    <select id="material_id" name="material_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">-- Select a Material --</option>
                        @foreach ($course->materials as $material)
                            <option value="{{ $material->id }}"
                                {{ old('material_id', $selectedMaterialId ?? null) == $material->id ? 'selected' : '' }}>
                                {{ $material->title }} ({{ ucfirst($material->material_type) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @else
                <p class="text-gray-500 text-sm mb-4">No materials available to link this assessment to in this course.</p>
                <input type="hidden" name="material_id" value="{{ $selectedMaterialId ?? '' }}">
            @endif

            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Assessment Title:</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Assessment Type:</label>
                <select id="type" name="type"
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="quiz" {{ old('type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                    <option value="activity" {{ old('type') == 'activity' ? 'selected' : '' }}>Activity</option>
                    <option value="exam" {{ old('type') == 'exam' ? 'selected' : '' }}>Exam</option>
                    <option value="assignment" {{ old('type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                    <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description / Instructions:</label>
                <textarea id="description" name="description" rows="5"
                          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description') }}</textarea>
            </div>

            {{-- Section for File Upload (Visible for Quiz, Exam, Assignment, Activity) --}}
            <div id="fileUploadSection" class="border p-4 rounded-md bg-gray-50 mb-4">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Upload Assessment File (Optional)</h3>
                <div class="mb-4">
                    <label for="assessment_file" class="block text-gray-700 text-sm font-bold mb-2">Upload File (e.g., PDF, Word, Excel for questions/briefs):</label>
                    <input type="file" id="assessment_file" name="assessment_file"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-1 text-sm text-gray-500">PDF, Word, Excel, PPT, TXT, ZIP, RAR (Max 20MB). Leave blank for text-only activities or if using online question builder.</p>
                </div>
            </div>

            {{-- Section for Quiz/Exam Specific Settings (Duration, Access Code) --}}
            <div id="quizSpecificSection" class="border p-4 rounded-md bg-gray-50 mb-4">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Quiz/Exam Settings</h3>
                <div class="mb-4">
                    <label for="duration_minutes" class="block text-gray-700 text-sm font-bold mb-2">Duration in Minutes (Optional):</label>
                    <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes') }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" min="0">
                </div>
                <div class="mb-4">
                    <label for="access_code" class="block text-gray-700 text-sm font-bold mb-2">Access Code (Optional):</label>
                    <input type="text" id="access_code" name="access_code" value="{{ old('access_code') }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p class="mt-1 text-sm text-gray-500">Students need this code to unlock the assessment.</p>
                </div>
            </div>

            <div id="questionBuilderSection" class="border p-4 rounded-md bg-blue-50 mb-4">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Online Quiz/Exam Questions</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Build your questions directly here. You can add multiple choice, identification, or true/false questions.
                </p>

                <div id="questionsContainer" class="space-y-6">
                    {{-- Questions will be appended here by JavaScript --}}
                </div>

                <button type="button" id="addQuestionButton"
                        class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    + Add Question
                </button>
            </div>

            {{-- Availability Timestamps --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="available_at" class="block text-gray-700 text-sm font-bold mb-2">Available From (Optional Date/Time):</label>
                    <input type="datetime-local" id="available_at" name="available_at" value="{{ old('available_at') }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label for="unavailable_at" class="block text-gray-700 text-sm font-bold mb-2">Available Until (Optional Date/Time):</label>
                    <input type="datetime-local" id="unavailable_at" name="unavailable_at" value="{{ old('unavailable_at') }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
            </div>


            <div class="flex items-center justify-between">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        id="submitAssessmentButton">
                    Create Assessment
                </button>
                <a href="{{ route('courses.show', $course->id) }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    {{-- !!! BEGIN: Upload Progress Modal (Reused from Materials) !!! --}}
    <div id="uploadProgressModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Creating Assessment...</h3>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div id="progressBar" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
            </div>
            <p id="progressText" class="text-sm text-gray-600 text-center">0% Complete</p>
        </div>
    </div>
    {{-- !!! END: Upload Progress Modal !!! --}}

    {{-- Question Template (Hidden) --}}
    <template id="question-template">
        <div class="question-item bg-white p-4 border border-gray-200 rounded-md shadow-sm relative">
            <button type="button" class="remove-question-button absolute top-2 right-2 text-red-500 hover:text-red-700 text-lg font-bold">&times;</button>
            <h4 class="text-md font-semibold text-gray-800 mb-3">Question <span class="question-number"></span></h4>

            <div class="mb-3">
                <label class="block text-gray-700 text-sm font-bold mb-1">Question Type:</label>
                <select name="questions[IDX][type]" class="question-type-select shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="identification">Identification</option>
                    <option value="true_false">True/False</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="block text-gray-700 text-sm font-bold mb-1">Question Text:</label>
                <textarea name="questions[IDX][text]" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
            </div>

            <div class="multiple-choice-options mb-3 hidden">
                <label class="block text-gray-700 text-sm font-bold mb-1">Options:</label>
                <div class="options-container space-y-2">
                    <div class="flex items-center space-x-2">
                        <input type="radio" name="questions[IDX][correct_option_index]" value="0" class="form-radio text-blue-600" required>
                        <input type="text" name="questions[IDX][options][0][text]" placeholder="Option A" class="shadow appearance-none border rounded w-full py-1 px-2 text-gray-700" required>
                        <input type="hidden" name="questions[IDX][options][0][order]" value="0">
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="radio" name="questions[IDX][correct_option_index]" value="1" class="form-radio text-blue-600">
                        <input type="text" name="questions[IDX][options][1][text]" placeholder="Option B" class="shadow appearance-none border rounded w-full py-1 px-2 text-gray-700" required>
                        <input type="hidden" name="questions[IDX][options][1][order]" value="1">
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="radio" name="questions[IDX][correct_option_index]" value="2" class="form-radio text-blue-600">
                        <input type="text" name="questions[IDX][options][2][text]" placeholder="Option C" class="shadow appearance-none border rounded w-full py-1 px-2 text-gray-700">
                        <input type="hidden" name="questions[IDX][options][2][order]" value="2">
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="radio" name="questions[IDX][correct_option_index]" value="3" class="form-radio text-blue-600">
                        <input type="text" name="questions[IDX][options][3][text]" placeholder="Option D" class="shadow appearance-none border rounded w-full py-1 px-2 text-gray-700">
                        <input type="hidden" name="questions[IDX][options][3][order]" value="3">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Select the radio button next to the correct option.</p>
                </div>
            </div>

            <div class="identification-answer mb-3 hidden">
                <label class="block text-gray-700 text-sm font-bold mb-1">Correct Answer:</label>
                <input type="text" name="questions[IDX][correct_answer_identification]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <p class="mt-1 text-sm text-gray-500">Enter the exact word or phrase for the correct answer.</p>
            </div>

            <div class="true-false-answer mb-3 hidden">
                <label class="block text-gray-700 text-sm font-bold mb-1">Correct Answer:</label>
                <select name="questions[IDX][correct_answer_true_false]" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Select an answer</option>
                    <option value="true">True</option>
                    <option value="false">False</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="block text-gray-700 text-sm font-bold mb-1">Points:</label>
                <input type="number" name="questions[IDX][points]" value="1" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
        </div>
    </template>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const fileUploadSection = document.getElementById('fileUploadSection');
            const quizSpecificSection = document.getElementById('quizSpecificSection');
            const questionBuilderSection = document.getElementById('questionBuilderSection');
            const assessmentFileField = document.getElementById('assessment_file'); // Get the file input field

            // Question Builder Elements
            const addQuestionButton = document.getElementById('addQuestionButton');
            const questionsContainer = document.getElementById('questionsContainer');
            const questionTemplate = document.getElementById('question-template');
            let questionCounter = 0; // To keep track of unique question indices

            // --- Main Assessment Type Toggle Logic ---
            function toggleSections() {
                const selectedType = typeSelect.value;

                fileUploadSection.classList.add('hidden');
                quizSpecificSection.classList.add('hidden');
                questionBuilderSection.classList.add('hidden');

                assessmentFileField.removeAttribute('required');

                // If currently showing questions and switching away, clear questions
                if (!(selectedType === 'quiz' || selectedType === 'exam')) {
                    questionsContainer.innerHTML = ''; // Clear all dynamic questions
                    questionCounter = 0; // Reset counter
                }


                if (selectedType === 'quiz' || selectedType === 'exam') {
                    fileUploadSection.classList.remove('hidden');
                    quizSpecificSection.classList.remove('hidden');
                    questionBuilderSection.classList.remove('hidden');
                    // Add a default question if none exist, when switching to quiz/exam and no questions are loaded from old input
                    if (questionsContainer.children.length === 0) {
                        addQuestion();
                    }
                } else if (selectedType === 'activity' || selectedType === 'assignment') {
                    fileUploadSection.classList.remove('hidden');
                }
                // 'other' type keeps all additional sections hidden.
            }

            // --- Question Builder Logic ---

            function updateQuestionNumbers() {
                const questionItems = questionsContainer.querySelectorAll('.question-item');
                questionItems.forEach((item, index) => {
                    item.querySelector('.question-number').textContent = index + 1;
                });
            }

            function toggleQuestionSubsections(questionItem) {
                const questionTypeSelect = questionItem.querySelector('.question-type-select');
                const selectedQuestionType = questionTypeSelect.value;
                const questionIndex = questionItem.getAttribute('data-question-index');

                const mcOptions = questionItem.querySelector('.multiple-choice-options');
                const identificationAnswer = questionItem.querySelector('.identification-answer');
                const trueFalseAnswer = questionItem.querySelector('.true-false-answer');

                // Get all inputs/selects within these sections
                const mcOptionInputs = mcOptions.querySelectorAll('input[type="text"], input[type="radio"]');
                // Safely get elements that might not exist for all question types
                const idAnswerInput = identificationAnswer ? identificationAnswer.querySelector('input') : null;
                const tfAnswerSelect = trueFalseAnswer ? trueFalseAnswer.querySelector('select') : null;

                // Hide all and remove required attributes first
                mcOptions.classList.add('hidden');
                identificationAnswer.classList.add('hidden');
                trueFalseAnswer.classList.add('hidden');

                mcOptionInputs.forEach(input => input.removeAttribute('required'));
                if (idAnswerInput) idAnswerInput.removeAttribute('required');
                if (tfAnswerSelect) tfAnswerSelect.removeAttribute('required');


                // Conditionally show and set required attributes
                if (selectedQuestionType === 'multiple_choice') {
                    mcOptions.classList.remove('hidden');
                    mcOptions.querySelectorAll('input[type="text"]').forEach(input => {
                        // Only make first two options required, others optional
                        if (input.name.includes(`questions[${questionIndex}][options][0]`) ||
                            input.name.includes(`questions[${questionIndex}][options][1]`)) {
                            input.setAttribute('required', 'required');
                        } else {
                            input.removeAttribute('required');
                        }
                    });
                    // Set 'required' on the radio group by targeting the first radio button.
                    // HTML5 validation for radio groups technically only requires one radio button in the group to have 'required'.
                    // The browser then ensures at least one is selected.
                    const firstRadio = mcOptions.querySelector('input[type="radio"]');
                    if (firstRadio) {
                        firstRadio.setAttribute('required', 'required');
                    }
                } else if (selectedQuestionType === 'identification') {
                    identificationAnswer.classList.remove('hidden');
                    if (idAnswerInput) idAnswerInput.setAttribute('required', 'required');
                } else if (selectedQuestionType === 'true_false') {
                    trueFalseAnswer.classList.remove('hidden');
                    if (tfAnswerSelect) tfAnswerSelect.setAttribute('required', 'required');
                }
            }


            function addQuestion() {
                const clone = questionTemplate.content.cloneNode(true);
                const questionItem = clone.querySelector('.question-item');

                // Assign a unique index to the question item itself
                questionItem.setAttribute('data-question-index', questionCounter);

                // Replace IDX placeholder with unique counter in all name attributes
                const inputsAndSelects = questionItem.querySelectorAll('[name*="IDX"]');
                inputsAndSelects.forEach(element => {
                    element.name = element.name.replace(/IDX/g, questionCounter);
                });

                // Attach event listener for question type change
                const questionTypeSelect = questionItem.querySelector('.question-type-select');
                questionTypeSelect.addEventListener('change', () => toggleQuestionSubsections(questionItem));

                // Attach event listener for remove button
                questionItem.querySelector('.remove-question-button').addEventListener('click', function() {
                    questionItem.remove();
                    updateQuestionNumbers();
                    // If all questions are removed for quiz/exam, maybe re-show file upload as primary fallback
                    const selectedType = typeSelect.value;
                    if ((selectedType === 'quiz' || selectedType === 'exam') && questionsContainer.children.length === 0) {
                        // Optionally, you could make assessment_file required here if no questions are built
                    }
                });

                questionsContainer.appendChild(questionItem);

                // Initial toggle for the newly added question
                toggleQuestionSubsections(questionItem);

                updateQuestionNumbers();
                questionCounter++; // Increment for the next question
            }

            // --- Initialization ---
            toggleSections(); // Initial toggle based on default/old value
            typeSelect.addEventListener('change', toggleSections); // Listen for main type changes

            addQuestionButton.addEventListener('click', addQuestion); // Listen for "Add Question" button

            // --- Form Submission Logic ---
            const form = document.getElementById('createAssessmentForm');
            const submitButton = document.getElementById('submitAssessmentButton');
            const modal = document.getElementById('uploadProgressModal');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');

            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                // --- Frontend Validation Before AJAX Submit ---
                const selectedType = typeSelect.value;
                const hasQuestions = questionsContainer.children.length > 0;
                const hasFile = assessmentFileField.files.length > 0;

                // Validate main assessment file vs. questions based on type
                if ((selectedType === 'quiz' || selectedType === 'exam') && !hasQuestions && !hasFile) {
                    alert('For Quiz or Exam, please add at least one question using the builder OR upload an assessment file.');
                    submitButton.removeAttribute('disabled');
                    submitButton.innerText = 'Create Assessment';
                    modal.classList.add('hidden');
                    return; // Stop submission
                }

                // If questions are present, validate them
                if (hasQuestions) { // This applies to quiz/exam types where builder is shown
                    const questionItems = questionsContainer.querySelectorAll('.question-item');
                    for (const item of questionItems) {
                        const qText = item.querySelector('textarea[name$="[text]"]');
                        const qTypeSelect = item.querySelector('.question-type-select');
                        const qPoints = item.querySelector('input[name$="[points]"]');

                        if (!qText.value.trim()) {
                            alert('Question text is required for all questions.');
                            qText.focus();
                            return; // Stop submission
                        }
                        if (!qPoints.value.trim() || parseInt(qPoints.value) < 1) {
                            alert('Points are required and must be at least 1 for all questions.');
                            qPoints.focus();
                            return; // Stop submission
                        }

                        const selectedQType = qTypeSelect.value;
                        if (selectedQType === 'multiple_choice') {
                            // Check if required options (first two) have text
                            const optionsInputs = item.querySelectorAll('.multiple-choice-options input[type="text"][required]');
                            for (let i = 0; i < optionsInputs.length; i++) {
                                if (!optionsInputs[i].value.trim()) {
                                    alert(`Option ${String.fromCharCode(65 + i)} text is required for multiple choice questions.`);
                                    optionsInputs[i].focus();
                                    return; // Stop submission
                                }
                            }
                            // Check if at least one radio button is checked for correct option
                            const radioButtons = item.querySelectorAll('.multiple-choice-options input[type="radio"]');
                            let checkedRadio = false;
                            for (const radio of radioButtons) {
                                if (radio.checked) {
                                    checkedRadio = true;
                                    break;
                                }
                            }
                            if (!checkedRadio) {
                                alert('Please select a correct option for all multiple choice questions.');
                                return; // Stop submission
                            }

                        } else if (selectedQType === 'identification') {
                            const answerInput = item.querySelector('.identification-answer input');
                            if (!answerInput || !answerInput.value.trim()) {
                                alert('Correct answer text is required for identification questions.');
                                if (answerInput) answerInput.focus();
                                return; // Stop submission
                            }
                        } else if (selectedQType === 'true_false') {
                            const answerSelect = item.querySelector('.true-false-answer select');
                            if (!answerSelect || !answerSelect.value) {
                                alert('Please select a correct answer for true/false questions.');
                                if (answerSelect) answerSelect.focus();
                                return; // Stop submission
                            }
                        }
                    }
                }
                // --- End Frontend Validation ---


                // Disable the submit button and show the modal
                submitButton.setAttribute('disabled', 'disabled');
                submitButton.innerText = 'Creating...';
                modal.classList.remove('hidden'); // Show the modal

                // Reset progress bar
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
                    submitButton.removeAttribute('disabled');
                    submitButton.innerText = 'Create Assessment';
                    modal.classList.add('hidden');
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
                    modal.classList.add('hidden');
                    submitButton.removeAttribute('disabled');
                    submitButton.innerText = 'Create Assessment';

                    if (xhr.status >= 200 && xhr.status < 300) {
                        window.location.href = "{{ route('courses.show', $course->id) }}"; // Redirect to course show page
                    } else {
                        let errorMessage = 'An error occurred during assessment creation.';
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMessage = response.message;
                            }
                            if (xhr.status === 422 && response.errors) {
                                let validationErrors = '';
                                for (const field in response.errors) {
                                    const readableFieldName = field.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase());
                                    validationErrors += `${readableFieldName}:\n` + response.errors[field].join('\n') + '\n';
                                }
                                alert('Validation Errors:\n' + validationErrors);
                                window.location.href = window.location.href; // Reload to show old input and Laravel's flash errors
                            } else {
                                alert(errorMessage + ' Please check your input and try again.');
                                window.location.href = window.location.href; // Reload on other errors too
                            }

                        } catch (e) {
                            console.error('Error parsing response or unexpected response:', e);
                            alert(errorMessage + ' (Details in console)');
                            window.location.href = window.location.href; // Reload on parsing errors
                        }
                    }
                };

                xhr.onerror = function() {
                    modal.classList.add('hidden');
                    submitButton.removeAttribute('disabled');
                    submitButton.innerText = 'Create Assessment';
                    alert('Network error or server unreachable. Please try again.');
                    window.location.href = window.location.href;
                };

                xhr.send(formData);
            });
        });
    </script>
</x-layout>
