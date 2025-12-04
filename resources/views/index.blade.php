@extends('layout.navbar')
@section('content')

<div class="min-h-screen bg-gradient-to-b from-[#f0f7ff] to-white flex items-center justify-center">
    <div class="flex flex-col md:flex-row items-center justify-between max-w-6xl mx-auto px-4">
        <div class="text-left max-w-md">
            <h1 class="text-4xl font-bold text-gray-900">
                Apa Itu <span class="text-[#008b8b] italic">Rekayasa Teknologi & Produk Unggulan?</span><br>
                Politeknik Negeri Jakarta
            </h1>
            <p class="mt-4 text-gray-600 leading-relaxed">
                Belajar lebih seru, fleksibel, dan berdampak melalui
                <span class="font-semibold">Learning Management System (LMS)</span> kami.
                Temukan kursus, raih sertifikasi, dan berkembang bersama kami.
            </p>
            <a href="#" class="text-[#009999] hover:underline mt-3 inline-block">Baca Selengkapnya</a>
        </div>
        <div class="mt-8 md:mt-0">
            <img src="{{ asset('/images/logo.png') }}" alt="Logo RTPU" class="w-[230px] md:w-[290px]">
        </div>
    </div>
</div>

<div class="py-10 bg-white"></div>


<div class="bg-white py-16 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                Cara Mendaftar Akun <span class="text-[#009999]">LMS RTPU</span>
            </h2>
            <p class="text-gray-600 text-lg">
                Ikuti langkah mudah berikut untuk mulai belajar bersama kami
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="relative bg-white rounded-2xl shadow-lg hover:shadow-xl transition duration-300 overflow-hidden group">
                <div class="absolute top-4 left-4 bg-[#009999] text-white rounded-full w-12 h-12 flex items-center justify-center text-2xl font-bold shadow-lg z-10">
                    1
                </div>
                <div class="aspect-video overflow-hidden bg-gray-100">
                    <img src="{{ asset('images/guide/1.png') }}" alt="Step 1" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Tekan Tombol Register/Login</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Klik tombol "Daftar Sekarang" di bawah ini atau di pojok kanan atas halaman untuk membuat akun baru.
                    </p>
                </div>
            </div>


            <div class="relative bg-white rounded-2xl shadow-lg hover:shadow-xl transition duration-300 overflow-hidden group">
                <div class="absolute top-4 left-4 bg-[#009999] text-white rounded-full w-12 h-12 flex items-center justify-center text-2xl font-bold shadow-lg z-10">
                    2
                </div>
                <div class="aspect-video overflow-hidden bg-gray-100">
                    <img src="{{ asset('images/guide/2.png') }}" alt="Step 2" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Klik "Create New Account"</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Di halaman login, klik link "Create a new account" untuk membuat akun baru.
                    </p>
                </div>
            </div>

            <div class="relative bg-white rounded-2xl shadow-lg hover:shadow-xl transition duration-300 overflow-hidden group">
                <div class="absolute top-4 left-4 bg-[#009999] text-white rounded-full w-12 h-12 flex items-center justify-center text-2xl font-bold shadow-lg z-10">
                    3
                </div>
                <div class="aspect-video overflow-hidden bg-gray-100">
                    <img src="{{ asset('images/guide/3.png') }}" alt="Step 3" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Isi Data Pendaftaran</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Masukkan nama lengkap, email aktif, dan password. Pastikan data yang diisi sudah benar.
                    </p>
                </div>
            </div>

            <div class="relative bg-white rounded-2xl shadow-lg hover:shadow-xl transition duration-300 overflow-hidden group">
                <div class="absolute top-4 left-4 bg-[#009999] text-white rounded-full w-12 h-12 flex items-center justify-center text-2xl font-bold shadow-lg z-10">
                    4
                </div>
                <div class="aspect-video overflow-hidden bg-gray-100">
                    <img src="{{ asset('images/guide/4.png') }}" alt="Step 4" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Tunggu Verifikasi Admin</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Akun Anda akan diverifikasi oleh admin. Setelah disetujui, Anda bisa login dan mulai belajar!
                    </p>
                </div>
            </div>
        </div>

         <div class="text-center mt-16 md:mt-20">
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-[#009999] hover:bg-[#007777] text-white font-bold py-4 px-8 rounded-full shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Daftar Sekarang
            </a>
            <p class="text-gray-600 mt-4 text-sm">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-[#009999] hover:underline font-semibold">Login di sini</a>
            </p>
        </div>
    </div>
</div>

<div class="py-10 bg-gradient-to-b from-white to-[#009999]"></div>

<div class="min-h-screen bg-[#009999] flex flex-col items-center py-10">
    <div class="flex flex-col md:flex-row items-center justify-between bg-white w-11/12 max-w-lg rounded-[80px] border border-gray-300 p-4 shadow-lg mb-10">
        <div class="text-center md:text-left mb-6 md:mb-0 ml-7">
            <h3 class="text-gray-700 text-lg font-semibold mb-1">We Are Part Of</h3>
            <h2 class="text-[#009999] text-xl md:text-xl font-bold">Politeknik Negeri Jakarta</h2>
        </div>
        <div>
            <img src="{{ asset('images/logo-pnj.png') }}" alt="Logo PNJ" class="w-[50px] md:w-[70px] object-contain mr-7">
        </div>
    </div>

    <div class="w-11/12 max-w-5xl">
        <div class="aspect-[16/9] mb-4">
            <img src="{{ asset('images/background1.jpg') }}" alt="Gambar 1" class="w-full h-full object-cover rounded-xl shadow-lg">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="aspect-[16/9]">
                <img src="{{ asset('/images/background1.jpg') }}" alt="Gambar 2" class="w-full h-full object-cover rounded-xl shadow-lg">
            </div>
            <div class="aspect-[16/9]">
                <img src="{{ asset('/images/background2.jpeg') }}" alt="Gambar 3" class="w-full h-full object-cover rounded-xl shadow-lg">
            </div>
            <div class="aspect-[16/9]">
                <img src="{{ asset('/images/background3.jpg') }}" alt="Gambar 4" class="w-full h-full object-cover rounded-xl shadow-lg">
            </div>
        </div>
    </div>
</div>

@if(session()->has('success'))
<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "timeOut": 3000,
        "positionClass": "toast-top-full-width"
    };
    toastr.success("{{ session('success') }}");
</script>
@endif

@endsection
