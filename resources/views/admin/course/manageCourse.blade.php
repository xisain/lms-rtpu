@extends('layout.sidebar')

@section('content')
<div class="mx-auto py-2">
    <div class="max-w-10xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-5">
            <!-- Header -->
            <div class="bg-[#009999] px-6 py-4 flex justify-between items-center">
                <h4 class="text-xl font-bold text-white uppercase">
                    Manage Course: "{{ $course->nama_course }}"
                </h4>
            </div>

            <!-- Content -->
            <div class="p-6 flex flex-col md:flex-row gap-6">
                <!-- Course Image -->
                <div class="w-full md:w-1/3">
                    <img
                        src="{{ $course->image_link ? asset('storage/'.$course->image_link) : 'https://via.placeholder.com/400x250?text=No+Image' }}"
                        alt="{{ $course->nama_course }}"
                        class="w-full h-64 object-cover rounded-lg shadow"
                    >
                </div>

                <!-- Course Info -->
                <div class="w-full md:w-2/3 flex flex-col justify-between">
                    <div class="space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-800">{{ $course->nama_course }}</h2>
                                <p class="text-sm text-gray-500">
                                    {{ $course->category ? $course->category->name : 'Uncategorized' }}
                                </p>
                            </div>

                            <!-- Tombol Edit -->
                            <a href="{{ route('course.edit', $course->id) }}"
                               class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-[#009999] hover:bg-[#007777] rounded-md shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232a2.5 2.5 0 113.536 3.536L7.5 20H4v-3.5l11.232-11.268z" />
                                </svg>
                                Edit
                            </a>
                        </div>

                        <div class="border-t pt-3">
                            <p class="text-gray-700">
                                <span class="font-semibold">Description:</span>
                                {{ $course->description ?? '-' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <p class="font-medium text-gray-700">Start Date:</p>
                                <p class="text-gray-600">
                                    {{ $course->start_date ? \Carbon\Carbon::parse($course->start_date)->format('d M Y') : '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="font-medium text-gray-700">End Date:</p>
                                <p class="text-gray-600">
                                    {{ $course->end_date ? \Carbon\Carbon::parse($course->end_date)->format('d M Y') : '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="font-medium text-gray-700">Teacher:</p>
                                <p class="text-gray-600">
                                    {{ $course->teacher ? $course->teacher->name : '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="font-medium text-gray-700">Status:</p>
                                @if ($course->expireCourse())
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-600">
                                        Expired
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-600">
                                        Active
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if ($course->isLimitedCourse)
                            <div class="mt-4">
                                <p class="font-medium text-gray-700">Enrollment:</p>
                                <p class="text-gray-600">
                                    {{ $course->countEnrollment() }} / {{ $course->maxEnrollment }}
                                    @if ($course->maxSlotEnrollment())
                                        <span class="ml-2 text-sm text-red-600 font-semibold">(Full)</span>
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Materials Section -->
            <div class="border-t mt-6 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Materials</h3>
                @if ($course->material->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">#</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Material Title</th>
                                    <th class="px-4 py-2 text-center font-medium text-gray-700">Submaterials</th>
                                    <th class="px-4 py-2 text-center font-medium text-gray-700">Quizzes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($course->material as $index => $m)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                                        <td class="px-4 py-2 font-medium text-gray-800">{{ $m->nama_materi ?? 'Untitled Material' }}</td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $m->submaterial ? $m->submaterial->count() : 0 }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $m->quiz ? $m->quiz->count() : 0 }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">No materials available for this course.</p>
                @endif
            </div>

            <!-- Enrolled Users Section -->
            <div class="border-t mt-6 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Enrolled Users</h3>
                @if($enrolluser->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">#</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Name</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Email</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Enrolled At</th>
                                    <th class="px-4 py-2 text-center font-medium text-gray-700">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($enrolluser as $index => $userEnroll)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                                        <td class="px-4 py-2">{{ $userEnroll->user->name ?? '-' }}</td>
                                        <td class="px-4 py-2">{{ $userEnroll->user->email ?? '-' }}</td>
                                        <td class="px-4 py-2">
                                            {{ $userEnroll->created_at ? $userEnroll->created_at->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-2 items-center">
                                            <a href="" class="text-red-500 hover:underline"><i class="fa fa-times" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">No users enrolled yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
