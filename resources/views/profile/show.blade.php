@extends('layout.navbar')

@section('title', 'My Profile')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Profile Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center space-x-4">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        {{-- <span class="inline-block mt-2 px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                            Premium Member
                        </span> --}}
                    </div>
                </div>
            </div>

            <!-- Subscription Section -->
            @if(!$user->subscriptions->isEmpty())
                <div class="bg-white rounded-lg shadow-md mb-6">
                    <button onclick="toggleAccordion('subscription')" class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-gray-50 transition-colors">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <h2 class="text-xl font-semibold text-gray-900">My Subscription</h2>
                        </div>
                        <svg id="subscription-icon" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div id="subscription-content" class="hidden border-t border-gray-200">
                        <div class="p-6">
                            @foreach ($user->subscriptions as $subscription)
                                <div class="grid md:grid-cols-2 gap-6">
                                    <!-- Current Plan -->
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <h3 class="font-semibold text-gray-900 mb-2">Current Plan</h3>
                                        <p class="text-2xl font-bold text-blue-600 mb-1">{{ $subscription->plan->name }}</p>
                                    </div>

                                    <!-- Billing Info -->
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <h3 class="font-semibold text-gray-900 mb-2">Billing Information</h3>
                                        <p class="text-gray-700">{{ 'Rp ' . number_format($subscription->plan->price, 0, ',', '.') }}</p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            @if($subscription->ends_at)
                                                {{ $subscription->ends_at }}
                                            @else
                                                Belum Dimulai
                                            @endif
                                        </p>
                                    </div>

                                    <!-- Subscription Status -->
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <h3 class="font-semibold text-gray-900 mb-2">Status</h3>
                                        @if($subscription->status == 'waiting_approval')
                                            <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">
                                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                                                Belum di Verifikasi
                                            </span>
                                        @elseif ($subscription->status == 'approved')
                                            <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full">
                                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                                Ditolak
                                            </span>
                                            <p class="text-sm text-gray-500 mt-2">Alasan: {{ $subscription->notes }}</p>
                                        @endif
                                        @if($subscription->starts_at)
                                            <p class="text-sm text-gray-500 mt-2">Member Sejak: {{ $subscription->starts_at->format('d/m/Y') }}</p>
                                        @else
                                            <p class="text-sm text-gray-500 mt-2">Member Sejak: Belum Di mulai</p>
                                        @endif
                                    </div>

                                    <!-- Features -->
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <h3 class="font-semibold text-gray-900 mb-2">Features</h3>
                                        @php
                                            $features = is_array($subscription->plan->features)
                                                ? $subscription->plan->features
                                                : json_decode($subscription->plan->features, true);
                                        @endphp

                                        @if (!empty($features))
                                            <ul class="space-y-2 text-sm text-gray-700">
                                                @foreach ($features as $feature)
                                                    <li class="flex items-center">
                                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ $feature }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-sm text-gray-500 italic">No features listed.</p>
                                        @endif
                                    </div>
                                </div>

                                @endforeach
                                <div class="mt-6 flex space-x-4">
                                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        Manage Subscription
                                    </button>
                                    <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                        View Invoices
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Enrolled Courses Section -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <button onclick="toggleAccordion('courses')" class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-900">My Courses</h2>
                    </div>
                    <svg id="courses-icon" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div id="courses-content" class="hidden border-t border-gray-200">
                    <div class="p-6">
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse($user->enrollment as $index => $enrollment)
                                @php
                                    $course = $enrollment->course;
                                    $progress = $courseProgress[$course->id];
                                    $colors = [
                                        ['from' => 'blue-400', 'to' => 'blue-600', 'text' => 'blue-600', 'hover' => 'blue-700'],
                                        ['from' => 'purple-400', 'to' => 'purple-600', 'text' => 'purple-600', 'hover' => 'purple-700'],
                                        ['from' => 'green-400', 'to' => 'green-600', 'text' => 'green-600', 'hover' => 'green-700'],
                                        ['from' => 'orange-400', 'to' => 'orange-600', 'text' => 'orange-600', 'hover' => 'orange-700'],
                                        ['from' => 'pink-400', 'to' => 'pink-600', 'text' => 'pink-600', 'hover' => 'pink-700'],
                                        ['from' => 'indigo-400', 'to' => 'indigo-600', 'text' => 'indigo-600', 'hover' => 'indigo-700'],
                                    ];
                                    $color = $colors[$index % count($colors)];
                                @endphp

                                <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                                    <div class="h-40 bg-gradient-to-br from-{{ $color['from'] }} to-{{ $color['to'] }} flex items-center justify-center">
                                        @if($course->image_link)
                                            <img src="{{ asset('storage/' . $course->image_link) }}" class="w-full h-full object-cover" alt="{{ $course->nama_course }}">
                                        @else
                                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-semibold text-gray-900 mb-2">{{ $course->nama_course }}</h3>
                                        <p class="text-sm text-gray-600 mb-3">{{ Str::limit($course->description, 60) }}</p>

                                        <!-- Progress Bar -->
                                        <div class="mb-3">
                                            <div class="flex justify-between text-sm mb-1">
                                                <span class="text-gray-600">Progress</span>
                                                <span class="text-{{ $color['text'] }} font-semibold">{{ $progress['percentage'] }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-{{ $color['text'] }} h-2 rounded-full" style="width: {{ $progress['percentage'] }}%"></div>
                                            </div>
                                        </div>

                                        @if($progress['percentage'] == 100)
                                            <div class="flex space-x-2">
                                                <a href="{{ route('course.show', $course->slugs) }}" class="flex-1 px-4 py-2 bg-{{ $color['text'] }} text-white rounded-lg hover:bg-{{ $color['hover'] }} transition-colors text-sm text-center">
                                                    Review
                                                </a>
                                            </div>
                                        @else
                                            <a href="{{ route('course.show', $course->slugs) }}" class="block w-full px-4 py-2 bg-{{ $color['text'] }} text-white rounded-lg hover:bg-{{ $color['hover'] }} transition-colors text-sm text-center">
                                                Continue Learning
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full">
                                    <div class="text-center py-12">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No courses yet</h3>
                                        <p class="mt-1 text-sm text-gray-500">Get started by enrolling in a course.</p>
                                        <div class="mt-6">
                                            <a href="{{ route('course.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                Browse Courses
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <!-- Summary Stats -->
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4 text-center">
                                <p class="text-3xl font-bold text-blue-600">{{ $totalCourses }}</p>
                                <p class="text-gray-600 text-sm">Total Courses</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4 text-center">
                                <p class="text-3xl font-bold text-green-600">{{ $completedCourses }}</p>
                                <p class="text-gray-600 text-sm">Completed</p>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4 text-center">
                                <p class="text-3xl font-bold text-purple-600">{{ $averageProgress }}%</p>
                                <p class="text-gray-600 text-sm">Average Progress</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction History Button -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <h2 class="text-xl font-semibold text-gray-900">Transaction History</h2>
                        </div>
                        <a href="{{ route('profile.transaction.history') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                            <span>View History</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                    <p class="text-gray-600 text-sm mt-2">View all your transaction history and payment details</p>
                </div>
            </div>

        </div>
    </div>

    @push('styles')
        <script>
            function toggleAccordion(section) {
                const content = document.getElementById(section + '-content');
                const icon = document.getElementById(section + '-icon');

                if (content.classList.contains('hidden')) {
                    content.classList.remove('hidden');
                    icon.classList.add('rotate-180');
                } else {
                    content.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                }
            }
        </script>
    @endpush
@endsection
