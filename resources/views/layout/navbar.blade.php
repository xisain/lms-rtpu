<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'LMS RTPU') }} - @yield('title')</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        @keyframes border-animate {
            0% {
                border-image: linear-gradient(0deg, #009999, transparent) 0;
                opacity: 1;
            }
            25% {
                border-image: linear-gradient(90deg, #009999, transparent) 1;
                opacity: 1;
            }
            50% {
                border-image: linear-gradient(180deg, #009999, transparent) 1;
                opacity: 1;
            }
            75% {
                border-image: linear-gradient(270deg, #009999, transparent) 1;
                opacity: 1;
            }
            90% {
                border-image: linear-gradient(360deg, #009999, transparent) 1;
                opacity: 1;
            }
            100% {
                border-image: linear-gradient(360deg, #009999, transparent) 0;
                opacity: 0; /* 🌟 Efek fade out */
            }
        }

        button:focus .animate-border {
        border-image-slice: 1;
        border-image-source: linear-gradient(90deg, #009999, transparent);
        animation: border-animate 2.5s ease-in-out forwards;
        }
    </style>

</head>

<body class="font-inter">
    <!-- Navbar -->
    <nav x-data="{ open: false }" class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <img src="/images/logo.png" alt="Logo" class="w-12 h-12">
                </div>

                <!-- Tombol Mobile Menu -->
                <div class="flex lg:hidden">
                    <button @click="open = !open" class="text-gray-700 hover:text-[#0f5757] focus:outline-none">
                        <svg x-show="!open" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="open" x-transition class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Menu Desktop -->
                @guest
                <!-- Guest: menu di tengah -->
                <div class="hidden lg:flex items-center space-x-4 absolute left-1/2 transform -translate-x-1/2">
                    <a href="{{ route('home') }}" class="text-base font-medium text-gray-700 hover:text-[#0f5757]">Home</a>
                    <a href="{{ route('list.kelas') }}" class="text-base font-medium text-gray-700 hover:text-[#0f5757]">Course</a>
                    <a href="{{ route('plan') }}" class="text-base font-medium text-gray-700 hover:text-[#0f5757]">Plan</a>
                </div>
                @endguest

                @auth
                <!-- Auth: menu kiri -->
                <div class="hidden lg:flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="text-base font-medium text-gray-700 hover:text-[#0f5757]">Home</a>
                    <a href="{{ route('course.index') }}" class="text-base font-medium text-gray-700 hover:text-[#0f5757]">Dashboard</a>


                    <!-- Dropdown Kelas -->
                    <div class="relative" x-data="{ openDropdown: false }">
                        <button
                            @click="openDropdown = !openDropdown"
                            class="relative flex items-center text-base font-medium text-gray-700 hover:text-[#0f5757]
                                focus:outline-none focus:ring-0 rounded-md px-1 py-1 transition-all duration-300">
                            My Course
                            <svg
                                class="w-4 h-4 ml-1 mt-1 transform transition-transform duration-300"
                                :class="openDropdown ? 'rotate-180 text-[#0f5757]' : 'rotate-0'"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="absolute inset-0 rounded-md border-2 border-transparent animate-border"></span>
                        </button>
                        <div x-show="openDropdown" x-transition:enter="transition ease-out duration-200 transform"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150 transform"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2" @click.away="openDropdown = false"
                            class="absolute left-0 mt-4 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                            @php
                            $enrollments = auth()->user()->enrollment()->with('course')->get();
                            @endphp

                            @if($enrollments->count() > 0)
                            @foreach($enrollments as $enrollment)
                            <a href="{{ route('course.show', $enrollment->course->slugs) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                {{ $enrollment->course->nama_course }}
                            </a>
                            @endforeach
                            <a href="{{ route('course.my') }}" class="block px-4 py-2 text-center text-sm font-bold text-gray-700 bg-teal-100">Semua Course Saya</a>
                            @else
                            <p class="px-4 py-2 text-sm text-gray-700">Belum ada course yang di-enroll.</p>
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('plan') }}" class="text-base font-medium text-gray-700 hover:text-[#0f5757]">Plan</a>
                </div>
                @endauth

        <!-- User/Login section kanan -->
        <div class="hidden lg:flex items-center space-x-4">
            @auth
            <div class="relative" x-data="{ userOpen: false }">
                <button
                    @click="userOpen = !userOpen"
                    class="flex items-center text-gray-700 hover:text-[#0f5757] focus:outline-none transition-all duration-300 px-2 py-1">
                    <span class="mr-2 font-medium">{{ auth()->user()->name }}</span>
                    <svg
                        class="h-5 w-5 transform transition-transform duration-300"
                        :class="userOpen ? 'rotate-180 text-[#0f5757]' : 'rotate-0'"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="absolute inset-0 rounded-md border-2 border-transparent animate-border"></span>
                </button>


                <div x-show="userOpen" x-transition @click.away="userOpen = false"
                    class="absolute right-0 mt-4 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                    @if(auth()->user()->role->name == "admin")
                    <a href="{{ route('admin.home') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Admin Dashboard</a>
                    @elseif(auth()->user()->role->name == "dosen")
                    <a href="{{ route('dosen.home') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dosen Dashboard</a>
                    @endif
                    <a href="{{ route('home') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
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
                class="text-white hover:bg-[#0f5757] font-medium bg-[#009999] max-w-lg rounded-[10px] border border-gray-300 p-2 shadow-lg">Login</a>
            @endauth
        </div>

        <!-- Menu Mobile (Dropdown) -->
        <div x-show="open" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 -translate-y-3" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-3"
            class="lg:hidden px-4 pb-4 space-y-2 bg-white border-t border-gray-200">
            <a href="{{ route('home') }}" class="block text-gray-700 hover:text-[#0f5757]">Home</a>
            <a href="{{ route('course.index') }}" class="block text-gray-700 hover:text-[#0f5757]">Dashboard</a>

            <div class="border-t border-gray-100 pt-2">
                <p class="text-sm font-semibold text-gray-500 mb-1">Kelas Ku</p>
                <a href="{{ route('course.index') }}" class="block text-gray-700 hover:text-[#0f5757]">Kelas Pemula</a>
                <a href="{{ route('course.index') }}" class="block text-gray-700 hover:text-[#0f5757]">Kelas
                    Menengah</a>
                <a href="{{ route('course.index') }}" class="block text-gray-700 hover:text-[#0f5757]">Kelas
                    Lanjutan</a>
            </div>

            <a href="{{ route('course.index') }}" class="block text-gray-700 hover:text-[#0f5757]">Event</a>
            @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left text-gray-700 hover:text-[#0f5757]">Logout</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="block text-gray-700 hover:text-[#0f5757]">Login</a>
            @endauth
        </div>
    </nav>

    <div class="min-h-screen">
        @yield("content")
    </div>
    @stack('scripts')
    @include('sweetalert2::index')
    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-8">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} {{ config('app.name', 'LMS RTPU') }}. All rights reserved.
            </p>
        </div>
    </footer>
    @stack('scripts')
</body>

</html>
