<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'LMS RTPU') }} - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
<nav class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <img src="/images/logo.png" alt="Logo" style="width: 50px; height: 50px;">
            </div>

            <!-- Main Navigation -->
            <div class="flex items-center space-x-8">
                <a href="{{ route('home') }}" 
                   class="text-base font-medium text-gray-700 hover:text-blue-800">
                   Home 
                </a>
                <a href="{{ route('course.index') }}" 
                   class="text-base font-medium text-gray-700 hover:text-blue-800">
                   Dashboard
                </a>
                <!-- Dropdown: Kelas -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center text-base font-medium text-gray-700 hover:text-blue-800 focus:outline-none" >
                        Kelas Ku
                        <svg class="w-4 h-4 ml-1 mt-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                  clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open"
                        @click.away="open = false"
                         x-transition
                         class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                        <a href="{{ route('course.index') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                           Kelas Pemula
                        </a>
                        <a href="{{ route('course.index') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                           Kelas Menengah
                        </a>
                        <a href="{{ route('course.index') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                           Kelas Lanjutan
                        </a>
                    </div>
                </div>
                <a href="{{ route('course.index') }}" 
                   class="text-base font-medium text-gray-700 hover:text-blue-800">
                   Event
                </a>
            </div>

            <!-- User Section -->
            <div class="flex items-center space-x-4">
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="mr-2 font-medium">{{ auth()->user()->name }}</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div x-show="open"
                             @click.away="open = false"
                             x-transition
                             class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" 
                       class="text-gray-700 hover:text-gray-900 font-medium">
                       Login
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
@yield("content")

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} {{ config('app.name', 'LMS RTPU') }}. All rights reserved.
            </p>
        </div>
    </footer>
    
</body>
</html>