<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OLIN System</title>
    <!-- Tailwind CSS CDN for easy styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom font for a clean look */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Header Section -->
    <header class="flex justify-between items-center px-4 py-3 bg-white border-b border-gray-200 shadow-sm">
        <!-- System Name on the Left -->
        <div class="text-2xl font-bold text-gray-800">
            OLIN
        </div>

        <div class="user-actions" style="display: flex; align-items: center; gap: 20px;">
            <a href="{{ Route('instructor.createCourse') }}" style="background-color: #007bff; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-weight: bold; white-space: nowrap;">+ Create Class</a>

            <div class="user-info" style="display: flex; align-items: center; gap: 10px;">
                <img src="https://via.placeholder.com/40" alt="User Avatar" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid #ccc;">
        
                <span class="instructor-name" style="font-weight: bold; color: #555;">
                    @auth
                        @if(Auth::user()->role === 'instructor')
                            {{ Auth::user()->name }}
                        @else
                            Guest/Admin
                        @endif
                    @else
                        Guest
                    @endauth
                </span>
            </div>
        </div>
    </header>

    <!-- Main Layout Container: Sidebar and Content -->
    <div class="flex flex-1">
        <!-- Navigation Sidebar -->
        <nav class="w-64 bg-white border-r border-gray-200 p-4 shadow-sm md:flex flex-col hidden">
            <ul class="space-y-2">
                <li><a href="#" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100 font-medium transition-colors duration-150">Active Classes</a></li>
                <li><a href="#" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100 font-medium transition-colors duration-150">Quiz Creator</a></li>
                <li><a href="#" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100 font-medium transition-colors duration-150">Chronos Log</a></li>
                <li><a href="#" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100 font-medium transition-colors duration-150">Task Registry</a></li>
                <li><a href="#" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100 font-medium transition-colors duration-150">Settings</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100 font-medium transition-colors duration-150">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>     
            </li>
            </ul>
        </nav>

        <!-- Main Content Area -->
        <main class="flex-1 p-6 overflow-auto">
            <?php echo $slot ?>
        </main>
    </div>
</body>
</html>
