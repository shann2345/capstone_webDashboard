<x-layout>
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        {{ isset($material) ? 'Edit Material for:' : 'Manage Materials for:' }} 
        <span class="text-blue-700">{{ $course->title }}</span>
    </h1>
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

    {{-- Display validation errors --}}
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

    {{-- Material Form --}}
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">
            {{ isset($material) ? 'Edit Material' : 'Upload New Material' }}
        </h2>
        
        <form action="{{ isset($material) ? route('materials.update', $material->id) : route('materials.store', $course->id) }}" 
              method="POST" 
              enctype="multipart/form-data" 
              id="materialForm">
            @csrf
            @if(isset($material))
                @method('PUT')
            @endif

            <input type="hidden" name="topic_id" value="{{ $topicId }}">

            <div class="mb-4">
                <label for="material_title" class="block text-gray-700 text-sm font-bold mb-2">Material Title:</label>
                <input type="text" id="material_title" name="title" 
                       value="{{ old('title', $material->title ?? '') }}"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="material_description" class="block text-gray-700 text-sm font-bold mb-2">Description (Optional):</label>
                <textarea id="material_description" name="description" rows="3"
                          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description', $material->description ?? '') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="material_file" class="block text-gray-700 text-sm font-bold mb-2">
                    {{ isset($material) ? 'Upload New File (Optional - leave empty to keep current file):' : 'Upload File:' }}
                </label>
                
                {{-- Show current file info if editing --}}
                @if(isset($material) && $material->file_path)
                    <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded text-sm">
                        <strong>Current File:</strong> {{ $material->original_filename ?? 'Unknown filename' }}<br>
                        <strong>Type:</strong> {{ strtoupper(pathinfo($material->file_path, PATHINFO_EXTENSION)) }}<br>
                        @if(Storage::disk('public')->exists($material->file_path))
                            <strong>Size:</strong> {{ round(Storage::disk('public')->size($material->file_path) / 1024 / 1024, 2) }} MB
                        @endif
                    </div>
                @endif
                
                <input type="file" id="material_file" name="material_file"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" 
                       {{ isset($material) ? '' : 'required' }}
                       accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.rtf,.odt,.xls,.xlsx,.csv,.java,.js,.py,.php,.html,.css,.json,.xml,.cpp,.c,.h,.hpp,.cs,.rb,.go,.swift,.kt,.scala,.r,.sql,.sh,.bat,.ps1,.yml,.yaml,.toml,.ini,.cfg,.conf,.md,.rst,.tex,.mp4,.mov,.avi,.webm,.ogg,.mkv,.flv,.wmv,.m4v,.3gp,.mpg,.mpeg,.mp3,.wav,.aac,.flac,.m4a,.wma,.opus,.aiff,.au,.jpg,.jpeg,.png,.gif,.svg,.webp,.bmp,.tiff,.tif,.ico,.heic,.heif,.zip,.rar,.7z,.tar,.gz,.bz2,.xz,.lzma,.cab,.iso,.apk,.exe,.msi,.deb,.rpm,.dmg,.pkg,.bin,.jar,.war,.ear">
                <p class="mt-1 text-sm text-gray-500">
                    <strong>Supported files (Max 100MB):</strong><br>
                    <span class="text-xs">
                        📄 <strong>Documents:</strong> PDF, Word, PPT, Excel, TXT, RTF, ODT, CSV<br>
                        💻 <strong>Code Files:</strong> Java, JS, Python, PHP, HTML, CSS, JSON, XML, C++, C#, Ruby, Go, Swift, Kotlin, R, SQL, Shell, YAML, Markdown<br>
                        🎥 <strong>Videos:</strong> MP4, MOV, AVI, WebM, MKV, FLV, WMV, MPEG<br>
                        🎵 <strong>Audio:</strong> MP3, WAV, AAC, FLAC, M4A, WMA, OPUS<br>
                        🖼️ <strong>Images:</strong> JPG, PNG, GIF, SVG, WebP, BMP, TIFF, HEIC<br>
                        📦 <strong>Archives:</strong> ZIP, RAR, 7Z, TAR, GZ, ISO<br>
                        ⚙️ <strong>Executables:</strong> APK, EXE, MSI, DEB, RPM, DMG, JAR
                    </span>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="available_at" class="block text-gray-700 text-sm font-bold mb-2">Available From (Optional Date/Time):</label>
                    <input type="datetime-local" id="available_at" name="available_at" 
                           value="{{ old('available_at', isset($material) && $material->available_at ? $material->available_at->setTimezone('Asia/Manila')->format('Y-m-d\TH:i') : '') }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <label for="unavailable_at" class="block text-gray-700 text-sm font-bold mb-2">Available Until (Optional Date/Time):</label>
                    <input type="datetime-local" id="unavailable_at" name="unavailable_at" 
                           value="{{ old('unavailable_at', isset($material) && $material->unavailable_at ? $material->unavailable_at->setTimezone('Asia/Manila')->format('Y-m-d\TH:i') : '') }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ isset($material) ? route('materials.show', $material->id) : route('courses.show', $course->id) }}" 
                   class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">
                    {{ isset($material) ? 'Cancel' : 'Back to Course Details' }}
                </a>
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        id="submitMaterialButton">
                    {{ isset($material) ? 'Update Material' : 'Upload Material' }}
                </button>
            </div>
        </form>
    </div>

    {{-- Upload Progress Modal --}}
    <div id="uploadProgressModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                {{ isset($material) ? 'Updating Material...' : 'Uploading Material...' }}
            </h3>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div id="progressBar" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
            </div>
            <p id="progressText" class="text-sm text-gray-600 text-center">0% Complete</p>
        </div>
    </div>

    <script>
        // File size validation
        document.getElementById('material_file').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const maxSize = 100 * 1024 * 1024; // 100MB in bytes
                if (file.size > maxSize) {
                    alert('File size exceeds 100MB limit. Please choose a smaller file.');
                    event.target.value = ''; // Clear the file input
                    return;
                }
                
                // Show file info
                const fileInfo = document.getElementById('fileInfo');
                if (fileInfo) {
                    fileInfo.remove();
                }
                
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                const infoDiv = document.createElement('div');
                infoDiv.id = 'fileInfo';
                infoDiv.className = 'mt-2 p-2 bg-blue-50 border border-blue-200 rounded text-sm';
                infoDiv.innerHTML = `
                    <strong>Selected:</strong> ${file.name}<br>
                    <strong>Size:</strong> ${fileSizeMB} MB<br>
                    <strong>Type:</strong> ${file.type || 'Unknown'}
                `;
                event.target.parentNode.appendChild(infoDiv);
            }
        });

        document.getElementById('materialForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const form = event.target;
            const fileInput = document.getElementById('material_file');
            const file = fileInput.files[0];
            const isEdit = {{ isset($material) ? 'true' : 'false' }};
            
            // Additional client-side validation for new uploads
            if (!isEdit && !file) {
                alert('Please select a file to upload.');
                return;
            }
            
            if (file) {
                const maxSize = 100 * 1024 * 1024; // 100MB in bytes
                if (file.size > maxSize) {
                    alert('File size exceeds 100MB limit. Please choose a smaller file.');
                    return;
                }
            }

            const submitButton = document.getElementById('submitMaterialButton');
            const modal = document.getElementById('uploadProgressModal');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');

            // Disable the submit button and show the modal
            submitButton.setAttribute('disabled', 'disabled');
            submitButton.innerText = isEdit ? 'Updating...' : 'Uploading...';
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
                submitButton.innerText = isEdit ? 'Update Material' : 'Upload Material';

                if (xhr.status >= 200 && xhr.status < 300) {
                    // Success: Redirect to the response URL
                    window.location.href = xhr.responseURL;
                } else {
                    // Error: Handle validation errors or server errors
                    let errorMessage = 'An error occurred during ' + (isEdit ? 'update' : 'upload') + '.';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }
                    alert(errorMessage + ' Please check your input and try again.');

                    if (xhr.status === 422 || xhr.status === 302) {
                        window.location.href = xhr.responseURL || window.location.href;
                    }
                }
            };

            // Error handler (network errors, etc.)
            xhr.onerror = function() {
                modal.classList.add('hidden'); // Hide modal on error
                submitButton.removeAttribute('disabled');
                submitButton.innerText = isEdit ? 'Update Material' : 'Upload Material';
                alert('Network error or server unreachable. Please try again.');
            };

            xhr.send(formData);
        });
    </script>
</x-layout>