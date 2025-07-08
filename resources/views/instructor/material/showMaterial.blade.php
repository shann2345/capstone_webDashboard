{{-- resources/views/materials/show.blade.php --}}

<x-layout>
    <x-slot name="title">
        {{ $material->title }} - Material Details
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $material->title }}</h1>
        <p class="text-gray-600 text-sm mb-4">
            Uploaded: {{ $material->created_at->format('M d, Y H:i A') }}
            @if($material->user)
                by {{ $material->user->name }}
            @endif
        </p>

        @if ($material->description)
            <div class="mb-4 text-gray-700">
                <h2 class="text-xl font-semibold mb-2">Description</h2>
                <p>{{ $material->description }}</p>
            </div>
        @endif

        <div class="mb-6 text-gray-700">
            <h2 class="text-xl font-semibold mb-2">Availability</h2>
            @if ($material->available_at || $material->unavailable_at)
                @if ($material->available_at)
                    <p><strong>Available From:</strong> {{ $material->available_at->format('M d, Y H:i A') }}</p>
                @else
                    <p><strong>Available From:</strong> Always (no start date specified)</p>
                @endif
                @if ($material->unavailable_at)
                    <p><strong>Available Until:</strong> {{ $material->unavailable_at->format('M d, Y H:i A') }}</p>
                @else
                    <p><strong>Available Until:</strong> Never (no end date specified)</p>
                @endif
                @if ($material->isAvailable())
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 mt-2">Currently Available</span>
                @else
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 mt-2">Currently Scheduled / Unavailable</span>
                @endif
            @else
                <p><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Always Available</span></p>
            @endif
        </div>

        <h2 class="text-xl font-semibold text-gray-800 mb-3">Material Content</h2>

        @if ($material->file_path)
            <div class="border rounded-lg overflow-hidden mb-6 p-4 bg-gray-50 flex flex-col items-center">
                @php
                    $fileExtension = pathinfo($material->file_path, PATHINFO_EXTENSION);
                    $materialType = $material->material_type ?? ''; // Assuming material_type is a property or derived from mime type
                @endphp

                @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'svg']))
                    <img src="{{ asset('storage/' . $material->file_path) }}" alt="{{ $material->title }}" class="max-w-full h-auto rounded-md shadow-sm">
                @elseif ($fileExtension == 'pdf')
                    <iframe src="{{ asset('storage/' . $material->file_path) }}#toolbar=0" class="w-full" style="height: 80vh;" frameborder="0"></iframe>
                    <p class="text-sm text-gray-600 mt-2">Cannot view PDF directly? <a href="{{ route('materials.download', $material->id) }}" class="text-blue-600 hover:underline">Download it here</a>.</p>
                @elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg']) || Str::startsWith($material->file_mime_type, 'video/'))
                    <video controls class="w-full max-h-[70vh] rounded-md shadow-sm">
                        <source src="{{ asset('storage/' . $material->file_path) }}" type="{{ $material->file_mime_type }}">
                        Your browser does not support the video tag.
                    </video>
                    <p class="text-sm text-gray-600 mt-2">Having trouble playing the video? <a href="{{ route('materials.download', $material->id) }}" class="text-blue-600 hover:underline">Download it here</a>.</p>
                @elseif (in_array($fileExtension, ['mp3', 'wav', 'ogg']) || Str::startsWith($material->file_mime_type, 'audio/'))
                    <audio controls class="w-full max-w-lg mt-4">
                        <source src="{{ asset('storage/' . $material->file_path) }}" type="{{ $material->file_mime_type }}">
                        Your browser does not support the audio element.
                    </audio>
                    <p class="text-sm text-gray-600 mt-2">Having trouble playing the audio? <a href="{{ route('materials.download', $material->id) }}" class="text-blue-600 hover:underline">Download it here</a>.</p>
                @elseif (in_array($fileExtension, ['txt', 'java', 'js', 'py', 'php', 'html', 'css', 'json', 'xml']))
                    {{-- For text-based files, attempt to display or provide download --}}
                    <div class="w-full max-h-96 overflow-auto bg-white p-4 rounded-md shadow-inner">
                        <pre class="text-sm text-gray-800 whitespace-pre-wrap font-mono">{{ file_get_contents(storage_path('app/public/' . $material->file_path)) }}</pre>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">Viewing as text. <a href="{{ route('materials.download', $material->id) }}" class="text-blue-600 hover:underline">Download original file</a>.</p>
                @else
                    <p class="text-gray-700 text-center mb-3">This material is a file of type: <span class="font-semibold">{{ strtoupper($fileExtension) }}</span>.</p>
                    <p class="text-center mb-4">It cannot be displayed directly in the browser.</p>
                    <a href="{{ route('materials.download', $material->id) }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Download File
                        <svg class="ml-2 -mr-0.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </a>
                @endif
            </div>
        @else
            <div class="text-gray-700 text-center py-8">
                <p>No file attached to this material.</p>
            </div>
        @endif

        <div class="flex justify-end mt-6">
            <a href="{{ route('courses.show', $material->course_id) }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800 mr-4">
                Back to Materials List
            </a>
            <a href="{{ route('courses.show', $material->course_id) }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Back to Course Details
            </a>
        </div>
    </div>
</x-layout>