@extends('layout.sidebar')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-teal-500 to-teal-700 px-6 py-4">
                <h4 class="text-xl font-bold text-white">Tambah Category Baru</h4>
            </div>

            <div class="p-6">
                <form action="{{ route('admin.category.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Category Name -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('category') border-red-500 @enderror"
                               id="category"
                               name="category"
                               value="{{ old('category') }}"
                               placeholder="Enter category name"
                               required>
                        @error('category')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('description') border-red-500 @enderror"
                                  id="description"
                                  name="description"
                                  rows="4"
                                  placeholder="Enter category description">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            Type
                        </label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('type') border-red-500 @enderror"
                                id="type"
                                name="type">
                            <option value="">Select Type</option>
                            <option value="pekerti" {{ old('type') == 'pekerti' ? 'selected' : '' }}>Pekerti</option>
                            <option value="pelatihan" {{ old('type') == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                        </select>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Instansi -->
                    <div>
                        <label for="instansi_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Instansi
                        </label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('instansi_id') border-red-500 @enderror"
                                id="instansi_id"
                                name="instansi_id">
                            <option value="">Select Instansi</option>
                            @foreach(\App\Models\Instansi::all() as $instansi)
                                <option value="{{ $instansi->id }}" {{ old('instansi_id') == $instansi->id ? 'selected' : '' }}>
                                    {{ $instansi->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('instansi_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Is Private -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <input type="checkbox"
                                   name="is_private"
                                   value="1"
                                   class="rounded border-gray-300 text-teal-600 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                   {{ old('is_private') ? 'checked' : '' }}>
                            <span class="ml-2">Private Category</span>
                        </label>
                        @error('is_private')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.category.index') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back
                        </a>
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-medium transition duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            Save Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
