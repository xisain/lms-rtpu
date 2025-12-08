@extends('layout.sidebar')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">

            <!-- Header -->
            <div class="bg-gradient-to-r from-teal-500 to-teal-700 px-6 py-4">
                <h4 class="text-xl font-bold text-white">Tambah Jurusan Baru</h4>
            </div>

            <div class="p-6">
                <form action="{{ route('admin.jurusan.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Kode Jurusan -->
                    <div>
                        <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Jurusan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                            id="kode" 
                            name="kode"
                            value="{{ old('kode') }}"
                            placeholder="Masukkan kode jurusan"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg
                            focus:ring-2 focus:ring-blue-500 focus:border-transparent
                            transition duration-200 @error('kode') border-red-500 @enderror">
                        @error('kode')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Jurusan -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Jurusan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                            id="nama" 
                            name="nama"
                            value="{{ old('nama') }}"
                            placeholder="Masukkan nama jurusan"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg
                            focus:ring-2 focus:ring-blue-500 focus:border-transparent
                            transition duration-200 @error('nama') border-red-500 @enderror">
                        @error('nama')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.jurusan.index') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-500 hover:bg-gray-600 
                            text-white rounded-lg font-medium transition duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 
                            text-white rounded-lg font-medium transition duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            Simpan Jurusan
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection
