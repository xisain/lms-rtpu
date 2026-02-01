@extends('layout.navbar')

@section('content')
<div class="mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="flex mb-6" aria-label="Breadcrumb">
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
                            clip-rule="evenodd">
                        </path>
                    </svg>
                    <p class="text-gray-600">Materi</p>
                </div>
            </li>
        </ol>
    </nav>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <main class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md p-8">

                <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $submateri->nama_submateri }}</h1>

                @php
                $type = $submateri->type ?? 'text';
                @endphp

                @if($type === 'text')
                <div class="prose max-w-none trix-content">
                    {!! ($submateri->isi_materi) !!}
                </div>
                @elseif($type === 'pdf')
                <iframe src="{{ asset('storage/' . $submateri->isi_materi) }}#toolbar=0"
                    class="w-full h-[600px] rounded-lg border"></iframe>
                @elseif($type === 'video')
                @php
                $youtubeId = str_replace('https://www.youtube.com/watch?v=', '', $submateri->isi_materi);
                @endphp
                <div class="relative w-full" style="padding-bottom: 56.25%;">
                    <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}"
                        class="absolute top-0 left-0 w-full h-full rounded-lg" allowfullscreen frameborder="0"></iframe>
                </div>
                @endif
            </div>
        </main>

        <!-- Sidebar -->
        <aside class="lg:col-span-1">
            <div class="sticky top-8">
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-[#009999] text-white px-6 py-4">
                        <h2 class="text-xl font-semibold">Course Module</h2>
                    </div>

                    @foreach($course->material as $index => $module)
                    <div class="module-item" aria-labelledby="module-button-{{ $index }}">
                        <button id="module-button-{{ $index }}" onclick="toggleModule({{ $index }})"
                            aria-expanded="false"
                            class="w-full px-6 py-4 flex justify-between items-center hover:bg-teal-50 transition-colors group rounded-lg">
                            <span class="font-medium text-gray-800 group-hover:text-teal-600 transition">
                                {{ $module->nama_materi }}
                            </span>
                            <svg id="icon-{{ $index }}"
                                class="w-5 h-5 text-gray-500 group-hover:text-teal-600 transition-all duration-300 "
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <div id="module-{{ $index }}" role="region" aria-hidden="true"
                            class="module-content bg-[#009999]/10 max-h-0 overflow-hidden transition-all duration-400 rounded-lg">
                            <div class="px-6 py-3 space-y-2">
                                @foreach($module->submaterial as $sub)
                                @php
                                $isActive = isset($submateri) && $submateri->id === $sub->id;
                                $isCompleted = App\Models\progress::isCompleted(auth()->id(), $sub->id);
                                $canAccess = App\Models\progress::canAccess(auth()->id(), $sub->id);
                                $isHidden = $sub->hidden;
                                @endphp
                                <div class="{{ (!$canAccess || $isHidden) ? 'opacity-60' : '' }}">
                                    @if($isHidden)
                                        {{-- Hidden submaterial - tampilkan header tapi dengan lock icon --}}
                                        <div class="submodule-item flex items-center gap-3 px-4 py-3 bg-gray-50 rounded-lg border-l-4 border-gray-300 cursor-not-allowed"
                                            title="Konten ini sedang tidak tersedia">
                                            <div class="checkbox w-5 h-5 rounded-full border-2 border-gray-400 flex items-center justify-center">
                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <span class="text-sm text-gray-600 font-medium">{{ $sub->nama_submateri }}</span>
                                                <p class="text-xs text-gray-500">Konten tidak dapat diakses</p>
                                            </div>
                                        </div>
                                    @else
                                        {{-- Non-hidden submaterial - tampilkan normal --}}
                                        <a href="{{ $canAccess ? route('course.mulai', ['slug' => $course->slugs, 'material' => $module->id, 'submaterial' => $sub->id]) : '#' }}"
                                            class="submodule-item flex items-center gap-3 px-4 py-3 bg-white rounded-lg transition-all border-l-4
                                                    {{ $isActive ? 'border-teal-600' : ($isCompleted ? 'border-green-500' : 'border-transparent') }}
                                                    {{ $canAccess ? 'cursor-pointer hover:shadow-md' : 'cursor-not-allowed' }}"
                                            @if(!$canAccess) title="Selesaikan materi sebelumnya terlebih dahulu" @endif>
                                            <div class="checkbox w-5 h-5 rounded-full border-2
                                                        {{ $isCompleted ? 'border-green-500 bg-green-500' : ($isActive ? 'border-teal-600 bg-teal-600' : 'border-gray-300') }}
                                                        flex items-center justify-center transition-all">
                                                @if($isCompleted || $isActive)
                                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                @elseif(!$canAccess)
                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <span
                                                    class="text-sm {{ $isCompleted ? 'text-green-700 font-medium' : 'text-gray-700' }}">
                                                    {{ $sub->nama_submateri }}
                                                </span>
                                                @if(!$canAccess)
                                                <p class="text-xs text-red-500">Selesaikan materi sebelumnya</p>
                                                @endif
                                            </div>
                                        </a>
                                    @endif
                                </div>
                                @endforeach

                                @if($module->quiz)
                                @php
                                $isQuizActive = isset($quiz) && $quiz->id === $module->quiz->id;
                                $isQuizCompleted = $module->quiz->isCompleted(auth()->id());
                                $canAccessQuiz = $module->isAllSubmaterialCompleted(auth()->id());
                                $lastAttempt = $module->quiz->getLastAttempt(auth()->id());
                                @endphp
                                <div class="{{ !$canAccessQuiz ? 'opacity-60' : '' }} mt-2">
                                    <a href="{{ $canAccessQuiz ? route('course.mulai', ['slug' => $course->slugs, 'material' => $module->id, 'submaterial' => 'quiz']) : '#' }}"
                                        class="submodule-item flex items-center gap-3 px-4 py-3 bg-white rounded-lg transition-all border-l-4
                                                {{ $isQuizActive ? 'border-teal-600' : ($isQuizCompleted ? 'border-green-500' : 'border-transparent') }}
                                                {{ $canAccessQuiz ? 'cursor-pointer hover:shadow-md' : 'cursor-not-allowed' }}"
                                        @if(!$canAccessQuiz) title="Selesaikan semua materi terlebih dahulu" @endif>
                                        <div class="checkbox w-5 h-5 rounded-full border-2
                                                    {{ $isQuizCompleted ? 'border-green-500 bg-green-500' : ($isQuizActive ? 'border-teal-600 bg-teal-600' : 'border-gray-300') }}
                                                    flex items-center justify-center transition-all">
                                            @if($isQuizCompleted)
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                            </svg>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="text-sm {{ $isQuizCompleted ? 'text-green-700 font-medium' : 'text-gray-700' }}">
                                                    {{ $module->quiz->judul_quiz }}
                                                </span>
                                                @if($lastAttempt && $lastAttempt->status === 'completed')
                                                <span
                                                    class="text-xs px-2 py-0.5 bg-green-100 text-green-800 rounded-full">
                                                    Nilai: {{ $lastAttempt->score }}
                                                </span>
                                                @endif
                                            </div>
                                            @if(!$canAccessQuiz)
                                            <p class="text-xs text-red-500">Selesaikan semua materi terlebih dahulu</p>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                                @endif

                                <!-- Progress bar untuk material ini -->
                                <div class="mt-4">
                                    @php
                                    $progress = App\Models\progress::getMaterialProgress(auth()->id(), $module->id);
                                    @endphp
                                    <div class="flex items-center justify-between text-xs text-dark mb-1">
                                        <span>Progress</span>
                                        <span>{{ number_format($progress, 0) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full transition-all duration-300"
                                            style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Final Task Button -->
                @php
                // Hitung progress course secara manual
                $totalItems = 0;
                $completedItems = 0;

                foreach($course->material as $material) {
                    // Hitung submaterial
                    $submaterialCount = $material->submaterial->count();
                    $totalItems += $submaterialCount;

                    $completedSubmaterials = App\Models\progress::where('user_id', auth()->id())
                        ->whereIn('submaterial_id', $material->submaterial->pluck('id'))
                        ->where('status', 'completed')
                        ->count();
                    $completedItems += $completedSubmaterials;

                    // Hitung quiz jika ada
                    if($material->quiz) {
                        $totalItems++;
                        if($material->quiz->isCompleted(auth()->id())) {
                            $completedItems++;
                        }
                    }
                }

                $courseProgress = $totalItems > 0 ? ($completedItems / $totalItems) * 100 : 0;
                $canAccessFinalTask = $courseProgress >= 100;
                $hasFinalTask = isset($course->finalTask) && $course->finalTask;
                @endphp

                @if($hasFinalTask)
                <div class="mt-6">
                    <div class="bg-gradient-to-r from-[#009999] to-teal-600 rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="bg-white/20 p-3 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-white font-bold text-lg">Final Task</h3>
                                    <p class="text-white/80 text-sm">Laporan Akhir Course</p>
                                </div>
                            </div>

                            @if($canAccessFinalTask)
                                <a href="{{ route('course.final_task', $course->slugs) }}"
                                   class="block w-full bg-white text-[#009999] font-semibold py-3 px-4 rounded-lg hover:bg-gray-50 transition-all text-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    <div class="flex items-center justify-center gap-2">
                                        <span>Kerjakan Final Task</span>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </div>
                                </a>
                            @else
                                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        <span class="text-white font-medium">Terkunci</span>
                                    </div>
                                    <p class="text-white/90 text-sm mb-3">Selesaikan semua materi dan quiz terlebih dahulu</p>
                                    <div class="flex items-center justify-between text-sm text-white/80 mb-2">
                                        <span>Progress Course</span>
                                        <span class="font-semibold">{{ number_format($courseProgress, 0) }}%</span>
                                    </div>
                                    <div class="w-full bg-white/20 rounded-full h-2">
                                        <div class="bg-white h-2 rounded-full transition-all duration-300"
                                            style="width: {{ $courseProgress }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Prevent right click
document.addEventListener('contextmenu', function(e) {
    if (e.target.tagName === 'IFRAME') {
        e.preventDefault();
    }
});

// Prevent keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Prevent Ctrl+S (Save)
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        return false;
    }
    // Prevent Ctrl+P (Print)
    if (e.ctrlKey && e.key === 'p') {
        e.preventDefault();
        return false;
    }
});
    function toggleModule(index) {
        const content = document.getElementById(`module-${index}`);
        const icon = document.getElementById(`icon-${index}`);

        document.querySelectorAll('.module-content').forEach((el, i) => {
            if (i !== index) {
                el.style.maxHeight = '0px';
                document.getElementById(`icon-${i}`).style.transform = 'rotate(0deg)';
            }
        });

        if (content.style.maxHeight && content.style.maxHeight !== '0px') {
            content.style.maxHeight = '0px';
            icon.style.transform = 'rotate(0deg)';
        } else {
            content.style.maxHeight = content.scrollHeight + 'px';
            icon.style.transform = 'rotate(90deg)';
        }
    }

    // Buka otomatis modul aktif
    document.addEventListener("DOMContentLoaded", () => {
        const activeSub = document.querySelector(".border-teal-600");
        if (activeSub) {
            const moduleEl = activeSub.closest(".module-content");
            const iconEl = moduleEl.previousElementSibling.querySelector("svg");
            moduleEl.style.maxHeight = moduleEl.scrollHeight + "px";
            iconEl.style.transform = "rotate(90deg)";
        }
    });
</script>
@endpush
