@extends('layout.sidebar')
@section('title', 'Jurusan')
@section('content')
<div class="mx-auto py-2">
    <div class="max-w-8xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-5">

            <!-- Header -->
            <div class="bg-[#009999] px-6 py-4 flex justify-between items-center">
                <h4 class="text-xl font-bold text-white">Daftar Jurusan</h4>
                <a href="{{ route('admin.jurusan.create') }}"
                    class="bg-white text-dark hover:bg-gray-200 px-4 py-2 rounded-lg font-semibold transition duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Jurusan
                </a>
            </div>

            <div class="overflow-x-auto p-4">

                <!-- Table -->
                <table id="jurusanTable" class="display w-full">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Jurusan</th>
                            <th>Nama Jurusan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jurusan as $index => $j)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="font-semibold">{{ $j->kode }}</td>
                            <td>{{ $j->nama }}</td>
                            <td>
                                <div class="flex justify-center items-center gap-4">
                                    <a href="{{ route('admin.jurusan.edit', $j->id) }}" class="text-yellow-400 hover:underline"
                                        title="Edit">
                                        <i class="fa-solid fa-pen text-lg"></i>
                                    </a>

                                    <form action="{{ route('admin.jurusan.destroy', $j->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline btn-delete"
                                            onclick="return confirm('Yakin ingin hapus jurusan ini?')" title="Hapus">
                                            <i class="fa-solid fa-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination + Info -->
                <div class="flex justify-between items-center mt-4 flex-wrap gap-4">
                    <div id="customInfo" class="text-gray-700 font-medium"></div>
                    <div id="customPagination" class="flex gap-1"></div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let table = new DataTable('#jurusanTable', {
            searchable: true,
            perPage: 10,
            perPageSelect: [5, 10, 25, 50, -1],
            labels: {
                placeholder: "Cari...",
                perPage: "{select} data per halaman",
                noRows: "Tidak ada data ditemukan",
                info: "Menampilkan {start} - {end} dari {rows} data",
            }
        });

        // Custom search
        document.getElementById("customSearch").addEventListener("input", function () {
            table.search(this.value);
        });

        // Custom length
        document.getElementById("customLength").addEventListener("change", function () {
            table.perPage(this.value);
        });

        // Custom info
        table.on('datatable.info', function (info) {
            document.getElementById("customInfo").innerHTML = info;
        });

        // Custom pagination
        table.on('datatable.page', function (page) {
            document.getElementById("customPagination").innerHTML = "";
            table.pages.forEach(function (_, i) {
                const btn = document.createElement("button");
                btn.innerText = i + 1;
                btn.className = "px-3 py-1 rounded border " + (page == i ? "bg-[#009999] text-white" : "bg-white");
                btn.onclick = () => table.page(i);
                document.getElementById("customPagination").appendChild(btn);
            });
        });

        table.update();
    });
</script>
