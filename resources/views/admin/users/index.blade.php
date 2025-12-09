@extends('layout.sidebar')
@include('sweetalert2::index')
@section('content')
    <div class="mx-auto py-2">
          @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded relative" role="alert">
                        <div class="flex justify-between items-center">
                            <span>{{ session('success') }}</span>
                            <button onclick="this.parentElement.parentElement.remove()" class="text-green-700 hover:text-green-900">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
            @endif
        <div class="max-w-10xl mx-auto">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-[#009999] px-6 py-4 flex justify-between items-center">
                    <h4 class="text-xl font-bold text-white">Pengguna</h4>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.user.active') }}"
                            class="bg-white text-dark hover:bg-gray-200 px-4 py-2 rounded-lg font-semibold transition duration-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Aktivasi
                            @if(isset($userNeedActivate) && $userNeedActivate > 0)
                                <span class="bg-red-500 text-white rounded-full px-2 py-0.5 text-xs font-bold">
                                    {{ $userNeedActivate }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('admin.user.create') }}"
                            class="bg-white text-dark hover:bg-gray-200 px-4 py-2 rounded-lg font-semibold transition duration-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Pengguna
                        </a>
                    </div>
                </div>
                </div>

                <div class=" bg-white overflow-x-auto p-4">
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
