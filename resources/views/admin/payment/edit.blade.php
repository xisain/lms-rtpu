@extends('layout.sidebar')

@section('title', 'Edit Metode Pembayaran')

@section('content')
<div class="container mx-auto px-4 py-9">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">

        {{-- Header --}}
        <div class="px-6 py-4 bg-[#009999]">
            <h4 class="text-xl font-bold text-white">
                Edit Metode Pembayaran
            </h4>
        </div>

        {{-- Form --}}
        <div class="p-6">
            <form action="{{ route('admin.payment.update', $payment->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nama Pembayaran --}}
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" id="nama"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009999] focus:border-[#009999] @error('nama') border-red-500 @enderror"
                        value="{{ old('nama', $payment->nama) }}"
                        placeholder="Contoh: BCA, Mandiri, GoPay">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Pemilik Rekening --}}
                <div>
                    <label for="account_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Pemilik Rekening <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="account_name" id="account_name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009999] focus:border-[#009999] @error('account_name') border-red-500 @enderror"
                        value="{{ old('account_name', $payment->account_name) }}"
                        placeholder="Contoh: John Doe">
                    @error('account_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nomor Rekening --}}
                <div>
                    <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Rekening <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="account_number" id="account_number"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009999] focus:border-[#009999] @error('account_number') border-red-500 @enderror"
                        value="{{ old('account_number', $payment->account_number) }}"
                        placeholder="Contoh: 1234567890">
                    @error('account_number')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009999] focus:border-[#009999] @error('status') border-red-500 @enderror">
                        <option value="aktif" {{ old('status', $payment->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $payment->status) == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info Waktu --}}
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Dibuat pada:</span>
                            <p class="font-medium">
                                {{ $payment->created_at ? $payment->created_at->format('d M Y, H:i') : '-' }}
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-600">Terakhir diupdate:</span>
                            <p class="font-medium">
                                {{ $payment->updated_at ? $payment->updated_at->format('d M Y, H:i') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-end space-x-3 pt-4">
                    <a href="{{ route('admin.payment.index') }}"
                        class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-[#009999] text-white rounded-lg hover:bg-[#007777] transition">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
