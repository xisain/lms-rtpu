<div>
    <!-- Waste no more time arguing what a good man should be, be one. - Marcus Aurelius -->
</div>
@extends('layout.navbar')

@section('title', 'Lupa Password')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Lupa Password?</h2>
            <p class="mt-2 text-sm text-gray-600">
                Masukkan alamat email Anda untuk menerima tautan reset password.
            </p>
        </div>

        {{-- Alert sukses/error --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm">
                <ul class="list-disc ml-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form kirim email reset --}}
        <form method="POST" action="{{ route('forgetpassword.request') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                           focus:ring-[#009999] focus:border-[#009999] sm:text-sm" />
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-[#009999] hover:bg-[#0f5757] text-white font-medium py-2 px-4 rounded-md shadow-md transition-all duration-200">
                    Kirim Link Reset Password
                </button>
            </div>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-sm text-[#009999] hover:text-[#0f5757]">
                Kembali ke halaman login
            </a>
        </div>
    </div>
</div>
@endsection
