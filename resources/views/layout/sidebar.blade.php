<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-100"
      x-data="{ sidebarOpen: true, dropdownOpen: {{ request()->routeIs('admin.category.*') || request()->routeIs('admin.course.*') ? 'true' : 'false' }} }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'LMS RTPU') }} - @yield('title')</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .sidebar-transition {
            transition: transform 0.25s ease-in-out;
        }
    </style>
</head>

<body class="h-full flex bg-gray-100">

    <!-- SIDEBAR -->
    <aside
        class="fixed inset-y-0 left-0 w-64 bg-white text-white z-40 sidebar-transition transform"
        :class="{ '-translate-x-full': !sidebarOpen }">

        <div class="flex items-center justify-between px-4 py-4">
            <div class="flex items-center justify-between ml-3">
                <img src="/images/logo.png" alt="Logo" class="w-8 h-8">
            </div>
            <button @click="sidebarOpen = false" class="text-gray-400 hover:text-gray-800">
                ✕
            </button>
        </div>

        <nav class="px-4 py-6 space-y-2 ">
            <a href="{{ route('admin.home') }}"
               class="block px-4 py-2 rounded {{ request()->routeIs('admin.home') ? 'bg-gray-700 font-semibold rounded-xl' : 'hover:bg-gray-700 rounded-xl' }}">
                <i class="fa-solid fa-house"></i> Dashboard
            </a>
            @if(Route::has('admin.category.index'))
            <a href="{{ route('admin.category.index') }}"
               class="block px-4 py-2 rounded {{ request()->routeIs('admin.category.*') ? 'bg-gray-700 font-semibold rounded-xl' : 'hover:bg-gray-700 rounded-xl' }}">
                <i class="fa-solid fa-layer-group"></i> Category
            </a>
            @endif

            <a href="{{ route('admin.course.index') }}"
               class="block px-4 py-2 rounded {{ request()->routeIs('admin.course.index') ? 'bg-gray-700 font-semibold rounded-xl' : 'hover:bg-gray-700 rounded-xl' }}">
                <i class="fa-solid fa-plus"></i> Buat Course
            </a>

            <!-- <div class="relative">
                <button
                    @click="dropdownOpen = !dropdownOpen"
                    class="flex items-center justify-between w-full px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.category.*') || request()->routeIs('admin.course.*') ? 'bg-gray-700 font-semibold' : '' }}">
                    <span>Kelas Ku</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform"
                         :class="{ 'rotate-180': dropdownOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="dropdownOpen" x-transition class="mt-2 space-y-1 bg-gray-700 rounded-md shadow-md">
                    <a href="{{ route('admin.category.index') }}"
                       class="block px-4 py-2 hover:bg-gray-600 {{ request()->routeIs('admin.category.*') ? 'bg-gray-600' : '' }}">
                        Kategori
                    </a>
                    @if(Route::has('admin.course.index'))
                    <a href="{{ route('admin.course.index') }}"
                       class="block px-4 py-2 hover:bg-gray-600 {{ request()->routeIs('admin.course.*') ? 'bg-gray-600' : '' }}">
                        Kursus
                    </a>
                    @endif
                </div>

            </div> -->

            @if(Route::has('admin.user.index'))
            <a href="{{ route('admin.user.index') }}"
               class="block px-4 py-2 rounded {{ request()->routeIs('admin.user.*') ? 'bg-gray-700 font-semibold rounded-xl' : 'hover:bg-gray-700 rounded-xl' }}">
                <i class="fa-solid fa-user"></i> Pengguna
            </a>
            @endif

        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col min-h-screen"
         :class="{ 'lg:ml-64': sidebarOpen, 'ml-0': !sidebarOpen }"
         style="transition: margin-left 0.25s ease-in-out;">

        <!-- NAVBAR -->
        <header class="flex items-center justify-between px-6 py-4 relative z-30">
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-700 text-xl focus:outline-none">
                ☰
            </button>
        </header>

        <!-- CONTENT -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    @include('sweetalert2::index')
    @stack('scripts')
</body>
</html>
