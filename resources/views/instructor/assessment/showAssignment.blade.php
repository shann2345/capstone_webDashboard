{{-- resources/views/instructor/assessment/showAssignment.blade.php --}}

<x-layout>
    <x-slot name="title">
        {{ $assessment->title }} - Assessment Details
    </x-slot>

    <div class="max-w-6xl mx-auto">
        {{-- Header Section --}}
        <div class="bg-gradient-to-r from-purple-600 to-indigo-700 text-white p-8 rounded-t-xl shadow-lg">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm border border-white/30 text-white mr-4">
                            {{ ucfirst($assessment->type) }}
                        </span>
                        @if($assessment->topic)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/10 backdrop-blur-sm border border-white/20 text-white">
                                {{ $assessment->topic->name }}
                            </span>
                        @endif
                    </div>
                    <h1 class="text-4xl font-bold mb-3">{{ $assessment->title }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-purple-100">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Created: {{ $assessment->created_at->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}
                        </div>
                        @if($assessment->creator)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                by {{ $assessment->creator->name }}
                            </div>
                        @endif
                    </div>
                </div>
                
                {{-- Action Buttons --}}
                <div class="flex gap-3 ml-6">
                    <a href="{{ route('assessments.edit.assignment', ['course' => $course->id, 'assessment' => $assessment->id]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg font-medium text-white hover:bg-white/30 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit {{ ucfirst($assessment->type) }}
                    </a>
                    
                    @if ($assessment->assessment_file_path)
                        <a href="{{ Storage::url($assessment->assessment_file_path) }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg font-medium text-white transition-all duration-200 shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download File
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-xl shadow-lg">
            {{-- Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 p-8">
                {{-- Main Content Area --}}
                <div class="lg:col-span-2">
                    @if ($assessment->description)
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-xl font-semibold text-gray-900">Instructions</h2>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-6 border-l-4 border-purple-500">
                                <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $assessment->description }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Assessment File Section --}}
                    <div class="mb-8">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">Assessment Materials</h2>
                        </div>

                        @if ($assessment->assessment_file_path)
                            <div class="border-2 border-dashed border-gray-200 rounded-xl overflow-hidden bg-gray-50">
                                @php
                                    $fileExtension = strtolower(pathinfo($assessment->assessment_file_path, PATHINFO_EXTENSION));
                                    $fileUrl = asset('storage/' . $assessment->assessment_file_path);
                                @endphp

                                @if ($fileExtension == 'pdf')
                                    <div class="p-6">
                                        <!-- Primary PDF viewer using object tag -->
                                        <object data="{{ $fileUrl }}" type="application/pdf" class="w-full rounded-lg border shadow-inner" style="height: 80vh;">
                                            <!-- Fallback iframe if object doesn't work -->
                                            <iframe src="{{ $fileUrl }}#toolbar=1&navpanes=1&scrollbar=1" 
                                                    class="w-full rounded-lg border shadow-inner" 
                                                    style="height: 80vh;" 
                                                    frameborder="0"
                                                    onload="this.style.display='block'">
                                                <!-- Final fallback if neither work -->
                                                <div class="p-8 text-center bg-white border rounded-lg">
                                                    <p class="text-gray-600 mb-4">Unable to display PDF file in this browser.</p>
                                                    <a href="{{ $fileUrl }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3"></path>
                                                        </svg>
                                                        Open PDF in New Tab
                                                    </a>
                                                </div>
                                            </iframe>
                                        </object>
                                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                            <p class="text-sm text-blue-700 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Cannot view PDF directly? 
                                                <a href="{{ $fileUrl }}" target="_blank" class="ml-1 font-semibold text-blue-600 hover:text-blue-800 underline">Open in new tab</a> or
                                                <a href="{{ $fileUrl }}" download class="ml-1 font-semibold text-blue-600 hover:text-blue-800 underline">download it here</a>.
                                            </p>
                                        </div>
                                    </div>
                                @elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']))
                                    <div class="p-6 text-center">
                                        <img src="{{ $fileUrl }}" 
                                             alt="{{ $assessment->title }}" 
                                             class="max-w-full h-auto rounded-lg shadow-lg mx-auto border"
                                             onerror="this.parentElement.innerHTML='<div class=\'p-8 text-center\'><p class=\'text-red-600 mb-4\'>Unable to load image file.</p><a href=\'' + this.src + '\' target=\'_blank\' class=\'text-blue-600 underline\'>Click to view file</a></div>'">
                                    </div>
                                @elseif (in_array($fileExtension, ['doc', 'docx']))
                                    <div class="p-6">
                                        <!-- Try to preview with Google Docs Viewer -->
                                        <iframe src="https://docs.google.com/gview?url={{ urlencode($fileUrl) }}&embedded=true" 
                                                class="w-full rounded-lg border shadow-inner" 
                                                style="height: 80vh;" 
                                                frameborder="0"
                                                onload="this.style.display='block'"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        </iframe>
                                        <div class="p-8 text-center bg-white border rounded-lg" style="display: none;">
                                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Word Document</h3>
                                            <p class="text-gray-600 mb-4">{{ basename($assessment->assessment_file_path) }}</p>
                                            <a href="{{ $fileUrl }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 mr-2">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                                Open
                                            </a>
                                            <a href="{{ $fileUrl }}" download class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3"></path>
                                                </svg>
                                                Download
                                            </a>
                                        </div>
                                        <div class="mt-4 p-4 bg-green-50 rounded-lg border border-green-200">
                                            <p class="text-sm text-green-700 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Word document preview powered by Google Docs Viewer.
                                            </p>
                                        </div>
                                    </div>
                                @elseif (in_array($fileExtension, ['xls', 'xlsx']))
                                    <div class="p-6">
                                        <!-- Try to preview with Google Docs Viewer -->
                                        <iframe src="https://docs.google.com/gview?url={{ urlencode($fileUrl) }}&embedded=true" 
                                                class="w-full rounded-lg border shadow-inner" 
                                                style="height: 80vh;" 
                                                frameborder="0"
                                                onload="this.style.display='block'">
                                        </iframe>
                                        <div class="mt-4 p-4 bg-green-50 rounded-lg border border-green-200">
                                            <p class="text-sm text-green-700 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Excel document preview. 
                                                <a href="{{ $fileUrl }}" target="_blank" class="ml-1 font-semibold text-green-600 hover:text-green-800 underline">Open in new tab</a> or
                                                <a href="{{ $fileUrl }}" download class="ml-1 font-semibold text-green-600 hover:text-green-800 underline">download</a>.
                                            </p>
                                        </div>
                                    </div>
                                @elseif (in_array($fileExtension, ['ppt', 'pptx']))
                                    <div class="p-6">
                                        <!-- Try to preview with Google Docs Viewer -->
                                        <iframe src="https://docs.google.com/gview?url={{ urlencode($fileUrl) }}&embedded=true" 
                                                class="w-full rounded-lg border shadow-inner" 
                                                style="height: 80vh;" 
                                                frameborder="0"
                                                onload="this.style.display='block'">
                                        </iframe>
                                        <div class="mt-4 p-4 bg-purple-50 rounded-lg border border-purple-200">
                                            <p class="text-sm text-purple-700 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                PowerPoint presentation preview.
                                                <a href="{{ $fileUrl }}" target="_blank" class="ml-1 font-semibold text-purple-600 hover:text-purple-800 underline">Open in new tab</a> or
                                                <a href="{{ $fileUrl }}" download class="ml-1 font-semibold text-purple-600 hover:text-purple-800 underline">download</a>.
                                            </p>
                                        </div>
                                    </div>
                                @elseif (in_array($fileExtension, ['txt', 'md', 'java', 'js', 'py', 'php', 'html', 'css', 'json', 'xml', 'csv']))
                                    <div class="p-6">
                                        <div class="bg-gray-900 rounded-lg p-6 overflow-auto" style="max-height: 500px;">
                                            @php
                                                $filePath = storage_path('app/public/' . $assessment->assessment_file_path);
                                                $fileContent = file_exists($filePath) ? file_get_contents($filePath) : 'File not found or cannot be read.';
                                            @endphp
                                            <pre class="text-sm text-green-400 whitespace-pre-wrap font-mono leading-relaxed">{{ $fileContent }}</pre>
                                        </div>
                                        <div class="mt-4 p-4 bg-gray-100 rounded-lg border border-gray-300">
                                            <p class="text-sm text-gray-600 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                                </svg>
                                                Viewing as text. 
                                                <a href="{{ $fileUrl }}" download class="ml-1 font-semibold text-gray-700 hover:text-gray-900 underline">Download original file</a>.
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="p-12 text-center">
                                        <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                                            <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-700 mb-2">{{ strtoupper($fileExtension) }} File</h3>
                                        <p class="text-gray-600 mb-6">{{ basename($assessment->assessment_file_path) }}</p>
                                        <div class="flex justify-center gap-3">
                                            <a href="{{ $fileUrl }}" target="_blank"
                                               class="inline-flex items-center px-6 py-3 bg-purple-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:bg-purple-700 transition ease-in-out duration-150 shadow-lg">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                                Open File
                                            </a>
                                            <a href="{{ $fileUrl }}" download
                                               class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:bg-green-700 transition ease-in-out duration-150 shadow-lg">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Download File
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500">No file attached to this assessment.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1 space-y-6">
                    {{-- Assessment Settings Card --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Assessment Settings</h3>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            @if($assessment->duration_minutes)
                                <div class="flex items-start">
                                    <div class="w-2 h-2 bg-orange-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Duration</p>
                                        <p class="text-sm text-gray-600">{{ $assessment->duration_minutes }} minutes</p>
                                    </div>
                                </div>
                            @endif

                            @if($assessment->access_code)
                                <div class="flex items-start">
                                    <div class="w-2 h-2 bg-red-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Access Code Required</p>
                                        <p class="text-sm text-gray-600 font-mono bg-gray-100 px-2 py-1 rounded">{{ $assessment->access_code }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-start">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Access Code</p>
                                        <p class="text-sm text-gray-600">Not required</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Availability Card --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Availability</h3>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            @if ($assessment->available_at || $assessment->unavailable_at)
                                @if ($assessment->available_at)
                                    <div class="flex items-start">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Available From</p>
                                            <p class="text-sm text-gray-600">{{ $assessment->available_at->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-start">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Available From</p>
                                            <p class="text-sm text-gray-600">Always (no start date)</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($assessment->unavailable_at)
                                    <div class="flex items-start">
                                        <div class="w-2 h-2 bg-red-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Available Until</p>
                                            <p class="text-sm text-gray-600">{{ $assessment->unavailable_at->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-start">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Available Until</p>
                                            <p class="text-sm text-gray-600">Never (no end date)</p>
                                        </div>
                                    </div>
                                @endif

                                <div class="pt-2">
                                    @php
                                        $now = \Carbon\Carbon::now();
                                        $isAvailable = (!$assessment->available_at || $now->gte($assessment->available_at)) && 
                                                      (!$assessment->unavailable_at || $now->lt($assessment->unavailable_at));
                                    @endphp
                                    @if ($isAvailable)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                            Currently Available
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                            Currently Unavailable
                                        </span>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Always Available
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- File Information Card --}}
                    @if ($assessment->assessment_file_path)
                        @php
                            $fileSize = Storage::disk('public')->exists($assessment->assessment_file_path) 
                                ? Storage::disk('public')->size($assessment->assessment_file_path) 
                                : 0;
                            $fileSizeMB = round($fileSize / 1024 / 1024, 2);
                            $fileExtension = pathinfo($assessment->assessment_file_path, PATHINFO_EXTENSION);
                        @endphp
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">File Information</h3>
                                </div>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 mb-1">Filename</p>
                                    <p class="text-sm text-gray-600 break-all">{{ basename($assessment->assessment_file_path) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 mb-1">File Type</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ strtoupper($fileExtension) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 mb-1">File Size</p>
                                    <p class="text-sm text-gray-600">{{ $fileSizeMB }} MB</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 rounded-b-xl">
                <div class="flex justify-between items-center">
                    <a href="{{ route('courses.show', $course->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Course
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout>