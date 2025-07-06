{{-- resources/views/instructor/assessment/createQuiz.blade.php --}}

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
                    <form method="POST" action="{{ route('assessments.store.quiz', $course->id) }}" enctype="multipart/form-data" id="createAssessmentForm">
                        @csrf

                        {{-- Hidden input for assessment type --}}
                        <input type="hidden" name="type" value="{{ $assessmentType }}">
                        @if(isset($topicId))
                            <input type="hidden" name="topic_id" value="{{ $topicId }}">
                        @endif

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

                        <!-- Basic Assessment Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                <textarea id="description" name="description" rows="3"
                                          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror"
                                          placeholder="Enter {{ $assessmentType }} description...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Quiz/Exam Specific Settings (Duration, Access Code) -->
                        <div id="quizSpecificSection" class="mt-6 p-6 border border-gray-200 rounded-lg bg-gray-50 shadow-sm">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ ucfirst($assessmentType) }} Settings</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="mb-4">
                                    <label for="duration_minutes" class="block text-gray-700 text-sm font-bold mb-2">Duration in Minutes (Optional):</label>
                                    <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes') }}"
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('duration_minutes') border-red-500 @enderror" min="0">
                                    <p class="text-gray-500 text-xs italic mt-1">Leave empty for no time limit</p>
                                    @error('duration_minutes')
                                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="access_code" class="block text-gray-700 text-sm font-bold mb-2">Access Code (Optional):</label>
                                    <input type="text" id="access_code" name="access_code" value="{{ old('access_code') }}"
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('access_code') border-red-500 @enderror"
                                           placeholder="Optional access code">
                                    <p class="mt-1 text-sm text-gray-500">Students will need this code to unlock the assessment.</p>
                                    @error('access_code')
                                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- File Upload Section -->
                        <div id="fileUploadSection" class="mt-6 p-6 border border-gray-200 rounded-lg bg-gray-50 shadow-sm">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Upload Assessment File (Optional)</h2>
                            <div class="mb-4">
                                <label for="assessment_file" class="block text-gray-700 text-sm font-bold mb-2">Upload File (e.g., PDF, Word, Excel for questions/briefs):</label>
                                <input type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('assessment_file') border-red-500 @enderror"
                                       id="assessment_file" name="assessment_file"
                                       accept=".pdf,.doc,.docx,.xlsx,.xls,.ppt,.pptx,.txt,.zip,.rar">
                                <p class="mt-1 text-sm text-gray-500">
                                    Supported formats: PDF, DOC, DOCX, XLSX, XLS, PPT, PPTX, TXT, ZIP, RAR (Max: 20MB).
                                    Leave blank if you are using the online question builder below.
                                </p>
                                @error('assessment_file')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Question Builder Section -->
                        <div id="questionBuilderSection" class="mt-6 p-6 border border-blue-200 rounded-lg bg-blue-50 shadow-sm">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Online {{ ucfirst($assessmentType) }} Questions</h2>
                            <p class="text-sm text-gray-600 mb-4">
                                Build your questions directly here. You can add multiple choice, identification, or true/false questions.
                            </p>

                            <div id="questionsContainer" class="space-y-6">
                                <!-- Questions will be appended here dynamically -->
                            </div>

                            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mt-4 hidden" id="no-questions-alert">
                                <span class="block sm:inline">No questions added yet. Click "Add Question" to start creating your {{ $assessmentType }}.</span>
                            </div>

                            <button type="button" id="addQuestionButton" class="mt-6 inline-flex items-center px-4 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition duration-300 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Add Question
                            </button>
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

    <!-- Question Template (Hidden) -->
    <template id="question-template">
        <div class="question-item bg-white p-6 rounded-lg shadow-md border border-gray-200 relative">
            <button type="button" class="remove-question-button absolute top-3 right-3 text-red-500 hover:text-red-700 text-2xl font-bold leading-none focus:outline-none">&times;</button>
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Question <span class="question-number"></span></h4>

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

            <div class="question-specific-fields">
                <!-- Question-specific fields will be added here dynamically by JS -->
                <div class="multiple-choice-options mb-4 hidden">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Options: <span class="text-red-500">*</span></label>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-2">
                            <input type="radio" name="questions[IDX][correct_answer]" value="0" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500" required>
                            <input type="text" name="questions[IDX][options][0][option_text]" placeholder="Option A" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <input type="hidden" name="questions[IDX][options][0][option_order]" value="0">
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="radio" name="questions[IDX][correct_answer]" value="1" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500">
                            <input type="text" name="questions[IDX][options][1][option_text]" placeholder="Option B" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <input type="hidden" name="questions[IDX][options][1][option_order]" value="1">
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="radio" name="questions[IDX][correct_answer]" value="2" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500">
                            <input type="text" name="questions[IDX][options][2][option_text]" placeholder="Option C" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <input type="hidden" name="questions[IDX][options][2][option_order]" value="2">
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="radio" name="questions[IDX][correct_answer]" value="3" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500">
                            <input type="text" name="questions[IDX][options][3][option_text]" placeholder="Option D" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <input type="hidden" name="questions[IDX][options][3][option_order]" value="3">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Select the radio button next to the correct option. Options A and B are required.</p>
                    </div>
                </div>

                <div class="identification-answer mb-4 hidden">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Correct Answer <span class="text-red-500">*</span></label>
                    <input type="text" name="questions[IDX][correct_answer]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter the correct answer" required>
                    <p class="mt-1 text-sm text-gray-500">Enter the exact word or phrase for the correct answer.</p>
                </div>

                <div class="true-false-answer mb-4 hidden">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Correct Answer <span class="text-red-500">*</span></label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="questions[IDX][correct_answer]" required>
                        <option value="">Select an answer</option>
                        <option value="true">True</option>
                        <option value="false">False</option>
                    </select>
                </div>

                <div class="essay-answer mb-4 hidden">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Answer (Optional)</label>
                    <input type="text" name="questions[IDX][correct_answer]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="(Optional) Enter a sample answer for reference">
                    <p class="mt-1 text-sm text-gray-500">Leave blank if you want to manually check and grade this essay.</p>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Points:</label>
                <input type="number" name="questions[IDX][points]" value="1" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
        </div>
    </template>

    <x-slot name="scripts">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // No need for typeSelect or toggleSections here, as this form is specific to quiz/exam
                const fileUploadSection = document.getElementById('fileUploadSection');
                const quizSpecificSection = document.getElementById('quizSpecificSection');
                const questionBuilderSection = document.getElementById('questionBuilderSection');
                const assessmentFileField = document.getElementById('assessment_file');

                const addQuestionButton = document.getElementById('addQuestionButton');
                const questionsContainer = document.getElementById('questionsContainer');
                const questionTemplate = document.getElementById('question-template');
                const noQuestionsAlert = document.getElementById('no-questions-alert');

                const form = document.getElementById('createAssessmentForm');
                const submitButton = document.getElementById('submitAssessmentButton');
                const uploadProgressModalElement = document.getElementById('uploadProgressModal');
                const progressBar = document.getElementById('progressBar');
                const progressText = document.getElementById('progressText');
                const formErrorsDiv = document.getElementById('form-errors');
                const errorList = document.getElementById('error-list');

                let questionCounter = 0; // To keep track of unique question indices
                addQuestionButton.addEventListener('click', addQuestion);


                // Helper function to reset submit button and hide modal
                function resetSubmitButtonAndModal() {
                    submitButton.removeAttribute('disabled');
                    submitButton.innerHTML = `<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg> Create {{ ucfirst($assessmentType) }}`;
                    uploadProgressModalElement.classList.add('hidden'); // Use Tailwind's hidden class
                }

                // --- Question Builder Logic ---
                function updateQuestionNumbers() {
                    const questionItems = questionsContainer.querySelectorAll('.question-item');
                    questionItems.forEach((item, index) => {
                        item.setAttribute('data-question-index', index);
                        // Update question title
                        item.querySelector('h4 .question-number').textContent = index + 1;

                        // Update all name attributes for this question
                        item.querySelectorAll('[name]').forEach(element => {
                            // Replace questions[OLD] or questions[IDX] with questions[index]
                            element.name = element.name
                                .replace(/questions\[\d+\]/g, `questions[${index}]`)
                                .replace(/questions\[IDX\]/g, `questions[${index}]`);
                        });
                    });
                    questionCounter = questionItems.length;
                    updateNoQuestionsAlert();
                }

                function toggleQuestionSpecificFields(questionItem) {
                    const questionTypeSelect = questionItem.querySelector('.question-type-select');
                    const selectedQuestionType = questionTypeSelect.value;

                    const mcOptions = questionItem.querySelector('.multiple-choice-options');
                    const identificationAnswer = questionItem.querySelector('.identification-answer');
                    const trueFalseAnswer = questionItem.querySelector('.true-false-answer');
                    const essayAnswer = questionItem.querySelector('.essay-answer');

                    // Hide all and disable their inputs
                    mcOptions.classList.add('hidden');
                    mcOptions.querySelectorAll('input,select').forEach(el => el.disabled = true);

                    identificationAnswer.classList.add('hidden');
                    identificationAnswer.querySelectorAll('input,select').forEach(el => el.disabled = true);

                    trueFalseAnswer.classList.add('hidden');
                    trueFalseAnswer.querySelectorAll('input,select').forEach(el => el.disabled = true);

                    essayAnswer.classList.add('hidden');
                    essayAnswer.querySelectorAll('input,select').forEach(el => el.disabled = true);

                    // Show and enable only the relevant fields
                    if (selectedQuestionType === 'multiple_choice') {
                        mcOptions.classList.remove('hidden');
                        mcOptions.querySelectorAll('input,select').forEach(el => el.disabled = false);
                    } else if (selectedQuestionType === 'identification') {
                        identificationAnswer.classList.remove('hidden');
                        identificationAnswer.querySelectorAll('input,select').forEach(el => el.disabled = false);
                    } else if (selectedQuestionType === 'true_false') {
                        trueFalseAnswer.classList.remove('hidden');
                        trueFalseAnswer.querySelectorAll('input,select').forEach(el => el.disabled = false);
                    } else if (selectedQuestionType === 'essay') {
                        essayAnswer.classList.remove('hidden');
                        essayAnswer.querySelectorAll('input,select').forEach(el => el.disabled = false);
                    }
                }

                function addQuestion() {
                    const clone = questionTemplate.content.cloneNode(true);
                    const questionItem = clone.querySelector('.question-item');

                    questionItem.setAttribute('data-question-index', questionCounter);

                    // Replace IDX placeholder with unique counter in all name attributes
                    questionItem.querySelectorAll('[name*="IDX"]').forEach(element => {
                        element.name = element.name.replace(/IDX/g, questionCounter);
                    });

                    questionsContainer.appendChild(questionItem);

                    const addedCard = questionsContainer.lastElementChild;
                    const typeSelectInCard = addedCard.querySelector('.question-type-select');
                    const removeBtnInCard = addedCard.querySelector('.remove-question-button');

                    toggleQuestionSpecificFields(addedCard); // Initial toggle

                    typeSelectInCard.addEventListener('change', function() {
                        toggleQuestionSpecificFields(addedCard);
                    });

                    removeBtnInCard.addEventListener('click', function() {
                        if (confirm('Are you sure you want to remove this question?')) {
                            addedCard.remove();
                            updateQuestionNumbers();
                        }
                    });

                    updateQuestionNumbers();
                    questionCounter++;

                    addedCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    addedCard.classList.add('ring-4', 'ring-blue-300', 'ring-opacity-50');
                    setTimeout(() => addedCard.classList.remove('ring-4', 'ring-blue-300', 'ring-opacity-50'), 2000);
                }

                function updateNoQuestionsAlert() {
                    const questionCount = questionsContainer.querySelectorAll('.question-item').length;
                    if (questionCount === 0) {
                        noQuestionsAlert.classList.remove('hidden');
                    } else {
                        noQuestionsAlert.classList.add('hidden');
                    }
                }

                // --- Form Submission Logic ---
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    errorList.innerHTML = '';
                    formErrorsDiv.classList.add('hidden');
                    const hasQuestions = questionsContainer.querySelectorAll('.question-item').length > 0;
                    const hasFile = assessmentFileField.files.length > 0;
                    const assessmentType = '{{ $assessmentType }}';
                    let clientErrors = [];

                    if (!document.getElementById('title').value.trim()) {
                        clientErrors.push('Assessment Title is required.');
                    }

                    if (hasQuestions) {
                        const questionItems = questionsContainer.querySelectorAll('.question-item');
                        questionItems.forEach((item, qIndex) => {
                            const qText = item.querySelector('textarea[name*="[question_text]"]');
                            const qTypeSelect = item.querySelector('.question-type-select');
                            const qPoints = item.querySelector('input[name*="[points]"]');
                            if (!qText.value.trim()) {
                                clientErrors.push(`Question #${qIndex + 1}: Question text is required.`);
                            }
                            if (!qPoints.value.trim() || parseInt(qPoints.value) < 1) {
                                clientErrors.push(`Question #${qIndex + 1}: Points are required and must be at least 1.`);
                            }

                            if (!qTypeSelect.value) {
                                clientErrors.push(`Question #${qIndex + 1}: Question type is required.`);
                                return; // Skip further validation if type isn't selected
                            }
                            const currentQType = qTypeSelect.value;
                            
                            if (currentQType === 'multiple_choice') {
                                const mcOptionTextInputs = item.querySelectorAll('.multiple-choice-options input[type="text"]');
                                const mcOptionRadioInputs = item.querySelectorAll('.multiple-choice-options input[type="radio"]');
                                let hasSelectedAnswer = false;
                                mcOptionTextInputs.forEach((input, optIndex) => {
                                    if (optIndex < 2 && !input.value.trim()) {
                                        clientErrors.push(`Question #${qIndex + 1} (Multiple Choice): Option ${String.fromCharCode(65 + optIndex)} text is required.`);
                                    }
                                });
                                mcOptionRadioInputs.forEach(radio => {
                                    if (radio.checked) {
                                        hasSelectedAnswer = true;
                                        const selectedOptionText = item.querySelector(`.multiple-choice-options input[name*="[options][${radio.value}][option_text]"]`);
                                        if (!selectedOptionText.value.trim()) {
                                            clientErrors.push(`Question #${qIndex + 1} (Multiple Choice): The selected correct option must have text.`);
                                        }
                                    }
                                });

                                if (!hasSelectedAnswer) {
                                    clientErrors.push(`Question #${qIndex + 1} (Multiple Choice): Please select a correct option.`);
                                }
                            } else if (currentQType === 'identification') {
                                const answerInput = item.querySelector('input[name*="[correct_answer]"]');
                                if (!answerInput || !answerInput.value.trim()) {
                                    clientErrors.push(`Question #${qIndex + 1} (Identification): Correct answer text is required.`);
                                }
                            } else if (currentQType === 'true_false') {
                                const answerSelect = item.querySelector('select[name*="[correct_answer]"]');
                                if (!answerSelect || !answerSelect.value) {
                                    clientErrors.push(`Question #${qIndex + 1} (True/False): Please select a correct answer.`);
                                }
                            } else if (currentQType === 'essay') {
                                
                            }
                        });
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

                // Initial setup: Add a default question if none are present (e.g., on first load)
                // This is simpler now as there's no dynamic type switching within this form.
                if (questionsContainer.children.length === 0) {
                    addQuestion();
                }
                updateNoQuestionsAlert(); // Ensure alert state is correct on load

                // Handle old input for questions (in case of validation errors on full page reload)
                @if(old('questions'))
                    @foreach(old('questions') as $index => $question)
                        questionCounter = {{ $index }};
                        addQuestion();
                        const lastQuestion = questionsContainer.lastElementChild;

                        const typeSelectInOld = lastQuestion.querySelector('.question-type-select');
                        if (typeSelectInOld) {
                            typeSelectInOld.value = '{{ $question['question_type'] ?? '' }}';
                            typeSelectInOld.dispatchEvent(new Event('change'));
                        }

                        const textAreaInOld = lastQuestion.querySelector('textarea[name*="[question_text]"]');
                        if (textAreaInOld) textAreaInOld.value = JSON.parse(JSON.stringify(`{{ $question['question_text'] ?? '' }}`));

                        const pointsInputInOld = lastQuestion.querySelector('input[name*="[points]"]');
                        if (pointsInputInOld) pointsInputInOld.value = '{{ $question['points'] ?? 1 }}';

                        setTimeout(function() {
                            const currentQType = typeSelectInOld.value;
                            @if(isset($question['correct_answer']))
                                if (currentQType === 'identification') {
                                    const idInput = lastQuestion.querySelector('input[name*="[correct_answer]"]');
                                    if(idInput) idInput.value = JSON.parse(JSON.stringify(`{{ $question['correct_answer'] }}`));
                                } else if (currentQType === 'true_false') {
                                    const tfSelect = lastQuestion.querySelector('select[name*="[correct_answer]"]');
                                    if(tfSelect) tfSelect.value = '{{ $question['correct_answer'] }}';
                                } else if (currentQType === 'multiple_choice') {
                                    const mcCorrectRadio = lastQuestion.querySelector(`input[name*="[correct_answer]"][value="{{ $question['correct_answer'] }}"]`);
                                    if(mcCorrectRadio) mcCorrectRadio.checked = true;
                                }
                            @endif

                            @if(isset($question['options']))
                                @foreach($question['options'] as $optionIndex => $option)
                                    const optionInput = lastQuestion.querySelector('input[name*="[options][{{ $optionIndex }}][option_text]"]');
                                    if(optionInput) optionInput.value = JSON.parse(JSON.stringify(`{{ $option['option_text'] ?? '' }}`));
                                @endforeach
                            @endif
                        }, 150);
                    @endforeach
                    questionCounter = questionsContainer.children.length;
                    updateQuestionNumbers();
                @endif
            });
        </script>
    </x-slot>
</x-layout>
