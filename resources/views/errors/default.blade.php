@extends('layout.navbar')

@section('title')
    Error @yield('code')
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#f0fbfb] via-white to-[#f7ffff] flex items-center justify-center px-4 py-20">
    <div class="relative max-w-3xl w-full bg-white/80 backdrop-blur-md shadow-2xl border border-white/30 rounded-3xl p-8 md:p-12 overflow-hidden">

        <!-- decorative blobs -->
        <div class="absolute -left-20 -top-10 w-52 h-52 bg-[#009999]/8 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -right-24 -bottom-12 w-72 h-72 bg-[#009999]/6 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex flex-col items-center text-center">

            <span class="inline-block px-3 py-1 rounded-full bg-[#E6FFFB] text-[#0e6b6b] font-semibold text-sm mb-3">
                Error
            </span>

            <h1 class="text-6xl md:text-7xl font-extrabold text-[#009999] tracking-tight">
                @yield('code')
            </h1>

            <p class="mt-3 text-2xl font-semibold text-gray-800">
                @yield('message')
            </p>

            @hasSection('description')
            <p class="mt-4 text-gray-600 max-w-xl leading-relaxed">
                @yield('description')
            </p>
            @endif

            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <a href="{{ url()->previous() }}" class="inline-flex items-center px-5 py-3 rounded-lg border border-[#009999] text-[#009999] font-medium bg-white hover:bg-[#f0fffe] transition">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                </a>
                <a href="{{ url('/') }}" class="inline-flex items-center px-5 py-3 rounded-lg bg-[#009999] text-white font-medium shadow hover:bg-[#0d6f6f] transition">
                    <i class="fa-solid fa-home mr-2"></i> Beranda
                </a>

            </div>

            <p class="mt-6 text-sm text-gray-400">
                Jika masalah berlanjut, silakan hubungi tim support atau refresh halaman.
            </p>

        </div>
    </div>
</div>
@endsection
