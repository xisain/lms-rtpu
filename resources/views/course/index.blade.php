@extends('layout.navbar')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Daftar Course</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($course as $item)
        <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition duration-300 overflow-hidden">
            <div class="p-5 flex flex-col justify-between h-full">
                <div class="relative bg-white p-8">
                    <img src="{{ asset('storage/images/course-test.png') }}"
                    alt="{{ $item->nama_course }}" class="w-full h-auto object-contain">
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
