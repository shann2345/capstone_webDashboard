{{-- resources/views/materials/index.blade.php --}}

<x-layout>
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Manage Materials for: <span class="text-blue-700">{{ $course->title }}</span></h1>
    <p class="text-gray-600 mb-8">Course Code: {{ $course->course_code }}</p>

    {{-- Display success message --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Display error message --}}
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Display validation errors (these appear after redirect on validation failure) --}}
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

    {{-- Upload New Material Form --}}
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Upload New Material</h2>
        <form action="{{ route('materials.store', $course->id) }}" method="POST" enctype="multipart/form-data" id="uploadMaterialForm">
            @csrf

            <div class="mb-4">
                <label for="material_title" class="block text-gray-700 text-sm font-bold mb-2">Material Title:</label>
                <input type="text" id="material_title" name="title" value="{{ old('title') }}"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="material_description" class="block text-gray-700 text-sm font-bold mb-2">Description (Optional):</label>
                <textarea id="material_description" name="description" rows="3"
                          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="material_file" class="block text-gray-700 text-sm font-bold mb-2">Upload File:</label>
                <input type="file" id="material_file" name="material_file"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                <p class="mt-1 text-sm text-gray-500">PDF, Word, PPT, TXT, Java, JS, Python, MP4, MOV, AVI (Max 20MB)</p>
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

            <div class="flex items-center justify-end">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        id="submitMaterialButton">
                    Upload Material
                </button>
            </div>
        </form>
    </div>

    {{-- List Existing Materials --}}
    {{-- <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Existing Materials</h2>
        @if ($materials->isEmpty())
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
                        @foreach ($materials as $material)
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
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($material->file_path) 
                                        <a href="{{ route('materials.download', $material->id) }}" class="text-blue-600 hover:text-blue-900 mr-4">Download</a>
                                    @endif
                                    
                                    <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <a href="#" class="text-red-600 hover:text-red-900 ml-4">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div> --}}

    <div class="flex justify-end mt-6">
        <a href="{{ route('courses.show', $course->id) }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
            Back to Course Details
        </a>
    </div>

    {{-- !!! BEGIN: Upload Progress Modal !!! --}}
    <div id="uploadProgressModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Uploading Material...</h3>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div id="progressBar" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
            </div>
            <p id="progressText" class="text-sm text-gray-600 text-center">0% Complete</p>
        </div>
    </div>
    {{-- !!! END: Upload Progress Modal !!! --}}

    <script>
        document.getElementById('uploadMaterialForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const form = event.target;
            const submitButton = document.getElementById('submitMaterialButton');
            const modal = document.getElementById('uploadProgressModal');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');

            // Disable the submit button and show the modal
            submitButton.setAttribute('disabled', 'disabled');
            submitButton.innerText = 'Uploading...';
            modal.classList.remove('hidden'); // Show the modal

            // Reset progress bar
            progressBar.style.width = '0%';
            progressText.innerText = '0% Complete';

            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();

            xhr.open('POST', form.action);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Progress event handler
            xhr.upload.onprogress = function(event) {
                if (event.lengthComputable) {
                    const percentComplete = (event.loaded / event.total) * 100;
                    progressBar.style.width = percentComplete.toFixed(0) + '%';
                    progressText.innerText = percentComplete.toFixed(0) + '% Complete';
                }
            };

            // Load (completion) event handler
            xhr.onload = function() {
                modal.classList.add('hidden'); // Hide modal on completion
                submitButton.removeAttribute('disabled');
                submitButton.innerText = 'Upload Material';

                if (xhr.status >= 200 && xhr.status < 300) {
                    // Success: Redirect to the materials index page (which refreshes the list)
                    window.location.href = xhr.responseURL;
                } else {
                    // Error: Handle validation errors or server errors
                    let errorMessage = 'An error occurred during upload.';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                        // If validation errors, Laravel usually redirects and flashes them.
                        // For API style, you'd show errors here. For now, a simple redirect on fail
                        // will show validation errors (if server redirects back).
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }
                    // Since Laravel validation redirects, for XHR success means redirect.
                    // For XHR errors, we generally wouldn't get a redirect, so a simple alert for now.
                    // In a full SPA, you'd parse errors and display them without page reload.
                    alert(errorMessage + ' Please check your input and try again.');

                    // Optionally, redirect to allow Laravel's validation errors to be displayed
                    // if the server actually returned a redirect with errors (which it would for validation failure)
                    if (xhr.status === 422 || xhr.status === 302) { // 422 for validation, 302 for redirect
                        window.location.href = xhr.responseURL || window.location.href;
                    }
                }
            };

            // Error handler (network errors, etc.)
            xhr.onerror = function() {
                modal.classList.add('hidden'); // Hide modal on error
                submitButton.removeAttribute('disabled');
                submitButton.innerText = 'Upload Material';
                alert('Network error or server unreachable. Please try again.');
            };

            xhr.send(formData);
        });
    </script>
</x-layout>