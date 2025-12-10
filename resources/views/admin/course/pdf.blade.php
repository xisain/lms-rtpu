<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pelatihan - {{ $course->nama_course }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            margin: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 20px;
        }

        .header p {
            margin: 3px 0;
            color: #7f8c8d;
            font-size: 10px;
        }

        .section-title {
            background-color: #3498db;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0 10px 0;
        }

        /* Table Styles - Excel Like */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }

        table th {
            background-color: #4472C4;
            color: white;
            font-weight: bold;
            padding: 8px 6px;
            border: 1px solid #2E5C9A;
            text-align: left;
        }

        table td {
            padding: 6px;
            border: 1px solid #D0D0D0;
            vertical-align: top;
        }

        table tbody tr:nth-child(even) {
            background-color: #F2F2F2;
        }

        table tbody tr:nth-child(odd) {
            background-color: #FFFFFF;
        }

        table tbody tr:hover {
            background-color: #E7E6E6;
        }

        /* Specific column styling */
        .col-no {
            width: 5%;
            text-align: center;
        }

        .col-name {
            width: 15%;
        }

        .col-status {
            width: 10%;
            text-align: center;
        }

        .col-progress {
            width: 15%;
        }

        .col-date {
            width: 12%;
        }

        /* Status badges */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #C6EFCE;
            color: #006100;
            border: 1px solid #006100;
        }

        .badge-warning {
            background-color: #FFEB9C;
            color: #9C6500;
            border: 1px solid #9C6500;
        }

        .badge-danger {
            background-color: #FFC7CE;
            color: #9C0006;
            border: 1px solid #9C0006;
        }

        .badge-info {
            background-color: #DDEBF7;
            color: #1F4E78;
            border: 1px solid #1F4E78;
        }

        /* Progress bar in table */
        .progress-cell {
            position: relative;
            background-color: #E7E6E6;
            border-radius: 3px;
            overflow: hidden;
            height: 18px;
        }

        .progress-fill {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            background-color: #70AD47;
            border-right: 1px solid #548235;
        }

        .progress-text {
            position: relative;
            z-index: 1;
            line-height: 18px;
            text-align: center;
            font-weight: bold;
            font-size: 9px;
        }

        /* Check/Cross icons */
        .check-icon {
            color: #00B050;
            font-weight: bold;
            font-size: 14px;
        }

        .cross-icon {
            color: #C00000;
            font-weight: bold;
            font-size: 14px;
        }

        .page-break {
            page-break-after: always;
        }

        /* Summary boxes */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .summary-box {
            border: 1px solid #D0D0D0;
            padding: 10px;
            background-color: #F8F9FA;
            text-align: center;
        }

        .summary-value {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }

        .summary-label {
            font-size: 10px;
            color: #7f8c8d;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $course->nama_course }}</h1>
        <p>Laporan Pelatihan - Dibuat pada {{ date('d F Y, H:i') }}</p>
    </div>


    <!-- Course Information Table -->
    <div class="section-title">Informasi Pelatihan</div>
    <table>
        <tr>
            <th style="width: 30%;">Keterangan</th>
            <th style="width: 70%;">Nilai</th>
        </tr>
        <tr>
            <td><strong>Nama Pelatihan</strong></td>
            <td>{{ $course->nama_course }}</td>
        </tr>
        <tr>
            <td><strong>Deskripsi</strong></td>
            <td>{{ $course->description }}</td>
        </tr>
        <tr>
            <td><strong>Status</strong></td>
            <td>
                <span class="badge {{ $course->public ? 'badge-success' : 'badge-warning' }}">
                    {{ $course->public ? 'PUBLIK' : 'PRIVAT' }}
                </span>
            </td>
        </tr>
        <tr>
            <td><strong>Jenis Pelatihan</strong></td>
            <td>
                <span class="badge {{ $course->is_paid ? 'badge-info' : 'badge-success' }}">
                    {{ $course->is_paid ? 'BERBAYAR' : 'GRATIS' }}
                </span>
                @if($course->price)
                    - Rp {{ number_format($course->price, 0, ',', '.') }}
                @endif
            </td>
        </tr>
        @if($course->isLimitedCourse)
            <tr>
                <td><strong>Periode Pelatihan</strong></td>
                <td>
                    {{ \Carbon\Carbon::parse($course->start_date)->format('d M Y') }} -
                    {{ \Carbon\Carbon::parse($course->end_date)->format('d M Y') }}
                    (Maks: {{ $course->maxEnrollment }} peserta)
                </td>
            </tr>
        @endif
        <tr>
            <td><strong>Jumlah Peserta</strong></td>
            <td>{{ $course->enrollment->count() }} Orang</td>
        </tr>
        <tr>
            <td><strong>Dibuat Pada</strong></td>
            <td>{{ \Carbon\Carbon::parse($course->created_at)->format('d F Y, H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Terakhir Diperbarui</strong></td>
            <td>{{ \Carbon\Carbon::parse($course->updated_at)->format('d F Y, H:i') }}</td>
        </tr>
    </table>

    <!-- Course Materials Table -->
    {{-- <div class="section-title">Materi Pelatihan</div>
    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-name">Nama Materi</th>
                <th class="col-name">Nama Sub Materi</th>
                <th style="width: 10%; text-align: center;">Tipe</th>
                <th class="col-date">Dibuat Pada</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($course->material as $material)
                @if($material->submaterial->count() > 0)
                    @foreach($material->submaterial as $index => $submaterial)
                        <tr>
                            @if($index == 0)
                                <td class="col-no" rowspan="{{ $material->submaterial->count() }}">{{ $no++ }}</td>
                                <td class="col-name" rowspan="{{ $material->submaterial->count() }}">
                                    <strong>{{ $material->nama_materi }}</strong>
                                </td>
                            @endif
                            <td class="col-name">{{ $submaterial->nama_submateri }}</td>
                            <td style="text-align: center;">
                                <span class="badge badge-info">{{ strtoupper($submaterial->type) }}</span>
                            </td>
                            <td class="col-date">{{ \Carbon\Carbon::parse($submaterial->created_at)->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="col-no">{{ $no++ }}</td>
                        <td class="col-name"><strong>{{ $material->nama_materi }}</strong></td>
                        <td colspan="2" style="text-align: center; color: #999;">Tidak ada sub materi</td>
                        <td class="col-date">{{ \Carbon\Carbon::parse($material->created_at)->format('d M Y') }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div> --}}

    <!-- Student Progress Table -->
    <div class="section-title">Laporan Progres Peserta</div>
    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-name">Nama Peserta</th>
                <th style="width: 20%;">Email</th>
                <th style="width: 20%;">Instansi</th>
                <th style="width: 20%;">Jurusan</th>
                <th style="width: 15%;">Progres</th>
                <th style="width: 10%; text-align: center;">Selesai</th>
                <th class="col-date">Tanggal Daftar</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalSubmaterials = $course->material->sum(function ($material) {
                    return $material->submaterial->count();
                });
            @endphp
            @foreach($course->enrollment as $index => $enrollment)
                @php
                    $completedCount = $enrollment->user->progress->count();
                    $progressPercentage = $totalSubmaterials > 0 ? round(($completedCount / $totalSubmaterials) * 100) : 0;
                @endphp
                <tr>
                    <td class="col-no">{{ $index + 1 }}</td>
                    <td class="col-name"><strong>{{ $enrollment->user->name }}</strong></td>
                    <td>{{ $enrollment->user->email }}</td>
                    <td>{{ $enrollment->user->instansi->nama ?? '-' }}</td>
                    <td>{{ $enrollment->user->jurusan->nama ?? '-' }}</td>
                    <td>
                        <div class="progress-cell">
                            <div class="progress-fill" style="width: {{ $progressPercentage }}%;"></div>
                            <div class="progress-text">{{ $progressPercentage }}%</div>
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <strong>{{ $completedCount }} / {{ $totalSubmaterials }}</strong>
                    </td>
                    <td class="col-date">{{ \Carbon\Carbon::parse($enrollment->created_at)->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Detailed Progress per Student -->

    @if($course->finalTask)
        <div class="page-break"></div>

        <!-- Final Task Table -->
        <div class="section-title">Tugas Akhir</div>
        <table>
            <tr>
                <th style="width: 20%;">Instruksi</th>
                <td>{{ $course->finalTask->instruksi }}</td>
            </tr>
            <tr>
                <th>Total Pengumpulan</th>
                <td><strong>{{ $course->finalTask->submission->count() }}</strong> pengumpulan</td>
            </tr>
        </table>

        @if($course->finalTask->submission->count() > 0)
            <!-- Submissions Table -->
            <div class="section-title">Ringkasan Pengumpulan</div>
            <table>
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-name">Nama Peserta</th>
                        <th style="width: 25%;">Link Pengumpulan</th>
                        <th style="width: 10%; text-align: center;">Status</th>
                        <th class="col-date">Tanggal Kirim</th>
                        <th style="width: 10%; text-align: center;">Direview</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($course->finalTask->submission as $index => $submission)
                        @php
                            $user = $course->enrollment->firstWhere('user_id', $submission->user_id)->user ?? null;
                        @endphp
                        <tr>
                            <td class="col-no">{{ $index + 1 }}</td>
                            <td class="col-name">{{ $user ? $user->name : 'Tidak Diketahui' }}</td>
                            <td style="font-size: 8px; word-break: break-all;">{{ $submission->link_google_drive }}</td>
                            <td style="text-align: center;">
                                @if($submission->status == 'approved')
                                    <span class="badge badge-success">DISETUJUI</span>
                                @elseif($submission->status == 'rejected')
                                    <span class="badge badge-danger">DITOLAK</span>
                                @else
                                    <span class="badge badge-warning">MENUNGGU</span>
                                @endif
                            </td>
                            <td class="col-date">{{ \Carbon\Carbon::parse($submission->created_at)->format('d M Y, H:i') }}</td>
                            <td style="text-align: center;">
                                @if($submission->review)
                                    <span class="check-icon">✓</span>
                                @else
                                    <span class="cross-icon">✗</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Detailed Reviews -->

        @endif
    @endif

    <!-- Footer -->
    <div
        style="margin-top: 30px; text-align: center; font-size: 9px; color: #7f8c8d; border-top: 1px solid #ddd; padding-top: 10px;">
        <p>Ini adalah laporan yang dibuat secara otomatis dari Sistem Manajemen Pembelajaran</p>
        <p>© {{ date('Y') }} - LMS RTPU</p>
    </div>
</body>

</html>
