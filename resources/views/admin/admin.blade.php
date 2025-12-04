@extends('layout.sidebar')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 -mt-9">Dashboard LMS</h1>
        <p class="text-gray-600 mt-1">Selamat datang kembali, {{ Auth::user()->name }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
         <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Users</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalUsers ?? 0 }}</h3>
                    <p class="text-green-600 text-sm mt-2">
                        <span class="font-semibold">{{ $newUsersThisMonth ?? 0 }}</span> user baru bulan ini
                    </p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3">
                <p class="text-gray-500 text-sm font-medium">Pendaftar Baru</p>
                <a href="{{ route('admin.user.active') }}"
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium hover:underline transition duration-150">
                    Lihat
                </a>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $userNeedActivate ?? 0 }}</h3>
            <p class="text-green-600 text-sm mt-2">
                <span class="font-semibold">User Ini Memerlukan Verifikasi Admin untuk memulai LMS</span>
            </p>
        </div>
        <div class="bg-blue-100 rounded-full p-3">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                </path>
            </svg>
        </div>
    </div>
</div>

        <!-- Total Courses -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Courses</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalCourses ?? 0 }}</h3>
                    <p class="text-green-600 text-sm mt-2">
                        <span class="font-semibold">{{ $activeCourses ?? 0 }}</span> course aktif
                    </p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </div>
            </div>
        </div>


        <!-- Total Enrollments -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Enrollments</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalEnrollments ?? 0 }}</h3>
                    <p class="text-purple-600 text-sm mt-2">
                        <span class="font-semibold">{{ $completionRate ?? 0 }}%</span> completion rate
                    </p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Popular Courses -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Course Terpopuler</h2>
                <a href="{{ route('admin.course.index') }}"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                @forelse($popularCourses ?? [] as $course)
                <a href="{{ route('course.show', $course->slugs) }}"
                    class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    <div class="flex-shrink-0">
                        @if($course->image_link)
                        <img src="{{ asset('storage/' . $course->image_link) }}" alt="{{ $course->nama_course }}"
                            class="w-16 h-16 rounded-lg object-cover">
                        @else
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-gray-800 font-semibold truncate">{{ $course->nama_course }}</h3>
                        <p class="text-gray-500 text-sm">{{ $course->category->category ?? 'Uncategorized' }}</p>
                        <div class="flex items-center mt-2 space-x-4">
                            <span class="text-xs text-gray-600">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                {{ $course->enrollment_count ?? 0 }} students
                            </span>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <span
                            class="{{ $course->public ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-xs font-semibold px-3 py-1 rounded-full">
                            {{ $course->public ? 'Public' : 'Private' }}
                        </span>
                    </div>

                </a>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <p>Belum ada course tersedia</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Categories Stats -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Categories</h2>
                <a href="{{ route('admin.category.index') }}"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                @forelse($topCategories ?? [] as $category)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-black font-bold text-sm">{{ substr($category->category, 0, 1) }}</span>
                        </div>
                        <div>
                            <h3 class="text-gray-800 font-medium text-sm">{{ $category->category }}</h3>
                            <p class="text-gray-500 text-xs">{{ $category->course_count ?? 0 }} courses</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                    <p class="text-sm">Belum ada kategori</p>
                </div>
                @endforelse
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Recent Activity</h3>
                <div class="space-y-3">
                    @forelse($recentActivities ?? [] as $activity)
                    <div class="flex items-start space-x-2">
                        <div class="bg-blue-100 rounded-full p-1 mt-1 flex-shrink-0">
                            <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                                <span class="font-semibold">{{ $activity->user->name }}</span>
                                mendaftar pada kursus
                                <span class="text-blue-600 font-medium">{{ $activity->course->nama_course }}</span>
                            </p>
                            <p class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-gray-500 text-center py-4">Belum ada aktivitas</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">User Terbaru</h2>
                <a href="{{ route('admin.user.index') }}"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Courses</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentUsers ?? [] as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($user->avatar)
                                    <img class="h-10 w-10 rounded-full object-cover"
                                        src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                    @else
                                    <div
                                        class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                        <span class="text-black font-semibold">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $user->role->name === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($user->role->name ?? 'student') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $user->enrollment_count ?? 0
                            }} courses</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $user->created_at->format('d M
                            Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $user->isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->isActive ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        {{-- Buat Nanti Show User jangan di apa-apain --}}
                        {{-- <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('users.show', $user->id) }}"
                                class="text-blue-600 hover:text-blue-900">Detail</a>
                        </td> --}}
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <p>Belum ada user terdaftar</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
