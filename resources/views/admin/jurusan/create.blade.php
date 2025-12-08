@extends('layout.sidebar')

@section('content')
<div class="p-6 w-full max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Tambah Jurusan</h2>

    <form method="POST" action="{{ route('admin.jurusan.store') }}">
        @csrf
        <input type="text" name="kode" placeholder="Kode" class="w-full border p-2 mb-3">
        <input type="text" name="nama" placeholder="Nama Jurusan" class="w-full border p-2 mb-3">

        <button class="bg-blue-600 px-4 py-2 text-white rounded">Simpan</button>
    </form>
</div>
@endsection
