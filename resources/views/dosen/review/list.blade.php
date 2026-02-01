@extends('layout.sidebar')
@section('title')
@endsection
@section('content')
    <div class="mx-auto py-2">
        <div class="max-w-10xl mx-auto">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Header -->
                <div class="bg-[#009999] px-6 py-4 flex justify-between items-center">
                    <h4 class="text-xl font-bold text-white">Review Laporan Akhir "{{ $course->nama_course }}"</h4>
                    <div class="flex gap-2">
                        <!-- Tombol Export PDF -->
                        <a href="{{ route('dosen.course.final_task.export.pdf', $course->slugs) }}"
                           class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded inline-flex items-center transition duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export PDF
                        </a>

                        <!-- Tombol Preview PDF (Optional) -->
                        <a href="{{ route('dosen.course.final_task.preview.pdf', $course->slugs) }}"
                           target="_blank"
                           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded inline-flex items-center transition duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Preview PDF
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto p-4">
                    <table class="min-w-full divide-y divide-gray-200" id="listFinalTask">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($taskList as $index => $tl)
                            <tr class="hover:bg-gray-50 transition duration-150">
                               <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $index + 1  }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $tl->user->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <span class="inline-flex items-center rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 inset-ring inset-ring-green-500/20">{{ $tl->status }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <a href="{{ route('dosen.course.final_task.review',[$course->slugs,$tl->id]) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script>
$(document).ready(function() {
    // DataTables
    $('#listFinalTask').DataTable({
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
        order: [[0, 'asc']]
    });
})
</script>
@endpush
@endsection
