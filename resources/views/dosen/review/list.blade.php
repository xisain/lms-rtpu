@extends('layout.sidebar')
@section('title')

@endsection
@section('content')
    <div class="mx-auto py-2">
        <div class="max-w-10xl mx-auto">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Header -->
                <div class="bg-[#009999] px-6 py-4 flex justify-between items-center">
                    <h4 class="text-xl font-bold text-white">Review Tugas Akhir "{{ $course->nama_course }}"</h4>
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
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <span class="inline-flex items-center rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 inset-ring inset-ring-green-500/20">{{ $tl->status }}</span>

                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <a href="{{ route('dosen.course.final_task.review',[$course->slugs,$tl->id]) }}">Lihat</a>
                                </td>
                            </tr>
                           @empty

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
