@extends('layout.sidebar')
@include('sweetalert2::index')
@section('content')
    <div class="mx-auto py-2">
        <div class="max-w-10xl mx-auto">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-[#009999] px-6 py-4 flex justify-between items-center">
                    <h4 class="text-xl font-bold text-white">Pengguna</h4>
                    <a href="{{ route('admin.user.create') }}"
                        class="bg-white text-dark hover:bg-gray-200 px-4 py-2 rounded-lg font-semibold transition duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Banyak Pengguna
                    </a>
                </div>

                <div class="overflow-x-auto p-4">
                    <table class="min-w-full divide-y divide-gray-200" id="usersTable">
                        <thead class="bg-gray-50">
                            <tr class="">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase tracking-wider">Nama
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase tracking-wider">email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase tracking-wider">Role
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase tracking-wider">
                                    Dibuat Pada</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-x divide-y divide-gray-200">
                            @forelse ($users as $index =>$user)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $user->role->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-6">
                                            <a href="{{ route('admin.user.edit', $user->id) }}"
                                                class="text-yellow-400 hover:underline">
                                                <i class="fa-solid fa-pen" style="color:"></i>
                                            </a>
                                            <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                class="text-red-600 hover:underline mr-4 btn-delete"
                                                alt="Hapus"><i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-gray-500">Tidak ada pengguna yang
                                        terdaftar.</td>
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
            // kasih id di table diatas
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
        });
    </script>
    @endpush
@endsection
