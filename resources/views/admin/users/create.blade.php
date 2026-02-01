@extends('layout.sidebar')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-[#009999]">
            <h4 class="text-xl font-bold text-white">
                Tambah Banyak User
            </h4>
        </div>
        <div class="p-6">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.user.bulk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- Ambil Instansi dlu lalu cek apakah ada categori dengan instansi id terkait lalu munculkan course dalam kategori terkait --}}
                <div class="mb-6">
                    <label for="instansi_id" class="block text-sm font-medium text-gray-700 mb-2">Instansi<span class="text-red-500">*</span></label>
                    <select name="instansi_id" id="instansi_id" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009999] focus:border-transparent transition">
                        <option value="">-- Instansi --</option>
                        @foreach($instansi as $i)
                            <option value="{{ $i->id }}" {{ old('instansi_id') == $i->id ? 'selected' : '' }}>
                                {{ $i->nama }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-sm text-gray-500 text-body">
                        Jika Instansi tidak tersedia harap buat terlebih dahulu <a href="/admin/instansi/create/" class="font-medium  text-blue-600 text-fg-brand underline hover:no-underline">disini</a>
                    </p>

                </div>
                {{-- Isi Course --}}
                <div class="mb-6">
                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Course <span class="text-red-500">*</span>
                    </label>
                    <select name="course_id" id="course_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009999] focus:border-transparent transition disabled:bg-gray-400 disabled:cursor-not-allowed"required>
                        <option value="">-</option>
                    </select>
                    <p class="mt-2 text-sm text-gray-500 text-body">
                        Jika Course Tidak Tersedia Harap buat <a href="/admin/category/create/" class="font-medium  text-blue-600 text-fg-brand underline hover:no-underline">Kategori</a> dengan Instansi Terkait Lalu buat Course dengan Kategori dengan Instansi
                    </p>
                </div>
                {{-- default password --}}
                <div class="mb-6">
                    <label for="default_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Default Password <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        name="default_password"
                        id="default_password"

                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009999] focus:border-transparent transition">
                    <p class="mt-2 text-sm text-gray-500">
                        Password ini akan digunakan jika kolom password di CSV kosong
                    </p>
                </div>


                <!-- Upload CSV -->
                <div class="mb-6">
                    <label for="csv_file" class="block text-sm font-medium text-gray-700 mb-2">
                        Upload File CSV <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="csv_file" id="csv_file" accept=".csv" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009999] focus:border-transparent transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#009999] file:text-white hover:file:bg-[#008080] file:cursor-pointer">
                    <p class="mt-2 text-sm text-gray-500">
                        Format CSV: email, name, password (optional)
                    </p>
                </div>

                <!-- Instruksi Format CSV -->
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-5">
                    <h5 class="font-semibold text-blue-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        Format File CSV
                    </h5>
                    <div class="text-sm text-blue-700 space-y-3">
                        <p>File CSV harus memiliki header dengan kolom berikut:</p>
                        <ul class="list-disc list-inside ml-4 space-y-1">
                            <li><strong>email</strong> (wajib): Alamat email user</li>
                            <li><strong>name</strong> (wajib): Nama lengkap user</li>
                            <li><strong>password</strong> (opsional): Password user (jika kosong, akan digenerate otomatis)</li>
                        </ul>
                        <p class="mt-4"><strong>Contoh format CSV:</strong></p>
                        <div class="bg-white p-4 rounded-lg border border-blue-300 font-mono text-xs overflow-x-auto">
                            <div>email,name,password</div>
                            <div>user1@example.com,John Doe,password123</div>
                            <div>user2@example.com,Jane Smith,</div>
                            <div>user3@example.com,Bob Johnson,mypassword</div>
                        </div>
                    </div>
                </div>

                <!-- Download Template -->
                <div class="mb-6">
                    <a href="{{ route('admin.user.bulk.template') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download Template CSV
                    </a>
                </div>

                <!-- Checkbox Options -->
                <div class="mb-8 pt-4 border-t border-gray-200">
                    <div class="flex items-center">
                        <input type="checkbox" name="send_email" id="send_email" value="1"
                            class="w-4 h-4 text-[#009999] border-gray-300 rounded focus:ring-[#009999] cursor-pointer"
                            {{ old('send_email') ? 'checked' : '' }}>
                        <label for="send_email" class="ml-3 text-sm font-medium text-gray-700 cursor-pointer">
                            Kirim email notifikasi ke user setelah berhasil dibuat
                        </label>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                    <button type="submit"
                        class="px-6 py-2.5 bg-[#009999] hover:bg-[#008080] text-white font-medium rounded-lg transition shadow-sm hover:shadow-md">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Upload dan Simpan
                        </span>
                    </button>
                    <a href="{{ route('admin.user.index') }}"
                        class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition shadow-sm hover:shadow-md">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
    <script>
document.getElementById('instansi_id').addEventListener('change', function () {
    const instansiId = this.value;
    const courseSelect = document.getElementById('course_id');

    courseSelect.innerHTML = '<option value="" class="disabled:bg-gray-200">Loading...</option>';

    if (!instansiId) {
        courseSelect.innerHTML = '<option value="" disabled>-- Pilih Course --</option>';
        return;
    }

    fetch(`/course/by-instansi/${instansiId}`)
        .then(res => {
            if (!res.ok) throw new Error('Unauthorized');
            return res.json();
        })
        .then(data => {
            courseSelect.innerHTML = '<option value="">-- Pilih Course --</option>';

            if (data.length === 0) {
                courseSelect.innerHTML += '<option value="">Tidak ada course</option>';
            }

            data.forEach(course => {
                courseSelect.innerHTML += `
                    <option value="${course.id}">${course.nama_course}</option>
                `;
            });
        })
        .catch(() => {
            courseSelect.innerHTML = '<option value="">Tidak tersedia</option>';
        });
});
</script>
@endpush
@endsection
