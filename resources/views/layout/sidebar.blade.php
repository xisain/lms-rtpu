<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-100" 
      x-data="{ sidebarOpen: true, dropdownOpen: {{ request()->routeIs('admin.category.*') || request()->routeIs('admin.course.*') ? 'true' : 'false' }}, userOpen: false }">
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

    <style>
        .sidebar-transition {
            transition: all 0.25s ease-in-out;
        }
    </style>
</head>

<body class="h-full flex bg-gray-100">

    <!-- SIDEBAR -->
    <aside 
        class="fixed inset-y-0 left-0 bg-white text-white z-40 sidebar-transition flex flex-col justify-between rounded-r-2xl border-r border-gray-100 shadow-xl"
        :class="sidebarOpen ? 'w-64' : 'w-20'">

        <!-- Logo -->
        <div>
         <div class="flex flex-col items-center py-4">
            <img src="/images/logo.png" alt="Logo" class="w-12 h-12" />
        </div>

            <!-- NAVIGATION -->
            <nav class="px-4 py-2 space-y-2">
                <a href="{{ route('admin.home') }}" 
                   class="flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.home') ? 'bg-[#009999] font-semibold' : 'text-gray-900 hover:bg-gray-100' }}"
                   :class="{ 'justify-center': !sidebarOpen, 'justify-start': sidebarOpen }">
                    <i class="fa-solid fa-house" :class="{ 'text-lg': !sidebarOpen }"></i>
                    <span class="ml-3 transition-opacity duration-200"
                          :class="{ 'opacity-0 hidden': !sidebarOpen, 'opacity-100': sidebarOpen }">
                        Dashboard
                    </span>
                </a>

                <a href="{{ route('admin.course.index') }}" 
                   class="flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.course.index') ? 'bg-[#009999] font-semibold' : 'text-gray-900 hover:bg-gray-100' }}"
                   :class="{ 'justify-center': !sidebarOpen, 'justify-start': sidebarOpen }">
                    <i class="fa-solid fa-plus" :class="{ 'text-lg': !sidebarOpen }"></i>
                    <span class="ml-3 transition-opacity duration-200"
                          :class="{ 'opacity-0 hidden': !sidebarOpen, 'opacity-100': sidebarOpen }">
                        Buat Course
                    </span>
                </a>

                @if(Route::has('admin.user.index'))
                <a href="{{ route('admin.user.index') }}" 
                   class="flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.user.*') ? 'bg-[#009999] font-semibold' : 'text-gray-900 hover:bg-gray-100' }}"
                   :class="{ 'justify-center': !sidebarOpen, 'justify-start': sidebarOpen }">
                    <i class="fa-solid fa-user" :class="{ 'text-lg': !sidebarOpen }"></i>
                    <span class="ml-3 transition-opacity duration-200"
                          :class="{ 'opacity-0 hidden': !sidebarOpen, 'opacity-100': sidebarOpen }">
                        Pengguna
                    </span>
                </a>
                @endif
            </nav>
        </div>

        <!-- USER MENU DROPDOWN (bagian bawah sidebar) -->
        <div class="relative p-4 border-t border-gray-400 rounded-2xl shadow-lg">
            <button @click="userOpen = !userOpen"
                    class="flex items-center justify-between w-full px-4 py-2 text-gray-900 rounded-md"
                    :class="{ 'justify-center px-2': !sidebarOpen }">
                <div class="flex items-center" :class="{ 'justify-center': !sidebarOpen }">
                    <i class="fa-solid fa-user-circle" :class="{ 'text-2xl': !sidebarOpen, 'text-2xl mr-2': sidebarOpen }"></i>
                    <span class="text-dark transition-opacity duration-200"
                          :class="{ 'opacity-0 hidden': !sidebarOpen, 'opacity-100': sidebarOpen }">
                        {{ auth()->user()->name ?? 'Pengguna' }}
                    </span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-all duration-200"
                     :class="{ 'rotate-180': userOpen, 'opacity-0 hidden': !sidebarOpen, 'opacity-100': sidebarOpen }" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" 
                          d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="userOpen" x-transition @click.away="userOpen = false"
                 class="absolute bottom-20 left-4 w-56 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50 mb-2">
                @if(auth()->user()->role->name == "admin")
                <a href="{{ route('admin.home') }}"
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                   Admin Dashboard
                </a>
                @endif
                <a href="{{ route('home') }}"
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                   Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col min-h-screen sidebar-transition" 
         :class="sidebarOpen ? 'lg:ml-64' : 'ml-20'">

        <!-- NAVBAR -->
        <header class="flex items-center justify-between px-6 py-4 relative z-30">
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-700 text-xl focus:outline-none">
                <i class="fa-solid fa-bars" style="color: #000000;"></i>
            </button>
        </header>

        <!-- CONTENT -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
    @include('sweetalert2::index')
    @stack('scripts')
</body>
</html>