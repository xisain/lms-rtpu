<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-100"
      x-data="{
          sidebarOpen: localStorage.getItem('sidebar_open') !== null ? localStorage.getItem('sidebar_open') === 'true' : true,
          dropdownOpen: {{ request()->routeIs('admin.category.*') || request()->routeIs('admin.course.*') ? 'true' : 'false' }},
          userOpen: false,

          toggleSidebar() {
              this.sidebarOpen = !this.sidebarOpen;
              localStorage.setItem('sidebar_open', this.sidebarOpen);

              // Sync ke server
              fetch('{{ route('sidebar.toggle') }}', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': '{{ csrf_token() }}'
                  },
                  body: JSON.stringify({ open: this.sidebarOpen })
              });
          }
      }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'LMS RTPU') }} - @yield('title')</title>

    <!-- Tailwind -->
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        .sidebar-transition {
            transition: all 0.25s ease-in-out;
        }

        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="h-full flex bg-gray-100" x-cloak>

    <!-- SIDEBAR -->
    <aside
        class="fixed inset-y-0 left-0 bg-white text-white z-40 sidebar-transition flex flex-col justify-between rounded-r-2xl shadow-xl border border-r-gray-200"
        :class="sidebarOpen ? 'w-64' : 'w-20'">

        <!-- Logo -->
        <div>
         <div class="flex flex-col items-center py-4">
            <img src="/images/logo.png" alt="Logo" class="w-12 h-12" />
            <span class="mt-2 text-gray-800 font-semibold text-sm transition-opacity duration-200"
                  :class="{ 'opacity-0 hidden': !sidebarOpen, 'opacity-100': sidebarOpen }">
                LMS RTPU
            </span>
         </div>
        @if (auth()->user()->role->name == "admin")
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

                <a href="{{ route('admin.category.index') }}"
                   class="flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.category.index') ? 'bg-[#009999] font-semibold' : 'text-gray-900 hover:bg-gray-100' }}"
                   :class="{ 'justify-center': !sidebarOpen, 'justify-start': sidebarOpen }">
                    <i class="fa-solid fa-list" :class="{ 'text-lg': !sidebarOpen }"></i>
                    <span class="ml-3 transition-opacity duration-200"
                          :class="{ 'opacity-0 hidden': !sidebarOpen, 'opacity-100': sidebarOpen }">
                        Kategori
                    </span>
                </a>

                <a href="{{ route('admin.course.index') }}"
                   class="flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.course.index') ? 'bg-[#009999] font-semibold' : 'text-gray-900 hover:bg-gray-100' }}"
                   :class="{ 'justify-center': !sidebarOpen, 'justify-start': sidebarOpen }">
                    <i class="fa-solid fa-graduation-cap" :class="{ 'text-lg': !sidebarOpen }"></i>
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
                 @if(Route::has('admin.transaction.index'))
                <a href="{{ route('admin.transaction.index') }}"
                   class="flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.transaction.*') ? 'bg-[#009999] font-semibold' : 'text-gray-900 hover:bg-gray-100' }}"
                   :class="{ 'justify-center': !sidebarOpen, 'justify-start': sidebarOpen }">
                    <i class="fa-solid fa-money-check-dollar" :class="{ 'text-lg': !sidebarOpen }"></i>
                    <span class="ml-3 transition-opacity duration-200"
                          :class="{ 'opacity-0 hidden': !sidebarOpen, 'opacity-100': sidebarOpen }">
                        Transaksi
                    </span>
                </a>
                @endif
                @if(Route::has('admin.plan.index'))
                <a href="{{ route('admin.plan.index') }}"
                   class="flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.plan.*') ? 'bg-[#009999] font-semibold' : 'text-gray-900 hover:bg-gray-100' }}"
                   :class="{ 'justify-center': !sidebarOpen, 'justify-start': sidebarOpen }">
                    <i class="fa-solid fa-shopping-cart" :class="{ 'text-lg': !sidebarOpen }"></i>
                    <span class="ml-3 transition-opacity duration-200"
                          :class="{ 'opacity-0 hidden': !sidebarOpen, 'opacity-100': sidebarOpen }">
                        Plan
                    </span>
                </a>
                @endif
                 @if(Route::has('admin.payment.index'))
                <a href="{{ route('admin.payment.index') }}"
                   class="flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.payment.*') ? 'bg-[#009999] font-semibold' : 'text-gray-900 hover:bg-gray-100' }}"
                   :class="{ 'justify-center': !sidebarOpen, 'justify-start': sidebarOpen }">
                    <i class="fa-solid fa-credit-card" :class="{ 'text-lg': !sidebarOpen }"></i>
                    <span class="ml-3 transition-opacity duration-200"
                          :class="{ 'opacity-0 hidden': !sidebarOpen, 'opacity-100': sidebarOpen }">
                        Payment Method
                    </span>
                </a>
                @endif
            </nav>
        @else
            <!-- NAVIGATION Dosen -->
            <nav class="px-4 py-2 space-y-2">
                <a href="{{ route('dosen.course.index') }}"
                    class="flex items-center px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('dosen.course.index') ? 'bg-[#009999] font-semibold' : 'text-gray-900 hover:bg-gray-100' }}"
                    :class="{ 'justify-center': !sidebarOpen, 'justify-start': sidebarOpen }">
                    <i class="fa-solid fa-graduation-cap" :class="{ 'text-lg': !sidebarOpen }"></i>
                    <span class="ml-3 transition-opacity duration-200"
                            :class="{ 'opacity-0 hidden': !sidebarOpen, 'opacity-100': sidebarOpen }">
                        Course Anda
                    </span>
                </a>
            </nav>
        @endif
        </div>

        <!-- USER MENU DROPDOWN (bagian bawah sidebar) -->
        <div class="relative p-4 border-t border-gray-400 rounded-2xl shadow-lg">
            <button @click="userOpen = !userOpen"
                    class="flex items-center w-full px-4 py-2 text-gray-900 rounded-md"
                    :class="{ 'justify-center px-2': !sidebarOpen, 'justify-between': sidebarOpen }">
                <div class="flex items-center" :class="{ 'justify-center': !sidebarOpen }">
                    <i class="fa-solid fa-user-circle text-2xl" :class="{ 'mr-0': !sidebarOpen, 'mr-4': sidebarOpen }"></i>
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
            <button @click="toggleSidebar()" class="text-gray-700 text-xl focus:outline-none">
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
</body>
</html>
