@extends('layout.sidebar')
@section('title', 'Course')
@section('content')
    <div class="mx-auto py-2">
        <div class="max-w-8xl mx-auto">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-5">
                <div class="bg-[#009999] px-6 py-4 flex justify-between items-center">
                    <h4 class="text-xl font-bold text-white">Daftar Course</h4>
                    <a href="{{ route('course.create') }}"
                        class="bg-white text-dark hover:bg-gray-200 px-4 py-2 rounded-lg font-semibold transition duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Course
                    </a>
                </div>
                <div class="overflow-x-auto p-4">
                    <!-- Custom Controls -->
                    <div class="flex justify-between items-center mb-4 flex-wrap gap-4">
                        <div class="flex items-center gap-2">
                            <label class="text-gray-700 font-medium">Tampilkan</label>
                            <select id="customLength" class="bg-white border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#009999] focus:border-[#009999]">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="-1">Semua</option>
                            </select>
                            <label class="text-gray-700 font-medium">data</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-gray-700 font-medium">Cari:</label>
                            <input type="text" id="customSearch" class="bg-white border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#009999] focus:border-[#009999]" placeholder="Cari...">
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table id="courseTable" class="display w-full">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Course</th>
                                    <th>Deskripsi</th>
                                    <th>Periode</th>
                                    <th>Material</th>
                                    <th>Status</th>
                                    <th class="">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($course as $index => $c)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="font-semibold">{{ $c->nama_course }}</td>
                                        <td>{{ Str::limit($c->description, 50) }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($c->start_date)->format('d M Y') }} -
                                            {{ \Carbon\Carbon::parse($c->end_date)->format('d M Y') }}
                                        </td>
                                        <td>
                                            @php
                                                $totalMaterial = $c->material->count();
                                                $totalSubmaterial = $c->material->sum(fn($m) => $m->submaterial->count());
                                            @endphp
                                            <span class="font-semibold">{{ $totalMaterial }}</span>
                                            (<span class="font-semibold">{{ $totalSubmaterial }}</span>)
                                        </td>
                                        <td>
                                            @if ($c->public)
                                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Publik</span>
                                            @else
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">Private</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="flex justify-center items-center gap-4">
                                                <a href="{{ route('course.show', $c->slugs) }}"
                                                    class="text-blue-600 hover:underline" title="Lihat Course">
                                                    <i class="fa-solid fa-eye text-lg"></i>
                                                </a>
                                                <a href="{{ route('dosen.course.edit', $c->id) }}"
                                                    class="text-yellow-400 hover:underline" title="Edit">
                                                    <i class="fa-solid fa-pen text-lg"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Custom Info and Pagination -->
                    <div class="flex justify-between items-center mt-4 flex-wrap gap-4">
                        <div id="customInfo" class="text-gray-700 font-medium"></div>
                        <div id="customPagination" class="flex gap-1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Hide default DataTables controls */
        #courseTable_wrapper .dataTables_length,
        #courseTable_wrapper .dataTables_filter,
        #courseTable_wrapper .dataTables_info,
        #courseTable_wrapper .dataTables_paginate {
            display: none !important;
        }

        /* Custom pagination buttons */
        .custom-page-btn {
            background-color: white;
            color: #333;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
        }

        .custom-page-btn:hover:not(.disabled):not(.active) {
            background-color: #f3f4f6;
            color: #009999;
            border-color: #009999;
        }

        .custom-page-btn.active {
            background-color: #009999;
            color: white;
            border-color: #009999;
        }

        .custom-page-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const courseTable = new DataTable('#courseTable', {
                language: {
                    processing: 'Memuat data...',
                    search: '',
                    lengthMenu: '',
                    info: '',
                    infoEmpty: '',
                    infoFiltered: '',
                    zeroRecords: 'Tidak ada data yang ditemukan',
                    emptyTable: 'Tidak ada data yang tersedia',
                    paginate: {
                        first: '>>',
                        last: '<<',
                        next: '>',
                        previous: '<'
                    }
                },
                order: [[1, 'desc']],
                columnDefs: [
                    {
                        targets: -1,
                        orderable: false,
                        searchable: false
                    }
                ],
                pageLength: 10,
                responsive: true,
                dom: 'rt' // Only show table
            });

            // Custom search
            $('#customSearch').on('keyup', function() {
                courseTable.search(this.value).draw();
                updateCustomInfo();
                updateCustomPagination();
            });

            // Custom length change
            $('#customLength').on('change', function() {
                const length = parseInt(this.value);
                courseTable.page.len(length).draw();
                updateCustomInfo();
                updateCustomPagination();
            });

            // Function to update custom info
            function updateCustomInfo() {
                const info = courseTable.page.info();
                let infoText = '';

                if (info.recordsDisplay === 0) {
                    infoText = 'Menampilkan 0 sampai 0 dari 0 data';
                } else {
                    infoText = `Menampilkan ${info.start + 1} sampai ${info.end} dari ${info.recordsDisplay} data`;
                    if (info.recordsTotal !== info.recordsDisplay) {
                        infoText += ` (difilter dari ${info.recordsTotal} total data)`;
                    }
                }

                $('#customInfo').text(infoText);
            }

            // Function to update custom pagination
            function updateCustomPagination() {
                const info = courseTable.page.info();
                const totalPages = info.pages;
                const currentPage = info.page;

                let paginationHtml = '';

                // Previous button
                paginationHtml += `<button class="custom-page-btn ${currentPage === 0 ? 'disabled' : ''}" data-page="prev">&lt;</button>`;

                // Page numbers
                let startPage = Math.max(0, currentPage - 2);
                let endPage = Math.min(totalPages - 1, currentPage + 2);

                // First page
                if (startPage > 0) {
                    paginationHtml += `<button class="custom-page-btn" data-page="0">1</button>`;
                    if (startPage > 1) {
                        paginationHtml += `<span class="px-2 py-1">...</span>`;
                    }
                }

                // Page numbers
                for (let i = startPage; i <= endPage; i++) {
                    paginationHtml += `<button class="custom-page-btn ${i === currentPage ? 'active' : ''}" data-page="${i}">${i + 1}</button>`;
                }

                // Last page
                if (endPage < totalPages - 1) {
                    if (endPage < totalPages - 2) {
                        paginationHtml += `<span class="px-2 py-1">...</span>`;
                    }
                    paginationHtml += `<button class="custom-page-btn" data-page="${totalPages - 1}">${totalPages}</button>`;
                }

                // Next button
                paginationHtml += `<button class="custom-page-btn ${currentPage === totalPages - 1 ? 'disabled' : ''}" data-page="next">&gt;</button>`;

                $('#customPagination').html(paginationHtml);
            }

            // Handle pagination clicks
            $(document).on('click', '.custom-page-btn:not(.disabled)', function() {
                const page = $(this).data('page');

                if (page === 'prev') {
                    courseTable.page('previous').draw('page');
                } else if (page === 'next') {
                    courseTable.page('next').draw('page');
                } else {
                    courseTable.page(page).draw('page');
                }

                updateCustomInfo();
                updateCustomPagination();
            });

            // Initial update
            updateCustomInfo();
            updateCustomPagination();

            // Delete confirmation
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                Swal.fire({
                    title: 'Yakin Menghapus Course ini?',
                    text: "Data Yang Terhapus Termasuk Material, Submaterial dan Quiz. Data yang di hapus tidak bisa di kembalikan",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
