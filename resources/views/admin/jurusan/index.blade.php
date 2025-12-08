@extends('layout.sidebar')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Data Jurusan</h2>

    <a href="{{ route('admin.jurusan.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Tambah Jurusan</a>

    <table class="w-full mt-4 border">
        <tr class="bg-gray-200">
            <th class="p-2">Kode</th>
            <th class="p-2">Nama</th>
            <th class="p-2">Aksi</th>
        </tr>

        @foreach($jurusan as $j)
        <tr>
            <td class="p-2">{{ $j->kode }}</td>
            <td class="p-2">{{ $j->nama }}</td>
            <td class="p-2 flex gap-2">
                <a href="{{ route('admin.jurusan.edit', $j->id) }}" class="text-yellow-600">Edit</a>
                <form action="{{ route('admin.jurusan.destroy', $j->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button class="text-red-600" onclick="return confirm('Yakin?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
