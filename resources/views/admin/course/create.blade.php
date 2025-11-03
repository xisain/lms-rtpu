@extends('layout.sidebar')
{{-- create.blade.php --}}
@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Buat Course Baru</h1>
            <p class="text-gray-600 mt-2">Isi form di bawah untuk membuat course baru beserta materi dan submateri</p>
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
        <!-- Form -->
        <form id="courseForm" action="{{ route('course.store') }}" method="POST" class="bg-white shadow-md rounded-lg p-6" enctype="multipart/form-data">
            @csrf

            <!-- Course Information -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Course</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Category -->
                    <div class="col-span-2">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                        <select name="category_id" id="category_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Course -->
                    <div class="col-span-2">
                        <label for="nama_course" class="block text-sm font-medium text-gray-700 mb-2">Nama Course
                            *</label>
                        <input type="text" name="nama_course" id="nama_course" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: Laravel Advanced Course">
                        @error('nama_course')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi *
                        </label>
                        <textarea name="description" id="description" rows="4" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Jelaskan tentang course ini..."></textarea>
                        <div class="flex justify-between items-center mt-1">
                            <div>
                                @error('description')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                            <p id="wordCount" class="text-sm text-gray-600">0 / 100 kata</p>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <label for="image_link" class="block text-sm font-medium text-gray-700 mb-2">Gambar Course
                            (opsional)</label>
                        <input type="file" name="image_link" id="image_link" accept="image/*"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('image_link')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">Teacher *</label>
                        <select name="teacher_id" id="teacher_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Teacher/PIC</option>
                            @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Is Limited Course -->
                    <div class="col-span-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="isLimitedCourse" id="isLimitedCourse" value="1"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Course dengan Batasan Waktu &
                                Kuota</span>
                        </label>
                    </div>

                    <!-- Limited Course Fields -->
                    <div id="limitedFields" class="col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4 hidden">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                                Mulai</label>
                            <input type="date" name="start_date" id="start_date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                                Selesai</label>
                            <input type="date" name="end_date" id="end_date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="maxEnrollment" class="block text-sm font-medium text-gray-700 mb-2">Max
                                Peserta</label>
                            <input type="number" name="maxEnrollment" id="maxEnrollment" min="1"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="50">
                        </div>
                    </div>

                    <!-- Public -->
                    <div class="col-span-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="public" id="public" value="1"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Publikasikan Course</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Materials Section -->
            <div class="mb-8 p-2 pt-6 rounded-lg border border-gray-300">
                <div class="flex justify-between items-center mb-4 pb-2">
                    <h2 class="text-xl font-semibold text-gray-800">Materi Course</h2>
                    <button type="button" id="addMaterial"
                        class="text-white hover:bg-[#0f5757] font-medium bg-[#009999] max-w-lg rounded-[10px] border border-gray-300 p-2 shadow-xs transition">
                        + Tambah Materi
                    </button>
                </div>

                <div id="materialsContainer" class="space-y-6">
                    <!-- Material items will be added here dynamically -->
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-3 pt-6">
                <a href="{{ route('course.index') }}"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="text-white hover:bg-[#0f5757] font-medium bg-[#009999]  max-w-lg rounded-[10px] border border-gray-300 p-2 shadow-lg transition">
                    Simpan Course
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let materialCount = 0;
let submaterialCounters = {};

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
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Contoh: Pengenalan Laravel">
            </div>

            <!-- Quiz Section -->
            <div class="mb-6 pt-4 pb-1 px-2 rounded-lg border border-gray-200 bg-white">
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm font-medium text-gray-700">Quiz untuk Materi Ini</label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" class="toggle-quiz w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            data-material-index="${materialIndex}">
                        <span class="ml-2 text-sm text-gray-600">Tambahkan Quiz</span>
                    </label>
                </div>

                <div class="quiz-section hidden" data-material-index="${materialIndex}">
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
                            <button type="button" class="add-question mt-3 text-base bg-[#009999] hover:bg-[#0f5757] text-white py-2 px-4 rounded-lg"
                                data-material-index="${materialIndex}">
                                + Tambah Pertanyaan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-sm font-medium text-gray-700">Submateri</label>
                    <button type="button" class="add-submaterial text-white hover:bg-[#0f5757] font-small bg-[#009999]  max-w-lg rounded-[10px] border border-gray-300 p-2 shadow-lg transition"
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
        e.target.closest('.material-item').remove();
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
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: Pengenalan Laravel">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Tipe *</label>
                        <select name="materials[${materialIndex}][submaterials][${submaterialIndex}][type]"
                                class="sub-type w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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

        parent.innerHTML = '';

        if (typeSelect.value === 'text') {
            // Rich Text Editor
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
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="https://www.youtube.com/watch?v=...">
            `;
        } else if (typeSelect.value === 'pdf') {
            parent.innerHTML = `
                <label class="block text-xs font-medium text-gray-700 mb-1">Upload File PDF *</label>
                <input type="file" name="materials[${materialIndex}][submaterials][${submaterialIndex}][isi_materi]"
                    accept="application/pdf" required
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            `;
        }
    }
});

// Optional: Custom styling dan konfigurasi Trix
document.addEventListener('trix-initialize', function(event) {
    const editor = event.target;

    // Konfigurasi toolbar jika perlu
    // Contoh: menghilangkan button tertentu
    const toolbar = editor.toolbarElement;
    // Hide file attachment button (karena kita handle PDF terpisah)
    const fileTools = toolbar.querySelector('[data-trix-button-group="file-tools"]',);
    if (fileTools) {
        fileTools.style.display = 'none';
    }
    const linkButton = toolbar.querySelector('[data-trix-action="link"]');
    if (linkButton) {
        linkButton.style.display = 'none';
    }
});

// Prevent file attachment di Trix (karena kita handle upload terpisah)
document.addEventListener('trix-file-accept', function(event) {
    event.preventDefault();
    Swal.fire({
        title: 'Perhatian!',
        text: 'Untuk upload file PDF, gunakan opsi "PDF" pada tipe submateri.',
        icon: 'info',
        confirmButtonText: 'OK',
        confirmButtonColor: '#009999'
    });
});

// Remove Submaterial
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-submaterial')) {
        e.target.closest('.submaterial-item').remove();
    }
});

// Form submission validation
document.getElementById('courseForm').addEventListener('submit', function(e) {
    const materials = document.querySelectorAll('.material-item');
    if (materials.length === 0) {
        e.preventDefault();
        Swal.fire({
            title: 'Gagal!',
            text: 'Minimal harus ada 1 materi!',
            icon: 'warning',
            confirmButtonText: 'OK',
            confirmButtonColor: '#009999'
        });
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
        Swal.fire({
            title: 'Gagal!',
            text: 'Setiap materi harus memiliki minimal 1 submateri!',
            icon: 'warning',
            confirmButtonText: 'OK',
            confirmButtonColor: '#009999'
        });
        return false;
    }
});

// Quiz functionality
let questionCounters = {};

// Toggle quiz section
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('toggle-quiz')) {
        const materialIndex = e.target.dataset.materialIndex;
        const quizSection = document.querySelector(`.quiz-section[data-material-index="${materialIndex}"]`);
        const quizInputs = quizSection.querySelectorAll('.quiz-input');

        if (e.target.checked) {
            quizSection.classList.remove('hidden');
            // HAPUS required = true karena quiz opsional
            if (!questionCounters[materialIndex]) {
                questionCounters[materialIndex] = 0;
                addQuestion(materialIndex);
            }
        } else {
            quizSection.classList.add('hidden');
            // Clear quiz data ketika di-uncheck
            quizSection.querySelectorAll('input, textarea').forEach(input => input.value = '');
            quizSection.querySelectorAll('input[type="radio"]').forEach(radio => radio.checked = false);
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
});

// maksimal teks deskripsi
document.addEventListener('DOMContentLoaded', function() {
    const descriptionField = document.getElementById('description');
    const wordCountDisplay = document.getElementById('wordCount');
    const maxWords = 100;

    function countWords(text) {
        // Trim dan split berdasarkan whitespace, lalu filter empty strings
        return text.trim().split(/\s+/).filter(word => word.length > 0).length;
    }

    function updateWordCount() {
        const text = descriptionField.value;
        const wordCount = countWords(text);
        
        wordCountDisplay.textContent = `${wordCount} / ${maxWords} kata`;
        
        if (wordCount > maxWords) {
            wordCountDisplay.classList.remove('text-gray-600');
            wordCountDisplay.classList.add('text-red-600', 'font-semibold');
        } else if (wordCount > maxWords * 0.9) {
            // Warning saat mendekati limit (90%)
            wordCountDisplay.classList.remove('text-gray-600', 'text-red-600');
            wordCountDisplay.classList.add('text-orange-500');
        } else {
            wordCountDisplay.classList.remove('text-red-600', 'text-orange-500', 'font-semibold');
            wordCountDisplay.classList.add('text-gray-600');
        }
    }

    // Update word count saat mengetik
    descriptionField.addEventListener('input', updateWordCount);

    // Validasi saat form submit
    document.getElementById('courseForm').addEventListener('submit', function(e) {
        const wordCount = countWords(descriptionField.value);
        
        if (wordCount > maxWords) {
            e.preventDefault();
            e.stopImmediatePropagation();
            
            Swal.fire({
                title: 'Deskripsi Terlalu Panjang!',
                text: `Deskripsi Anda ${wordCount} kata. Maksimal hanya ${maxWords} kata. Silakan persingkat deskripsi Anda.`,
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#009999'
            });
            
            // Scroll ke field description
            descriptionField.focus();
            descriptionField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            return false;
        }
    }, true); // Use capture phase untuk prioritas
});

// Add validation for quiz
document.getElementById('courseForm').addEventListener('submit', function(e) {
    const materials = document.querySelectorAll('.material-item');
    if (materials.length === 0) {
        e.preventDefault();
        Swal.fire({
            title: 'Gagal!',
            text: 'Minimal harus ada 1 materi!',
            icon: 'warning',
            confirmButtonText: 'OK',
            confirmButtonColor: '#009999'
        });
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
        Swal.fire({
            title: 'Gagal!',
            text: 'Setiap materi harus memiliki minimal 1 submateri!',
            icon: 'warning',
            confirmButtonText: 'OK',
            confirmButtonColor: '#009999'
        });
        return false;
    }

    // Validasi quiz HANYA jika quiz di-enable
    let quizError = false;
    let quizErrorMessage = '';

    materials.forEach(material => {
        const quizToggle = material.querySelector('.toggle-quiz');
        if (quizToggle && quizToggle.checked) {
            const materialIndex = quizToggle.dataset.materialIndex;
            const quizSection = document.querySelector(`.quiz-section[data-material-index="${materialIndex}"]`);

            // Cek judul quiz
            const judulQuiz = quizSection.querySelector('input[name*="[judul_quiz]"]');
            if (!judulQuiz.value.trim()) {
                quizError = true;
                quizErrorMessage = 'Judul quiz tidak boleh kosong!';
                return false;
            }

            const questions = material.querySelectorAll('.question-item');
            if (questions.length === 0) {
                quizError = true;
                quizErrorMessage = 'Quiz harus memiliki minimal 1 pertanyaan!';
                return false;
            }

            questions.forEach(question => {
                const correctOption = question.querySelector('input[type="radio"]:checked');
                if (!correctOption) {
                    quizError = true;
                    quizErrorMessage = 'Setiap pertanyaan harus memiliki jawaban yang benar!';
                    return false;
                }
            });
        }
    });

    if (quizError) {
        e.preventDefault();
        Swal.fire({
            title: 'Gagal!',
            text: quizErrorMessage,
            icon: 'warning',
            confirmButtonText: 'OK',
            confirmButtonColor: '#009999'
        });
        return false;
    }
});

// Add first material by default
document.getElementById('addMaterial').click();
</script>
@endpush
@endsection
