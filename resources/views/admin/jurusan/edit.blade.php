@extends('layout.sidebar')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">

            <!-- Header -->
            <div class="bg-gradient-to-r from-teal-500 to-teal-700 px-6 py-4">
                <h4 class="text-xl font-bold text-white">Edit Jurusan</h4>
            </div>

            <div class="p-6">
                <form action="{{ route('admin.jurusan.update', $jurusan->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Kode Jurusan -->
                    <div>
                        <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Jurusan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                            id="kode" 
                            name="kode"
                            value="{{ old('kode', $jurusan->kode) }}"
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
                            value="{{ old('nama', $jurusan->nama) }}"
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
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-500 hover:bg-teal-700 
                            text-white rounded-lg font-medium transition duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Update Jurusan
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection
