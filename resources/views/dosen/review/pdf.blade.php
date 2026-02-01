<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Review Tugas Akhir - {{ $course->nama_course }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 7px;
            line-height: 1.3;
            padding: 50px;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #333;
        }

        .header h2 {
            font-size: 14px;
            margin-bottom: 4px;
            color: #2196F3;
        }

        .header p {
            font-size: 8px;
            margin-bottom: 2px;
            color: #555;
        }

        .info-section {
            margin-bottom: 10px;
            background-color: #f5f5f5;
            padding: 6px;
            border-radius: 3px;
            font-size: 7px;
        }

        .info-section p {
            display: inline-block;
            margin-right: 15px;
            margin-bottom: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th, td {
            border: 0.8px solid #333;
            padding: 3px 2px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            font-size: 6px;
            line-height: 1.2;
        }

        td {
            font-size: 6px;
        }

        .header-row th {
            background-color: #2196F3;
            font-size: 7px;
            padding: 4px 2px;
        }

        .subheader-row th {
            background-color: #64B5F6;
            font-size: 5.5px;
            padding: 3px 2px;
            line-height: 1.2;
        }

        /* Data rows - single row per entry */
        .data-row-1 {
            background-color: #ffffff;
        }

        .data-row-1:nth-child(even) {
            background-color: #f9f9f9;
        }

        .name-column {
            text-align: left;
            padding-left: 4px;
            font-size: 6px;
        }

        .label-cell {
            background-color: #e3f2fd;
            font-weight: bold;
            text-align: right;
            padding-right: 4px;
            font-size: 5.5px;
        }

        .checkmark {
            color: #28a745;
            font-weight: bold;
            font-size: 9px;
        }

        .cross {
            color: #dc3545;
            font-weight: bold;
            font-size: 9px;
        }

        .catatan-column {
            text-align: left;
            font-size: 5.5px;
            padding: 3px;
            word-wrap: break-word;
            line-height: 1.4;
        }

        .belum-review {
            background-color: #fff3cd;
            color: #856404;
            font-style: italic;
            font-size: 6px;
            padding: 8px;
        }

        .footer {
            margin-top: 12px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            font-size: 7px;
        }

        .footer p {
            margin-bottom: 3px;
        }

        .legend-item {
            display: inline;
            margin-right: 10px;
            font-size: 6.5px;
        }

        /* Status badges */
        .status-badge {
            padding: 2px 4px;
            border-radius: 2px;
            font-size: 5.5px;
            font-weight: bold;
        }

        .status-submitted {
            background-color: #ffc107;
            color: #000;
        }

        .status-approved {
            background-color: #28a745;
            color: white;
        }

        .status-rejected {
            background-color: #dc3545;
            color: white;
        }

        /* Column widths */
        .col-no { width: 20px; }
        .col-label { width: 60px; }
        .col-name { width: 90px; }
        .col-inst { width: 70px; }
        .col-prodi { width: 70px; }
        .col-status { width: 35px; }
        .col-kurikulum { width: 20px; }
        .col-check { width: 18px; }
        .col-catatan { width: 90px; }

        /* Wrap text */
        .wrap {
            word-break: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }

        /* No wrap untuk nomor */
        .no-wrap {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>REVIEW TUGAS AKHIR</h2>
        <p><strong>Mata Kuliah: {{ $course->nama_course }}</strong></p>
        <p>Tanggal Export: {{ \Carbon\Carbon::now()->locale('id')->format('d F Y, H:i') }} WIB</p>
    </div>

    <div class="info-section">
        <p><strong>Total Peserta:</strong> {{ $taskList->count() }}</p>
        <p><strong>Sudah Direview:</strong> {{ $taskList->whereNotNull('review')->count() }}</p>
        <p><strong>Belum Direview:</strong> {{ $taskList->whereNull('review')->count() }}</p>
    </div>

    <table>
        <thead>
            <!-- Header Row 1 -->
            <tr class="header-row">
                <th rowspan="2" class="col-no">No</th>
                <th rowspan="2" class="col-name">Nama Lengkap</th>
                <th rowspan="2" class="col-inst">Institusi</th>
                <th rowspan="2" class="col-prodi">Program Studi</th>
                <th rowspan="2" class="col-status">Status</th>
                <th colspan="2">Kurikulum</th>
                <th colspan="17">Komponen Review</th>
                <th rowspan="2" class="col-catatan">Catatan</th>
            </tr>

            <!-- Header Row 2 -->
            <tr class="subheader-row">
                <th class="col-kurikulum">P39</th>
                <th class="col-kurikulum">P3</th>
                <th class="col-check">CPL</th>
                <th class="col-check">Dist MK</th>
                <th class="col-check">CPL-MK</th>
                <th class="col-check">Mtrx</th>
                <th class="col-check">Tujuan</th>
                <th class="col-check">Peta</th>
                <th class="col-check">SKS</th>
                <th class="col-check">SCL</th>
                <th class="col-check">Case</th>
                <th class="col-check">RPS</th>
                <th class="col-check">Nil-Sem</th>
                <th class="col-check">Tgs-1</th>
                <th class="col-check">Instr</th>
                <th class="col-check">Rbrk</th>
                <th class="col-check">RPS-M</th>
                <th class="col-check">Mat-M</th>
                <th class="col-check">Nil-M</th>
            </tr>
        </thead>
        <tbody>
            @forelse($taskList as $index => $task)
                <!-- Single Row per Entry -->
                <tr class="data-row-1">
                    <td class="no-wrap">{{ $index + 1 }}</td>
                    <td class="name-column wrap">{{ $task->user->name }}</td>
                    <td class="wrap">{{ $task->user->instansi->nama ?? '-' }}</td>
                    <td class="wrap">{{ $task->user->jurusan->nama ?? '-' }}</td>
                    <td>
                        @if($task->status === 'submitted')
                            <span class="status-badge status-submitted">Submit</span>
                        @elseif($task->status === 'approved')
                            <span class="status-badge status-approved">Approve</span>
                        @elseif($task->status === 'rejected')
                            <span class="status-badge status-rejected">Reject</span>
                        @else
                            <span style="font-size: 5px;">{{ $task->status }}</span>
                        @endif
                    </td>

                    @if($task->review)
                        <!-- Kurikulum (2 kolom) -->
                        <td>{!! $task->review->kurikulum_permen_39_2025 ? '<span class="checkmark">v</span>' : '<span class="cross">x</span>' !!}</td>
                        <td>{!! $task->review->kurikulum_permen_3_2020 ? '<span class="checkmark">v</span>' : '<span class="cross">x</span>' !!}</td>

                        <!-- Checklist Komponen (19 kolom) -->
                        <td>{!! $task->review->cpl_prodi ? '<span class="checkmark">v</span>' : '<span class="cross">x</span>' !!}</td>
                        <td>{!! $task->review->distribusi_mata_kuliah_dan_highlight ? '<span class="checkmark">v</span>' : '<span class="cross">x</span>' !!}</td>
                        <td>{!! $task->review->cpl_prodi_yang_dibebankan_pada_mata_kuliah ? '<span class="checkmark">v</span>' : '<span class="cross">x</span>' !!}</td>
                        <td>{!! $task->review->matriks_kajian ? '<span class="checkmark">v</span>' : '<span class="cross">x</span>' !!}</td>
                        <td>{!! $task->review->tujuan_belajar ? '<span class="checkmark">v</span>' : '<span class="cross">x</span>' !!}</td>
                        <td>{!! $task->review->peta_kompentensi ? '<span class="checkmark">v</span>' : '<span class="cross">x</span>' !!}</td>
                        <td>{!! $task->review->perhitungan_sks ? '<span class="checkmark">v</span>' : '<span class="cross">x</span>' !!}</td>
                        <td>{!! $task->review->scl ? '<span class="checkmark">v</span>' : '<span class="cross">x</span>' !!}</td>
                        <td>{!! $task->review->metode_case_study_dan_team_based_project ? '<span class="checkmark">v</span>' : '<span class="cross">x</span>' !!}</td>
                        <td>{!! $task->review->rps ? '<span class="checkmark">v</span>' : '<span class="cross">x</span>' !!}</td>
                        <td>{!! $task->review->rancangan_penilaian_dalam_1_semester ? '<span class="checkmark">v</span>' : '<span class="cross">x</span>' !!}</td>
                        <td>{!! $task->review->rancangan_tugas_1_pertemuan ? '<span class="checkmark">v</span>' : '<span class="cross">x</span>' !!}</td>
                        <td>{!! $task->review->instrumen_penilaian_hasil_belajar ? '<span class="checkmark">v</span>' : '<span class="cross">x</span>' !!}</td>
                        <td>{!! $task->review->rubrik_penilaian ? '<span class="checkmark">v</span>' : '<span class="cross">x</span>' !!}</td>
                        <td>{!! $task->review->rps_microteaching ? '<span class="checkmark">v</span>' : '<span class="cross">x</span>' !!}</td>
                        <td>{!! $task->review->materi_microteaching ? '<span class="checkmark">v</span>' : '<span class="cross">x</span>' !!}</td>
                        <td>{!! $task->review->penilaian_microteaching ? '<span class="checkmark">v</span>' : '<span class="cross">x</span>' !!}</td>]


                        <!-- Catatan (1 kolom) -->
                        <td class="catatan-column wrap">{{ $task->review->catatan ?? '-' }}</td>
                    @else
                        <!-- Total: 2 (Kurikulum) + 19 (Komponen) + 1 (Catatan) = 22 kolom -->
                        {{-- <td colspan="21" class="belum-review">Belum direview</td> --}}
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>


                    @endif
                </tr>

            @empty
            <tr>
                <td colspan="26" style="text-align: center; padding: 15px; background-color: #f8f9fa;">
                    Tidak ada data submission
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Keterangan Simbol:</strong></p>
        <div>
            <span class="legend-item"><span class="checkmark">v</span> = Tersedia</span>
            <span class="legend-item"><span class="cross">x</span> = Tidak Tersedia</span>
            <span class="legend-item">|</span>
            <span class="legend-item"><span class="status-badge status-submitted">Submit</span> = Menunggu Review</span>
            <span class="legend-item"><span class="status-badge status-approved">Approve</span> = Disetujui</span>
            <span class="legend-item"><span class="status-badge status-rejected">Reject</span> = Ditolak</span>
        </div>
        <p style="margin-top: 8px;"><strong>Singkatan:</strong> P39=Permen 39/2025, P3=Permen 3/2020, Dist MK=Distribusi Mata Kuliah, CPL-MK=CPL Prodi pada MK, Mtrx=Matriks, Nil-Sem=Penilaian Semester, Tgs-1=Tugas 1 Pertemuan, Instr=Instrumen, Rbrk=Rubrik, M=Microteaching</p>
    </div>
</body>
</html>
