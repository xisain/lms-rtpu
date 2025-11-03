@extends('layout.sidebar')

@section('content')
    <div class="mx-auto py-2">
        <div class="max-w-10xl mx-auto">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-5">
                <div class="bg-[#009999] px-6 py-4 flex justify-between items-center">
                    <h4 class="text-xl font-bold text-white">Daftar Course</h4>
                    <a href="{{ route('admin.category.create') }}"
                        class="bg-white text-dark hover:bg-gray-200 px-4 py-2 rounded-lg font-semibold transition duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Course
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase tracking-wider">#
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase tracking-wider">Nama
                                    Cours</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase tracking-wider">
                                    Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase tracking-wider">
                                    Periode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase tracking-wider">
                                    Material</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase tracking-wider">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($course as $index => $c)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-semibold">{{ $c->nama_course }}</td>
                                    <td class="px-6 py-4">{{ Str::limit($c->description, 50) }}</td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($c->start_date)->format('d M Y') }} -
                                        {{ \Carbon\Carbon::parse($c->end_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            // Hitung jumlah material dan total submateri
                                            $totalMaterial = $c->material->count();
                                            $totalSubmaterial = $c->material->sum(fn($m) => $m->submaterial->count());
                                        @endphp

                                        <p class="text-sm text-gray-700 mb-3">
                                            <span class="font-semibold">{{ $totalMaterial }}</span>
                                            (<span class="font-semibold">{{ $totalSubmaterial }}</span>)
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($c->public)
                                            <span
                                                class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Publik</span>
                                        @else
                                            <span
                                                class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">Private</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center space-x-5">
                                        <a href="{{ route('course.show', $c->slugs) }}"
                                            class="text-blue-600 hover:underline -mr-1" alt="Lihat Course"><i
                                                class="fa-solid fa-eye"></i></a>
                                        <a href="{{ route('course.edit', $c->id) }}" class="text-yellow-400 hover:underline"
                                            alt="Edit"><i class="fa-solid fa-pen"></i></a>
                                        <form action="{{ route('course.destroy', $c->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline mr-4 btn-delete"
                                                alt="Hapus">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-6 text-gray-500">
                                        Tidak ada course ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="py-6 px-4">
                    {{ $course->links() }}
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Yakin Menghapus Course ini?',
                        text: "Data Yang Terhapus Termasuk Material, Submaterial dan Quiz. Data yang di hapus tidak bisa di kembalikan",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
