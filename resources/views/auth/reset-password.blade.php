@extends('layout.navbar')

@section('title', 'Reset Password')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Reset Password</h2>
            <p class="mt-2 text-sm text-gray-600">
                Silakan masukkan kata sandi baru Anda di bawah ini.
            </p>
        </div>

        {{-- Alert sukses --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Alert status --}}
        @if (session('status'))
            <div class="bg-blue-100 text-blue-700 px-4 py-2 rounded mb-4 text-sm">
                {{ session('status') }}
            </div>
        @endif

        {{-- Alert error --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm">
                <ul class="list-disc ml-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Reset Password --}}
        <form method="POST" action="{{ route('resetpassword.store') }}" class="space-y-5">
            @csrf

            {{-- Token dan Email tersembunyi --}}
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email ?? request()->email ?? old('email') }}">

            {{-- Display Email (readonly, tidak dikirim) --}}
            <div>
                <label for="email_display" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                <input id="email_display" type="email"
                    value="{{ $email ?? request()->email ?? old('email') }}"
                    readonly
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-md shadow-sm cursor-not-allowed sm:text-sm" />
            </div>

            {{-- Password Baru --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Kata Sandi Baru <span class="text-red-500">*</span>
                </label>
                <input id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    minlength="8"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#009999] focus:border-[#009999] sm:text-sm @error('password') border-red-500 @enderror" />
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter</p>
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                    Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                </label>
                <input id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    minlength="8"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#009999] focus:border-[#009999] sm:text-sm" />
            </div>

            {{-- Submit Button --}}
            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-[#009999] hover:bg-[#0f5757] text-white font-medium py-2 px-4 rounded-md shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009999]">
                    Simpan Kata Sandi Baru
                </button>
            </div>
        </form>

        {{-- Link kembali --}}
        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-sm text-[#009999] hover:text-[#0f5757] hover:underline">
                ← Kembali ke halaman login
            </a>
        </div>
    </div>
</div>

{{-- Optional: JavaScript untuk validasi real-time --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');

        // Validasi saat mengetik konfirmasi password
        passwordConfirmation.addEventListener('input', function() {
            if (password.value !== passwordConfirmation.value) {
                passwordConfirmation.setCustomValidity('Password tidak cocok');
                passwordConfirmation.classList.add('border-red-500');
            } else {
                passwordConfirmation.setCustomValidity('');
                passwordConfirmation.classList.remove('border-red-500');
            }
        });
    });
</script>
@endpush
@endsection
