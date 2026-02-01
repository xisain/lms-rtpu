@extends('layout.sidebar')
@section('title')

@endsection
@section('content')

    {{-- {{ $findTask }} --}}
    <div class="mx-auto py-2">
        <div class="max-w-10xl mx-auto">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-[#009999] px-6 py-4 flex justify-between items-center">
                    <h4 class="text-xl font-bold text-white">Review Laporan Akhir Peserta</h4>
                </div>
                <div class="overflow-x-auto p-4">
                    <table class="min-w-full divide-y divide-gray-200" id="finalTaskTable">
                        <thead class="bg-gray-50">
                            <tr class="">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase tracking-wider">#
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase">Nama
                                    Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-x divide-y divide-gray-200">
                            @forelse ($courses as $index => $cwr)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cwr->nama_course }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($cwr->finalTask)
                                            <a href="{{ route('dosen.course.final_task.list', $cwr->slugs) }}"class="text-blue-600 hover:underline">Lihat</a>
                                        @else
                                            <span class="text-red-600 font-semibold">
                                                Belum ada final task
                                            </span>
                                        @endif
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

            $(document).ready(function () {
                // DataTables
                $('#finalTaskTable').DataTable({
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
