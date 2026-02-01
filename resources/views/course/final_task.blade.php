@extends('layout.navbar')

@section('title', 'Laporan Akhir - ' . $course->nama_course)

@section('content')
    <div class="min-h-screen bg-gray-100 py-8">
        <div class="max-w-10xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('course.my') }}" class="text-gray-600 hover:text-blue-600">
                            Kursus Saya
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('course.show', $course->slugs) }}" class="text-gray-600 hover:text-blue-600">
                                {{ $course->nama_course }}
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-500">Laporan Akhir</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left Column - Course Info -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-8">
                        <!-- Course Image -->
                        <div class="relative bg-gradient-to-br from-blue-50 to-indigo-50 p-6">
                            <img src="{{ asset('storage/' . $course->image_link) }}" alt="{{ $course->nama_course }}"
                                class="w-full h-48 object-contain rounded-lg">
                        </div>

                        <!-- Course Details -->
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-3">
                                {{ $course->nama_course }}
                            </h2>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                                {{ $course->description }}
                            </p>

                            <!-- Progress Info -->
                            <div class="border-t pt-4 space-y-3">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Progres Kursus</span>
                                    <span class="font-semibold text-blue-600">
                                        {{ $userProgress ?? 0 }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                        style="width: {{ $userProgress ?? 0 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Final Task Content -->
                <div class="lg:col-span-2">

                    <!-- Task Instructions -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                        <div class="flex items-center mb-6">
                            <div class="bg-blue-100 p-3 rounded-xl mr-4">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-800">Laporan Akhir</h1>
                                <p class="text-gray-500 text-sm mt-1">Selesaikan pengumpulan proyek akhir Anda</p>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg mb-6">
                            <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Petunjuk
                            </h3>
                            <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                                {{ $finalTask->instruksi }}
                            </div>
                        </div>

                        <!-- Requirements Checklist -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="font-semibold text-gray-800 mb-4">Persyaratan Pengumpulan:</h3>
                            <ul class="space-y-2 text-sm text-gray-700">
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Unggah file Anda ke Google Drive
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Atur izin berbagi ke "Siapa pun yang memiliki tautan dapat melihat"
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Tempel tautan yang dapat dibagikan di formulir di bawah
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Submission Status Card (if already submitted) -->
                    @if($submission)
                        <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">Status Pengumpulan</h3>

                            <!-- Status Badge -->
                            <div class="flex items-center mb-6">
                                @php
                                    $status = $statusConfig[$submission->status] ?? $statusConfig['submitted'];
                                @endphp

                                <span
                                    class="px-4 py-2 rounded-full text-sm font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                                    {{ $status['label'] }}
                                </span>
                                <span class="ml-4 text-sm text-gray-500">
                                    Dikirim {{ $submission->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <!-- Submitted Link -->
                            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                <label class="text-sm font-medium text-gray-700 mb-2 block">Pengumpulan Anda:</label>
                                <a href="{{ $submission->link_google_drive }}" target="_blank"
                                    class="text-blue-600 hover:text-blue-800 break-all flex items-center">
                                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z">
                                        </path>
                                        <path
                                            d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z">
                                        </path>
                                    </svg>
                                    {{ $submission->link_google_drive }}
                                </a>
                            </div>

                            <!-- Review Feedback (if reviewed) -->
                            @if($submission->review)
                                <div class="border-t pt-6 mt-6">
                                    <h4 class="font-semibold text-gray-800 mb-4">Umpan Balik Review</h4>

                                    <!-- Score/Nilai Badge -->
                                    @if($submission->review->nilai !== null)
                                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 p-4 rounded-lg mb-6 border-2 border-purple-200">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-600">Nilai Laporan Akhir</p>
                                                    <p class="text-3xl font-bold text-purple-600 mt-1">{{ number_format($submission->review->nilai, 2) }}</p>
                                                </div>
                                                <div class="text-right">
                                                    @php
                                                        $nilai = $submission->review->nilai;
                                                        if ($nilai >= 85) {
                                                            $grade = 'A (Sangat Baik)';
                                                            $color = 'text-green-600';
                                                        } elseif ($nilai >= 75) {
                                                            $grade = 'B (Baik)';
                                                            $color = 'text-blue-600';
                                                        } elseif ($nilai >= 65) {
                                                            $grade = 'C (Cukup)';
                                                            $color = 'text-yellow-600';
                                                        } else {
                                                            $grade = 'D (Kurang)';
                                                            $color = 'text-red-600';
                                                        }
                                                    @endphp
                                                    <p class="text-sm text-gray-600">Grade</p>
                                                    <p class="text-xl font-bold {{ $color }} mt-1">{{ $grade }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($course->category->type === 'pekerti')
                                    <!-- Review Checklist -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                        @foreach($reviewItems as $key => $label)
                                            <div class="flex items-center text-sm">
                                                @if($submission->review->$key)
                                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                @endif
                                                <span class="text-gray-700">{{ $label }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    @endif

                                    <!-- Reviewer Notes -->
                                    @if($submission->review->catatan)
                                        @if($course->category->type === 'pelatihan')
                                            <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded">
                                                <p class="text-sm font-semibold text-orange-800 mb-2">📝 Catatan Program Pelatihan:</p>
                                                <p class="text-sm text-orange-700">{{ $submission->review->catatan }}</p>
                                            </div>
                                        @elseif($course->category->type === 'pekerti')
                                            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                                                <p class="text-sm font-semibold text-green-800 mb-2">📊 Penilaian Rubrik PEKERTI:</p>
                                                <p class="text-sm text-green-700">{{ $submission->review->catatan }}</p>
                                            </div>
                                        @else
                                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                                                <p class="text-sm font-semibold text-yellow-800 mb-2">Catatan Reviewer:</p>
                                                <p class="text-sm text-yellow-700">{{ $submission->review->catatan }}</p>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endif

                            <!-- Resubmit Button (if needed) -->
                            @if(in_array($submission->status, ['rejected', 'resubmmit']))
                                <button onclick="document.getElementById('resubmitForm').classList.remove('hidden')"
                                    class="mt-6 w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                                    Kirim Versi Baru
                                </button>
                            @endif
                        </div>
                    @endif

                    <!-- Submission Form -->
                    <div id="resubmitForm"
                        class="{{ $submission && !in_array($submission->status, ['rejected', 'resubmmit']) ? 'hidden' : '' }}">
                        <div class="bg-white rounded-2xl shadow-lg p-8">
                            <h3 class="text-xl font-bold text-gray-800 mb-6">
                                {{ $submission ? 'Kirim Ulang Pekerjaan Anda' : 'Kirim Pekerjaan Anda' }}
                            </h3>

                            <form action="{{ $submission && in_array($submission->status, ['rejected', 'resubmmit']) ? route('course.final_task.resubmit', ['slug' => $course->slugs]) : route('course.final_task.submit', $course->slugs) }}" method="POST">
                                @csrf
                                @if($submission && in_array($submission->status, ['rejected', 'resubmmit']))
                                    <input type="hidden" name="submission_id" value="{{ $submission->id }}">
                                @else
                                    <input type="hidden" name="final_task_id" value="{{ $finalTask->id }}">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                @endif
                                <div class="mb-6">
                                    <label for="link_google_drive" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tautan Google Drive <span class="text-red-500">*</span>
                                    </label>
                                    <input type="url" name="link_google_drive" id="link_google_drive" required
                                        placeholder="https://drive.google.com/..."
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                        value="{{ old('link_google_drive', $submission->link_google_drive ?? '') }}">
                                    @error('link_google_drive')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Terms Agreement -->
                                <div class="mb-6">
                                    <label class="flex items-start">
                                        <input type="checkbox" name="agreement" required
                                            class="mt-1 mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="text-sm text-gray-700">
                                            Saya mengkonfirmasi bahwa ini adalah karya asli saya dan saya telah menetapkan izin berbagi yang sesuai di Google Drive.
                                        </span>
                                    </label>
                                </div>

                                <!-- Submit Button -->
                                <div class="flex gap-4">
                                    @if($submission && in_array($submission->status, ['rejected', 'resubmmit']))
                                        <button type="button"
                                            onclick="document.getElementById('resubmitForm').classList.add('hidden')"
                                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-lg transition duration-200">
                                            Batal
                                        </button>
                                    @endif
                                    <button type="submit"
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                        </svg>
                                        Kirim Laporan Akhir
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif
@endsection
