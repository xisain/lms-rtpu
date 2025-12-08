@extends('layout.sidebar')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Edit Instansi</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.instansi.update', $instansi->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Instansi <span class="text-danger">*</span></label>
            <input type="text" value="{{ $instansi->nama }}" name="nama" class="form-control @error('nama') is-invalid @enderror" required>
            @error('nama')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" value="{{ $instansi->email }}" name="email" class="form-control @error('email') is-invalid @enderror">
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ $instansi->alamat }}</textarea>
            @error('alamat')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Telepon</label>
            <input type="text" value="{{ $instansi->telepon }}" name="telepon" class="form-control @error('telepon') is-invalid @enderror">
            @error('telepon')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('admin.instansi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection

