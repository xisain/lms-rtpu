@forelse($course as $item)
<div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition duration-300 overflow-hidden">
    <div class="p-5 flex flex-col justify-between h-full">
        <div class="relative bg-white p-8">
            <img src="{{ asset('storage/'.$item->image_link) }}"
                 alt="{{ $item->nama_course }}"
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

            {{-- Progress Bar --}}
            @if(request()->routeIs('course.my'))
            <div class="mt-4">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-xs font-medium text-gray-700">Progress</span>
                    <span class="text-xs font-semibold text-[#009999]">{{ $item->progress ?? 0 }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-[#009999] h-2.5 rounded-full transition-all duration-300"
                         style="width: {{ $item->progress ?? 0 }}%"></div>
                </div>
            </div>
            @endif
        </div>

        {{-- Tombol aksi --}}
        <div class="flex justify-center items-center">
            <a href="{{ route('course.show', $item->slugs) }}"
               class="flex justify-center items-center px-4 py-2 text-white rounded-md hover:bg-[#0f5757] font-medium bg-[#009999] mt-3 w-full">
                @if(request()->routeIs('course.my'))
                {{ ($item->progress ?? 0) >= 100 ? 'Lihat Ulang' : 'Lanjutkan Belajar' }}
                @else
                Lihat Kelas
                @endif

            </a>
        </div>
    </div>
</div>
@empty
<div class="col-span-full text-center py-12">
    <i class="fa-solid fa-inbox text-gray-300 text-6xl mb-4"></i>
    <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Course Ditemukan</h3>
    <p class="text-gray-500">Coba ubah filter atau kata kunci pencarian Anda</p>
</div>
@endforelse
