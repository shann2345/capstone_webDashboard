<x-layout>
    <div class="p-3 sm:p-4 lg:p-6">
        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Archived Courses</h1>
                    <p class="text-sm sm:text-base text-gray-600">View and manage your archived courses</p>
                </div>
                <a href="{{ route('instructor.dashboard') }}" class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 text-sm sm:text-base">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>

        {{-- Archived Courses Section --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            @if($archivedCourses->isEmpty())
                {{-- Empty State --}}
                <div class="p-8 sm:p-12 text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 sm:h-20 sm:w-20 rounded-full bg-gray-100 mb-4 sm:mb-6">
                        <svg class="h-8 w-8 sm:h-10 sm:w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-medium text-gray-900 mb-2">No Archived Courses</h3>
                    <p class="text-sm sm:text-base text-gray-500 mb-6">You don't have any archived courses yet. Courses that are no longer active will appear here.</p>
                    <a href="{{ route('instructor.myCourse') }}" class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 text-sm sm:text-base">
                        View Active Courses
                    </a>
                </div>
            @else
                {{-- Header with count --}}
                <div class="p-4 sm:p-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-900">
                            {{ $archivedCourses->count() }} Archived {{ $archivedCourses->count() === 1 ? 'Course' : 'Courses' }}
                        </h2>
                        <div class="flex items-center text-xs sm:text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Archived courses are read-only
                        </div>
                    </div>
                </div>

                {{-- Courses Grid --}}
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        @foreach($archivedCourses as $course)
                            <div class="group bg-gray-50 rounded-lg border-2 border-gray-200 hover:border-gray-300 transition-all duration-200">
                                {{-- Course Image or Placeholder --}}
                                <div class="relative h-32 sm:h-40 bg-gradient-to-r from-gray-400 to-gray-500 rounded-t-lg overflow-hidden">
                                    @if($course->image)
                                        <img src="{{ asset('storage/' . $course->image) }}" 
                                             alt="{{ $course->title }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="flex items-center justify-center h-full">
                                            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    {{-- Archived Badge --}}
                                    <div class="absolute top-2 right-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-700 text-gray-100">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                            </svg>
                                            Archived
                                        </span>
                                    </div>
                                </div>

                                {{-- Course Info --}}
                                <div class="p-4 sm:p-5">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors duration-200 line-clamp-2">
                                            {{ $course->title }}
                                        </h3>
                                    </div>
                                    
                                    <div class="flex items-center text-xs sm:text-sm text-gray-500 mb-3">
                                        <span class="font-medium">{{ $course->course_code }}</span>
                                        @if($course->program)
                                            <span class="mx-2">•</span>
                                            <span>{{ $course->program->name }}</span>
                                        @endif
                                    </div>

                                    @if($course->description)
                                        <p class="text-xs sm:text-sm text-gray-600 mb-4 line-clamp-2">
                                            {{ Str::limit($course->description, 100) }}
                                        </p>
                                    @endif

                                    {{-- Course Stats --}}
                                    <div class="flex items-center justify-between text-xs sm:text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                            </svg>
                                            {{ $course->students->count() }} {{ $course->students->count() === 1 ? 'student' : 'students' }}
                                        </div>
                                        <div class="text-right">
                                            <span class="text-gray-400">Archived</span>
                                            <br>
                                            <span class="text-xs">{{ $course->updated_at->format('M j, Y') }}</span>
                                        </div>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <div class="flex flex-col sm:flex-row gap-2">
                                            <a href="{{ route('instructor.courseDetails', $course->id) }}" 
                                               class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200 text-xs sm:text-sm">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layout>