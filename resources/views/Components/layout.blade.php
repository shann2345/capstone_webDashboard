<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OLIN System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: "Inter", sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .sidebar {
            background-color: #334155; /* Slate-700 */
        }
        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #3B82F6; /* Blue-500 */
        }
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <div class="bg-gray-800"></div>

    <header class="flex justify-between items-center px-6 py-4 bg-blue-600 text-white shadow-md">
        <div class="text-2xl font-extrabold">
            OLIN
        </div>

        <div class="flex items-center space-x-4">
            <button class="text-white hover:text-gray-200 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>
            <div class="user-info" style="display: flex; align-items: center; gap: 10px;">
                <img src="https://via.placeholder.com/40" alt="User Avatar" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid #ccc;">
        
                <span class="instructor-name" style="font-weight: bold; color: #555;">
                </span>
            </div>
        </div>
    </header>

    <div class="flex flex-1">
        <nav class="w-55 sidebar text-white p-3 shadow-lg min-h-full flex flex-col">
            <ul class="space-y-4">
                <li class="uppercase text-xs text-gray-400 font-semibold mb-2">Main</li>
                <li>
                    <a href="{{ route('instructor.dashboard') }}" class="sidebar-link flex items-center space-x-3 py-2 px-3 rounded-md text-gray-200 hover:bg-gray-700 transition-colors duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        <span class="text-sm">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('instructor.myCourse') }}" class="sidebar-link flex items-center space-x-3 py-2 px-3 rounded-md text-gray-200 hover:bg-gray-700 transition-colors duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-1a1 1 0 00-1-1H9a1 1 0 00-1 1v1a1 1 0 11-2 0V4zm3 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">Course Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('instructor.studenManagement') }}" class="sidebar-link flex items-center space-x-3 py-2 px-3 rounded-md text-gray-200 hover:bg-gray-700 transition-colors duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-1a1 1 0 00-1-1H9a1 1 0 00-1 1v1a1 1 0 11-2 0V4zm3 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">Student Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('instructor.studentProgress') }}" class="sidebar-link flex items-center space-x-3 py-2 px-3 rounded-md text-gray-200 hover:bg-gray-700 transition-colors duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z" />
                        </svg>
                        <span class="text-sm">Student Progress</span>
                    </a>
                </li>
            </ul>

            <ul class="space-y-4 mt-6">
                <li class="uppercase text-xs text-gray-400 font-semibold mb-2">Analytics</li>
                <li>
                    <a href="#" class="sidebar-link flex items-center space-x-3 py-2 px-3 rounded-md text-gray-200 hover:bg-gray-700 transition-colors duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                        </svg>
                        <span class="text-sm">Reports</span>
                    </a>
                </li>
            </ul>

            <ul class="space-y-4 mt-6">
                <li class="uppercase text-xs text-gray-400 font-semibold mb-2">Support</li>
                <li>
                    <a href="#" class="sidebar-link flex items-center space-x-3 py-2 px-3 rounded-md text-gray-200 hover:bg-gray-700 transition-colors duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2-2.83V7z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">Help Center</span>
                    </a>
                </li>
            </ul>
        </nav>

        <main class="flex-1 p-6 overflow-auto">
            {{-- <div class="flex justify-start space-x-4 mb-6">
                <a href="{{ Route('course.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Create New Course</span>
                </a>
                <button class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2h8a2 2 0 012 2v10.586a1 1 0 01-1.707.707L15 15.586V6a4 4 0 00-4-4H9a4 4 0 00-4 4v9.586l-.293.293A1 1 0 014 15.586V5zM14 6H6v8h8V6z" clip-rule="evenodd" />
                    </svg>
                    <span>View All My Courses</span>
                </button>
                <button class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                    </svg>
                    <span>View Reports</span>
                </button>
            </div>
             --}}
            <?php echo $slot ?>
        </main>
    </div>
    {{ $scripts ?? '' }}
</body>
</html>