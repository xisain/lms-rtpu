@extends('layout.layout')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Daftar Course</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($course as $item)
        <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition duration-300 overflow-hidden">
            <div class="p-5 flex flex-col justify-between h-full">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $item->nama_course }}</h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $item->description }}</p>

                    <div class="space-y-1 text-sm text-gray-700">
                        <p><span class="font-semibold text-gray-800">Mulai:</span> {{ $item->start_date }}</p>
                        <p><span class="font-semibold text-gray-800">Selesai:</span> {{ $item->end_date }}</p>
                        <p>
                        </p>
                    </div>
                </div>

                {{-- Tombol aksi --}}
                <div class="mt-4 flex justify-between items-center">
                    <a href="{{ route('course.show', $item->slugs) }}"
                       class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                       Lihat Detail →
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
