@extends('layout.navbar')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="mx-auto px-4 sm:px-6 lg:px-4 py-8">
        <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('course.my') }}" class="text-gray-600 hover:text-blue-600">
                    Kursus Saya
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                        </svg>
                            <a href="{{ route('course.show', $courseData->slugs) }}" class="text-gray-600 hover:text-blue-600">
                                {{ $courseData->nama_course }}
                            </a>
                        </div>
            </li>
        </ol>
    </nav>
    </div>
    <div class="flex">
        <!-- Sidebar -->
        <!-- Main Content Area -->
        <div class="flex-1 p-8">
            <div class="flex gap-8 items-stretch">
                <!-- Course Card -->
                <div class="w-full md:max-w-sm lg:max-w-md flex-shrink-0 mx-auto md:mx-0">
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl shadow-xl overflow-hidden border border-gray-200">
                        <!-- Course Image -->
                        <div class="relative bg-white p-8">
                            <img src="{{ asset('storage/'.$courseData->image_link) }}" alt="{{ $courseData->nama_course }}" class="w-full h-auto object-contain rounded-lg">
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
                                    {{-- User sudah enroll --}}
                                    @if($expireCourse)
                                        <button disabled class="w-full bg-gray-400 text-gray-600 font-semibold py-3 px-4 rounded-lg cursor-not-allowed shadow-md opacity-50">
                                            Course Expired
                                        </button>
                                    @else
                                        @if($firstMaterial && $firstSubmaterial)
                                            <a href="{{ route('course.mulai', ['slug' => $courseData->slugs,'material' => $firstMaterial->id,'submaterial' => $firstSubmaterial->id]) }}" class="w-full block text-center hover:bg-[#0f5757] bg-[#009999] text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-md">
                                                Start Course
                                            </a>
                                        @else
                                            <p class="text-gray-500 text-center">Belum ada materi.</p>
                                        @endif
                                    @endif
                                @else
                                    {{-- User belum enroll --}}
                                    @if($expireCourse || $courseMaxEnroll)
                                        <button disabled class="w-full bg-gray-400 text-gray-600 font-semibold py-3 px-4 rounded-lg cursor-not-allowed shadow-md opacity-50">
                                            @if($expireCourse)
                                                Course Expired
                                            @else
                                                Course Full
                                            @endif
                                        </button>
                                    @else
                                        @if($courseData->is_paid)
                                            @if($pendingPurchase)
                                                {{-- Pending purchase status --}}
                                                <div class="w-full bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                                    <div class="flex items-center gap-3">
                                                        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                        <div class="flex-1">
                                                            <p class="text-sm font-semibold text-yellow-800">Pembelian Anda Menunggu Verifikasi Admin</p>
                                                            <p class="text-xs text-yellow-700 mt-1">Mohon tunggu admin memverifikasi pembayaran Anda</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                {{-- Course berbayar - tombol Buy --}}
                                                <a href="{{ route('course.purchase.checkout', $courseData->slugs) }}" class="w-full block text-center hover:bg-[#0f5757] bg-[#009999] text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-md">
                                                    Buy Course (Rp {{ number_format($courseData->price, 0, ',', '.') }})
                                                </a>
                                            @endif
                                        @else
                                            {{-- Course gratis - tombol Enroll --}}
                                            <form action="{{ route('course.enroll', $courseData->slugs) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full hover:bg-[#0f5757] bg-[#009999] text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-md">
                                                    Enroll In Course
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                @endif
                            @else
                                {{-- Admin --}}
                                <a href="{{ route('admin.course.index')}}" class="w-full block text-center bg-[#009999] hover:bg-[#0f5757] text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-md">
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
                                            @if($sub->hidden)
                                                {{-- Hidden submaterial dengan lock icon --}}
                                                <div class="flex justify-between items-center py-1.5 text-xs opacity-60">
                                                    <div class="flex items-center gap-1.5">
                                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                        </svg>
                                                        <span class="text-gray-400">{{ $sub->nama_submateri }}</span>
                                                    </div>
                                                    <span class="text-gray-400 font-medium text-xs">Tidak tersedia</span>
                                                </div>
                                            @else
                                                {{-- Normal submaterial --}}
                                                <div class="flex justify-between items-center py-1.5 text-xs">
                                                    <span class="text-gray-500">{{ $sub->nama_submateri }}</span>
                                                    <span class="text-blue-600 font-medium">{{ ucfirst($sub->type) }}</span>
                                                </div>
                                            @endif
                                        @endforeach

                                        @if($material->quiz)
                                            <div class="mt-2 flex justify-between items-center py-1.5 text-xs border-t border-gray-100 pt-2">
                                                <span class="text-gray-500">{{ $material->quiz->judul_quiz }}</span>
                                                @auth
                                                    @if($isEnrolled)
                                                        @php
                                                            $lastAttempt = $material->quiz->getLastAttempt(auth()->id());
                                                        @endphp

                                                        @if($lastAttempt && $lastAttempt->status === 'completed')
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                Nilai: {{ $lastAttempt->score }}
                                                            </span>
                                                        @elseif($material->isAllSubmaterialCompleted(auth()->id()))
                                                            <a href="{{ route('course.mulai', ['slug' => $courseData->slugs, 'material' => $material->id, 'submaterial' => 'quiz']) }}" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200">
                                                                Mulai Quiz
                                                            </a>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
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
                <div class="flex-1 max-w-7xl">
                    <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-200 max-w-7xl">
                        @if(!$isEnrolled && $previewSubmaterial)
                            <!-- Preview Section -->
                            <div class="border-2 border-teal-200 rounded-2xl p-6 relative overflow-hidden mb-3">
                                <!-- Preview Badge -->
                                <div class="absolute top-4 right-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-600 text-white shadow-md">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                        Preview
                                    </span>
                                </div>

                                <div class="mb-4">
                                    <h2 class="text-2xl font-bold text-gray-800 mb-4">
                                        Preview: {{ $previewSubmaterial->nama_submateri }}
                                    </h2>

                                    @php
                                        $type = $previewSubmaterial->type ?? 'text';
                                    @endphp

                                    @if($type === 'text')
                                        <div class="relative prose max-w-none h-[400px] text-gray-700 leading-relaxed overflow-hidden rounded-lg bg-white">
                                            {!! \Illuminate\Support\Str::of(strip_tags($previewSubmaterial->isi_materi))->split('/(\r?\n){2,}/')->take(2)->implode("\n\n") !!}
                                            <div class="absolute -bottom-10 left-0 w-full h-25 bg-gradient-to-t from-white via-white/95 to-transparent pointer-events-none"></div>
                                        </div>
                                        <div class="mt-4 text-center">
                                            <p class="text-sm text-gray-500 italic">Teks dipersingkat untuk pratinjau.</p>
                                        </div>
                                    @elseif($type === 'pdf')
                                        <iframe src="{{ asset('storage/' . $previewSubmaterial->isi_materi) }}#page=1&zoom=100" class="w-full h-[400px] rounded-lg border"></iframe>
                                        <div class="mt-4 text-center">
                                            <p class="text-sm text-gray-500 italic">Hanya menampilkan 7 halaman pertama (gunakan kontrol PDF untuk navigasi halaman).</p>
                                        </div>
                                    @elseif($type === 'video')
                                        @php
                                            $youtubeId = str_replace('https://www.youtube.com/watch?v=', '', $previewSubmaterial->isi_materi);
                                        @endphp
                                        <div class="relative w-full" style="padding-bottom: 56.25%;">
                                            <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}?start=0&end=20" class="absolute top-0 left-0 w-full h-full rounded-lg" allowfullscreen frameborder="0"></iframe>
                                        </div>
                                        <div class="mt-4 text-center">
                                            <p class="text-sm text-gray-500 italic">Menampilkan 20 detik pertama dari video.</p>
                                        </div>
                                    @else
                                        <p class="text-gray-500 italic">Jenis materi belum didukung untuk pratinjau.</p>
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
                                            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200 shadow-md whitespace-nowrap">
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
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
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

                        <!-- Final Task Section (if exists) -->
                        @if($courseData->finalTask && $isEnrolled)
                        <div class="mt-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <p class="text-sm font-semibold text-green-800">
                                            Laporan Akhir
                                        </p>
                                    </div>
                                    <p class="text-sm text-green-700 mb-3">
                                        Selesaikan dan kumpulkan laporan akhir course untuk menyelesaikan pembelajaran.
                                    </p>

                                    @if($finalTaskSubmission)
                                        <!-- Submission Status -->
                                        <div class="flex items-center gap-3">
                                            @php
                                                $statusConfig = [
                                                    'submitted' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Menunggu Review'],
                                                    'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Disetujui'],
                                                    'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditolak'],
                                                    'resubmmit' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'label' => 'Perlu Diubah'],
                                                ];
                                                $status = $statusConfig[$finalTaskSubmission->status] ?? $statusConfig['submitted'];
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                                                {{ $status['label'] }}
                                            </span>
                                            <span class="text-xs text-green-600">
                                                Dikirim {{ $finalTaskSubmission->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    @else
                                        @if($courseProgress >= 100)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                Siap Dikerjakan ✓
                                            </span>
                                        @else
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                                    Belum Bisa Dikerjakan
                                                </span>
                                                <span class="text-xs text-gray-600">
                                                    Selesaikan {{ 100 - round($courseProgress) }}% materi terlebih dahulu
                                                </span>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                @if($courseProgress >= 100 || $finalTaskSubmission)
                                    <a href="{{ route('course.final_task', $courseData->slugs) }}" class="ml-4 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 shadow-md whitespace-nowrap text-sm">
                                        {{ $finalTaskSubmission ? 'Lihat' : 'Kerjakan' }}
                                    </a>
                                @else
                                    <button disabled class="ml-4 bg-gray-400 text-gray-600 font-semibold py-2 px-4 rounded-lg cursor-not-allowed shadow-md whitespace-nowrap text-sm opacity-50">
                                        Kerjakan
                                    </button>
                                @endif
                            </div>
                        </div>
                        @endif
                        @if ($isEnrolled && $courseData->whatsapp_group)
                        <div class="mt-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-5 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700 mb-1">Gabung Grup WhatsApp</p>
                                    <a href="{{ $courseData->whatsapp_group }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 font-semibold text-base group transition-colors duration-200">
                                        <span>Join Group</span>
                                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Course Period Info -->
                        <div class="mt-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-0.5">
                                    <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-800">
                                        <strong>Course Period:</strong> {{ \Carbon\Carbon::parse($courseData->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($courseData->end_date)->format('d M Y') }}
                                    </p>
                                    @if($courseData->maxEnrollment)
                                        <p class="text-sm text-blue-800 mt-1">
                                            <strong>Student Enrollment:</strong> {{ $countEnroll }}/{{ $courseData->maxEnrollment }} students
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
