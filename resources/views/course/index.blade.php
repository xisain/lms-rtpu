@extends('layout.navbar')

@section('content')
<div class="p-6" x-data="courseFilter()" x-init="init()">

    {{-- Search + Filter Tengah --}}
    <div class="flex flex-col sm:flex-row items-center justify-center mb-6 gap-4">
        {{-- Search Bar --}}
        <div class="w-full sm:max-w-md">
            <div class="relative">
                <input type="text"
                       placeholder="Cari course..."
                       x-model="search"
                       @input.debounce.500ms="filterCourses()"
                       class="w-full px-4 py-2 rounded-[70px] border border-[#009999] focus:outline-none focus:ring-2 focus:ring-[#009999] focus:border-transparent">
                <button type="button"
                    class="absolute right-2 top-1/2 -translate-y-1/2 text-[#009999] px-3 py-1 rounded-md hover:text-[#0f5757]">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </div>

        {{-- Filter Button --}}
        <div class="relative">
            {{-- Tombol Filter --}}
            <button @click="open = !open" class="px-4 py-2">
                <i class="fa-solid fa-sliders fa-lg" style="color: #099999;"></i>
            </button>

            {{-- Form filter --}}
            <div x-show="open"
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="absolute top-full mt-2 right-1/2 translate-x-1/2 w-96 bg-white border border-gray-200 rounded-md shadow-lg p-6 z-10">

                {{-- Header --}}
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-bold text-gray-800">Category Course</h4>
                </div>

                {{-- Select Category --}}
                <select x-model="category"
                        class="w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#009999]">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->category }}</option>
                    @endforeach
                </select>

                {{-- Tanggal Mulai & Selesai horizontal --}}
                <div class="flex gap-4 mt-4">
                    <div class="flex-1">
                        <h4 class="font-bold mb-1">Tanggal Mulai</h4>
                        <input type="date"
                               x-model="startDate"
                               class="w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#009999]">
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold mb-1">Tanggal Selesai</h4>
                        <input type="date"
                               x-model="endDate"
                               class="w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#009999]">
                    </div>
                </div>

                {{-- Button Terapkan --}}
                <button @click="filterCourses(); open = false"
                        class="w-full mt-4 bg-[#009999] text-white px-4 py-2 rounded-md hover:bg-[#0f5757]">
                    Terapkan Filter
                </button>
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-semibold mb-6 text-gray-800">
        @if (request()->routeIs('course.my'))
        My Course
        @else
        Daftar Course
        @endif


    </h2>

    {{-- Loading State --}}
    <div x-show="loading" class="text-center py-8">
        <i class="fa-solid fa-spinner fa-spin text-[#009999] text-3xl"></i>
        <p class="text-gray-600 mt-2">Memuat course...</p>
    </div>

    {{-- Course Grid --}}
    <div id="course-grid"
         x-show="!loading"
         class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @include('course.partials.course-cards', ['course' => $course])
    </div>
</div>

<script>
function courseFilter() {
    return {
        search: '',
        category: '',
        startDate: '',
        endDate: '',
        open: false,
        loading: false,

        init() {

            const urlParams = new URLSearchParams(window.location.search);
            this.search = urlParams.get('search') || '';
            this.category = urlParams.get('category') || '';
            this.startDate = urlParams.get('start_date') || '';
            this.endDate = urlParams.get('end_date') || '';
        },

        async filterCourses() {
            this.loading = true;

            const params = new URLSearchParams();
            if (this.search) params.append('search', this.search);
            if (this.category) params.append('category', this.category);
            if (this.startDate) params.append('start_date', this.startDate);
            if (this.endDate) params.append('end_date', this.endDate);

            // Update URL tanpa reload
            const newUrl = params.toString() ? `${window.location.pathname}?${params}` : window.location.pathname;
            window.history.pushState({}, '', newUrl);

            try {
                const url = `{{ route('course.filter') }}?${params}`;
                console.log('Fetching:', url); // Debug

                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                });

                console.log('Response status:', response.status); // Debug

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Error response:', errorText);
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const html = await response.text();
                document.querySelector('#course-grid').innerHTML = html;

            } catch (error) {
                console.error('Error filtering courses:', error);
                document.querySelector('#course-grid').innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <i class="fa-solid fa-exclamation-triangle text-red-500 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Terjadi Kesalahan</h3>
                        <p class="text-gray-500 mb-4">${error.message}</p>
                        <button onclick="location.reload()" class="px-4 py-2 bg-[#009999] text-white rounded-md hover:bg-[#0f5757]">
                            Muat Ulang Halaman
                        </button>
                    </div>
                `;
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endsection
