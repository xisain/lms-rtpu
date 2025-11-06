@extends('layout.sidebar')

@section('content')
    <div class="container mx-auto px-4 py-9">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            {{-- Header --}}
            <div class="bg-[#009999] px-6 py-4">
                <h4 class="text-xl font-bold text-white">
                    Tambah Plan Baru
                </h4>
            </div>

            {{-- Form --}}
            <div class="p-6">
                <form action="{{ route('admin.plan.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Nama Plan --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Plan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" placeholder="Masukan Nama Plan"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#009999] focus:border-transparent transition duration-200 @error('name') border-red-500 @enderror"
                            value="{{ old('name') }}">
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
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#009999] focus:border-transparent transition duration-200 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
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
                                value="{{ old('price') }}">
                            <input type="hidden" name="price" id="price" value="{{ old('price') }}">
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
                            value="{{ old('duration_in_days') }}">
                        @error('duration_in_days')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fitur Plan --}}
                    <div>
                        <label for="features" class="block text-sm font-medium text-gray-700 mb-2">
                            Fitur Plan (opsional)
                        </label>
                        <input type="text" name="features" id="features"
                            placeholder="Ketik dan tekan Enter untuk menambah fitur"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <p class="text-xs text-gray-500 mt-1">Ketik fitur dan tekan Enter. Contoh: Akses Premium, Sertifikat, Support 24 Jam</p>
                        @error('features')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Course --}}
                    <div>
                        <label for="course" class="block text-sm font-medium text-gray-700 mb-2">
                            Course <span class="text-red-500">*</span>
                        </label>
                        <select name="course[]" id="course" multiple class="w-full">
                            @forelse ($course as $c)
                                <option value="{{ $c->id }}" {{ (collect(old('course'))->contains($c->id)) ? 'selected' : '' }}>
                                    {{ $c->nama_course }}
                                </option>
                            @empty
                                <option value="" disabled>Tidak ada Course</option>
                            @endforelse
                        </select>
                        @error('course')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="is_active" value="1" class="text-[#009999]"
                                    {{ old('is_active', 1) == 1 ? 'checked' : '' }}>
                                <span class="ml-2">Aktif</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="is_active" value="0" class="text-[#009999]"
                                    {{ old('is_active') == 0 ? 'checked' : '' }}>
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

@push('styles')
<style>
    .choices__inner {
        border-radius: 0.5rem;
        border-color: #d1d5db;
        min-height: 42px;
        padding: 0.375rem 0.75rem;
    }
    .choices__list--multiple .choices__item {
        background-color: #009999;
        border-color: #007f7f;
        border-radius: 0.375rem;
    }
    .choices[data-type*=select-multiple] .choices__button,
    .choices[data-type*=text] .choices__button {
        border-left-color: rgba(255, 255, 255, 0.4);
    }
    .choices__list--dropdown .choices__item--selectable.is-highlighted {
        background-color: #009999;
    }
    .is-focused .choices__inner {
        border-color: #009999;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Price formatting (guard element existence)
        const priceInput = document.getElementById('price_display');
        const hiddenPrice = document.getElementById('price');

        if (priceInput && hiddenPrice) {
            priceInput.addEventListener('input', function(e) {
                const value = e.target.value.replace(/[^0-9]/g, '');
                hiddenPrice.value = value;
                e.target.value = new Intl.NumberFormat('id-ID').format(value);
            });
        }

        // Initialize Choices.js when available (retry a few times)
        function initChoices(retry = 0) {
            if (typeof Choices === 'undefined') {
                if (retry > 20) {
                    console.warn('Choices.js not available after retries');
                    return;
                }
                return setTimeout(() => initChoices(retry + 1), 50);
            }

            // Features (tags input)
            const featuresInput = document.getElementById('features');
            if (featuresInput) {
                const featuresChoices = new Choices(featuresInput, {
                    removeItemButton: true,
                    addItems: true,
                    duplicateItemsAllowed: false,
                    delimiter: ',',
                    editItems: true,
                    maxItemCount: -1,
                    placeholder: true,
                    placeholderValue: 'Ketik dan tekan Enter untuk menambah fitur',
                    searchEnabled: false,
                    addItemText: (value) => {
                        return `Tekan Enter untuk menambah "<b>${value}</b>"`;
                    },
                });

                // Set old values for features if validation fails
                @if(old('features'))
                    const oldFeatures = "{{ old('features') }}".split(',');
                    featuresChoices.setValue(oldFeatures);
                @endif
            }

            // Course (multi-select)
            const courseSelect = document.getElementById('course');
            if (courseSelect) {
                const courseChoices = new Choices(courseSelect, {
                    removeItemButton: true,
                    searchEnabled: true,
                    searchPlaceholderValue: 'Cari course...',
                    noResultsText: 'Tidak ada hasil',
                    noChoicesText: 'Tidak ada pilihan',
                    itemSelectText: 'Klik untuk memilih',
                    maxItemCount: -1,
                    placeholder: true,
                    placeholderValue: 'Pilih course',
                    shouldSort: false,
                });
            }
        }

        initChoices();
    });
</script>
@endpush
