<x-layout>
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Create New Assessment for: <span class="text-blue-700">{{ $course->title }}</span></h1>
    <p class="text-gray-600 mb-8">Course Code: {{ $course->course_code }}</p>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

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
            @if(isset($course->materials) && $course->materials->isNotEmpty())
                <div class="mb-4">
                    <label for="material_id" class="block text-gray-700 text-sm font-bold mb-2">Associate with Material (Optional):</label>
                    <select id="material_id" name="material_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">-- Select a Material --</option>
                        @foreach ($course->materials as $material)
                            <option value="{{ $material->id }}"
                                {{ old('material_id', $selectedMaterialId ?? null) == $material->id ? 'selected' : '' }}>
                                {{-- The `selectedMaterialId` will be used if passed from the URL --}}
                                {{ $material->title }} ({{ ucfirst($material->material_type) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @else
                <p class="text-gray-500 text-sm mb-4">No materials available to link this assessment to in this course.</p>
                {{-- If no materials exist, we still need to send a nullable material_id --}}
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
            <div id="fileUploadSection" class="border p-4 rounded-md bg-gray-50 mb-4">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Upload Assessment File (Optional)</h3>
                <div class="mb-4">
                    <label for="assessment_file" class="block text-gray-700 text-sm font-bold mb-2">Upload File (e.g., PDF, Word, Excel for questions/briefs):</label>
                    <input type="file" id="assessment_file" name="assessment_file"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-1 text-sm text-gray-500">PDF, Word, Excel, PPT, TXT, ZIP, RAR (Max 20MB). Leave blank for text-only activities or if using online question builder.</p>
                </div>
            </div>
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

    <div id="uploadProgressModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Creating Assessment...</h3>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div id="progressBar" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
            </div>
            <p id="progressText" class="text-sm text-gray-600 text-center">0% Complete</p>
        </div>
    </div>

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
                        <input type="radio" name="questions[IDX][correct_option_index]" value="0" class="form-radio text-blue-600">
                        <input type="text" name="questions[IDX][options][0][text]" placeholder="Option A" class="shadow appearance-none border rounded w-full py-1 px-2 text-gray-700">
                        <input type="hidden" name="questions[IDX][options][0][order]" value="0">
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="radio" name="questions[IDX][correct_option_index]" value="1" class="form-radio text-blue-600">
                        <input type="text" name="questions[IDX][options][1][text]" placeholder="Option B" class="shadow appearance-none border rounded w-full py-1 px-2 text-gray-700">
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

            const addQuestionButton = document.getElementById('addQuestionButton');
            const questionsContainer = document.getElementById('questionsContainer');
            const questionTemplate = document.getElementById('question-template');
            let questionCounter = 0; // To keep track of unique question indices

            function toggleSections() {
                // console.log('toggleSections called');
                const selectedType = typeSelect.value;

                fileUploadSection.classList.add('hidden');
                quizSpecificSection.classList.add('hidden');
                questionBuilderSection.classList.add('hidden');

                // Remove 'required' from assessment file by default when sections are hidden
                assessmentFileField.removeAttribute('required');

                if (!(selectedType === 'quiz' || selectedType === 'exam')) {
                    // Clear all dynamic questions and reset counter when not a quiz/exam
                    questionsContainer.innerHTML = '';
                    questionCounter = 0;
                    // console.log('Cleared questions, questionCounter reset.');
                }


                if (selectedType === 'quiz' || selectedType === 'exam') {
                    fileUploadSection.classList.remove('hidden');
                    quizSpecificSection.classList.remove('hidden');
                    questionBuilderSection.classList.remove('hidden');
                    // console.log('Quiz/Exam selected, question builder visible.');

                    // Ensure at least one question is present if the builder is active
                    if (questionsContainer.children.length === 0) {
                        // console.log('No questions found, adding first question automatically.');
                        addQuestion(); // Add a default question if none exist
                    }
                } else if (selectedType === 'activity' || selectedType === 'assignment') {
                    fileUploadSection.classList.remove('hidden');
                    // For activity/assignment, you might want to make file upload conditionally required
                    // or enforce at least description or file. For now, it's optional as per current setup.
                    // console.log('Activity/Assignment selected, file upload visible.');
                } else {
                    // console.log('Other type selected, hiding all extra sections.');
                }
            }

            function updateQuestionNumbers() {
                const questionItems = questionsContainer.querySelectorAll('.question-item');
                questionItems.forEach((item, index) => {
                    item.querySelector('.question-number').textContent = index + 1;
                });
                // console.log('Question numbers updated.');
            }

            function toggleQuestionSubsections(questionItem) {
                // console.log('toggleQuestionSubsections called for question:', questionItem);
                const questionTypeSelect = questionItem.querySelector('.question-type-select');
                const selectedQuestionType = questionTypeSelect.value;
                const questionIndex = questionItem.getAttribute('data-question-index');

                const mcOptions = questionItem.querySelector('.multiple-choice-options');
                const identificationAnswer = questionItem.querySelector('.identification-answer');
                const trueFalseAnswer = questionItem.querySelector('.true-false-answer');

                // Get all relevant inputs/selects within this specific question item
                const mcOptionTextInputs = mcOptions.querySelectorAll('input[type="text"]');
                const mcRadioInputs = mcOptions.querySelectorAll('input[type="radio"]');
                const idAnswerInput = identificationAnswer.querySelector('input');
                const tfAnswerSelect = trueFalseAnswer.querySelector('select');

                // Hide all and remove their 'required' attributes initially
                mcOptions.classList.add('hidden');
                identificationAnswer.classList.add('hidden');
                trueFalseAnswer.classList.add('hidden');

                mcOptionTextInputs.forEach(input => input.removeAttribute('required'));
                mcRadioInputs.forEach(input => input.removeAttribute('required')); // Crucial: Remove from radios as well
                if (idAnswerInput) idAnswerInput.removeAttribute('required');
                if (tfAnswerSelect) tfAnswerSelect.removeAttribute('required');


                if (selectedQuestionType === 'multiple_choice') {
                    mcOptions.classList.remove('hidden');
                    // Set required for the first two options and the radio group for multiple choice
                    if (mcOptionTextInputs.length > 0) mcOptionTextInputs[0].setAttribute('required', 'required');
                    if (mcOptionTextInputs.length > 1) mcOptionTextInputs[1].setAttribute('required', 'required');

                    // IMPORTANT: Set 'required' on at least one radio button in the group
                    // This creates a "required group" behavior for HTML5 validation.
                    if (mcRadioInputs.length > 0) {
                        mcRadioInputs[0].setAttribute('required', 'required');
                    }
                    // console.log('Multiple Choice options visible and required attributes set.');
                } else if (selectedQuestionType === 'identification') {
                    identificationAnswer.classList.remove('hidden');
                    if (idAnswerInput) idAnswerInput.setAttribute('required', 'required');
                    // console.log('Identification answer visible and required.');
                } else if (selectedQuestionType === 'true_false') {
                    trueFalseAnswer.classList.remove('hidden');
                    if (tfAnswerSelect) tfAnswerSelect.setAttribute('required', 'required');
                    // console.log('True/False answer visible and required.');
                }
            }


            function addQuestion() {
                // console.log('addQuestion called, questionCounter:', questionCounter);
                const clone = questionTemplate.content.cloneNode(true);
                const questionItem = clone.querySelector('.question-item');

                if (!questionItem) {
                    console.error('Error: .question-item not found in template clone. Template content:', questionTemplate.content);
                    return;
                }
                // Assign a unique data attribute to the cloned question item
                questionItem.setAttribute('data-question-index', questionCounter);
                // console.log('Assigned data-question-index:', questionCounter);

                // Replace IDX with the current questionCounter for all relevant inputs
                const inputsAndSelects = questionItem.querySelectorAll('[name*="IDX"]');
                inputsAndSelects.forEach(element => {
                    element.name = element.name.replace(/IDX/g, questionCounter);
                });
                // console.log('IDX replaced with', questionCounter, 'for all inputs/selects.');

                // Attach event listener for question type change
                const questionTypeSelect = questionItem.querySelector('.question-type-select');
                if (questionTypeSelect) {
                    questionTypeSelect.addEventListener('change', () => toggleQuestionSubsections(questionItem));
                    // console.log('Question type select event listener attached.');
                } else {
                    console.error('Error: question-type-select not found in new question item.');
                }

                // Attach event listener for remove button
                const removeButton = questionItem.querySelector('.remove-question-button');
                if (removeButton) {
                    removeButton.addEventListener('click', function() {
                        questionItem.remove();
                        updateQuestionNumbers();
                        // console.log('Question removed.');
                        // Re-evaluate if file upload should become required if all questions are removed for quiz/exam
                        const selectedType = typeSelect.value;
                        // You could add logic here to make the file upload required if quiz/exam AND no questions remain
                        // if ((selectedType === 'quiz' || selectedType === 'exam') && questionsContainer.children.length === 0) {
                        //     assessmentFileField.setAttribute('required', 'required');
                        //     console.log('All questions removed, making file upload required.');
                        // }
                    });
                    // console.log('Remove button event listener attached.');
                } else {
                    console.error('Error: remove-question-button not found in new question item.');
                }

                questionsContainer.appendChild(questionItem);
                // console.log('Question item appended to container.');

                // Initialize subsections visibility for the newly added question
                toggleQuestionSubsections(questionItem);

                updateQuestionNumbers();
                questionCounter++; // Increment for the next question
                // console.log('addQuestion finished, new questionCounter:', questionCounter);
            }

            // Initial setup on page load
            toggleSections(); // Initial toggle based on default/old value
            typeSelect.addEventListener('change', toggleSections);

            addQuestionButton.addEventListener('click', addQuestion);
            // console.log('DOM Content Loaded. Initial setup complete.');


            // --- Form Submission with Progress Bar and Enhanced Frontend Validation ---
            const form = document.getElementById('createAssessmentForm');
            const submitButton = document.getElementById('submitAssessmentButton');
            const modal = document.getElementById('uploadProgressModal');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');

            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default form submission
                // console.log('Form submission attempted.');

                const selectedType = typeSelect.value;
                const hasQuestions = questionsContainer.children.length > 0;
                const hasFile = assessmentFileField.files.length > 0;

                // Combined validation for quiz/exam: either questions or a file must exist
                if ((selectedType === 'quiz' || selectedType === 'exam') && !hasQuestions && !hasFile) {
                    alert('For Quiz or Exam, please add at least one question using the builder OR upload an assessment file.');
                    // Re-enable button and hide modal if validation fails
                    submitButton.removeAttribute('disabled');
                    submitButton.innerText = 'Create Assessment';
                    modal.classList.add('hidden');
                    // console.warn('Submission stopped: No questions or file for quiz/exam.');
                    return; // Stop submission
                }

                // Detailed validation for dynamically added questions (if builder is active)
                if (hasQuestions) { // This block only applies if questions are present
                    const questionItems = questionsContainer.querySelectorAll('.question-item');
                    for (const item of questionItems) {
                        const qText = item.querySelector('textarea[name$="[text]"]'); // Ends with "[text]"
                        const qTypeSelect = item.querySelector('.question-type-select');
                        const qPoints = item.querySelector('input[name$="[points]"]'); // Ends with "[points]"

                        if (!qText.value.trim()) {
                            alert('Question text is required for all questions.');
                            qText.focus();
                            // console.warn('Submission stopped: Missing question text.');
                            return; // Stop submission
                        }
                        if (!qPoints.value.trim() || parseInt(qPoints.value) < 1) {
                            alert('Points are required and must be at least 1 for all questions.');
                            qPoints.focus();
                            // console.warn('Submission stopped: Invalid points.');
                            return; // Stop submission
                        }

                        const selectedQType = qTypeSelect.value;
                        if (selectedQType === 'multiple_choice') {
                            let hasCorrectOption = false;
                            const optionsInputs = item.querySelectorAll('.multiple-choice-options input[type="text"]');
                            // Validate that at least the first two options have text
                            if (optionsInputs.length > 0 && !optionsInputs[0].value.trim()) {
                                alert('Option A text is required for multiple choice questions.');
                                optionsInputs[0].focus();
                                return;
                            }
                            if (optionsInputs.length > 1 && !optionsInputs[1].value.trim()) {
                                alert('Option B text is required for multiple choice questions.');
                                optionsInputs[1].focus();
                                return;
                            }

                            const radioButtons = item.querySelectorAll('.multiple-choice-options input[type="radio"]');
                            for (const radio of radioButtons) {
                                if (radio.checked) {
                                    hasCorrectOption = true;
                                    break;
                                }
                            }
                            if (!hasCorrectOption) {
                                alert('Please select a correct option for all multiple choice questions.');
                                // No focus needed, as it's a radio group
                                // console.warn('Submission stopped: No correct MC option selected.');
                                return; // Stop submission
                            }

                        } else if (selectedQType === 'identification') {
                            const answerInput = item.querySelector('.identification-answer input');
                            if (!answerInput.value.trim()) {
                                alert('Correct answer text is required for identification questions.');
                                answerInput.focus();
                                // console.warn('Submission stopped: Missing identification answer.');
                                return; // Stop submission
                            }
                        } else if (selectedQType === 'true_false') {
                            const answerSelect = item.querySelector('.true-false-answer select');
                            if (!answerSelect.value) { // Check if 'Select an answer' is still selected
                                alert('Please select a correct answer (True/False) for all true/false questions.');
                                answerSelect.focus();
                                // console.warn('Submission stopped: Missing True/False answer.');
                                return; // Stop submission
                            }
                        }
                    }
                }

                // If all frontend validations pass, proceed with AJAX submission
                // console.log('Frontend validation passed. Proceeding with AJAX submission.');
                submitButton.setAttribute('disabled', 'disabled');
                submitButton.innerText = 'Creating...';
                modal.classList.remove('hidden'); // Show the modal

                progressBar.style.width = '0%';
                progressText.innerText = '0% Complete';

                const formData = new FormData(form);
                const xhr = new XMLHttpRequest();

                xhr.open('POST', form.action);
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken.getAttribute('content'));
                } else {
                    console.error('CSRF token meta tag not found!');
                    alert('CSRF token missing. Please ensure your layout has <meta name="csrf-token" content="{{ csrf_token() }}">');
                    submitButton.removeAttribute('disabled');
                    submitButton.innerText = 'Create Assessment';
                    modal.classList.add('hidden');
                    return; // Stop submission
                }

                xhr.upload.onprogress = function(event) {
                    if (event.lengthComputable) {
                        const percentComplete = (event.loaded / event.total) * 100;
                        progressBar.style.width = percentComplete.toFixed(0) + '%';
                        progressText.innerText = percentComplete.toFixed(0) + '% Complete';
                    }
                };

                xhr.onload = function() {
                    modal.classList.add('hidden'); // Hide modal on completion
                    submitButton.removeAttribute('disabled');
                    submitButton.innerText = 'Create Assessment';
                    // console.log('AJAX request loaded. Status:', xhr.status);

                    if (xhr.status >= 200 && xhr.status < 300) {
                        // console.log('Assessment created successfully. Redirecting...');
                        // Laravel typically redirects on success for standard form submissions,
                        // so you might not get a JSON response for success.
                        // If you expect a redirect, let the browser handle it.
                        // If you expect a JSON success message, handle it here.
                        window.location.href = "{{ route('courses.show', $course->id) }}"; // Redirect to index
                    } else {
                        let errorMessage = 'An error occurred during assessment creation.';
                        // console.error('AJAX error response:', xhr.responseText);
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMessage = response.message;
                            } else if (response.errors) {
                                // Extract validation errors
                                let errorDetails = Object.values(response.errors).flat().join('\n');
                                errorMessage = `Validation Errors:\n${errorDetails}`;
                            }
                        } catch (e) {
                            // If response is not JSON, use generic message
                        }
                        alert(`Error: ${errorMessage}`);
                    }
                };

                xhr.onerror = function() {
                    modal.classList.add('hidden');
                    submitButton.removeAttribute('disabled');
                    submitButton.innerText = 'Create Assessment';
                    alert('Network error or server unreachable. Please try again.');
                    console.error('XMLHttpRequest failed.');
                };

                xhr.send(formData);
            });
        });
    </script>
</x-layout>