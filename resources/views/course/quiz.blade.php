@extends('layout.navbar')

@section('content')
<div class="mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <main class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md p-8">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $quiz->judul_quiz }}</h1>
                    @if($lastAttempt && $lastAttempt->status === 'completed')
                    <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg">
                        Nilai Terakhir: {{ $lastAttempt->score }}
                    </div>
                    @endif
                </div>

                @if(!$lastAttempt || $lastAttempt->status !== 'completed')
                <form action="{{ route('quiz.submit', ['slug' => $course->slugs, 'material' => $materi->id]) }}"
                    method="POST" class="space-y-8" id="quizForm">
                    @csrf

                    @foreach($quiz->questions as $index => $question)
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            {{ $index + 1 }}. {{ $question->pertanyaan }}
                        </h3>
                        <div class="space-y-3">
                            @foreach($question->options as $option)
                            <label
                                class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}"
                                    class="mt-1 h-4 w-4 text-purple-600 focus:ring-purple-500">
                                <span class="text-gray-700">{{ $option->teks_pilihan }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('course.mulai', ['slug' => $course->slugs, 'material' => $materi->id]) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Kembali ke Materi
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Submit Quiz
                        </button>
                    </div>
                </form>
                @else
                <!-- Score Summary -->
                <div class="text-center py-8 mb-8">
                    <div class="mb-4">
                        <svg class="mx-auto h-20 w-20 text-green-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <h3 class="text-lg font-medium text-gray-900">Quiz Selesai!</h3>
                    <p class="mt-1 text-sm text-gray-500">Anda telah menyelesaikan quiz ini dengan nilai</p>

                    @php
                    $scoreClass = $lastAttempt->score >= 80 ? 'text-green-600' : ($lastAttempt->score >= 60 ?
                    'text-yellow-600' : 'text-red-600');
                    $answers = json_decode($lastAttempt->answers, true);
                    $correctCount = collect($answers)->where('is_correct', true)->count();
                    @endphp

                    <div class="{{ $scoreClass }} text-5xl font-bold mt-4 mb-2">
                        {{ $lastAttempt->score }}
                    </div>
                    <div class="text-gray-500 text-sm mb-6">
                        {{ $correctCount }} dari {{ count($answers) }} jawaban benar
                    </div>

                    @if($lastAttempt->score >= 80)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 max-w-md mx-auto mb-6">
                        <p class="text-green-800 font-medium">🎉 Selamat! Nilai Anda sangat baik!</p>
                    </div>
                    @elseif($lastAttempt->score >= 60)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 max-w-md mx-auto mb-6">
                        <p class="text-yellow-800 font-medium">👍 Lumayan! Terus tingkatkan pemahaman Anda!</p>
                    </div>
                    @else
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 max-w-md mx-auto mb-6">
                        <p class="text-red-800 font-medium">💪 Jangan menyerah! Pelajari kembali materinya!</p>
                    </div>
                    @endif
                </div>

                <!-- Answer Details -->
                <div class="space-y-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Pembahasan Jawaban</h2>

                    @foreach($answers as $index => $answer)
                    <div
                        class="border {{ $answer['is_correct'] ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50' }} rounded-lg p-6">
                        <!-- Question -->
                        <div class="flex items-start mb-4">
                            <span
                                class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full {{ $answer['is_correct'] ? 'bg-green-500' : 'bg-red-500' }} text-white font-bold text-sm mr-3">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{ $answer['question_text'] }}
                                </h3>
                            </div>
                            <div class="ml-3">
                                @if($answer['is_correct'])
                                <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                @else
                                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                @endif
                            </div>
                        </div>

                        <!-- Answer Options -->
                        <div class="ml-11 space-y-3">
                            @php
                            $question = $quiz->questions->find($answer['question_id']);
                            @endphp

                            @foreach($question->options as $option)
                            @php
                            $isSelected = $option->id == $answer['selected_option_id'];
                            $isCorrect = $option->is_correct;
                            @endphp

                            <div class="flex items-start p-3 rounded-lg
                            {{ $isSelected && $isCorrect ? 'bg-green-100 border-2 border-green-500' : '' }}
                            {{ $isSelected && !$isCorrect ? 'bg-red-100 border-2 border-red-500' : '' }}
                            {{ !$isSelected && $isCorrect ? 'bg-green-50 border-2 border-green-400' : '' }}
                            {{ !$isSelected && !$isCorrect ? 'bg-white border border-gray-200' : '' }}">

                                <div class="flex-shrink-0 mt-1">
                                    @if($isSelected && $isCorrect)
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    @elseif($isSelected && !$isCorrect)
                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    @elseif(!$isSelected && $isCorrect)
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    @else
                                    <div class="w-5 h-5 rounded-full border-2 border-gray-300"></div>
                                    @endif
                                </div>

                                <div class="ml-3 flex-1">
                                    <span class="
                                    {{ $isSelected && $isCorrect ? 'text-green-900 font-semibold' : '' }}
                                    {{ $isSelected && !$isCorrect ? 'text-red-900 font-semibold' : '' }}
                                    {{ !$isSelected && $isCorrect ? 'text-green-800 font-medium' : '' }}
                                    {{ !$isSelected && !$isCorrect ? 'text-gray-700' : '' }}">
                                        {{ $option->teks_pilihan }}
                                    </span>

                                    @if($isSelected && !$isCorrect)
                                    <span class="ml-2 text-xs text-red-600 font-medium">(Jawaban Anda)</span>
                                    @elseif($isSelected && $isCorrect)
                                    <span class="ml-2 text-xs text-green-600 font-medium">(Jawaban Anda - Benar!)</span>
                                    @elseif(!$isSelected && $isCorrect)
                                    <span class="ml-2 text-xs text-green-600 font-medium">(Jawaban yang Benar)</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between">
                    <a href="{{ route('course.mulai', ['slug' => $course->slugs, 'material' => $materi->id]) }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Materi
                    </a>
                    {{-- Unused --}}
                    {{-- <a href="{{ route('course.mulai', ['slug' => $course->slugs, 'material' => $materi->id, 'submaterial'=>'quiz']) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Coba Lagi
                    </a> --}}
                </div>
                @endif
            </div>
        </main>

        <!-- Sidebar -->
        <aside class="lg:col-span-1">
            <div class="sticky top-8">
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-4">
                        <h2 class="text-2xl text-dark font-semibold">Quiz Info</h2>
                    </div>
                    <div class="p-6 space-y-4 -mt-3">
                        <div>
                            <h3 class="text-lg font-medium text-gray-500">Materi</h3>
                            <p class="mt-1 text-base text-gray-900">{{ $materi->nama_materi }}</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-500">Jumlah Pertanyaan</h3>
                            <p class="mt-1 text-base text-gray-900">{{ $quiz->questions->count() }}</p>
                        </div>
                        @if($lastAttempt)
                        <div>
                            <h3 class="text-lg font-medium text-gray-500">Status</h3>
                            <p class="mt-1">
                                @if($lastAttempt->status === 'completed')
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Selesai
                                </span>
                                @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    Belum Selesai
                                </span>
                                @endif
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('quizForm')?.addEventListener('submit', function(e) {
    const questions = this.querySelectorAll('[name^="answers["]');
    const total = {{ $quiz->questions->count() }};
    let answered = 0;

    questions.forEach(input => {
        const name = input.getAttribute('name');
        if (document.querySelector(`[name="${name}"]:checked`)) {
            answered++;
        }
    });

    if (answered < total) {
        e.preventDefault();
        alert(`Mohon jawab semua pertanyaan (${answered}/${total} terjawab)`);
    }
});
</script>
@endpush
