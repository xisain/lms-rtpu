@extends('layout.sidebar')

@section('content')
<div class="container mx-auto -mt-4 ml-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Daftar Course</h1>
        <a href="{{ route('course.create') }}" class="text-white hover:bg-[#0f5757] font-medium bg-[#009999] w-11/11 max-w-lg rounded-[10px] border border-gray-300 p-2 shadow-lg">
            + Tambah Course
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">No.</th>
                    <th class="px-6 py-3">Nama Course</th>
                    <th class="px-6 py-3">Deskripsi</th>
                    <th class="px-6 py-3">Periode</th>
                    <th class="px-6 py-3">Material</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
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
                        @if($c->public)
                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Publik</span>
                        @else
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">Private</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center space-x-5">
                        <a href="{{ route('course.show', $c->slugs) }}" class="text-blue-600 hover:underline" alt="Lihat Course"><i class="fa-solid fa-eye"></i></a>
                        <a href="{{ route('course.edit', $c->id) }}" class="text-yellow-400 hover:underline" alt="Edit"><i class="fa-solid fa-pen"></i></a>
                        <form action="{{ route('course.destroy', $c->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline" alt="Hapus"
                                onclick="return confirm('Yakin ingin menghapus course ini?')">
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
</div>
@endsection
