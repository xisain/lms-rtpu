@extends('layout.layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <main class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Header</h1>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                    when an unknown printer took a galley of type and scrambled it to make a type 
                    specimen book. It has survived not only five centuries, but also the leap into 
                    electronic typesetting, remaining essentially unchanged.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    It was popularised in the 1960s with the release of Letraset sheets containing 
                    Lorem Ipsum passages, and more recently with desktop publishing software like 
                    Aldus PageMaker including versions of Lorem Ipsum.
                </p>
            </div>
        </main>

        <!-- Sidebar -->
        <aside class="lg:col-span-1">
            <div class="sticky top-8">
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <!-- Module Header -->
                    <div class="bg-gradient-to-r from-purple-500 to-purple-700 text-white px-6 py-4">
                        <h2 class="text-xl font-semibold">Course Module</h2>
                    </div>

                    <!-- Module List -->
                    <div class="divide-y divide-gray-200">
                        @php
                            $modules = [
                                [
                                    'title' => 'Pendahuluan',
                                    'submodules' => [
                                        'Pengenalan Materi',
                                        'Tujuan Pembelajaran',
                                        'Metode Belajar'
                                    ]
                                ],
                                [
                                    'title' => 'Materi Inti',
                                    'submodules' => [
                                        'Konsep Dasar',
                                        'Praktik Pembelajaran',
                                        'Studi Kasus',
                                        'Diskusi Kelompok'
                                    ]
                                ],
                                [
                                    'title' => 'Evaluasi',
                                    'submodules' => [
                                        'Quiz Harian',
                                        'Tugas Praktik',
                                        'Ujian Akhir'
                                    ]
                                ]
                            ];
                        @endphp

                        @foreach($modules as $index => $module)
                            <div class="module-item">
                                <button 
                                    onclick="toggleModule({{ $index }})"
                                    class="w-full px-6 py-4 flex justify-between items-center hover:bg-purple-50 transition-colors group"
                                >
                                    <span class="font-medium text-gray-800 group-hover:text-purple-600 transition">
                                        {{ $module['title'] }}
                                    </span>
                                    <svg 
                                        id="icon-{{ $index }}" 
                                        class="w-5 h-5 text-gray-500 group-hover:text-purple-600 transition-all duration-300" 
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                                
                                <div id="module-{{ $index }}" class="module-content bg-purple-50 max-h-0 overflow-hidden transition-all duration-400">
                                    <div class="px-6 py-3 space-y-2">
                                        @foreach($module['submodules'] as $subIndex => $submodule)
                                            <div 
                                                onclick="toggleCompletion({{ $index }}, {{ $subIndex }})"
                                                class="submodule-item flex items-center gap-3 px-4 py-3 bg-white rounded-lg cursor-pointer hover:shadow-md transition-all border-l-4 border-transparent hover:border-purple-500"
                                            >
                                                <div class="checkbox w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center transition-all">
                                                    <svg class="w-3 h-3 text-white opacity-0 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                <span class="text-sm text-gray-700">{{ $submodule }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>

@push('scripts')
<script>
    function toggleModule(index) {
        const content = document.getElementById(`module-${index}`);
        const icon = document.getElementById(`icon-${index}`);
        
        // Close all other modules
        document.querySelectorAll('.module-content').forEach((el, i) => {
            if (i !== index) {
                el.style.maxHeight = '0px';
                document.getElementById(`icon-${i}`).style.transform = 'rotate(0deg)';
            }
        });
        
        // Toggle current module
        if (content.style.maxHeight && content.style.maxHeight !== '0px') {
            content.style.maxHeight = '0px';
            icon.style.transform = 'rotate(0deg)';
        } else {
            content.style.maxHeight = content.scrollHeight + 'px';
            icon.style.transform = 'rotate(90deg)';
        }
    }