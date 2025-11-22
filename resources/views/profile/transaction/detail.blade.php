@extends('layout.navbar')

@section('title', 'Transaction Detail')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('profile.transaction.history') }}"
                    class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Transactions
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Transaction Details</h1>
                <p class="mt-2 text-sm text-gray-600">Transaction #TRX-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>

            <!-- Status Banner -->
            <div class="mb-6">
                @if($transaction->status == 'waiting_approval')
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Your transaction is pending approval. We will review your payment proof shortly.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($transaction->status == 'approved')
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    Your transaction has been approved! You can now access the course.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 font-medium">
                                    Your transaction has been rejected.
                                </p>
                                @if($transaction->notes)
                                    <p class="text-sm text-red-600 mt-1">
                                        Reason: {{ $transaction->notes }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <!-- Transaction Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Transaction Information
                    </h2>
                    <dl class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <dt class="text-sm font-medium text-gray-500">Transaction ID</dt>
                            <dd class="text-sm font-semibold text-gray-900">#TRX-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <dt class="text-sm font-medium text-gray-500">Transaction Date</dt>
                            <dd class="text-sm text-gray-900">{{ $transaction->created_at->format('d F Y, H:i') }}</dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="text-sm">
                                @if($transaction->status == 'waiting_approval')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                                        Pending Approval
                                    </span>
                                @elseif($transaction->status == 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        Approved
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                        Rejected
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <dt class="text-sm font-medium text-gray-500">Amount Paid</dt>
                            <dd class="text-sm font-bold text-blue-600">{{ 'Rp ' . number_format($transaction->price_paid, 0, ',', '.') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Course Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Course Details
                    </h2>
                    <div class="space-y-4">
                        @if($transaction->course->image_link)
                            <img src="{{ asset('storage/' . $transaction->course->image_link) }}"
                                 alt="{{ $transaction->course->nama_course }}"
                                 class="w-full h-40 object-cover rounded-lg">
                        @else
                            <div class="w-full h-40 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-semibold text-gray-900 text-lg">{{ $transaction->course->nama_course }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($transaction->course->description, 100) }}</p>
                        </div>
                        @if($transaction->status == 'approved')
                            <a href="{{ route('course.show', $transaction->course->slugs) }}"
                                class="block w-full px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition-colors">
                                Go to Course
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Payment Information
                </h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <dl class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $transaction->payment->nama }}</dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <dt class="text-sm font-medium text-gray-500">Account Number</dt>
                            <dd class="text-sm text-gray-900">{{ $transaction->payment->account_number }}</dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <dt class="text-sm font-medium text-gray-500">Account Name</dt>
                            <dd class="text-sm text-gray-900">{{ $transaction->payment->account_name }}</dd>
                        </div>
                    </dl>
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Payment Proof</h3>
                        @if($transaction->payment_proof_link)
                            <a href="{{ asset('storage/' . $transaction->payment_proof_link) }}"
                               target="_blank"
                               class="block border border-gray-300 rounded-lg overflow-hidden hover:border-blue-500 transition-colors">
                                <img src="{{ asset('storage/' . $transaction->payment_proof_link) }}"
                                     alt="Payment Proof"
                                     class="w-full h-48 object-cover">
                                <div class="p-2 bg-gray-50 text-center">
                                    <span class="text-sm text-blue-600 hover:text-blue-800">Click to view full size</span>
                                </div>
                            </a>
                        @else
                            <div class="border border-gray-300 rounded-lg p-4 text-center text-gray-500">
                                No payment proof uploaded
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Notes Section (if rejected) -->
            @if($transaction->status == 'rejected' && $transaction->notes)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        Rejection Note
                    </h2>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-sm text-gray-700">{{ $transaction->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
