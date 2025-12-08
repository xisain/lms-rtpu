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

                @if (auth ()-> user()->roles_id == 3)
                <div>
                    <label class="block text-sm font-medium mb-1">Category</label>
                    <select name="category_id"
                            class="w-full border border-gray-300 rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($category as $c)
                            <option value="{{ $c->id }}"
                                {{ old('category_id', $user->category_id) == $c->id ? 'selected' : '' }}>
                                {{ ucfirst($c->category) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

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
    </div>
</div>
@endsection


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
</script>