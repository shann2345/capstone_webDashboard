
    <x-layout>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Welcome to Your Dashboard!</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-blue-50 p-4 rounded-lg shadow-sm">
                    <h2 class="text-xl font-semibold text-blue-800 mb-2">Upcoming Class</h2>
                    <p class="text-blue-700">Mathematics 101 - Today at 2:00 PM</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg shadow-sm">
                    <h2 class="text-xl font-semibold text-green-800 mb-2">Recent Activity</h2>
                    <p class="text-green-700">Quiz 1 grades posted for Science</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg shadow-sm">
                    <h2 class="text-xl font-semibold text-yellow-800 mb-2">Notifications</h2>
                    <p class="text-yellow-700">New message from your student, John Doe</p>
                </div>
                @if ($courses->isEmpty())
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative text-center" role="alert">
                        <span class="block sm:inline">You haven't created any courses yet. Start by clicking the "+ Create New Course" button!</span>
                    </div>
                @else
                    <div class="grid grid-cols-1"> {{-- Added sm:grid-cols-2 for better responsiveness and auto-rows-fr for equal height rows --}}
                    @foreach ($courses as $course)
                        {{-- Each course as a clickable card/box --}}
                        <a href="{{ route('courses.show', $course->id) }}" class="block h-full"> {{-- Ensure the anchor tag itself takes full height --}}
                            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden cursor-pointer h-full flex flex-col"> {{-- Added flex flex-col to enable proper flexbox behavior for content distribution --}}
                                <div class="p-6 flex-grow"> {{-- Use flex-grow to allow this section to expand and push the footer down --}}
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $course->title }}</h3>
                                    <p class="text-sm text-gray-500 mb-4">
                                        {{ $course->course_code }}
                                        @if($course->program)
                                            <span class="ml-2 px-2 py-1 text-gray-700 text-xs font-semibold"><br>{{ $course->program->name }}</span>
                                        @endif
                                    </p>
                                    <p class="text-gray-600 text-sm leading-relaxed line-clamp-3 mb-4"> {{-- line-clamp for description --}}
                                        {{ $course->description ?: 'No description provided for this course.' }}
                                    </p>
                                </div>
                                {{-- This div acts as a sticky footer for the card content --}}
                                <div class="p-6 pt-0 flex justify-between items-center text-sm"> {{-- pt-0 to reduce double padding if p-6 above already applies bottom padding --}}
                                    <span class="text-blue-600 font-medium">Credits: {{ $course->credits ?: 'N/A' }}</span>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($course->status === 'published') bg-green-200 text-green-800
                                        @elseif($course->status === 'draft') bg-yellow-200 text-yellow-800
                                        @else bg-red-200 text-red-800
                                        @endif">
                                        {{ ucfirst($course->status) }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                    </div>
                @endif
    </x-layout>