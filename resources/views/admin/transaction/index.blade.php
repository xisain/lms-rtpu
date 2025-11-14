@extends('layout.sidebar')
@section('title')
    Transaction
@endsection
@section('content')
    <div class="max-w-10xl mx-auto mt-2">
        <!-- Session Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3"
                    onclick="this.parentElement.style.display='none'">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3"
                    onclick="this.parentElement.style.display='none'">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </button>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-[#009999] px-6 py-4 flex justify-between items-center">
                <h4 class="text-xl font-bold text-white">
                    Tabel Transaksi
                </h4>
            </div>

            <!-- Tabs -->
            <div class="flex border-b border-gray-200">
                <button onclick="switchTab('plans')" id="plans-tab" class="flex-1 px-4 py-3 font-semibold text-[#009999] border-b-2 border-[#009999]">
                    Plan Subscriptions
                </button>
                <button onclick="switchTab('courses')" id="courses-tab" class="flex-1 px-4 py-3 font-semibold text-gray-600 border-b-2 border-transparent hover:text-gray-800">
                    Course Purchases
                </button>
            </div>

            <!-- Plans Tab -->
            <div id="plans-content" class="overflow-x-auto p-4">
                @if($subscriptions->isEmpty())
                <table class="min-w-full divide-y divide-gray-200">
                @else
                <table class="min-w-full divide-y divide-gray-200" id="transactionTable">
                @endif
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Mulai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Berakhir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Metode Pembayaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti
                                Pembayaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($subscriptions as $item)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->plan->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if ($item->starts_at)
                                        {{ $item->starts_at }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if ($item->ends_at)
                                        {{ $item->ends_at }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->payment->nama }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <button onclick="openModal('{{ asset('storage/' . $item->payment_proof_link) }}')"
                                        class="text-blue-600 hover:text-blue-800 underline">
                                        Lihat Bukti
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($item->status == 'waiting_approval')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Menunggu Persetujuan
                                        </span>
                                    @elseif($item->status == 'approved')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Disetujui
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($item->status == 'waiting_approval')
                                        <div class="flex space-x-2">
                                            <form action="{{ route('admin.transaction.approval', $item->id) }}"
                                                method="POST" class="inline-block">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit"
                                                    class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition duration-150">
                                                    Setujui
                                                </button>
                                            </form>
                                            <button type="button" onclick="openRejectModal({{ $item->id }})"
                                                class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition duration-150">
                                                Tolak
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada data transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Plans -->
            <div class="px-6 py-4 bg-gray-50">
                {{ $subscriptions->links() }}
            </div>

            <!-- Courses Tab Content -->
            <div id="courses-content" class="hidden">
                <div class="overflow-x-auto p-4">
                    @if($coursePurchases->isEmpty())
                    <table class="min-w-full divide-y divide-gray-200">
                    @else
                    <table class="min-w-full divide-y divide-gray-200" id="coursePurchaseTable">
                    @endif
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proof</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($coursePurchases as $item)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->course->nama_course }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Rp {{ number_format($item->price_paid, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->payment->nama }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <button onclick="openModal('{{ asset('storage/' . $item->payment_proof_link) }}')"
                                            class="text-blue-600 hover:text-blue-800 underline">
                                            View
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if ($item->status == 'waiting_approval')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Waiting Approval
                                            </span>
                                        @elseif ($item->status == 'approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Approved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                        @if ($item->status == 'waiting_approval')
                                            <button onclick="openApproveCourseModal({{ $item->id }})"
                                                class="text-green-600 hover:text-green-800 font-semibold">
                                                Approve
                                            </button>
                                            <button onclick="openRejectCourseModal({{ $item->id }})"
                                                class="text-red-600 hover:text-red-800 font-semibold">
                                                Reject
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                        No course purchase data
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Courses -->
                <div class="px-6 py-4 bg-gray-50">
                    {{ $coursePurchases->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Reject Course Purchase -->
    <div id="rejectCoursePurchaseModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Reject Course Purchase</h3>
                <button type="button" onclick="closeRejectCourseModal()"
                    class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="rejectCoursePurchaseForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="rejected">

                <div class="mb-4">
                    <label for="course_notes" class="block text-sm font-medium text-gray-700 mb-2">Reason for Rejection
                        <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="course_notes"
                        name="notes"
                        rows="4"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Enter rejection reason...">
                    </textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectCourseModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal untuk Approve Course Purchase -->
    <div id="approveCoursePurchaseModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Approve Course Purchase</h3>
                <button type="button" onclick="closeApproveCourseModal()"
                    class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">
                <p class="text-sm text-green-800">
                    This action will approve the course purchase and automatically enroll the user in the course.
                </p>
            </div>

            <form id="approveCoursePurchaseForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="approved">

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeApproveCourseModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        Approve
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="paymentProofModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Bukti Pembayaran</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="mt-2">
                <img id="paymentProofImage" src="" alt="Bukti Pembayaran" class="w-full h-auto rounded-lg">
            </div>
            <div class="flex justify-end mt-4">
                <button onclick="closeModal()"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition duration-150">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Modal untuk Penolakan dengan Catatan -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Tolak Transaksi</h3>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="rejected">

                <div class="mt-4">
                    <label for="rnotes" class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="notes"
                        name="notes"
                        rows="4"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Masukkan alasan penolakan transaksi..."
                    ></textarea>
                    <p class="mt-1 text-sm text-gray-500">
                        Catatan ini akan dikirimkan kepada pengguna.
                    </p>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeRejectModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition duration-150">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition duration-150">
                        Tolak Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#transactionTable').DataTable({
                    language: {
                        processing: 'Memuat data...',
                        search: 'Cari:',
                        lengthMenu: 'Tampilkan _MENU_ data',
                        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                        infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
                        infoFiltered: '(difilter dari _MAX_ total data)',
                        zeroRecords: 'Tidak ada data yang ditemukan',
                        emptyTable: 'Tidak ada data yang tersedia',
                        paginate: {
                            first: '<<',
                            last: '>>',
                            next: '>',
                            previous: '<'
                        }
                    },
                    order: [
                        [0, 'asc']
                    ]
                });

                $('#coursePurchaseTable').DataTable({
                    language: {
                        processing: 'Loading...',
                        search: 'Search:',
                        lengthMenu: 'Show _MENU_ records',
                        info: 'Showing _START_ to _END_ of _TOTAL_ records',
                        infoEmpty: 'Showing 0 to 0 of 0 records',
                        infoFiltered: '(filtered from _MAX_ total records)',
                        zeroRecords: 'No matching records found',
                        emptyTable: 'No data available',
                        paginate: {
                            first: '<<',
                            last: '>>',
                            next: '>',
                            previous: '<'
                        }
                    },
                    order: [
                        [0, 'asc']
                    ]
                });
            })

            function switchTab(tab) {
                const plansTab = document.getElementById('plans-tab');
                const coursesTab = document.getElementById('courses-tab');
                const plansContent = document.getElementById('plans-content');
                const coursesContent = document.getElementById('courses-content');

                if (tab === 'plans') {
                    plansTab.classList.add('border-b-2', 'border-[#009999]', 'text-[#009999]');
                    plansTab.classList.remove('border-transparent', 'text-gray-600');
                    coursesTab.classList.remove('border-b-2', 'border-[#009999]', 'text-[#009999]');
                    coursesTab.classList.add('border-transparent', 'text-gray-600');
                    plansContent.classList.remove('hidden');
                    coursesContent.classList.add('hidden');
                } else {
                    coursesTab.classList.add('border-b-2', 'border-[#009999]', 'text-[#009999]');
                    coursesTab.classList.remove('border-transparent', 'text-gray-600');
                    plansTab.classList.remove('border-b-2', 'border-[#009999]', 'text-[#009999]');
                    plansTab.classList.add('border-transparent', 'text-gray-600');
                    coursesContent.classList.remove('hidden');
                    plansContent.classList.add('hidden');
                }
            }
        </script>
    @endpush

    <script>
        // Payment Proof Modal Functions
        function openModal(imageUrl) {
            document.getElementById('paymentProofImage').src = imageUrl;
            document.getElementById('paymentProofModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('paymentProofModal').classList.add('hidden');
        }

        // Reject Modal Functions
        function openRejectModal(transactionId) {
            const form = document.getElementById('rejectForm');
            form.action = '/admin/transaction/' + transactionId + '/approval';
            document.getElementById('notes').value = '';
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('notes').value = '';
        }

        // Close modals when clicking outside
        document.getElementById('paymentProofModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });

        // Course Purchase Modal Functions
        function openApproveCourseModal(purchaseId) {
            const form = document.getElementById('approveCoursePurchaseForm');
            form.action = '/admin/transaction/course-purchase/' + purchaseId + '/approve';
            document.getElementById('approveCoursePurchaseModal').classList.remove('hidden');
        }

        function closeApproveCourseModal() {
            document.getElementById('approveCoursePurchaseModal').classList.add('hidden');
        }

        function openRejectCourseModal(purchaseId) {
            const form = document.getElementById('rejectCoursePurchaseForm');
            form.action = '/admin/transaction/course-purchase/' + purchaseId + '/approve';
            document.getElementById('course_notes').value = '';
            document.getElementById('rejectCoursePurchaseModal').classList.remove('hidden');
        }

        function closeRejectCourseModal() {
            document.getElementById('rejectCoursePurchaseModal').classList.add('hidden');
            document.getElementById('course_notes').value = '';
        }

        document.getElementById('rejectCoursePurchaseModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectCourseModal();
            }
        });

        document.getElementById('approveCoursePurchaseModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeApproveCourseModal();
            }
        });

        // Close modals with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closeRejectModal();
                closeRejectCourseModal();
                closeApproveCourseModal();
            }
        });
    </script>
@endsection
