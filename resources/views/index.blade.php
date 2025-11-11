@extends('layout.navbar')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#f0f7ff] to-white flex items-center justify-center">
    <div class="flex flex-col md:flex-row items-center justify-between max-w-6xl mx-auto">
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

<div class="min-h-screen bg-[#009999] flex flex-col items-center py-10">
    <div class="flex flex-col md:flex-row items-center justify-between bg-white w-11/12 max-w-lg rounded-[80px] border border-gray-300 p-4 shadow-lg mb-10">
        <div class="text-center md:text-left mb-6 md:mb-0 ml-7">
            <h3 class="text-gray-700 text-lg font-semibold mb-1">We Are Part Of</h3>
            <h2 class="text-[#009999] text-xl md:text-xl font-bold">Politeknik Negeri Jakarta</h2>
        </div>
        <div>
            <img src="{{ asset('images/logo-pnj.png') }}" alt="Logo RTPU" class="w-[50px] md:w-[70px] object-contain mr-7">
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

@endsection
