@extends('layout.sidebar')

@section('content')
<div class="p-6 w-full max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Jurusan</h2>

    <form method="POST" action="{{ route('admin.jurusan.update', $jurusan->id) }}">
        @csrf @method('PUT')

        <input type="text" name="kode" value="{{ $jurusan->kode }}" class="w-full border p-2 mb-3">
        <input type="text" name="nama" value="{{ $jurusan->nama }}" class="w-full border p-2 mb-3">

        <button class="bg-yellow-600 px-4 py-2 text-white rounded">Update</button>
    </form>
</div>
@endsection
