@extends('layout.sidebar')
{{-- edit.blade.php --}}
@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Edit Course</h1>
            <p class="text-gray-600 mt-2">Ubah informasi course, tambah atau hapus materi dan submateri</p>
        </div>

        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <strong class="font-semibold">Terjadi kesalahan:</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="courseForm" action="{{ route('course.update', $course->id) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-6">
            @csrf
            @method('PUT')

            <!-- Course Information -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Course</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Category -->
                    <div class="col-span-2">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                        <select name="category_id" id="category_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $course->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Teacher -->
                    <div class="col-span-2">
                        <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">Pengajar *</label>
                        <select name="teacher_id" id="teacher_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $course->teacher_id == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Course Name -->
                    <div class="col-span-2">
                        <label for="nama_course" class="block text-sm font-medium text-gray-700 mb-2">Nama Course *</label>
                        <input type="text" name="nama_course" id="nama_course" required
                            value="{{ old('nama_course', $course->nama_course) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Description -->
                    <div class="col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi *</label>
                        <textarea name="description" id="description" rows="4" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $course->description) }}</textarea>
                    </div>

                    <!-- Image -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Course</label>
                        @if($course->image_link)
                            <img src="{{ asset('storage/' . $course->image_link) }}" alt="Course Image"
                                 class="w-40 h-28 object-cover rounded-md mb-2">
                        @endif
                        <input type="file" name="image_link" accept="image/*"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Limited Course -->
                    <div class="col-span-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="isLimitedCourse" id="isLimitedCourse" value="1"
                                   {{ $course->isLimitedCourse ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Course dengan Batasan Waktu & Kuota</span>
                        </label>
                    </div>

                    <!-- Limited Fields -->
                    <div id="limitedFields" class="col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4 {{ $course->isLimitedCourse ? '' : 'hidden' }}">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date"
                                   value="{{ old('start_date', $course->start_date) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                            <input type="date" name="end_date" id="end_date"
                                   value="{{ old('end_date', $course->end_date) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label for="maxEnrollment" class="block text-sm font-medium text-gray-700 mb-2">Max Peserta</label>
                            <input type="number" name="maxEnrollment" id="maxEnrollment" min="1"
                                   value="{{ old('maxEnrollment', $course->maxEnrollment) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>

                    <!-- Public -->
                    <div class="col-span-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="public" id="public" value="1"
                                   {{ $course->public ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Publikasikan Course</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Materials Section -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h2 class="text-xl font-semibold text-gray-800">Materi Course</h2>
                    <button type="button" id="addMaterial"
                        class="text-white hover:bg-[#0f5757] font-medium bg-[#009999] rounded-[10px] border border-gray-300 px-4 py-2 shadow-lg transition">
                        + Tambah Materi
                    </button>
                </div>

                <div id="materialsContainer" class="space-y-6">
                    @foreach ($course->material as $mIndex => $material)
                    <div class="material-item border border-gray-300 rounded-lg p-4 bg-gray-50" data-index="{{ $mIndex }}" data-material-id="{{ $material->id }}">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Materi #{{ $mIndex + 1 }}</h3>
                            <button type="button" class="remove-material text-red-600 hover:text-red-800">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <input type="hidden" name="materials[{{ $mIndex }}][id]" value="{{ $material->id }}">

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Materi *</label>
                            <input type="text" name="materials[{{ $mIndex }}][nama_materi]" required
                                value="{{ old("materials.$mIndex.nama_materi", $material->nama_materi) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Quiz Section -->
                        <div class="mb-4 border-t pt-4">
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-medium text-gray-700">Quiz untuk Materi Ini</label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" class="toggle-quiz w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        data-material-index="{{ $mIndex }}" {{ $material->quiz ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600">Tambahkan Quiz</span>
                                </label>
                            </div>

                            <div class="quiz-section {{ $material->quiz ? '' : 'hidden' }}" data-material-index="{{ $mIndex }}">
                                <div class="bg-white border border-gray-200 rounded-lg p-4 mb-4">
                                    @if($material->quiz)
                                    <input type="hidden" name="materials[{{ $mIndex }}][quiz][id]" value="{{ $material->quiz->id }}">
                                    @endif

                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Quiz *</label>
                                        <input type="text" name="materials[{{ $mIndex }}][quiz][judul_quiz]"
                                            value="{{ old("materials.$mIndex.quiz.judul_quiz", $material->quiz->judul_quiz ?? '') }}"
                                            class="quiz-input w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                            placeholder="Contoh: Quiz Pengenalan Laravel">
                                    </div>

                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Pertanyaan</label>
                                        <div class="questions-container space-y-4" data-material-index="{{ $mIndex }}">
                                            @if($material->quiz)
                                                @foreach ($material->quiz->questions as $qIndex => $question)
                                                <div class="question-item bg-gray-50 border border-gray-200 rounded p-3" data-question-id="{{ $question->id }}">
                                                    <input type="hidden" name="materials[{{ $mIndex }}][quiz][questions][{{ $qIndex }}][id]" value="{{ $question->id }}">

                                                    <div class="flex justify-between items-start mb-2">
                                                        <label class="block text-sm font-medium text-gray-700">Pertanyaan #{{ $qIndex + 1 }}</label>
                                                        <button type="button" class="remove-question text-red-600 hover:text-red-800">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <div class="mb-3">
                                                        <input type="text" name="materials[{{ $mIndex }}][quiz][questions][{{ $qIndex }}][pertanyaan]"
                                                            value="{{ old("materials.$mIndex.quiz.questions.$qIndex.pertanyaan", $question->pertanyaan) }}"
                                                            class="quiz-input w-full px-3 py-2 text-sm border border-gray-300 rounded"
                                                            placeholder="Tulis pertanyaan di sini...">
                                                    </div>

                                                    <div class="space-y-2">
                                                        <p class="text-sm font-medium text-gray-700 mb-1">Pilihan Jawaban:</p>
                                                        <div class="grid grid-cols-1 gap-2">
                                                            @foreach ($question->options as $oIndex => $option)
                                                            <div class="flex items-center gap-2">
                                                                <input type="hidden" name="materials[{{ $mIndex }}][quiz][questions][{{ $qIndex }}][options_ids][]" value="{{ $option->id }}">
                                                                <input type="radio"
                                                                    name="materials[{{ $mIndex }}][quiz][questions][{{ $qIndex }}][correct_option]"
                                                                    value="{{ $oIndex }}"
                                                                    {{ $option->is_correct ? 'checked' : '' }}
                                                                    class="quiz-input">
                                                                <input type="text"
                                                                    name="materials[{{ $mIndex }}][quiz][questions][{{ $qIndex }}][options][]"
                                                                    value="{{ old("materials.$mIndex.quiz.questions.$qIndex.options.$oIndex", $option->teks_pilihan) }}"
                                                                    class="quiz-input flex-1 px-3 py-1.5 text-sm border border-gray-300 rounded"
                                                                    placeholder="Pilihan {{ chr(65 + $oIndex) }}">
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <button type="button" class="add-question mt-3 text-sm bg-gray-100 hover:bg-gray-200 text-gray-600 py-2 px-4 rounded"
                                            data-material-index="{{ $mIndex }}">
                                            + Tambah Pertanyaan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-sm font-medium text-gray-700">Submateri</label>
                                <button type="button" class="add-submaterial text-white hover:bg-[#0f5757] font-small bg-[#009999] rounded-[10px] border border-gray-300 px-3 py-2 shadow-lg transition"
                                    data-material-index="{{ $mIndex }}">
                                    + Tambah Submateri
                                </button>
                            </div>
                            <div class="submaterials-container space-y-3" data-material-index="{{ $mIndex }}">
                                @foreach ($material->submaterial as $sIndex => $sub)
                                <div class="submaterial-item border border-gray-200 rounded-lg p-3 bg-white" data-submaterial-index="{{ $sIndex }}" data-submaterial-id="{{ $sub->id }}">
                                    <input type="hidden" name="materials[{{ $mIndex }}][submaterials][{{ $sIndex }}][id]" value="{{ $sub->id }}">

                                    <div class="flex justify-between items-center mb-3">
                                        <h4 class="text-sm font-semibold text-gray-700">Submateri #{{ $sIndex + 1 }}</h4>
                                        <button type="button" class="remove-submaterial text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Nama Submateri *</label>
                                            <input type="text" name="materials[{{ $mIndex }}][submaterials][{{ $sIndex }}][nama_submateri]" required
                                                value="{{ old("materials.$mIndex.submaterials.$sIndex.nama_submateri", $sub->nama_submateri) }}"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Tipe *</label>
                                            <select name="materials[{{ $mIndex }}][submaterials][{{ $sIndex }}][type]"
                                                    class="sub-type w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                                    data-material-index="{{ $mIndex }}"
                                                    data-submaterial-index="{{ $sIndex }}" required>
                                                <option value="text" {{ $sub->type == 'text' ? 'selected' : '' }}>Text</option>
                                                <option value="video" {{ $sub->type == 'video' ? 'selected' : '' }}>Video (YouTube)</option>
                                                <option value="pdf" {{ $sub->type == 'pdf' ? 'selected' : '' }}>PDF</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="sub-content">
                                        @if ($sub->type === 'text')
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Isi Materi (Teks) *</label>
                                        <input id="trix-{{ $mIndex }}-{{ $sIndex }}" type="hidden"
                                            name="materials[{{ $mIndex }}][submaterials][{{ $sIndex }}][isi_materi]"
                                            value="{{ old("materials.$mIndex.submaterials.$sIndex.isi_materi", $sub->isi_materi) }}"
                                            required>
                                        <trix-editor input="trix-{{ $mIndex }}-{{ $sIndex }}"
                                            class="trix-content border border-gray-300 rounded-lg min-h-[200px]">
                                        </trix-editor>
                                        @elseif ($sub->type === 'video')
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Link YouTube *</label>
                                        <input type="text" name="materials[{{ $mIndex }}][submaterials][{{ $sIndex }}][isi_materi]" required
                                            value="{{ old("materials.$mIndex.submaterials.$sIndex.isi_materi", $sub->isi_materi) }}"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                            placeholder="https://www.youtube.com/watch?v=...">
                                        @elseif ($sub->type === 'pdf')
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Upload File PDF</label>
                                        <p class="text-xs text-gray-500 mb-1">File saat ini: {{ $sub->isi_materi }}</p>
                                        <input type="file" name="materials[{{ $mIndex }}][submaterials][{{ $sIndex }}][isi_materi]"
                                            accept="application/pdf"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                                        <input type="hidden" name="materials[{{ $mIndex }}][submaterials][{{ $sIndex }}][isi_materi]" value="{{ $sub->isi_materi }}">
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-3 pt-6 border-t">
                <a href="{{ route('course.index') }}"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="text-white hover:bg-[#0f5757] font-medium bg-[#009999] rounded-[10px] border border-gray-300 px-6 py-2 shadow-lg transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let materialCount = {{ count($course->material) }};
let submaterialCounters = {};
let questionCounters = {};

// Initialize counters from existing data
@foreach ($course->material as $mIndex => $material)
    submaterialCounters[{{ $mIndex }}] = {{ count($material->submaterial) }};
    questionCounters[{{ $mIndex }}] = {{ $material->quiz ? count($material->quiz->questions) : 0 }};
@endforeach

// Toggle limited course fields
document.getElementById('isLimitedCourse').addEventListener('change', function() {
    const limitedFields = document.getElementById('limitedFields');
    if (this.checked) {
        limitedFields.classList.remove('hidden');
        document.getElementById('start_date').required = true;
        document.getElementById('end_date').required = true;
        document.getElementById('maxEnrollment').required = true;
    } else {
        limitedFields.classList.add('hidden');
        document.getElementById('start_date').required = false;
        document.getElementById('end_date').required = false;
        document.getElementById('maxEnrollment').required = false;
    }
});

// Add Material
document.getElementById('addMaterial').addEventListener('click', function() {
    const container = document.getElementById('materialsContainer');
    const materialIndex = materialCount++;
    submaterialCounters[materialIndex] = 0;
    questionCounters[materialIndex] = 0;

    const materialHtml = `
        <div class="material-item border border-gray-300 rounded-lg p-4 bg-gray-50" data-index="${materialIndex}">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Materi #${materialIndex + 1}</h3>
                <button type="button" class="remove-material text-red-600 hover:text-red-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Materi *</label>
                <input type="text" name="materials[${materialIndex}][nama_materi]" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Contoh: Pengenalan Laravel">
            </div>

            <!-- Quiz Section -->
            <div class="mb-4 border-t pt-4">
                <div class="flex items-center justify-between mb-3">
                    <button type="button" class="quiz-accordion-toggle flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition"
                        data-material-index="${materialIndex}">
                        <svg class="quiz-accordion-icon w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span>Quiz untuk Materi Ini</span>
                    </button>
                    <label class="inline-flex items-center">
                        <input type="checkbox" class="toggle-quiz w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            data-material-index="${materialIndex}">
                        <span class="ml-2 text-sm text-gray-600">Tambahkan Quiz</span>
                    </label>
                </div>

                <div class="quiz-section hidden" data-material-index="${materialIndex}">
                    <div class="quiz-accordion-content hidden">
                    <div class="bg-white border border-gray-200 rounded-lg p-4 mb-4">
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Quiz *</label>
                            <input type="text" name="materials[${materialIndex}][quiz][judul_quiz]"
                                class="quiz-input w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                placeholder="Contoh: Quiz Pengenalan Laravel">
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pertanyaan</label>
                            <div class="questions-container space-y-4" data-material-index="${materialIndex}">
                                <!-- Questions will be added here -->
                            </div>
                            <button type="button" class="add-question mt-3 text-sm bg-gray-100 hover:bg-gray-200 text-gray-600 py-2 px-4 rounded"
                                data-material-index="${materialIndex}">
                                + Tambah Pertanyaan
                            </button>
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-sm font-medium text-gray-700">Submateri</label>
                    <button type="button" class="add-submaterial text-white hover:bg-[#0f5757] font-small bg-[#009999] rounded-[10px] border border-gray-300 px-3 py-2 shadow-lg transition"
                        data-material-index="${materialIndex}">
                        + Tambah Submateri
                    </button>
                </div>
                <div class="submaterials-container space-y-3" data-material-index="${materialIndex}">
                    <!-- Submaterials will be added here -->
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', materialHtml);
});

// Remove Material
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-material')) {
        if (confirm('Yakin ingin menghapus materi ini? Semua submateri dan quiz akan ikut terhapus.')) {
            e.target.closest('.material-item').remove();
        }
    }
});

// Add Submaterial
document.addEventListener('click', function(e) {
    if (e.target.closest('.add-submaterial')) {
        const btn = e.target.closest('.add-submaterial');
        const materialIndex = btn.dataset.materialIndex;
        const submaterialIndex = submaterialCounters[materialIndex]++;
        const container = document.querySelector(`.submaterials-container[data-material-index="${materialIndex}"]`);

        const submaterialHtml = `
            <div class="submaterial-item border border-gray-200 rounded-lg p-3 bg-white" data-submaterial-index="${submaterialIndex}">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-sm font-semibold text-gray-700">Submateri #${submaterialIndex + 1}</h4>
                    <button type="button" class="remove-submaterial text-red-600 hover:text-red-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nama Submateri *</label>
                        <input type="text" name="materials[${materialIndex}][submaterials][${submaterialIndex}][nama_submateri]" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                            placeholder="Contoh: Pengenalan Laravel">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Tipe *</label>
                        <select name="materials[${materialIndex}][submaterials][${submaterialIndex}][type]"
                                class="sub-type w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                data-material-index="${materialIndex}"
                                data-submaterial-index="${submaterialIndex}" required>
                            <option value="">Pilih Tipe</option>
                            <option value="text">Text</option>
                            <option value="video">Video (YouTube)</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                </div>

                <div class="sub-content">
                    <!-- Isi Materi akan muncul di sini berdasarkan tipe -->
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', submaterialHtml);
    }
});

// Handle perubahan tipe submateri
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('sub-type')) {
        const typeSelect = e.target;
        const materialIndex = typeSelect.dataset.materialIndex;
        const submaterialIndex = typeSelect.dataset.submaterialIndex;
        const parent = typeSelect.closest('.submaterial-item').querySelector('.sub-content');
        const submaterialItem = typeSelect.closest('.submaterial-item');
        const existingPdf = submaterialItem.dataset.submaterialId ?
            submaterialItem.querySelector('input[name*="[existing_pdf]"]')?.value : null;

        parent.innerHTML = '';

        if (typeSelect.value === 'text') {
            const trixId = `trix-${materialIndex}-${submaterialIndex}`;
            parent.innerHTML = `
                <label class="block text-xs font-medium text-gray-700 mb-1">Isi Materi (Teks) *</label>
                <input id="${trixId}" type="hidden"
                    name="materials[${materialIndex}][submaterials][${submaterialIndex}][isi_materi]"
                    required>
                <trix-editor input="${trixId}"
                    class="trix-content border border-gray-300 rounded-lg min-h-[200px]"
                    placeholder="Tulis isi materi di sini...">
                </trix-editor>
            `;
        } else if (typeSelect.value === 'video') {
            parent.innerHTML = `
                <label class="block text-xs font-medium text-gray-700 mb-1">Link YouTube *</label>
                <input type="text" name="materials[${materialIndex}][submaterials][${submaterialIndex}][isi_materi]" required
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                    placeholder="https://www.youtube.com/watch?v=...">
            `;
        } else if (typeSelect.value === 'pdf') {
            const existingPdfHtml = existingPdf ? `<p class="text-xs text-gray-500 mb-1">File saat ini: ${existingPdf}</p>` : '';
            parent.innerHTML = `
                <label class="block text-xs font-medium text-gray-700 mb-1">Upload File PDF ${existingPdf ? '' : '*'}</label>
                ${existingPdfHtml}
                <input type="file" name="materials[${materialIndex}][submaterials][${submaterialIndex}][isi_materi]"
                    accept="application/pdf" ${existingPdf ? '' : 'required'}
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                ${existingPdf ? `<input type="hidden" name="materials[${materialIndex}][submaterials][${submaterialIndex}][isi_materi]" value="${existingPdf}">` : ''}
            `;
        }
    }
});

// Trix editor configuration
document.addEventListener('trix-initialize', function(event) {
    const editor = event.target;
    const toolbar = editor.toolbarElement;

    const fileTools = toolbar.querySelector('[data-trix-button-group="file-tools"]');
    if (fileTools) {
        fileTools.style.display = 'none';
    }

    const linkButton = toolbar.querySelector('[data-trix-action="link"]');
    if (linkButton) {
        linkButton.style.display = 'none';
    }
});

document.addEventListener('trix-file-accept', function(event) {
    event.preventDefault();
    alert('Untuk upload file PDF, gunakan opsi "PDF" pada tipe submateri.');
});

// Remove Submaterial
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-submaterial')) {
        if (confirm('Yakin ingin menghapus submateri ini?')) {
            e.target.closest('.submaterial-item').remove();
        }
    }
});

// Toggle quiz section
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('toggle-quiz')) {
        const materialIndex = e.target.dataset.materialIndex;
        const quizSection = document.querySelector(`.quiz-section[data-material-index="${materialIndex}"]`);
        const quizInputs = quizSection.querySelectorAll('.quiz-input');
        const accordionContent = quizSection.querySelector('.quiz-accordion-content');
        const accordionIcon = quizSection.closest('.mb-4').querySelector('.quiz-accordion-icon');

        if (e.target.checked) {
            quizSection.classList.remove('hidden');
            accordionContent.classList.remove('hidden');
            accordionIcon.classList.add('rotate-90');
            // Initialize question counter if not exists
            if (questionCounters[materialIndex] === undefined) {
                questionCounters[materialIndex] = 0;
            }
            // Add first question if container is empty
            const questionsContainer = quizSection.querySelector('.questions-container');
            if (questionsContainer.children.length === 0) {
                addQuestion(materialIndex);
            }
        } else {
            if (confirm('Yakin ingin menghapus quiz ini? Semua pertanyaan akan terhapus.')) {
                quizSection.classList.add('hidden');
                accordionContent.classList.add('hidden');
                accordionIcon.classList.remove('rotate-90');
                quizSection.querySelectorAll('input, textarea').forEach(input => input.value = '');
                quizSection.querySelectorAll('input[type="radio"]').forEach(radio => radio.checked = false);
            } else {
                e.target.checked = true;
            }
        }
    }
});

// Quiz accordion toggle
document.addEventListener('click', function(e) {
    if (e.target.closest('.quiz-accordion-toggle')) {
        const button = e.target.closest('.quiz-accordion-toggle');
        const materialIndex = button.dataset.materialIndex;
        const quizSection = document.querySelector(`.quiz-section[data-material-index="${materialIndex}"]`);
        const accordionContent = quizSection?.querySelector('.quiz-accordion-content');
        const icon = button.querySelector('.quiz-accordion-icon');

        if (accordionContent && !quizSection.classList.contains('hidden')) {
            accordionContent.classList.toggle('hidden');
            icon.classList.toggle('rotate-90');
        }
    }
});

// Add Question
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('add-question')) {
        const materialIndex = e.target.dataset.materialIndex;
        addQuestion(materialIndex);
    }
});

function addQuestion(materialIndex) {
    if (!questionCounters[materialIndex]) {
        questionCounters[materialIndex] = 0;
    }
    const questionIndex = questionCounters[materialIndex]++;
    const container = document.querySelector(`.questions-container[data-material-index="${materialIndex}"]`);

    const questionHtml = `
        <div class="question-item bg-gray-50 border border-gray-200 rounded p-3">
            <div class="flex justify-between items-start mb-2">
                <label class="block text-sm font-medium text-gray-700">Pertanyaan #${questionIndex + 1}</label>
                <button type="button" class="remove-question text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mb-3">
                <input type="text" name="materials[${materialIndex}][quiz][questions][${questionIndex}][pertanyaan]"
                    class="quiz-input w-full px-3 py-2 text-sm border border-gray-300 rounded"
                    placeholder="Tulis pertanyaan di sini...">
            </div>

            <div class="space-y-2">
                <p class="text-sm font-medium text-gray-700 mb-1">Pilihan Jawaban:</p>
                <div class="grid grid-cols-1 gap-2">
                    <div class="flex items-center gap-2">
                        <input type="radio" name="materials[${materialIndex}][quiz][questions][${questionIndex}][correct_option]"
                            value="0" class="quiz-input">
                        <input type="text" name="materials[${materialIndex}][quiz][questions][${questionIndex}][options][]"
                            class="quiz-input flex-1 px-3 py-1.5 text-sm border border-gray-300 rounded"
                            placeholder="Pilihan A">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="radio" name="materials[${materialIndex}][quiz][questions][${questionIndex}][correct_option]"
                            value="1" class="quiz-input">
                        <input type="text" name="materials[${materialIndex}][quiz][questions][${questionIndex}][options][]"
                            class="quiz-input flex-1 px-3 py-1.5 text-sm border border-gray-300 rounded"
                            placeholder="Pilihan B">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="radio" name="materials[${materialIndex}][quiz][questions][${questionIndex}][correct_option]"
                            value="2" class="quiz-input">
                        <input type="text" name="materials[${materialIndex}][quiz][questions][${questionIndex}][options][]"
                            class="quiz-input flex-1 px-3 py-1.5 text-sm border border-gray-300 rounded"
                            placeholder="Pilihan C">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="radio" name="materials[${materialIndex}][quiz][questions][${questionIndex}][correct_option]"
                            value="3" class="quiz-input">
                        <input type="text" name="materials[${materialIndex}][quiz][questions][${questionIndex}][options][]"
                            class="quiz-input flex-1 px-3 py-1.5 text-sm border border-gray-300 rounded"
                            placeholder="Pilihan D">
                    </div>
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', questionHtml);
}

// Remove Question
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-question')) {
        if (confirm('Yakin ingin menghapus pertanyaan ini?')) {
            const questionItem = e.target.closest('.question-item');
            const materialIndex = questionItem.closest('.questions-container').dataset.materialIndex;
            const questionsContainer = questionItem.closest('.questions-container');

            questionItem.remove();

            // If no questions left and quiz is enabled, add one question
            if (questionsContainer.children.length === 0 &&
                !questionsContainer.closest('.quiz-section').classList.contains('hidden')) {
                addQuestion(materialIndex);
            }
        }
    }
});

// Form validation
document.getElementById('courseForm').addEventListener('submit', function(e) {
    const materials = document.querySelectorAll('.material-item');

    if (materials.length === 0) {
        e.preventDefault();
        alert('Minimal harus ada 1 materi!');
        return false;
    }

    let hasSubmaterial = false;
    materials.forEach(material => {
        const submaterials = material.querySelectorAll('.submaterial-item');
        if (submaterials.length > 0) {
            hasSubmaterial = true;
        }
    });

    if (!hasSubmaterial) {
        e.preventDefault();
        alert('Setiap materi harus memiliki minimal 1 submateri!');
        return false;
    }

    // Quiz validation
    let quizError = false;
    materials.forEach(material => {
        const quizToggle = material.querySelector('.toggle-quiz');
        if (quizToggle && quizToggle.checked) {
            const materialIndex = quizToggle.dataset.materialIndex;
            const quizSection = document.querySelector(`.quiz-section[data-material-index="${materialIndex}"]`);

            const judulQuiz = quizSection.querySelector('input[name*="[judul_quiz]"]');
            if (!judulQuiz.value.trim()) {
                e.preventDefault();
                alert('Judul quiz tidak boleh kosong!');
                quizError = true;
                return false;
            }

            const questions = quizSection.querySelectorAll('.question-item');
            if (questions.length === 0) {
                e.preventDefault();
                alert('Quiz harus memiliki minimal 1 pertanyaan!');
                quizError = true;
                return false;
            }

            questions.forEach(question => {
                const correctOption = question.querySelector('input[type="radio"]:checked');
                if (!correctOption) {
                    e.preventDefault();
                    alert('Setiap pertanyaan harus memiliki jawaban yang benar!');
                    quizError = true;
                    return false;
                }
            });
        }
    });

    if (quizError) return false;
});
</script>
@endpush
@endsection
