@extends('layout.sidebar')
@section('title', 'Instansi')
@section('content')
<div class="mx-auto py-2">
    <div class="max-w-8xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-5">

            {{-- Header --}}
            <div class="bg-[#009999] px-6 py-4 flex justify-between items-center">
                <h4 class="text-xl font-bold text-white">Daftar Instansi</h4>
                <a href="{{ route('admin.instansi.create') }}"
                    class="bg-white text-dark hover:bg-gray-200 px-4 py-2 rounded-lg font-semibold transition duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Instansi
                </a>
            </div>

            <div class="overflow-x-auto p-4">

                {{-- Success message --}}
                @if(session('success'))
                    <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-3">
                        {{ session('success') }}
                    </div>
                @endif

            

                {{-- Table --}}
                <table id="instansiTable" class="display w-full">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Instansi</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($instansi as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="font-semibold">{{ $item->nama }}</td>
                            <td>{{ $item->email ?? '-' }}</td>
                            <td>{{ $item->alamat ?? '-' }}</td>
                            <td>{{ $item->telepon ?? '-' }}</td>
                            <td>
                                <div class="flex justify-center items-center gap-4">
                                    <a href="{{ route('admin.instansi.edit', $item->id) }}"
                                        class="text-yellow-400 hover:underline" title="Edit">
                                        <i class="fa-solid fa-pen text-lg"></i>
                                    </a>

                                    <form action="{{ route('admin.instansi.destroy', $item->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Yakin hapus?')"
                                            class="text-red-600 hover:underline" title="Hapus">
                                            <i class="fa-solid fa-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Info + Pagination --}}
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
    let table = new DataTable('#instansiTable', {
        searchable: true,
        perPage: 10,
        perPageSelect: [5, 10, 25, 50, -1],
        labels: {
            placeholder: "Cari...",
            perPage: "{select} data per halaman",
            noRows: "Tidak ada data",
            info: "Menampilkan {start} - {end} dari {rows} data",
        }
    });

    document.getElementById("customSearch").addEventListener("input", function () {
        table.search(this.value);
    });
    document.getElementById("customLength").addEventListener("change", function () {
        table.perPage(this.value);
    });
    table.on('datatable.info', info => document.getElementById("customInfo").innerHTML = info);
    table.on('datatable.page', page => {
        document.getElementById("customPagination").innerHTML = "";
        table.pages.forEach((_, i) => {
            const btn = document.createElement("button");
            btn.innerText = i + 1;
            btn.className = "px-3 py-1 border rounded " + (page == i ? "bg-[#009999] text-white" : "bg-white");
            btn.onclick = () => table.page(i);
            document.getElementById("customPagination").appendChild(btn);
        });
    });
    table.update();
});
</script>
