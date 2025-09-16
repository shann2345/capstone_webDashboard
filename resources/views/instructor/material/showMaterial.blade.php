{{-- resources/views/materials/show.blade.php --}}

<x-layout>
    <x-slot name="title">
        {{ $material->title }} - Material Details
    </x-slot>

    <div class="max-w-6xl mx-auto">
        {{-- Header Section --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-8 rounded-t-xl shadow-lg">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h1 class="text-4xl font-bold mb-3">{{ $material->title }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-blue-100">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Uploaded: {{ $material->created_at->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}
                        </div>
                        @if($material->user)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                by {{ $material->user->name }}
                            </div>
                        @endif
                    </div>
                </div>
                
                {{-- Action Buttons --}}
                <div class="flex gap-3 ml-6">
                    <a href="{{ route('materials.edit', $material->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg font-medium text-white hover:bg-white/30 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Material
                    </a>
                    
                    @if ($material->file_path)
                        <a href="{{ route('materials.download', $material->id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg font-medium text-white transition-all duration-200 shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download
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
                    @if ($material->description)
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-xl font-semibold text-gray-900">Description</h2>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-6 border-l-4 border-blue-500">
                                <p class="text-gray-700 leading-relaxed">{{ $material->description }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Material Content --}}
                    <div class="mb-8">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">Material Content</h2>
                        </div>

                        @if ($material->file_path)
                            <div class="border-2 border-dashed border-gray-200 rounded-xl overflow-hidden bg-gray-50">
                                @php
                                    $fileExtension = pathinfo($material->file_path, PATHINFO_EXTENSION);
                                    $materialType = $material->material_type ?? '';
                                @endphp

                                @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'svg']))
                                    <div class="p-6 text-center">
                                        <img src="{{ asset('storage/' . $material->file_path) }}" 
                                             alt="{{ $material->title }}" 
                                             class="max-w-full h-auto rounded-lg shadow-lg mx-auto border">
                                    </div>
                                @elseif ($fileExtension == 'pdf')
                                    <div class="p-6">
                                        <iframe src="{{ asset('storage/' . $material->file_path) }}#toolbar=0" 
                                                class="w-full rounded-lg border shadow-inner" 
                                                style="height: 80vh;" 
                                                frameborder="0"></iframe>
                                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                            <p class="text-sm text-blue-700 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Cannot view PDF directly? 
                                                <a href="{{ route('materials.download', $material->id) }}" class="ml-1 font-semibold text-blue-600 hover:text-blue-800 underline">Download it here</a>.
                                            </p>
                                        </div>
                                    </div>
                                @elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg']) || Str::startsWith($material->file_mime_type, 'video/'))
                                    <div class="p-6">
                                        <video controls class="w-full max-h-[70vh] rounded-lg shadow-lg bg-black">
                                            <source src="{{ asset('storage/' . $material->file_path) }}" type="{{ $material->file_mime_type }}">
                                            Your browser does not support the video tag.
                                        </video>
                                        <div class="mt-4 p-4 bg-green-50 rounded-lg border border-green-200">
                                            <p class="text-sm text-green-700 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Having trouble playing the video? 
                                                <a href="{{ route('materials.download', $material->id) }}" class="ml-1 font-semibold text-green-600 hover:text-green-800 underline">Download it here</a>.
                                            </p>
                                        </div>
                                    </div>
                                @elseif (in_array($fileExtension, ['mp3', 'wav', 'ogg']) || Str::startsWith($material->file_mime_type, 'audio/'))
                                    <div class="p-6 text-center">
                                        <div class="mb-4">
                                            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <audio controls class="w-full max-w-lg mx-auto rounded-lg shadow-lg">
                                            <source src="{{ asset('storage/' . $material->file_path) }}" type="{{ $material->file_mime_type }}">
                                            Your browser does not support the audio element.
                                        </audio>
                                        <div class="mt-4 p-4 bg-purple-50 rounded-lg border border-purple-200">
                                            <p class="text-sm text-purple-700 flex items-center justify-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Having trouble playing the audio? 
                                                <a href="{{ route('materials.download', $material->id) }}" class="ml-1 font-semibold text-purple-600 hover:text-purple-800 underline">Download it here</a>.
                                            </p>
                                        </div>
                                    </div>
                                @elseif (in_array($fileExtension, ['txt', 'java', 'js', 'py', 'php', 'html', 'css', 'json', 'xml']))
                                    <div class="p-6">
                                        <div class="bg-gray-900 rounded-lg p-6 overflow-auto" style="max-height: 500px;">
                                            <pre class="text-sm text-green-400 whitespace-pre-wrap font-mono leading-relaxed">{{ file_get_contents(storage_path('app/public/' . $material->file_path)) }}</pre>
                                        </div>
                                        <div class="mt-4 p-4 bg-gray-100 rounded-lg border border-gray-300">
                                            <p class="text-sm text-gray-600 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                                </svg>
                                                Viewing as text. 
                                                <a href="{{ route('materials.download', $material->id) }}" class="ml-1 font-semibold text-gray-700 hover:text-gray-900 underline">Download original file</a>.
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
                                        <p class="text-gray-600 mb-6">This file cannot be displayed directly in the browser.</p>
                                        <a href="{{ route('materials.download', $material->id) }}"
                                           class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-lg">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Download File
                                        </a>
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
                                <p class="text-gray-500">No file attached to this material.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1 space-y-6">
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
                            @if ($material->available_at || $material->unavailable_at)
                                @if ($material->available_at)
                                    <div class="flex items-start">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Available From</p>
                                            <p class="text-sm text-gray-600">{{ $material->available_at->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}</p>
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

                                @if ($material->unavailable_at)
                                    <div class="flex items-start">
                                        <div class="w-2 h-2 bg-red-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Available Until</p>
                                            <p class="text-sm text-gray-600">{{ $material->unavailable_at->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}</p>
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
                                    @if ($material->isAvailable())
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
                    @if ($material->file_path)
                        @php
                            $fileSize = Storage::disk('public')->exists($material->file_path) 
                                ? Storage::disk('public')->size($material->file_path) 
                                : 0;
                            $fileSizeMB = round($fileSize / 1024 / 1024, 2);
                            $fileExtension = pathinfo($material->file_path, PATHINFO_EXTENSION);
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
                                    <p class="text-sm font-medium text-gray-900 mb-1">Original Filename</p>
                                    <p class="text-sm text-gray-600 break-all">{{ $material->original_filename ?? 'Unknown' }}</p>
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
                                <div>
                                    <p class="text-sm font-medium text-gray-900 mb-1">Material Type</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($material->material_type) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 rounded-b-xl">
                <div class="flex justify-between items-center">
                    <a href="{{ route('courses.show', $material->course_id) }}" 
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