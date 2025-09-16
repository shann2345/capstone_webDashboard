<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OLIN - Admin Page</title>
    
    {{-- Assets and styles from layout.blade.php --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: "Inter", sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        /* Changed to darker green */
        .sidebar {
            background-color: #1a2e1a;
        }
        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #004318;
        }

        /* Changed to darker green gradient */
        .profile-icon {
            background: linear-gradient(135deg, #1f2937 80%, #096F4D 20%);
        }

        /* Much darker green gradient */
        #sidebar, .modern-sidebar {
            background: linear-gradient(180deg, #111827 0%, #075a3f 100%);
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
        }

        .sidebar-nav {
            padding: 20px 0;
            flex: 1;
        }

        .nav-section {
            margin-bottom: 32px;
        }

        .nav-section-title {
            color: #9ca3af; /* Softer gray text */
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
            padding: 0 20px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #d1d5db; /* Softer white */
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            margin: 0 12px;
            border-radius: 10px;
        }
        
        .nav-item:hover {
            background: rgba(34, 197, 94, 0.1); /* Darker green hover */
            color: #f3f4f6;
            transform: translateX(4px);
        }
        
        /* Darker green gradient */
        .nav-item-active {
            background: linear-gradient(135deg, #004017 0%, #298c6b 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.2); /* Softer green glow */
        }

        .nav-item-active:hover {
            transform: none;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 16px;
        }

        .nav-text {
            font-weight: 500;
            font-size: 14px;
            flex: 1;
        }

        .nav-indicator {
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: currentColor;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .nav-item-active .nav-indicator {
            opacity: 1;
        }

        .modern-sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .modern-sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .modern-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
        }

        .modern-sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        @media (max-width: 768px) {
            .modern-sidebar {
                box-shadow: 8px 0 32px rgba(0, 0, 0, 0.3);
            }
        }
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    {{-- Header updated to match instructor layout style --}}
    <header class="bg-[#096F4D] shadow-sm h-16 fixed top-0 w-full z-20 flex items-center justify-between px-4 sm:px-6 md:px-8">
        <div class="flex items-center">
            <button id="menu-toggle" class="md:hidden p-2 text-white hover:text-gray-200 focus:outline-none transition-colors duration-200 mr-4 relative z-40">
                <i class="fa-solid fa-bars fa-lg"></i>
            </button>
            <div class="text-2xl font-extrabold text-white">OLIN</div>
        </div>

        <div class="flex items-center space-x-4 relative z-40">
            <button class="relative p-2 text-white hover:text-gray-200 transition-colors duration-200 focus:outline-none">
                <i class="fa-solid fa-bell fa-lg"></i>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            <div class="relative">
                @if(Auth::user()->profile_image)
                    <a href="#">
                        <img class="w-10 h-10 rounded-full object-cover cursor-pointer transition-transform duration-300 hover:scale-105"
                             src="{{ asset('storage/' . Auth::user()->profile_image) }}" 
                             alt="{{ Auth::user()->name }}'s profile image">
                    </a>
                @else
                    <a href="#" 
                       class="profile-icon bg-white text-[#FFFFFF] font-semibold rounded-full w-10 h-10 flex items-center justify-center cursor-pointer transition-transform duration-300 hover:scale-105">
                       {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </a>
                @endif
                {{-- Updated border to green for consistency --}}
                <span class="absolute bottom-0 right-0 block w-3 h-3 bg-[#10B981] rounded-full border-2 border-[#10B981]"></span>
            </div>
        </div>
    </header>

    <div class="flex pt-16">
        <aside id="sidebar"
        class="modern-sidebar fixed top-16 left-0 h-[calc(100%-4rem)] w-64 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto z-10">
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    
                    <a href="{{ route('admin.dashboard') }}" 
                       class="nav-item {{ Request::routeIs('admin.dashboard') ? 'nav-item-active' : '' }}">
                        <div class="nav-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <span class="nav-text">Dashboard</span>
                        <div class="nav-indicator"></div>
                    </a>
                    
                     <a href="{{-- route('admin.user_management') --}}" 
                       class="nav-item {{-- Request::routeIs('admin.user_management') ? 'nav-item-active' : '' --}}">
                        <div class="nav-icon">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <span class="nav-text">User Management</span>
                        <div class="nav-indicator"></div>
                    </a>
        
                    <a href="{{-- route('admin.course_management') --}}" 
                       class="nav-item {{-- Request::routeIs('admin.course_management') ? 'nav-item-active' : '' --}}">
                        <div class="nav-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <span class="nav-text">Course Management</span>
                        <div class="nav-indicator"></div>
                    </a>
                </div>
        
                <div class="nav-section">
                    <div class="nav-section-title">Admin Tools</div>
                    
                    <a href="{{-- route('admin.settings') --}}" 
                       class="nav-item {{-- Request::routeIs('admin.settings') ? 'nav-item-active' : '' --}}">
                        <div class="nav-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <span class="nav-text">Settings</span>
                        <div class="nav-indicator"></div>
                    </a>
                    
                    <a href="{{-- route('admin.reports_logs') --}}" 
                       class="nav-item {{-- Request::routeIs('admin.reports_logs') ? 'nav-item-active' : '' --}}">
                        <div class="nav-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <span class="nav-text">Reports & Logs</span>
                        <div class="nav-indicator"></div>
                    </a>
                </div>
        
                <div class="nav-section">
                    <div class="nav-section-title">Support</div>
                    
                    <a href="{{-- route('admin.help') --}}" 
                       class="nav-item {{-- Request::routeIs('admin.help') ? 'nav-item-active' : '' --}}">
                        <div class="nav-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <span class="nav-text">Help Center</span>
                        <div class="nav-indicator"></div>
                    </a>
                </div>
            </nav>
        </aside>

        <main class="flex-1 p-6 overflow-auto ml-64">
            {{ $slot }}
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById('menu-toggle').addEventListener('click', function () {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.toggle('-translate-x-full');
            });
        });
    </script>
</body>
</html>