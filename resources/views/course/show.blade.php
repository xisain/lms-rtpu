@extends('layout.navbar')

@section('content')
<div class="min-h-screen bg-gray-100">

    <div class="flex">
        <!-- Sidebar -->
        <!-- Main Content Area -->
        <div class="flex-1 p-8">
            <div class="flex gap-8 items-stretch">
                <!-- Course Card -->
                <div class="w-96 flex-shrink-0">
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl shadow-xl overflow-hidden border border-gray-200">
                        <!-- Course Image -->
                        <div class="relative bg-white p-8">
                            <img src="{{ asset('storage/'.$courseData->image_link) }}"
                                alt="{{ $courseData->nama_course }}" class="w-full h-auto object-contain rounded-lg">
                        </div>

                        <!-- Course Title -->
                        <div class="px-6 py-2 bg-white">
                            <h2 class="text-lg font-bold text-gray-800 text-center">{{ $courseData->nama_course }}</h2>
                        </div>

                        <!-- Description -->
                        <div class="mb-8 px-6 py-2 bg-white rounded-b-xl shadow-md">
                            <p class="text-gray-700 text-sm leading-relaxed text-justify">
                                {{ $courseData->description }}
                            </p>
                        </div>

                        <!-- Enroll Button -->
                        <div class="px-6 pt-4 -mt-4">
                            @if (auth()->user()->roles_id !== 1)

                            @if($isEnrolled)
                            @if($firstMaterial && $firstSubmaterial)
                            <a href="{{ route('course.mulai', ['slug' => $courseData->slugs,'material' => $firstMaterial->id,'submaterial' => $firstSubmaterial->id]) }}"
                                class="w-full block text-center hover:bg-[#0f5757] bg-[#009999] text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-md">
                                Start Course
                            </a>
                            @else
                            <p class="text-gray-500 text-center">Belum ada materi.</p>
                            @endif
                            @else
                            <form action="{{ route('course.enroll', $courseData->slugs) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full  hover:bg-[#0f5757] bg-[#009999] text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-md">
                                    Enroll In Course
                                </button>
                            </form>
                            @endif
                            @else
                            <a href="{{ route('admin.course.index')}}"
                                class="w-full block text-center bg-[#009999]  hover:bg-[#0f5757] text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-md">
                                Lihat di Panel
                            </a>
                            @endif

                        </div>

                        <!-- Course Modules -->
                        <div class="px-6 pb-6 pt-4">
                            <h3 class="text-xs font-semibold text-gray-500 mb-3">Course Modules :</h3>

                            @forelse($courseData->material as $material)
                            <div class="mb-3">
                                <h4 class="font-semibold text-gray-800 text-sm mb-2">{{ $material->nama_materi }}</h4>

                                @if(isset($material->submaterial) && count($material->submaterial) > 0)
                                @foreach($material->submaterial as $sub)
                                <div class="flex justify-between items-center py-1.5 text-xs">
                                    <span class="text-gray-500">{{ $sub->nama_submateri }}</span>
                                    <span class="text-blue-600 font-medium">
                                        {{ ucfirst($sub->type) }}
                                    </span>
                                </div>
                                @endforeach

                                @if($material->quiz)
                                <div
                                    class="mt-2 flex justify-between items-center py-1.5 text-xs border-t border-gray-100 pt-2">
                                    <span class="text-gray-500">{{ $material->quiz->judul_quiz }}</span>
                                    @auth
                                    @if($isEnrolled)
                                    @php
                                    $lastAttempt = $material->quiz->getLastAttempt(auth()->id());
                                    @endphp

                                    @if($lastAttempt && $lastAttempt->status === 'completed')
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Nilai: {{ $lastAttempt->score }}
                                    </span>
                                    @elseif($material->isAllSubmaterialCompleted(auth()->id()))
                                    <a href="{{ route('course.mulai', ['slug' => $courseData->slugs, 'material' => $material->id, 'submaterial' => 'quiz']) }}"
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200">
                                        Mulai Quiz
                                    </a>
                                    @else
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        Selesaikan Materi
                                    </span>
                                    @endif
                                    @endif
                                    @endauth
                                </div>
                                @endif
                                @else
                                <p class="text-gray-400 text-xs italic">Pendahuluan</p>
                                <p class="text-gray-400 text-xs italic">Materi Lengkap</p>
                                <p class="text-gray-400 text-xs italic">Materi Lanjutan 2</p>
                                @endif
                            </div>
                            @empty
                            <p class="text-gray-400 text-sm">No materials available yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Description Card -->
                <div class="flex-1">
                    <!-- 🔹 BAGIAN BARU UNTUK PREVIEW SUBMATERI -->
                    <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-200">
                        @if(!$isEnrolled && $previewSubmaterial)
                        <!-- Preview Section -->
                        <div class="border-2 border-teal-200 rounded-2xl p-6 relative overflow-hidden mb-3">
                            <!-- Preview Badge -->
                            <div class="absolute top-4 right-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-600 text-white shadow-md">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd"
                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Preview
                                </span>
                            </div>
                            <div class="mb-4">
                                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                                    Preview: {{ $previewSubmaterial->nama_submateri }}
                                </h2>
                                @if($previewSubmaterial)
                                    @php
                                        $type = $previewSubmaterial->type ?? 'text';
                                    @endphp
                                    {{-- 🔹 Preview berdasarkan jenis submateri --}}
                                    @if($type === 'text')
                                        <div class="relative prose max-w-none h-[400px] text-gray-700 leading-relaxed overflow-hidden rounded-lg bg-white">
                                            {!! \Illuminate\Support\Str::of(strip_tags($previewSubmaterial->isi_materi))
                                                ->split('/(\r?\n){2,}/')
                                                ->take(2)
                                                ->implode("\n\n") !!}
                                            <div class="absolute -bottom-10 left-0 w-full h-25 bg-gradient-to-t from-white via-white/95 to-transparent pointer-events-none"></div>
                                        </div>
                                        <div class="mt-4 text-center">
                                            <p class="text-sm text-gray-500 italic">Teks dipersingkat untuk pratinjau.</p>
                                        </div>
                                    @elseif($type === 'pdf')
                                        <iframe
                                            src="{{ asset('storage/' . $previewSubmaterial->isi_materi) }}#page=1&zoom=100"
                                            class="w-full h-[400px] rounded-lg border"></iframe>

                                        <div class="mt-4 text-center">
                                            <p class="text-sm text-gray-500 italic">
                                                Hanya menampilkan 7 halaman pertama (gunakan kontrol PDF untuk navigasi halaman).
                                            </p>
                                        </div>

                                    @elseif($type === 'video')
                                        @php
                                            $youtubeId = str_replace('https://www.youtube.com/watch?v=', '', $previewSubmaterial->isi_materi);
                                        @endphp
                                        <div class="relative w-full" style="padding-bottom: 56.25%;">
                                            <iframe
                                                src="https://www.youtube.com/embed/{{ $youtubeId }}?start=0&end=20"
                                                class="absolute top-0 left-0 w-full h-full rounded-lg"
                                                allowfullscreen
                                                frameborder="0">
                                            </iframe>
                                        </div>

                                        <div class="mt-4 text-center">
                                            <p class="text-sm text-gray-500 italic">Menampilkan 20 detik pertama dari video.</p>
                                        </div>
                                    @else
                                        <p class="text-gray-500 italic">Jenis materi belum didukung untuk pratinjau.</p>
                                    @endif
                                @else
                                    <p class="text-gray-400 italic text-center">Belum ada submateri untuk ditampilkan sebagai preview.</p>
                                @endif
                            </div>
                            <!-- CTA -->
                            <div class="mt-6 pt-4 border-t border-teal-200">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm text-gray-600">
                                        <span class="font-semibold text-teal-700">Ingin melihat lebih banyak?</span>
                                        <br>Enroll untuk akses penuh ke semua materi!
                                    </p>
                                    <form action="{{ route('course.enroll', $courseData->slugs) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200 shadow-md whitespace-nowrap">
                                            Enroll Sekarang
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(!$isEnrolled)
                        <!-- Enrollment Notice -->
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-0.5">
                                    <svg class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-800">
                                        You need to enroll in this course to access the modules and materials.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($courseData->isLimitedCourse)
                        <!-- Course Period Info -->
                        @endif

                        @if($isEnrolled && $certificateStatus)
                            <!-- Certificate Section -->
                            <div class="mt-4 p-6 border-2 {{ $certificateStatus['completed'] ? 'border-green-200 bg-green-50' : 'border-gray-200 bg-gray-50' }} rounded-xl">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold {{ $certificateStatus['completed'] ? 'text-green-700' : 'text-gray-700' }}">
                                            Course Certificate
                                        </h3>
                                        <p class="text-sm {{ $certificateStatus['completed'] ? 'text-green-600' : 'text-gray-500' }}">
                                            @if($certificateStatus['completed'])
                                            Selamat! Anda telah memenuhi semua persyaratan.
                                            @else
                                                Selesaikan semua materi dan kuis untuk mendapatkan sertifikat Anda.
                                            @endif
                                        </p>
                                    </div>

                                    @if($certificateStatus['completed'])
                                        @if($certificateStatus['certificate'])
                                            @if($certificateStatus['certificate']->pdf_path)
                                                <a href="{{ route('certificate.download', $certificateStatus['certificate']->id) }}"
                                                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    Download Certificate
                                                </a>
                                            @else
                                                <button class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200">
                                                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Generating Certificate...
                                                </button>
                                            @endif
                                        @else
                                            <form action="{{ route('certificate.generate', ['course' => $courseData->id, 'user' => Auth::id()]) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Generate Certificate
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <button disabled class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-500 bg-gray-100 cursor-not-allowed">
                                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            Certificate Locked
                                        </button>
                                    @endif
                                </div>

                                @if(!$certificateStatus['completed'])
                                    <!-- Progress Indicator -->
                                    <div class="mt-4 space-y-2">
                                        <div class="text-xs text-gray-500">Course Completion Requirements:</div>
                                        <ul class="text-sm space-y-1">
                                            @foreach($courseData->material as $material)
                                                <li class="flex items-center space-x-2">
                                                    @php
                                                        $materialCompleted = true;
                                                        foreach($material->submaterial as $sub) {
                                                            $progress = App\Models\progress::where('user_id', Auth::id())
                                                                ->where('submaterial_id', $sub->id)
                                                                ->where('status', 'completed')
                                                                ->exists();
                                                            if(!$progress) {
                                                                $materialCompleted = false;
                                                                break;
                                                            }
                                                        }

                                                        if($material->quiz) {
                                                            $quizCompleted = App\Models\quiz_attempt::where('user_id', Auth::id())
                                                                ->where('quiz_id', $material->quiz->id)
                                                                ->where('status', 'completed')
                                                                ->where('score', '>=', 70)
                                                                ->exists();
                                                            $materialCompleted = $materialCompleted && $quizCompleted;
                                                        }
                                                    @endphp

                                                    @if($materialCompleted)
                                                        <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    @else
                                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    @endif
                                                    <span class="{{ $materialCompleted ? 'text-green-600' : 'text-gray-500' }}">
                                                        {{ $material->nama_materi }}
                                                        @if($material->quiz)
                                                            (Termasuk quiz)
                                                        @endif
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Course Period Info -->
                        <div class="mt-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-0.5">
                                    <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-800">
                                        <strong>Course Period:</strong> {{
                                        \Carbon\Carbon::parse($courseData->start_date)->format('d M Y') }} - {{
                                        \Carbon\Carbon::parse($courseData->end_date)->format('d M Y') }}
                                    </p>
                                    @if($courseData->maxEnrollment)
                                    <p class="text-sm text-blue-800 mt-1">
                                        <strong>Max Enrollment:</strong> {{ $courseData->maxEnrollment }} students
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
