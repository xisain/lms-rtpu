@extends('layout.sidebar')
@section('title', 'Course')
@section('content')
    <div class="mx-auto py-2">
        <div class="max-w-10xl mx-auto">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-5">
                <div class="bg-[#009999] px-6 py-4 flex justify-between items-center">
                    <h4 class="text-xl font-bold text-white">Daftar Course</h4>
                    <a href="{{ route('course.create') }}"
                        class="bg-white text-dark hover:bg-gray-200 px-4 py-2 rounded-lg font-semibold transition duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Course
                    </a>
                </div>
                <div class="overflow-x-auto p-4">
                    <table id="courseTable" class="display w-full">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Course</th>
                                <th>Deskripsi</th>
                                <th>Periode</th>
                                <th>Material</th>
                                <th>Status</th>
                                <th class="">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($course as $index => $c)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="font-semibold">{{ $c->nama_course }}</td>
                                    <td>{{ Str::limit($c->description, 50) }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($c->start_date)->format('d M Y') }} -
                                        {{ \Carbon\Carbon::parse($c->end_date)->format('d M Y') }}
                                    </td>
                                    <td>
                                        @php
                                            $totalMaterial = $c->material->count();
                                            $totalSubmaterial = $c->material->sum(fn($m) => $m->submaterial->count());
                                        @endphp
                                        <span class="font-semibold">{{ $totalMaterial }}</span>
                                        (<span class="font-semibold">{{ $totalSubmaterial }}</span>)
                                    </td>
                                    <td>
                                        @if ($c->public)
                                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Publik</span>
                                        @else
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">Private</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex justify-center items-center gap-4">
                                            <a href="{{ route('course.show', $c->slugs) }}"
                                                class="text-blue-600 hover:underline" title="Lihat Course">
                                                <i class="fa-solid fa-eye text-lg"></i>
                                            </a>
                                            <a href="{{ route('course.edit', $c->id) }}"
                                                class="text-yellow-400 hover:underline" title="Edit">
                                                <i class="fa-solid fa-pen text-lg"></i>
                                            </a>
                                            <a href="{{ route('course.manage', $c->id) }}"
                                                class="text-brown-400 hover:underline" title="Kelola Course">
                                                <i class="fa-solid fa-user"></i>
                                            </a>
                                            <form action="{{ route('course.destroy', $c->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline btn-delete" title="Hapus">
                                                    <i class="fa-solid fa-trash text-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables -->

    <script>
        $(document).ready(function() {
            // Initialize DataTable dengan konfigurasi Tailwind
            const courseTable = new DataTable('#courseTable', {
                language: {
                    processing: 'Memuat data...',
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
                    infoFiltered: '(difilter dari _MAX_ total data)',
                    zeroRecords: 'Tidak ada data yang ditemukan',
                    emptyTable: 'Tidak ada data yang tersedia',
                    paginate: {
                        first: '<<',
                        last: '>>',
                        next: '>',
                        previous: '<'
                    }
                },
                order: [[1, 'asc']], // Sort by course name by default
                columnDefs: [
                    {
                        targets: -1, // Last column (actions)
                        orderable: false,
                        searchable: false
                    }
                ],
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
                responsive: true
            });

            // Delete confirmation
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

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
