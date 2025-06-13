<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>
</head>
<body>
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
            </div>
                <p class="text-gray-600 mt-6">
                    <br>
                    <hr class="my-4 border-gray-200">
                    <br>
                    This section demonstrates the content that would typically come from your `$slot` variable in a Blade layout.
                    <br>
                    <hr class="my-4 border-gray-200">
                    <br>
                    <?php
                        // This simulates the $slot content you had before.
                        // In a real Laravel environment, $slot would automatically render the child view.
                        // For this standalone HTML, I'm just adding a placeholder.
                        echo '<p class="text-gray-700">Your dynamic content will appear here.</p>';
                    ?>
                </p>
            </div>
    </x-layout>
</body>
</html>