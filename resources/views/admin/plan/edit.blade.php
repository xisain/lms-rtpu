@extends('layout.sidebar')

@section('content')
    <div class="container mx-auto px-4 py-9">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            {{-- Header --}}
            <div class="bg-[#009999] px-6 py-4">
                <h4 class="text-xl font-bold text-white">
                    Edit Plan
                </h4>
            </div>

            {{-- Form --}}
            <div class="p-6">
                <form action="{{ route('admin.plan.update',$plan->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method("PUT")
                    {{-- Nama Plan --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Plan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" placeholder="Masukan Nama Plan"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#009999] focus:border-transparent transition duration-200 @error('name') border-red-500 @enderror"
                            value="{{ old("name",$plan->name) }}">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" id="description" rows="4" placeholder="Masukan deskripsi plan"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#009999] focus:border-transparent transition duration-200 @error('description') border-red-500 @enderror">{{ old('description',$plan->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Harga --}}
                    <div>
                        <label for="price_display" class="block text-sm font-medium text-gray-700 mb-2">
                            Harga (IDR) <span class="text-red-500">*</span>
                        </label>
                        <div
                            class="flex rounded-lg border border-gray-300 focus-within:ring-2 focus-within:ring-[#009999] transition duration-200">
                            <span
                                class="inline-flex items-center px-3 bg-gray-100 border-r border-gray-300 text-gray-700 rounded-l-lg">
                                Rp
                            </span>
                            <input type="text" id="price_display" placeholder="Masukan Harga Plan"
                                class="w-full px-4 py-2 rounded-r-lg focus:ring-0 focus:border-transparent"
                                value="{{ old('price', intval($plan->price)) }}">
                            <input type="hidden" name="price" id="price" value="{{ old('price', intval($plan->price)) }}">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Ketik angka saja, contoh: 150000</p>
                        @error('price')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Durasi --}}
                    <div>
                        <label for="duration_in_days" class="block text-sm font-medium text-gray-700 mb-2">
                            Durasi (hari) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="duration_in_days" id="duration_in_days"
                            placeholder="Contoh: 30 untuk 30 hari"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#009999] focus:border-transparent transition duration-200 @error('duration_in_days') border-red-500 @enderror"
                            value="{{ old('duration_in_days', $plan->duration_in_days) }}">
                        @error('duration_in_days')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fitur Plan --}}
                    <div>
                        <label for="features" class="block text-sm font-medium text-gray-700 mb-2">
                            Fitur Plan (opsional)
                        </label>
                        <textarea name="features" id="features" rows="3"
                            placeholder="Pisahkan fitur dengan koma, misal: Akses Premium, Sertifikat, Support 24 Jam"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#009999] focus:border-transparent transition duration-200">{{ old('features', is_array($plan->features) ? implode(', ', $plan->features) : $plan->features) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Contoh: Akses Premium, Sertifikat, Support 24 Jam</p>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="is_active" value="1" class="text-[#009999]"
                                    {{ old('is_active', $plan->is_active) == 1 ? 'checked' : '' }}>
                                <span class="ml-2">Aktif</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="is_active" value="0" class="text-[#009999]"
                                    {{ old('is_active',$plan->is_active) == 0 ? 'checked' : '' }}>
                                <span class="ml-2">Nonaktif</span>
                            </label>
                        </div>
                        @error('is_active')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-6 py-2 bg-[#009999] text-white rounded-lg hover:bg-[#007f7f] transition duration-200">
                            Simpan Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    const priceInput = document.getElementById('price_display');
    const hiddenPrice = document.getElementById('price');

    // Saat user mengetik harga baru
    priceInput.addEventListener('input', function(e) {
        const value = e.target.value.replace(/[^0-9]/g, '');
        hiddenPrice.value = value;
        e.target.value = new Intl.NumberFormat('id-ID').format(value);
    });

    // Saat halaman pertama kali dibuka (format awal)
    document.addEventListener('DOMContentLoaded', function () {
        const initialValue = priceInput.value.replace(/[^0-9]/g, '');
        priceInput.value = new Intl.NumberFormat('id-ID').format(initialValue);
        hiddenPrice.value = initialValue;
    });
</script>
@endpush
