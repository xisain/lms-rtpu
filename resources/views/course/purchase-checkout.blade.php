@extends('layout.navbar')
@section('title') Checkout - {{ $course->nama_course }} @endsection

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="mb-8">
        <a href="{{ route('course.show', $course->slugs) }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Detail Course
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Left Column - Payment Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Detail Pembayaran</h2>

                <form action="{{ route('course.purchase') }}" method="POST" enctype="multipart/form-data" id="paymentForm">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">

                    <!-- Payment Method Selection -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-4">
                            Pilih Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <div class="grid sm:grid-cols-2 gap-4">
                            @forelse($paymentMethod as $method)
                            <label class="relative cursor-pointer">
                                <input type="radio"
                                       name="payment_method_id"
                                       value="{{ $method->id }}"
                                       class="peer sr-only"
                                       {{ old('payment_method_id') == $method->id ? 'checked' : '' }}
                                       required>
                                <div class="border-2 border-gray-200 rounded-xl p-4 hover:border-[#009999] transition-all
                                            peer-checked:border-[#009999] peer-checked:bg-[#009999]/10 peer-checked:shadow-md">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-semibold text-gray-900">{{ $method->nama }}</span>
                                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-[#009999] peer-checked:bg-[#009999] flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="currentColor" viewBox="0 0 12 12">
                                                <path d="M10 3L4.5 8.5L2 6"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    @if($method->account_number)
                                    <p class="text-sm text-gray-600">{{ $method->account_number }}</p>
                                    @endif
                                    @if($method->account_name)
                                    <p class="text-xs text-gray-500 mt-1">a.n {{ $method->account_name }}</p>
                                    @endif
                                </div>
                            </label>
                            @empty
                            <div class="col-span-2 text-center py-8 text-gray-500">
                                <p>Belum ada metode pembayaran tersedia</p>
                            </div>
                            @endforelse
                        </div>
                        @error('payment_method_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Proof Upload -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Bukti Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-gray-500 mb-3">Upload screenshot atau foto bukti transfer Anda (Max: 2MB, Format: JPG, PNG, PDF)</p>

                        <div class="relative">
                            <input type="file"
                                   name="payment_proof"
                                   id="payment_proof"
                                   accept="image/*,.pdf"
                                   class="hidden"
                                   required
                                   onchange="handleFileSelect(this)">
                            <label for="payment_proof"
                                   class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6" id="uploadPlaceholder">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG, atau PDF (Max 2MB)</p>
                                </div>
                                <div class="hidden" id="filePreview">
                                    <img id="previewImage" class="max-h-32 rounded-lg" alt="Preview">
                                    <p id="fileName" class="mt-2 text-sm text-gray-700 font-medium"></p>
                                </div>
                            </label>
                        </div>
                        @error('payment_proof')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes (Optional) -->
                    <div class="mb-8">
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea name="notes"
                                  id="notes"
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#009999] focus:border-transparent transition-all"
                                  placeholder="Tambahkan catatan jika diperlukan...">{{ old('notes') }}</textarea>
                        @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="mb-8">
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox"
                                   name="agree_terms"
                                   class="mt-1 w-4 h-4 text-[#009999] border-gray-300 rounded focus:ring-[#009999]"
                                   required>
                            <span class="ml-3 text-sm text-gray-700">
                                Saya setuju dengan <a href="#" class="text-[#009999] hover:underline">syarat dan ketentuan</a>
                                yang berlaku
                            </span>
                        </label>
                        @error('agree_terms')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full bg-[#009999] hover:bg-[#0f5757] text-white font-semibold py-4 px-6 rounded-xl
                                   transition-all duration-200 shadow-lg hover:shadow-xl">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Proses Pembayaran
                        </span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column - Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Ringkasan Pesanan</h3>

                <!-- Course Details -->
                <div class="mb-6">
                    <div class="relative rounded-xl overflow-hidden mb-4">
                        <img src="{{ asset('storage/'.$course->image_link) }}"
                             alt="{{ $course->nama_course }}"
                             class="w-full h-48 object-cover">
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <h4 class="font-semibold text-gray-900 mb-2">{{ $course->nama_course }}</h4>
                        <p class="text-sm text-gray-600 line-clamp-3">{{ $course->description }}</p>
                    </div>
                </div>

                <!-- Price Breakdown -->
                <div class="space-y-3 mb-6 pb-6 border-b border-gray-200">
                    <div class="flex justify-between text-gray-700">
                        <span>Harga Course</span>
                        <span class="font-medium">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Biaya Admin</span>
                        <span class="font-medium">Rp 0</span>
                    </div>
                </div>

                <!-- Total -->
                <div class="mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-900">Total Pembayaran</span>
                        <span class="text-2xl font-bold text-[#009999]">
                            Rp {{ number_format($course->price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Info Notice -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
                    <p class="text-xs text-gray-700">
                        <span class="font-semibold">ℹ️ Informasi Penting</span><br>
                        Pembelian course Anda akan menunggu persetujuan admin. Setelah disetujui, Anda akan otomatis mendapatkan akses ke course.
                    </p>
                </div>

                <!-- Help Info -->
                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                    <p class="text-xs text-gray-700">
                        <span class="font-semibold">💡 Butuh bantuan?</span><br>
                        Hubungi customer service kami jika ada pertanyaan seputar pembayaran.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function handleFileSelect(input) {
    const file = input.files[0];
    const placeholder = document.getElementById('uploadPlaceholder');
    const preview = document.getElementById('filePreview');
    const previewImage = document.getElementById('previewImage');
    const fileName = document.getElementById('fileName');

    if (file) {
        // Check file size (2MB = 2097152 bytes)
        if (file.size > 2097152) {
            alert('Ukuran file terlalu besar! Maksimal 2MB');
            input.value = '';
            return;
        }

        fileName.textContent = file.name;

        // If image file, show preview
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                placeholder.classList.add('hidden');
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            // For PDF or other files, just show filename
            placeholder.classList.add('hidden');
            preview.classList.remove('hidden');
            previewImage.classList.add('hidden');
        }
    }
}

// Form validation
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    const paymentMethod = document.querySelector('input[name="payment_method_id"]:checked');
    const paymentProof = document.getElementById('payment_proof');
    const agreeTerms = document.querySelector('input[name="agree_terms"]');

    if (!paymentMethod) {
        e.preventDefault();
        alert('Silakan pilih metode pembayaran!');
        return;
    }

    if (!paymentProof.files.length) {
        e.preventDefault();
        alert('Silakan upload bukti pembayaran!');
        return;
    }

    if (!agreeTerms.checked) {
        e.preventDefault();
        alert('Silakan setujui syarat dan ketentuan!');
        return;
    }
});
</script>

<style>
    /* Custom radio button styling */
    input[type="radio"]:checked + div {
        animation: scaleIn 0.2s ease-out;
    }

    .peer:checked + div {
        border-color: #009999 !important;
        background-color: rgba(0, 153, 153, 0.08) !important;
    }

    .peer:checked + div .peer-checked\:border-\[\#009999\] {
        border-color: #009999 !important;
    }

    .peer:checked + div .peer-checked\:bg-\[\#009999\] {
        background-color: #009999 !important;
    }

    .peer:checked + div .peer-checked\:block {
        display: block !important;
    }

    @keyframes scaleIn {
        from {
            transform: scale(0.95);
        }
        to {
            transform: scale(1);
        }
    }

    /* Smooth transitions */
    .transition-all {
        transition: all 0.3s ease;
    }

    /* Line clamp utility */
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
