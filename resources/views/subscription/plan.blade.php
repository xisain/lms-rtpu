@extends('layout.navbar')
@section('title') Pilih Plan Subscription @endsection

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Header Section -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Pilih Plan yang Tepat untuk Anda</h1>
        <p class="text-xl text-gray-600">Dapatkan akses ke course berkualitas dengan harga terjangau</p>
    </div>

    <!-- Plans Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
        @forelse($plans as $plan)
        <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 overflow-hidden border border-gray-200 flex flex-col
            {{ str_contains($plan->name, 'Premium') ? 'ring-2 ring-blue-500 transform scale-105' : '' }}">

            <!-- Popular Badge (untuk plan tertentu) -->
            @if(str_contains($plan->name, 'Premium'))
            <div class="bg-blue-500 text-white text-center py-2 px-4 font-semibold text-sm">
                ⭐ PALING POPULER
            </div>
            @endif

            <!-- Plan Header -->
            <div class="p-8 text-center {{ str_contains($plan->name, 'Premium') ? 'bg-gradient-to-br from-blue-50 to-indigo-50' : 'bg-gray-50' }}">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h2>
                <p class="text-gray-600 text-sm mb-4">{{ $plan->description }}</p>

                <!-- Price -->
                <div class="mb-4">
                    <span class="text-3xl font-bold text-gray-900">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                </div>

                <!-- Duration Badge -->
                <div class="inline-block px-4 py-2 rounded-full text-sm font-medium shadow-sm
                    {{ str_contains($plan->name, 'Premium')
                        ? 'bg-gradient-to-r from-yellow-400 via-orange-400 to-red-400 text-white animate-fire'
                        : 'bg-white text-gray-700' }}">
                    🔥 Akses {{ $plan->duration_in_days }} hari
                </div>
            </div>

            <!-- Features List -->
            <div class="p-8 flex-grow">
                <h3 class="font-semibold text-gray-900 mb-4 text-lg">Fitur yang Didapatkan:</h3>
                <ul class="space-y-3 mb-6">
                    @if(is_array($plan->features) && count($plan->features) > 0)
                        @foreach($plan->features as $feature)
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">{{ $feature }}</span>
                        </li>
                        @endforeach
                    @else
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Akses ke semua course</span>
                        </li>
                    @endif
                </ul>

                <!-- Course Access Info -->
                @if($plan->course && count($plan->course) > 0)
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-900 mb-2 text-sm">Course yang Tersedia:</h4>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-sm text-gray-700">
                            <span class="font-semibold text-blue-600">{{ count($plan->course) }}</span> course premium
                        </p>
                    </div>
                </div>
                @endif
            </div>

            <!-- CTA Button -->
            <div class="p-8 pt-0">
                @if($plan->is_active)
                <a href="{{ route('plan.checkout', $plan->id) }}"
                    class="block w-full py-4 px-6 rounded-xl font-semibold text-lg text-center transition-all duration-200
                    {{ $plan->name === 'Premium'
                        ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-lg hover:shadow-xl'
                        : 'bg-[#00999a] hover:bg-teal-800 text-white' }}">
                    Pilih Plan Ini
                </a>
                @else
                <button disabled
                    class="w-full py-4 px-6 rounded-xl font-semibold text-lg bg-gray-300 text-gray-500 cursor-not-allowed">
                    Tidak Tersedia
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <div class="text-gray-400 mb-4">
                <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Plan Tersedia</h3>
            <p class="text-gray-500">Silakan hubungi admin untuk informasi lebih lanjut</p>
        </div>
        @endforelse
    </div>

    <!-- FAQ or Additional Info -->
    <div class="mt-16 text-center max-w-3xl mx-auto">
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">💡 Butuh Bantuan Memilih?</h3>
            <p class="text-gray-700 mb-4">
                Tidak yakin plan mana yang cocok untuk Anda? Hubungi tim support kami untuk konsultasi gratis!
            </p>
            <a href="#" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors">
                Hubungi Kami
            </a>
        </div>
    </div>
</div>

<style>
    /* Custom animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fire {
        0%, 100% {
            transform: scale(1) translateY(0);
            filter: brightness(1);
        }
        25% {
            transform: scale(1.05) translateY(-2px);
            filter: brightness(1.2);
        }
        50% {
            transform: scale(1) translateY(0);
            filter: brightness(1.1);
        }
        75% {
            transform: scale(1.05) translateY(-2px);
            filter: brightness(1.2);
        }
    }

    .animate-fire {
        animation: fire 2s ease-in-out infinite;
        box-shadow: 0 4px 15px rgba(251, 146, 60, 0.5);
    }

    .grid > div {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .grid > div:nth-child(1) { animation-delay: 0.1s; }
    .grid > div:nth-child(2) { animation-delay: 0.2s; }
    .grid > div:nth-child(3) { animation-delay: 0.3s; }
</style>
@endsection
