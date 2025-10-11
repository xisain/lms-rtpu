@extends('layout.navbar')

@section('content')
<div class="p-6">

    {{-- Search + Filter Tengah --}}
    <div class="flex flex-col sm:flex-row items-center justify-center mb-6 gap-4">
        {{-- Search Bar --}}
        <div class="w-full sm:max-w-md">
            <div class="relative">
                <input type="text" placeholder="Cari course..."
                    class="w-full px-4 py-2 rounded-[70px] border border-[#009999] focus:outline-none focus:ring-2 focus:ring-[#009999] focus:border-transparent">
                <button type="button"
                    class="absolute right-2 top-1/2 -translate-y-1/2 text-[#009999] px-3 py-1 rounded-md hover:text-[#0f5757]">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </div>

        {{-- Filter Button --}}
        <div x-data="{ open: false }" class="relative">
            {{-- Tombol Filter --}}
            <button @click="open = !open" class="px-4 py-2">
                <i class="fa-solid fa-sliders fa-lg" style="color: #099999;"></i>
            </button>

            {{-- Form filter --}}
            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="absolute top-full mt-2 right-1/2 translate-x-1/2 w-96 bg-white border border-gray-200 rounded-md shadow-lg p-6 z-10">

                {{-- Header dengan close button sejajar --}}
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-bold text-gray-800">Category Course</h4>
                    <!-- <button type="button" @click="open = false" class="text-red-600 hover:text-red-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button> -->
                </div>

                {{-- Select Category --}}
                <select
                    class="w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#009999]">
                    <option>Semua Kategori</option>
                    <option>Kategori 1</option>
                    <option>Kategori 2</option>
                </select>

                {{-- Tanggal Mulai & Selesai horizontal --}}
                <div class="flex gap-4 mt-4">
                    <div class="flex-1">
                        <h4 class="font-bold mb-1">Tanggal Mulai</h4>
                        <input type="date"
                            class="w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#009999]">
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold mb-1">Tanggal Selesai</h4>
                        <input type="date"
                            class="w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#009999]">
                    </div>
                </div>

                {{-- Button Terapkan --}}
                <button class="w-full mt-4 bg-[#009999] text-white px-4 py-2 rounded-md hover:bg-[#0f5757]">
                    Terapkan Filter
                </button>
            </div>
        </div>

    </div>

    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Daftar Course</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($course as $item)
        <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition duration-300 overflow-hidden">
            <div class="p-5 flex flex-col justify-between h-full">
                <div class="relative bg-white p-8">
                    <img src="{{ asset('storage/'.$item->image_link) }}" alt="{{ $item->nama_course }}"
                        class="w-full h-48 object-cover rounded-lg">
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $item->nama_course }}</h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $item->description }}</p>

                    <div class="space-y-1 text-sm text-gray-700">
                        @if ($item->start_date)
                        <p><span class="font-semibold text-gray-800">Course Terbatas</span></p>
                        <p><span class="font-semibold text-gray-800">Mulai:</span> {{ $item->start_date }}</p>
                        <p><span class="font-semibold text-gray-800">Selesai:</span> {{ $item->end_date }}</p>
                        @else
                        <p><span class="font-semibold text-gray-800">Self Pace Class</span></p>
                        @endif


                    </div>
                </div>

                {{-- Tombol aksi --}}
                <div class="flex justify-center items-center">
                    <a href="{{ route('course.show', $item->slugs) }}"
                        class="flex justify-center items-center px-4 py-2 text-white rounded-md hover:bg-[#0f5757] font-medium bg-[#009999] mt-3 w-full">
                        Lihat Kelas
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
