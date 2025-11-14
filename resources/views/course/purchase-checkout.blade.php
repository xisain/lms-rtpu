@extends('layout.navbar')

@section('content')
<div class="min-h-screen bg-gray-100 py-12">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#009999] to-[#0f5757] px-6 py-8">
                <h1 class="text-3xl font-bold text-white">Buy Course</h1>
                <p class="text-gray-100 mt-2">Complete your course purchase</p>
            </div>

            <!-- Course Summary -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-24 h-24">
                        <img src="{{ asset('storage/'.$course->image_link) }}" alt="{{ $course->nama_course }}" class="w-full h-full object-cover rounded">
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-800">{{ $course->nama_course }}</h2>
                        <p class="text-gray-600 text-sm mt-1">{{ $course->description }}</p>
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <p class="text-2xl font-bold text-[#009999]">Rp {{ number_format($course->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <form action="{{ route('course.purchase') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf

                <!-- Course ID -->
                <input type="hidden" name="course_id" value="{{ $course->id }}">

                <!-- Payment Method -->
                <div class="mb-6">
                    <label for="payment_method_id" class="block text-sm font-semibold text-gray-700 mb-2">Payment Method <span class="text-red-500">*</span></label>
                    <select id="payment_method_id" name="payment_method_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009999] focus:border-transparent" required>
                        <option value="">-- Select Payment Method --</option>
                        @foreach($paymentMethod as $method)
                            <option value="{{ $method->id }}" {{ old('payment_method_id') == $method->id ? 'selected' : '' }}>
                                {{ $method->nama }} - {{ $method->account_name }} ({{ $method->account_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('payment_method_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009999] focus:border-transparent" placeholder="Tambahkan catatan pembayaran Anda..."></textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Proof -->
                <div class="mb-6">
                    <label for="payment_proof" class="block text-sm font-semibold text-gray-700 mb-2">Payment Proof <span class="text-red-500">*</span></label>
                    <p class="text-xs text-gray-500 mb-2">Upload screenshot/photo of your payment proof (JPG, JPEG, PNG, PDF - Max 2MB)</p>
                    <input type="file" id="payment_proof" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009999] focus:border-transparent" required>
                    @error('payment_proof')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alert -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <strong>Note:</strong> Your course purchase will be pending admin approval. Once approved, you will automatically have access to the course.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <a href="{{ route('course.show', $course->slugs) }}" class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-center">
                        Cancel
                    </a>
                    <button type="submit" class="flex-1 px-4 py-3 bg-[#009999] text-white font-semibold rounded-lg hover:bg-[#0f5757] transition">
                        Confirm Purchase
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
