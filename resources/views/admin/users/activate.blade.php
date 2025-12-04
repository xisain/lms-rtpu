@extends('layout.sidebar')
@include('sweetalert2::index')

@section('title', 'Aktivasi Pengguna')

@section('content')
<div class="mx-auto py-2">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg mx-auto max-w-10xl">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg mx-auto max-w-10xl">
            {{ session('error') }}
        </div>
    @endif

    <div class="max-w-10xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-[#009999] px-6 py-4 flex justify-between items-center">
                <h4 class="text-xl font-bold text-white">Aktivasi Pengguna</h4>
                <a href="{{ route('admin.user.index') }}"
                    class="bg-white text-dark hover:bg-gray-200 px-4 py-2 rounded-lg font-semibold transition duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
            </div>

            <div class="overflow-x-auto p-4">
                <table class="min-w-full divide-y divide-gray-200" id="usersTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase tracking-wider">Dibuat Pada</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-x divide-y divide-gray-200">
                        @forelse ($users as $index => $user)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-6">
                                        <!-- Approve Button -->
                                        <form action="{{ route('admin.user.approved', $user->id) }}" method="POST" class="inline form-approve">
                                            @csrf
                                            <button type="button"
                                                    class="text-green-600 hover:underline btn-approve"
                                                    title="Setujui"
                                                    data-name="{{ $user->name }}">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>

                                        <!-- Reject Button -->
                                        <form action="{{ route('admin.user.rejected', $user->id) }}" method="POST" class="inline form-reject">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="text-red-600 hover:underline mr-4 btn-reject"
                                                    title="Tolak"
                                                    data-name="{{ $user->name }}">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-gray-500">Tidak ada pengguna yang menunggu aktivasi.</td>
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
        $('#usersTable').DataTable({
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

        // SweetAlert untuk Approve
        $('.btn-approve').on('click', function() {
            const form = $(this).closest('.form-approve');
            const userName = $(this).data('name');

            Swal.fire({
                title: 'Setujui Pengguna?',
                html: `Apakah Anda yakin ingin menyetujui pendaftaran <strong>${userName}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // SweetAlert untuk Reject
        $('.btn-reject').on('click', function() {
            const form = $(this).closest('.form-reject');
            const userName = $(this).data('name');

            Swal.fire({
                title: 'Tolak Pengguna?',
                html: `Apakah Anda yakin ingin menolak pendaftaran <strong>${userName}</strong>?<br><small class="text-gray-500">Akun ini akan dihapus secara permanen.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Tolak',
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
