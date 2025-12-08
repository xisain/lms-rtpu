@extends('layout.navbar')

@section('title', 'Edit Profile')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Profile</h1>

            @php
                $gradientClass = $user->role->name === 'dosen'
                    ? 'from-green-500 to-cyan-500'
                    : 'from-blue-500 to-purple-600';
            @endphp

            <center>
                <div class="w-30 h-30 mb-8 rounded-full flex items-center justify-center text-white text-2xl font-bold bg-gradient-to-br {{ $gradientClass }}">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            </center>

            {{-- Alert Message --}}
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error Message --}}
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                    <ul class="list-disc ml-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- NAME --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400
                        focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                        required>
                </div>

                {{-- EMAIL --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Email</label>
                    <input id="email" name="email" type="email" required autocomplete="email" value="{{ old('email', $user->email) }}""
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400
                        focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm @error('email') border-red-500 @enderror">
                </div>

                @if (auth()->user()->roles_id == 2 ) 
                <div>
                    <label class="block text-sm font-medium mb-1">Role</label>
                    <select name="roles_id"
                            class="w-full border border-gray-300 rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($role as $r)
                            <option value="{{ $r->id }}"
                                {{ old('roles_id', $user->roles_id) == $r->id ? 'selected' : '' }}>
                                {{ ucfirst($r->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Instansi
                    </label>
                    <select name="instansi_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="">-- Pilih Instansi --</option>
                        @foreach ($instansi as $item)
                            <option value="{{ $item->id }}" {{ old('instansi_id', $user->instansi_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                        <p class="text-xs text-blue-600 mt-1 cursor-pointer hover:underline"
                            onclick="scrollToInstansiForm()">
                            Tidak ada instansi? Tambahkan jika tidak ada
                        </p>
                </div>
                


                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Jurusan
                    </label>
                    <select name="jurusan_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach ($jurusan as $item)
                            <option value="{{ $item->id }}" {{ old('jurusan_id', $user->jurusan_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-blue-600 mt-1 cursor-pointer hover:underline"
                        onclick="scrollToJurusanForm()">
                        Tidak ada jurusan? Tambahkan jika tidak ada
                    </p>
                </div>


                <h2 class="text-lg font-semibold text-gray-800 mb-2">Ubah Password (opsional)</h2>

                {{-- PASSWORD --}}
                <div class="mt-1 relative">
                    <label class="block text-gray-700 font-medium mb-1">Password Baru</label>
                    <input id="password" name="password" type="password" autocomplete="new-password"
                        class="appearance-none block w-full pr-12 px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400
                        focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm @error('password') border-red-500 @enderror">
                    <button type="button" id="togglePassword"
                            class="absolute mt-1 inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 text-gray-500">
                            <i id="eyeIcon" class="fa-solid fa-eye"></i>
                    </button>
                    <p class="text-sm text-gray-500 mt-1">Isi jika ingin mengganti password.</p>
                </div>

                {{-- CONFIRM PASSWORD --}}
                <div class="mt-1 relative">
                    <label class="block text-gray-700 font-medium mb-1">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        class="appearance-none block w-full pr-12 px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400
                        focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm @error('password') border-red-500 @enderror">
                    <button type="button" id="toggleConfirmPassword"
                            class="absolute mt-7 inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 text-gray-500">
                            <i id="eyeIconConfirm" class="fa-solid fa-eye"></i>
                    </button>
                </div>


                <div class="flex justify-end space-x-3 pt-4">
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- FORM INSTANSI (OUTSIDE PROFILE FORM) --}}
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <div onclick="toggleInstansiForm()" class="cursor-pointer mb-1 p-4 text-dark rounded-lg flex justify-between items-center hover:bg-gray-50">
                <span class="text-sm font-medium">Tambah Instansi Baru Jika Tidak Ada</span>
                <i id="chevronIconInstansi" class="fa-solid fa-chevron-down transition-transform duration-300"></i>
            </div>

            <div id="form-instansi" class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6">
                    <form id="instansiForm" action="{{ route('profile.instansi.store') }}" method="POST" class="space-y-6" onsubmit="handleInstansiSubmit(event)">
                        @csrf
                        {{-- Nama Instansi --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Instansi <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                name="nama"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                placeholder="Masukkan nama instansi"
                                required>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email"
                                name="email"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                placeholder="Masukkan email instansi">
                        </div>

                        {{-- Alamat --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat
                            </label>
                            <textarea name="alamat"
                                    rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                    placeholder="Masukkan alamat instansi"></textarea>
                        </div>

                        {{-- Telepon --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Telepon
                            </label>
                            <input type="text"
                                name="telepon"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                placeholder="Masukkan nomor telepon instansi">
                        </div>

                        {{-- Tombol --}}
                        <div class="flex justify-end pt-4 border-t border-gray-200">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-medium transition duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                </svg>
                                Simpan Instansi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- FORM JURUSAN (OUTSIDE PROFILE FORM) --}}
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <div onclick="toggleJurusanForm()" class="cursor-pointer mb-1 p-4 text-dark rounded-lg flex justify-between items-center hover:bg-gray-50">
                <span class="text-sm font-medium">Tambah Jurusan Baru Jika Tidak Ada</span>
                <i id="chevronIconJurusan" class="fa-solid fa-chevron-down transition-transform duration-300"></i>
            </div>

            <div id="form-jurusan" class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6">
                    <form id="jurusanForm" action="{{ route('profile.jurusan.store') }}" method="POST" class="space-y-6" onsubmit="handleJurusanSubmit(event)">
                        @csrf

                        <!-- Kode Jurusan -->
                        <div>
                            <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">
                                Kode Jurusan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                id="kode" 
                                name="kode"
                                placeholder="Masukkan kode jurusan"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        </div>

                        <!-- Nama Jurusan -->
                        <div>
                            <label for="jurusan_nama" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Jurusan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                id="jurusan_nama" 
                                name="nama"
                                placeholder="Masukkan nama jurusan"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end pt-4 border-t border-gray-200">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-medium transition duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                </svg>
                                Simpan Jurusan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggles = [
        { button: 'togglePassword', input: 'password', icon: 'eyeIcon' },
        { button: 'toggleConfirmPassword', input: 'password_confirmation', icon: 'eyeIconConfirm' }
    ];

    toggles.forEach(({ button, input, icon }) => {
        const btn = document.getElementById(button);
        const field = document.getElementById(input);
        const eye = document.getElementById(icon);

        if (btn && field && eye) {
            btn.addEventListener('click', function () {
                const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
                field.setAttribute('type', type);
                eye.classList.toggle('fa-eye');
                eye.classList.toggle('fa-eye-slash');
            });
        }
    });
});

function toggleInstansiForm() {
    const form = document.getElementById("form-instansi");
    const icon = document.getElementById("chevronIconInstansi");

    if (form.style.maxHeight && form.style.maxHeight !== "0px") {
        form.style.maxHeight = "0px";
        icon.style.transform = "rotate(0deg)";
    } else {
        form.style.maxHeight = form.scrollHeight + "px";
        icon.style.transform = "rotate(180deg)";
    }
}

function toggleJurusanForm() {
    const form = document.getElementById("form-jurusan");
    const icon = document.getElementById("chevronIconJurusan");

    if (form.style.maxHeight && form.style.maxHeight !== "0px") {
        form.style.maxHeight = "0px";
        icon.style.transform = "rotate(0deg)";
    } else {
        form.style.maxHeight = form.scrollHeight + "px";
        icon.style.transform = "rotate(180deg)";
    }
}

// Handle Instansi Form Submit
function handleInstansiSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    console.log('Submitting instansi form:', form.action);
    console.log('CSRF Token present:', formData.has('_token'));

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Instansi response status:', response.status);
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Server error: ' + response.status);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Instansi response data:', data);
        if (data.success && data.data) {
            // Tambah option baru ke dropdown instansi
            const select = document.querySelector('select[name="instansi_id"]');
            if (select) {
                const newOption = document.createElement('option');
                newOption.value = data.data.id;
                newOption.textContent = data.data.nama;
                newOption.selected = true;
                select.appendChild(newOption);
            }
            
            // Clear form
            document.getElementById('instansiForm').reset();
            // Close form
            toggleInstansiForm();
            // Show success message
            showAlert('✓ Instansi berhasil ditambahkan! Silakan update profile untuk menyimpan.', 'success');
        } else {
            let errorMsg = data.message || 'Gagal menambahkan instansi';
            if (data.errors) {
                const errorsArray = Object.values(data.errors).flat();
                errorMsg = errorsArray.join(', ');
            }
            showAlert('✗ ' + errorMsg, 'error');
        }
    })
    .catch(error => {
        console.error('Instansi submit error:', error);
        showAlert('✗ Error: ' + error.message, 'error');
    });
}

// Handle Jurusan Form Submit
function handleJurusanSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    console.log('Submitting jurusan form:', form.action);
    console.log('CSRF Token present:', formData.has('_token'));

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Jurusan response status:', response.status);
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Server error: ' + response.status);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Jurusan response data:', data);
        if (data.success && data.data) {
            // Tambah option baru ke dropdown jurusan
            const select = document.querySelector('select[name="jurusan_id"]');
            if (select) {
                const newOption = document.createElement('option');
                newOption.value = data.data.id;
                newOption.textContent = data.data.nama;
                newOption.selected = true;
                select.appendChild(newOption);
            }
            
            // Clear form
            document.getElementById('jurusanForm').reset();
            // Close form
            toggleJurusanForm();
            // Show success message
            showAlert('✓ Jurusan berhasil ditambahkan! Silakan update profile untuk menyimpan.', 'success');
        } else {
            let errorMsg = data.message || 'Gagal menambahkan jurusan';
            if (data.errors) {
                const errorsArray = Object.values(data.errors).flat();
                errorMsg = errorsArray.join(', ');
            }
            showAlert('✗ ' + errorMsg, 'error');
        }
    })
    .catch(error => {
        console.error('Jurusan submit error:', error);
        showAlert('✗ Error: ' + error.message, 'error');
    });
}

// Reload Instansi Dropdown (tidak digunakan lagi, tapi disimpan untuk compatibility)
function reloadInstansiDropdown() {
    fetch('{{ route("profile.edit") }}')
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newSelect = doc.querySelector('select[name="instansi_id"]');
            const oldSelect = document.querySelector('select[name="instansi_id"]');
            if (newSelect && oldSelect) {
                oldSelect.innerHTML = newSelect.innerHTML;
            }
        });
}

// Reload Jurusan Dropdown (tidak digunakan lagi, tapi disimpan untuk compatibility)
function reloadJurusanDropdown() {
    fetch('{{ route("profile.edit") }}')
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newSelect = doc.querySelector('select[name="jurusan_id"]');
            const oldSelect = document.querySelector('select[name="jurusan_id"]');
            if (newSelect && oldSelect) {
                oldSelect.innerHTML = newSelect.innerHTML;
            }
        });
}

// Show Alert
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `mb-4 p-3 ${type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'} rounded`;
    alertDiv.textContent = message;
    
    const form = document.querySelector('form');
    form.parentNode.insertBefore(alertDiv, form);
    
    setTimeout(() => alertDiv.remove(), 3000);
}
function scrollToInstansiForm() {
    const form = document.getElementById("form-instansi");
    const icon = document.getElementById("chevronIconInstansi");

    // buka form jika belum terbuka
    if (form.style.maxHeight === "0px" || !form.style.maxHeight) {
        form.style.maxHeight = form.scrollHeight + "px";
        icon.style.transform = "rotate(180deg)";
    }

    // scroll smooth
    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function scrollToJurusanForm() {
    const form = document.getElementById("form-jurusan");
    const icon = document.getElementById("chevronIconJurusan");

    // buka form jika belum terbuka
    if (form.style.maxHeight === "0px" || !form.style.maxHeight) {
        form.style.maxHeight = form.scrollHeight + "px";
        icon.style.transform = "rotate(180deg)";
    }

    // scroll smooth
    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

</script>
@endpush