@extends('layout.sidebar')
@section('title')
    Review Tugas Akhir
@endsection
@section('content')
    <div class="mx-auto py-2">
        <div class="max-w-10xl mx-auto">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-[#009999] px-6 py-4 flex justify-between items-center">
                    <h4 class="text-xl font-bold text-white">Review Tugas Akhir</h4>
                </div>

                {{-- Card Submission --}}
                <div class="px-6 py-4 border-b bg-gradient-to-r from-teal-50 to-cyan-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#009999]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Peserta
                            </p>
                            <p class="font-semibold text-gray-800">{{ $submission->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#009999]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                    </path>
                                </svg>
                                Link Google Drive
                            </p>
                            <a href="{{ $submission->link_google_drive }}" target="_blank"
                                class="text-[#009999] hover:underline font-medium">{{ Str::limit($submission->link_google_drive, 50) }}</a>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#009999]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Status
                            </p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                @if($submission->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($submission->status == 'approved') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($submission->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#009999]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Tanggal Submit
                            </p>
                            <p class="font-semibold text-gray-800">{{ $submission->created_at->format('d-m-Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Form Review --}}
                {{-- Session & Error Alert --}}
                @if(session('success'))
                    <div class="px-6 pt-4">
                        <div class="p-4 mb-4 text-green-800 bg-green-100 border border-green-300 rounded-lg">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="px-6 pt-4">
                        <div class="p-4 mb-4 text-red-800 bg-red-100 border border-red-300 rounded-lg">
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="px-6 pt-4">
                        <div class="p-4 mb-4 text-red-800 bg-red-100 border border-red-300 rounded-lg">
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <form action="{{ route('dosen.course.final_task.approval', [$course->id, $submission->id]) }}" method="POST" class="px-6 py-6">
                    @csrf
                    <input type="hidden" name="final_task_id" value="{{ $submission->final_task_id }}">
                    <input type="hidden" name="final_task_submission_id" value="{{ $submission->id }}">

                    <div class="space-y-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1 h-8 bg-[#009999] rounded-full"></div>
                            <h5 class="text-xl font-bold text-gray-800">Checklist Komponen</h5>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Kurikulum --}}
                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#009999] hover:bg-teal-50 transition-all duration-200 group">
                                <input type="checkbox" name="kurikulum_permen_39_2025" id="kurikulum_permen_39_2025"
                                    value="1"
                                    class="mt-1 w-5 h-5 text-[#009999] bg-white border-gray-300 rounded focus:ring-[#009999] focus:ring-2">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#009999]">Kurikulum
                                        Permen 39 2025</span>
                                    <p class="text-xs text-gray-500 mt-1">Peraturan Menteri terbaru</p>
                                </div>
                            </label>

                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#009999] hover:bg-teal-50 transition-all duration-200 group">
                                <input type="checkbox" name="kurikulum_permen_3_2020" id="kurikulum_permen_3_2020" value="1"
                                    class="mt-1 w-5 h-5 text-[#009999] bg-white border-gray-300 rounded focus:ring-[#009999] focus:ring-2">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#009999]">Kurikulum
                                        Permen 3 2020</span>
                                    <p class="text-xs text-gray-500 mt-1">Standar nasional pendidikan tinggi</p>
                                </div>
                            </label>

                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#009999] hover:bg-teal-50 transition-all duration-200 group">
                                <input type="checkbox" name="cpl_prodi" id="cpl_prodi" value="1"
                                    class="mt-1 w-5 h-5 text-[#009999] bg-white border-gray-300 rounded focus:ring-[#009999] focus:ring-2">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#009999]">CPL
                                        Prodi</span>
                                    <p class="text-xs text-gray-500 mt-1">Capaian Pembelajaran Lulusan</p>
                                </div>
                            </label>

                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#009999] hover:bg-teal-50 transition-all duration-200 group">
                                <input type="checkbox" name="distribusi_mata_kuliah_dan_highlight"
                                    id="distribusi_mata_kuliah_dan_highlight" value="1"
                                    class="mt-1 w-5 h-5 text-[#009999] bg-white border-gray-300 rounded focus:ring-[#009999] focus:ring-2">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#009999]">Distribusi
                                        Mata Kuliah dan Highlight</span>
                                    <p class="text-xs text-gray-500 mt-1">Pemetaan dan penekanan materi</p>
                                </div>
                            </label>

                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#009999] hover:bg-teal-50 transition-all duration-200 group">
                                <input type="checkbox" name="cpl_prodi_yang_dibebankan_pada_mata_kuliah"
                                    id="cpl_prodi_yang_dibebankan_pada_mata_kuliah" value="1"
                                    class="mt-1 w-5 h-5 text-[#009999] bg-white border-gray-300 rounded focus:ring-[#009999] focus:ring-2">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#009999]">CPL Prodi
                                        pada Mata Kuliah</span>
                                    <p class="text-xs text-gray-500 mt-1">CPL yang dibebankan</p>
                                </div>
                            </label>

                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#009999] hover:bg-teal-50 transition-all duration-200 group">
                                <input type="checkbox" name="matriks_kajian" id="matriks_kajian" value="1"
                                    class="mt-1 w-5 h-5 text-[#009999] bg-white border-gray-300 rounded focus:ring-[#009999] focus:ring-2">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#009999]">Matriks
                                        Kajian</span>
                                    <p class="text-xs text-gray-500 mt-1">Analisis kajian pembelajaran</p>
                                </div>
                            </label>

                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#009999] hover:bg-teal-50 transition-all duration-200 group">
                                <input type="checkbox" name="tujuan_belajar" id="tujuan_belajar" value="1"
                                    class="mt-1 w-5 h-5 text-[#009999] bg-white border-gray-300 rounded focus:ring-[#009999] focus:ring-2">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#009999]">Tujuan
                                        Belajar</span>
                                    <p class="text-xs text-gray-500 mt-1">Learning objectives</p>
                                </div>
                            </label>

                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#009999] hover:bg-teal-50 transition-all duration-200 group">
                                <input type="checkbox" name="peta_kompentensi" id="peta_kompentensi" value="1"
                                    class="mt-1 w-5 h-5 text-[#009999] bg-white border-gray-300 rounded focus:ring-[#009999] focus:ring-2">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#009999]">Peta
                                        Kompetensi</span>
                                    <p class="text-xs text-gray-500 mt-1">Mapping kompetensi mahasiswa</p>
                                </div>
                            </label>

                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#009999] hover:bg-teal-50 transition-all duration-200 group">
                                <input type="checkbox" name="perhitungan_sks" id="perhitungan_sks" value="1"
                                    class="mt-1 w-5 h-5 text-[#009999] bg-white border-gray-300 rounded focus:ring-[#009999] focus:ring-2">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#009999]">Perhitungan
                                        SKS</span>
                                    <p class="text-xs text-gray-500 mt-1">Sistem kredit semester</p>
                                </div>
                            </label>

                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#009999] hover:bg-teal-50 transition-all duration-200 group">
                                <input type="checkbox" name="scl" id="scl" value="1"
                                    class="mt-1 w-5 h-5 text-[#009999] bg-white border-gray-300 rounded focus:ring-[#009999] focus:ring-2">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#009999]">SCL</span>
                                    <p class="text-xs text-gray-500 mt-1">Student Centered Learning</p>
                                </div>
                            </label>

                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#009999] hover:bg-teal-50 transition-all duration-200 group">
                                <input type="checkbox" name="metode_case_study_dan_team_based_project"
                                    id="metode_case_study_dan_team_based_project" value="1"
                                    class="mt-1 w-5 h-5 text-[#009999] bg-white border-gray-300 rounded focus:ring-[#009999] focus:ring-2">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#009999]">Metode Case
                                        Study & TBP</span>
                                    <p class="text-xs text-gray-500 mt-1">Team Based Project</p>
                                </div>
                            </label>

                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#009999] hover:bg-teal-50 transition-all duration-200 group">
                                <input type="checkbox" name="rps" id="rps" value="1"
                                    class="mt-1 w-5 h-5 text-[#009999] bg-white border-gray-300 rounded focus:ring-[#009999] focus:ring-2">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#009999]">RPS</span>
                                    <p class="text-xs text-gray-500 mt-1">Rencana Pembelajaran Semester</p>
                                </div>
                            </label>

                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#009999] hover:bg-teal-50 transition-all duration-200 group">
                                <input type="checkbox" name="rancangan_penilaian_dalam_1_semester"
                                    id="rancangan_penilaian_dalam_1_semester" value="1"
                                    class="mt-1 w-5 h-5 text-[#009999] bg-white border-gray-300 rounded focus:ring-[#009999] focus:ring-2">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#009999]">Rancangan
                                        Penilaian 1 Semester</span>
                                    <p class="text-xs text-gray-500 mt-1">Desain evaluasi semesteran</p>
                                </div>
                            </label>

                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#009999] hover:bg-teal-50 transition-all duration-200 group">
                                <input type="checkbox" name="rancangan_tugas_1_pertemuan" id="rancangan_tugas_1_pertemuan"
                                    value="1"
                                    class="mt-1 w-5 h-5 text-[#009999] bg-white border-gray-300 rounded focus:ring-[#009999] focus:ring-2">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#009999]">Rancangan
                                        Tugas 1 Pertemuan</span>
                                    <p class="text-xs text-gray-500 mt-1">Desain tugas per pertemuan</p>
                                </div>
                            </label>

                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#009999] hover:bg-teal-50 transition-all duration-200 group">
                                <input type="checkbox" name="instrumen_penilaian_hasil_belajar"
                                    id="instrumen_penilaian_hasil_belajar" value="1"
                                    class="mt-1 w-5 h-5 text-[#009999] bg-white border-gray-300 rounded focus:ring-[#009999] focus:ring-2">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#009999]">Instrumen
                                        Penilaian Hasil Belajar</span>
                                    <p class="text-xs text-gray-500 mt-1">Tools assessment</p>
                                </div>
                            </label>

                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#009999] hover:bg-teal-50 transition-all duration-200 group">
                                <input type="checkbox" name="rubrik_penilaian" id="rubrik_penilaian" value="1"
                                    class="mt-1 w-5 h-5 text-[#009999] bg-white border-gray-300 rounded focus:ring-[#009999] focus:ring-2">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#009999]">Rubrik
                                        Penilaian</span>
                                    <p class="text-xs text-gray-500 mt-1">Kriteria dan standar penilaian</p>
                                </div>
                            </label>

                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#009999] hover:bg-teal-50 transition-all duration-200 group">
                                <input type="checkbox" name="rps_microteaching" id="rps_microteaching" value="1"
                                    class="mt-1 w-5 h-5 text-[#009999] bg-white border-gray-300 rounded focus:ring-[#009999] focus:ring-2">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#009999]">RPS
                                        Microteaching</span>
                                    <p class="text-xs text-gray-500 mt-1">Rencana pembelajaran mikro</p>
                                </div>
                            </label>

                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#009999] hover:bg-teal-50 transition-all duration-200 group">
                                <input type="checkbox" name="materi_microteaching" id="materi_microteaching" value="1"
                                    class="mt-1 w-5 h-5 text-[#009999] bg-white border-gray-300 rounded focus:ring-[#009999] focus:ring-2">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#009999]">Materi
                                        Microteaching</span>
                                    <p class="text-xs text-gray-500 mt-1">Bahan ajar microteaching</p>
                                </div>
                            </label>

                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#009999] hover:bg-teal-50 transition-all duration-200 group">
                                <input type="checkbox" name="penilaian_microteaching" id="penilaian_microteaching" value="1"
                                    class="mt-1 w-5 h-5 text-[#009999] bg-white border-gray-300 rounded focus:ring-[#009999] focus:ring-2">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-800 group-hover:text-[#009999]">Penilaian
                                        Microteaching</span>
                                    <p class="text-xs text-gray-500 mt-1">Evaluasi microteaching</p>
                                </div>
                            </label>
                        </div>

                        {{-- Status Dropdown --}}
                        <div
                            class="mt-8 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border-2 border-blue-200">
                            <label for="status" class="flex items-center gap-2 text-sm font-semibold text-gray-800 mb-3">
                                <svg class="w-5 h-5 text-[#009999]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Status Review <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#009999] focus:border-[#009999] transition-all bg-white font-medium text-gray-700">
                                <option value="" disabled selected>-- Pilih Status Review --</option>
                                <option value="approved" class="text-green-600 font-semibold">✓ Approved (Disetujui)
                                </option>
                                <option value="rejected" class="text-red-600 font-semibold">✗ Rejected (Ditolak)</option>
                            </select>
                            <p class="text-xs text-gray-600 mt-2">Pilih status untuk menentukan apakah tugas akhir disetujui
                                atau ditolak</p>
                        </div>

                        {{-- Catatan --}}
                        <div class="mt-6 p-6 bg-gray-50 rounded-lg border border-gray-200">
                            <label for="catatan" class="flex items-center gap-2 text-sm font-semibold text-gray-800 mb-3">
                                <svg class="w-5 h-5 text-[#009999]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Catatan untuk Peserta (Opsional)
                            </label>
                            <textarea name="catatan" id="catatan" rows="5"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#009999] focus:border-[#009999] transition-all"
                                placeholder="Berikan catatan, saran, atau feedback untuk peserta..."></textarea>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('dosen.course.final_task') }}"
                                class="px-8 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batal
                            </a>
                            <button type="submit"
                                class="px-8 py-3 bg-[#009999] text-white rounded-lg font-semibold hover:bg-[#008080] shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Review
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
