@extends('layout.sidebar')
@section('title')

@endsection
@section('content')
    {{-- {{ $courseWithReview }} --}}
    <div class="mx-auto py-2">
        <div class="max-w-10xl mx-auto">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-[#009999] px-6 py-4 flex justify-between items-center">
                    <h4 class="text-xl font-bold text-white">Review Tugas Akhir Peserta</h4>
                </div>
                <div class="overflow-x-auto p-4">
                    <table class="min-w-full divide-y divide-gray-200" id="finalTaskTable">
                        <thead class="bg-gray-50">
                            <tr class="">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase tracking-wider">#
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase">Nama
                                    Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray uppercase">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-x divide-y divide-gray-200">
                            @forelse ($courseWithReview as $index => $cwr)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cwr->nama_course }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('dosen.course.final_task.list',$cwr->slugs) }}">Lihat</a></td>
                            </tr>
                            @empty

                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
